<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$userId = $_SESSION['user_id'];
$successMessage = '';
$errorMessage = '';

$sql = "SELECT u.*, d.departmentName 
        FROM users u 
        LEFT JOIN department d ON u.departmentId = d.departmentId 
        WHERE u.userId = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$user = $stmt->fetch();

if (!$user) {
    header("Location: logout.php");
    exit;
}

$ticketSql = "SELECT status, COUNT(*) as count FROM ticket WHERE userId = ? GROUP BY status";
$ticketStmt = $pdo->prepare($ticketSql);
$ticketStmt->execute([$userId]);
$ticketStats = $ticketStmt->fetchAll();
$stats = ['Pending' => 0, 'Processing' => 0, 'Resolved' => 0, 'Completed' => 0, 'Cancelled' => 0];
foreach ($ticketStats as $stat) {
    if (isset($stats[$stat['status']])) {
        $stats[$stat['status']] = $stat['count'];
    }
}
$totalTickets = array_sum($stats);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['update_profile'])) {
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $email = trim($_POST['email']);
        $phone = trim($_POST['phone'] ?? '');
        $currentPassword = $_POST['currentPassword'] ?? '';
        $newPassword = $_POST['newPassword'] ?? '';
        $confirmPassword = $_POST['confirmPassword'] ?? '';

        $errors = [];
        if (empty($firstName) || empty($lastName) || empty($email)) {
            $errors[] = "First name, last name, and email are required.";
        }

        if (!empty($currentPassword) || !empty($newPassword) || !empty($confirmPassword)) {
            if (empty($currentPassword)) {
                $errors[] = "Current password is required to change password.";
            } elseif (!password_verify($currentPassword, $user['password'])) {
                $errors[] = "Current password is incorrect.";
            }

            if (empty($newPassword)) {
                $errors[] = "New password is required.";
            } elseif (strlen($newPassword) < 6) {
                $errors[] = "New password must be at least 6 characters.";
            }

            if ($newPassword !== $confirmPassword) {
                $errors[] = "New passwords do not match.";
            }
        }

        if (empty($errors)) {
            try {
                if (!empty($newPassword)) {
                    $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
                    $updateSql = "UPDATE users SET firstName = ?, lastName = ?, email = ?, phone = ?, password = ? WHERE userId = ?";
                    $updateStmt = $pdo->prepare($updateSql);
                    $updateStmt->execute([$firstName, $lastName, $email, $phone, $hashedPassword, $userId]);
                    $_SESSION['fullname'] = $firstName . ' ' . $lastName;
                } else {
                    $updateSql = "UPDATE users SET firstName = ?, lastName = ?, email = ?, phone = ? WHERE userId = ?";
                    $updateStmt = $pdo->prepare($updateSql);
                    $updateStmt->execute([$firstName, $lastName, $email, $phone, $userId]);
                    $_SESSION['fullname'] = $firstName . ' ' . $lastName;
                }
                $successMessage = "Profile updated successfully.";
                $stmt->execute([$userId]);
                $user = $stmt->fetch();
            } catch (PDOException $e) {
                $errorMessage = "Failed to update profile: " . $e->getMessage();
            }
        } else {
            $errorMessage = implode(" ", $errors);
        }
    }

    if (isset($_POST['upload_picture'])) {
        if (isset($_FILES['profilePicture']) && $_FILES['profilePicture']['error'] === 0) {
            $allowed = ['jpg', 'jpeg', 'png', 'gif', 'webp'];
            $filename = $_FILES['profilePicture']['name'];
            $ext = strtolower(pathinfo($filename, PATHINFO_EXTENSION));

            if (in_array($ext, $allowed)) {
                $newFilename = 'user_' . $userId . '_' . time() . '.' . $ext;
                $uploadPath = 'uploads/profiles/' . $newFilename;

                if (move_uploaded_file($_FILES['profilePicture']['tmp_name'], $uploadPath)) {
                    $oldPicture = $user['profilePicture'] ?? null;
                    try {
                        $updatePicSql = "UPDATE users SET profilePicture = ? WHERE userId = ?";
                        $updatePicStmt = $pdo->prepare($updatePicSql);
                        $updatePicStmt->execute([$uploadPath, $userId]);

                        if ($oldPicture && file_exists($oldPicture) && $oldPicture !== 'uploads/profiles/default.png') {
                            unlink($oldPicture);
                        }

                        $successMessage = "Profile picture updated successfully.";
                        $stmt->execute([$userId]);
                        $user = $stmt->fetch();
                    } catch (PDOException $e) {
                        $errorMessage = "Failed to update profile picture: " . $e->getMessage();
                    }
                } else {
                    $errorMessage = "Failed to upload file.";
                }
            } else {
                $errorMessage = "Invalid file type. Allowed: jpg, jpeg, png, gif, webp.";
            }
        } else {
            $errorMessage = "Please select a valid image to upload.";
        }
    }
}

$profilePicture = $user['profilePicture'] ?? null;

$page = 'profile';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>My Profile - DepEd Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <div style="position: fixed; top: 0; left: 0; height: 100vh; width: 280px; z-index: 1000; overflow-y: auto;">
        <?php include 'sidebar.php'; ?>
    </div>

    <div style="margin-left: 280px;">

        <?php include 'header.php'; ?>

        <div class="container-fluid py-5 px-5">

            <?php if (!empty($successMessage)): ?>
                <div class="alert alert-success alert-dismissible fade show" role="alert">
                    <i class="bi bi-check-circle me-2"></i><?php echo htmlspecialchars($successMessage); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <?php if (!empty($errorMessage)): ?>
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <i class="bi bi-exclamation-triangle me-2"></i><?php echo htmlspecialchars($errorMessage); ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                </div>
            <?php endif; ?>

            <div class="row g-4">

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body text-center py-5">
                            <div class="mb-4 position-relative d-inline-block">
                                <?php if ($profilePicture && file_exists($profilePicture)): ?>
                                    <img src="<?php echo htmlspecialchars($profilePicture); ?>" alt="Profile Picture" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #198754;">
                                <?php else: ?>
                                    <div class="rounded-circle bg-success bg-opacity-10 d-inline-flex align-items-center justify-content-center" style="width: 120px; height: 120px;">
                                        <i class="bi bi-person-fill text-success" style="font-size: 4rem;"></i>
                                    </div>
                                <?php endif; ?>
                            </div>
                            <form method="POST" enctype="multipart/form-data" class="mb-3">
                                <label for="profilePicture" class="btn btn-sm btn-outline-success">
                                    <i class="bi bi-camera me-1"></i> Upload Photo
                                </label>
                                <input type="file" name="profilePicture" id="profilePicture" class="d-none" accept="image/*" onchange="this.form.submit()">
                                <input type="hidden" name="upload_picture" value="1">
                            </form>
                            <h4 class="fw-bold mb-1"><?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></h4>
                            <span class="badge <?php
                                                echo $user['role'] === 'Officer' ? 'bg-dark' : ($user['role'] === 'Technician' ? 'bg-info text-dark' : 'bg-secondary');
                                                ?> mb-3">
                                <?php echo htmlspecialchars($user['role']); ?>
                            </span>
                            <p class="text-muted mb-1"><i class="bi bi-building me-2"></i><?php echo htmlspecialchars($user['departmentName'] ?? 'N/A'); ?></p>
                            <p class="text-muted mb-0"><i class="bi bi-calendar3 me-2"></i>Joined <?php echo date("F Y", strtotime($user['createdAt'])); ?></p>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white fw-bold py-3">
                            <i class="bi bi-bar-chart-fill me-2 text-success"></i>Ticket Statistics
                        </div>
                        <div class="card-body p-0">
                            <div class="p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-muted"><i class="bi bi-list-ul me-2"></i>Total Tickets</span>
                                    <span class="fw-bold fs-5"><?php echo $totalTickets; ?></span>
                                </div>
                            </div>
                            <div class="p-3 border-bottom bg-light bg-opacity-50">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-warning"><i class="bi bi-hourglass-split me-2"></i>Pending</span>
                                    <span class="fw-bold"><?php echo $stats['Pending']; ?></span>
                                </div>
                            </div>
                            <div class="p-3 border-bottom">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-info"><i class="bi bi-gear me-2"></i>Processing</span>
                                    <span class="fw-bold"><?php echo $stats['Processing']; ?></span>
                                </div>
                            </div>
                            <div class="p-3 border-bottom bg-light bg-opacity-50">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-success"><i class="bi bi-check-circle me-2"></i>Resolved</span>
                                    <span class="fw-bold"><?php echo $stats['Resolved']; ?></span>
                                </div>
                            </div>
                            <div class="p-3">
                                <div class="d-flex justify-content-between align-items-center">
                                    <span class="text-secondary"><i class="bi bi-check-all me-2"></i>Completed</span>
                                    <span class="fw-bold"><?php echo $stats['Completed']; ?></span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-8">
                    <div class="card shadow-sm border-0">
                        <div class="card-header bg-white fw-bold py-3">
                            <i class="bi bi-pencil-square me-2 text-success"></i>Edit Profile
                        </div>
                        <div class="card-body">
                            <form method="POST" action="">
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label class="form-label">First Name</label>
                                        <input type="text" class="form-control" name="firstName" value="<?php echo htmlspecialchars($user['firstName']); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Last Name</label>
                                        <input type="text" class="form-control" name="lastName" value="<?php echo htmlspecialchars($user['lastName']); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Email Address</label>
                                        <input type="email" class="form-control" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="form-label">Phone Number</label>
                                        <input type="text" class="form-control" name="phone" value="<?php echo htmlspecialchars($user['phone'] ?? ''); ?>">
                                    </div>

                                    <div class="col-12">
                                        <hr class="my-2">
                                        <h6 class="text-muted mb-3"><i class="bi bi-lock me-2"></i>Change Password (leave blank to keep current)</h6>
                                    </div>

                                    <div class="col-md-4">
                                        <label class="form-label">Current Password</label>
                                        <input type="password" class="form-control" name="currentPassword">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">New Password</label>
                                        <input type="password" class="form-control" name="newPassword">
                                    </div>
                                    <div class="col-md-4">
                                        <label class="form-label">Confirm New Password</label>
                                        <input type="password" class="form-control" name="confirmPassword">
                                    </div>

                                    <div class="col-12 mt-4">
                                        <button type="submit" name="update_profile" class="btn btn-success fw-bold px-4">
                                            <i class="bi bi-check-lg me-2"></i>Save Changes
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mt-4">
                        <div class="card-header bg-white fw-bold py-3">
                            <i class="bi bi-info-circle me-2 text-success"></i>Account Information
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label">User ID</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['userId']); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Role</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['role']); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Department</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['departmentName'] ?? 'N/A'); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Phone Number</label>
                                    <input type="text" class="form-control" value="<?php echo htmlspecialchars($user['phone'] ?? 'N/A'); ?>" readonly>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Account Created</label>
                                    <input type="text" class="form-control" value="<?php echo date("F d, Y h:i A", strtotime($user['createdAt'])); ?>" readonly>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>