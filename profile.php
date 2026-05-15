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

$sql = "SELECT u.*, d.departmentName, d.departmentCode, d.departmentHead, p.positionTitle,
               e.firstName AS h_fn, e.middleName AS h_mn, e.lastName AS h_ln, e.extension AS h_ext, e.positionTitle AS h_pos
        FROM users u
        LEFT JOIN department d ON u.departmentId = d.departmentId
        LEFT JOIN employees e ON d.departmentHead = e.employeeID
        LEFT JOIN position p ON u.positionID = p.positionID
        WHERE u.userId = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$userId]);
$user = $stmt->fetch();

$posSql = "SELECT * FROM position ORDER BY positionTitle";
$posStmt = $pdo->prepare($posSql);
$posStmt->execute();
$positions = $posStmt->fetchAll();

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
        $positionID = !empty($_POST['positionID']) ? (int)$_POST['positionID'] : null;
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
                    $updateSql = "UPDATE users SET firstName = ?, lastName = ?, email = ?, phone = ?, positionID = ?, password = ? WHERE userId = ?";
                    $updateStmt = $pdo->prepare($updateSql);
                    $updateStmt->execute([$firstName, $lastName, $email, $phone, $positionID, $hashedPassword, $userId]);
                    $_SESSION['fullname'] = $firstName . ' ' . $lastName;
                } else {
                    $updateSql = "UPDATE users SET firstName = ?, lastName = ?, email = ?, phone = ?, positionID = ? WHERE userId = ?";
                    $updateStmt = $pdo->prepare($updateSql);
                    $updateStmt->execute([$firstName, $lastName, $email, $phone, $positionID, $userId]);
                }

                if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                    $uploadDir = 'uploads/';
                    if (!is_dir($uploadDir)) {
                        mkdir($uploadDir, 0755, true);
                    }

                    $fileExt = strtolower(pathinfo($_FILES['profile_picture']['name'], PATHINFO_EXTENSION));
                    $allowedExt = ['jpg', 'jpeg', 'png', 'gif'];

                    if (in_array($fileExt, $allowedExt)) {
                        $newFilename = 'user_' . $userId . '_' . time() . '.' . $fileExt;
                        $targetPath = $uploadDir . $newFilename;

                        if (move_uploaded_file($_FILES['profile_picture']['tmp_name'], $targetPath)) {
                            if (!empty($user['profilePicture']) && file_exists($user['profilePicture'])) {
                                unlink($user['profilePicture']);
                            }

                            $picStmt = $pdo->prepare("UPDATE users SET profilePicture = ? WHERE userId = ?");
                            $picStmt->execute([$targetPath, $userId]);
                        }
                    }
                }

                $successMessage = "Profile updated successfully!";
                $stmt = $pdo->prepare($sql);
                $stmt->execute([$userId]);
                $user = $stmt->fetch(); // Refresh user data
                $user['profilePicture'] = $targetPath ?? $user['profilePicture'];
            } catch (Exception $e) {
                $errorMessage = "Error updating profile: " . $e->getMessage();
            }
        } else {
            $errorMessage = implode('<br>', $errors);
        }
    }

    if (isset($_POST['update_signature']) && !empty($_POST['signature_data'])) {
        $signatureData = $_POST['signature_data'];
        $stmt = $pdo->prepare("UPDATE users SET signature = ? WHERE userId = ?");
        if ($stmt->execute([$signatureData, $userId])) {
            $successMessage = "Signature updated successfully!";
            $stmt = $pdo->prepare($sql);
            $stmt->execute([$userId]);
            $user = $stmt->fetch();
        } else {
            $errorMessage = "Error updating signature.";
        }
    }
}

$page = 'profile';
$pageTitle = 'My Profile - DepEd Helpdesk';
include 'head.php';
?>

<body class="bg-light">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 main-content" style="min-height: 100vh; overflow-y: auto;">

            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">

            <div class="row g-4">

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body text-center py-5">
                            <div class="mb-4 position-relative d-inline-block">
                                <?php if (!empty($user['profilePicture']) && file_exists($user['profilePicture'])): ?>
                                    <img src="<?php echo htmlspecialchars($user['profilePicture']); ?>" alt="Profile Picture" class="rounded-circle" style="width: 120px; height: 120px; object-fit: cover; border: 3px solid #198754;">
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
                            <p class="text-muted mb-1"><i class="bi bi-building me-2"></i><?php echo htmlspecialchars(($user['departmentName'] ?? 'N/A') . ' (' . ($user['departmentCode'] ?? 'N/A') . ')'); ?></p>
                            <?php
                            $headName = trim(($user['h_fn'] ?? '') . ' ' . ($user['h_mn'] ?? '') . ' ' . ($user['h_ln'] ?? '') . ' ' . ($user['h_ext'] ?? ''));
                            $headName = trim(preg_replace('/\s+/', ' ', $headName));
                            if ($headName): ?>
                            <p class="text-muted mb-1 small"><i class="bi bi-person-badge me-2"></i>Dept Head: <?php echo htmlspecialchars($headName . ' — ' . ($user['h_pos'] ?? 'N/A')); ?></p>
                            <?php endif; ?>
                            <?php if (!empty($user['positionTitle'])): ?>
                                <p class="text-muted mb-1"><i class="bi bi-briefcase me-2"></i><?php echo htmlspecialchars($user['positionTitle']); ?></p>
                            <?php endif; ?>
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
                                        <label class="form-label">Position</label>
                                        <select class="form-select" name="positionID">
                                            <option value="">-- Select Position --</option>
                                            <?php foreach ($positions as $pos): ?>
                                                <option value="<?php echo $pos['positionID']; ?>" <?php echo ($user['positionID'] == $pos['positionID']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($pos['positionTitle']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
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
                            <i class="bi bi-vector-pen me-2 text-success"></i>My Signature
                        </div>
                        <div class="card-body">
                            <?php if (!empty($user['signature'])): ?>
                                <div class="mb-3">
                                    <label class="form-label fw-bold">Current Signature</label>
                                    <div class="p-3 border rounded bg-light text-center">
                                        <img src="<?php echo htmlspecialchars($user['signature']); ?>" alt="Current Signature" style="max-width: 100%; height: auto; max-height: 200px;">
                                    </div>
                                </div>
                            <?php endif; ?>
                            <button type="button" class="btn btn-success fw-bold px-4" data-bs-toggle="modal" data-bs-target="#signatureModal">
                                <i class="bi bi-pencil-square me-2"></i>Update Signature
                            </button>
                        </div>
                    </div>

                    <div class="modal fade" id="signatureModal" tabindex="-1" aria-labelledby="signatureModalLabel" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="signatureModalLabel">Update Your Signature</h5>
                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                </div>
                                <div class="modal-body">
                                    <div class="mb-3">
                                        <div class="d-flex justify-content-between align-items-end mb-2">
                                            <label class="fw-bold">Draw your signature below:</label>
                                            <button type="button" class="btn btn-sm btn-light border text-secondary" id="modal-clear-signature">
                                                <i class="bi bi-eraser-fill me-1"></i> Clear
                                            </button>
                                        </div>
                                        <div style="border: 2px dashed #dee2e6; border-radius: 8px; background: white; height: 200px;">
                                            <canvas id="modal-signature-pad" class="signature-pad" style="width: 100%; height: 100%;"></canvas>
                                        </div>
                                    </div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                    <button type="button" class="btn btn-success" id="modal-save-signature">
                                        <i class="bi bi-check-lg me-2"></i>Save Signature
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <form method="POST" id="signature-update-form">
                        <input type="hidden" name="update_signature" value="1">
                        <input type="hidden" name="signature_data" id="modal-signature-data">
                    </form>
                </div>

            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const sidebarToggle = document.getElementById('sidebarToggle');
        const sidebarContainer = document.querySelector('.sidebar-container');
        const sidebarOverlay = document.getElementById('sidebarOverlay');

        if (sidebarToggle && sidebarContainer && sidebarOverlay) {
            sidebarToggle.addEventListener('click', function() {
                sidebarContainer.classList.toggle('show');
                sidebarOverlay.classList.toggle('show');
            });

            sidebarOverlay.addEventListener('click', function() {
                sidebarContainer.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            });
        }

        if (window.innerWidth <= 768) {
            document.querySelectorAll('.sidebar-container .nav-link').forEach(function(link) {
                link.addEventListener('click', function() {
                    sidebarContainer.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            });
        }

        const signatureModal = document.getElementById('signatureModal');
        let signaturePad = null;

        if (signatureModal) {
            signatureModal.addEventListener('shown.bs.modal', function() {
                const canvas = document.getElementById('modal-signature-pad');
                if (canvas && !signaturePad) {
                    function resizeCanvas() {
                        const ratio = Math.max(window.devicePixelRatio || 1, 1);
                        canvas.width = canvas.offsetWidth * ratio;
                        canvas.height = canvas.offsetHeight * ratio;
                        canvas.getContext("2d").scale(ratio, ratio);
                    }
                    resizeCanvas();
                    signaturePad = new SignaturePad(canvas, {
                        penColor: "rgb(0, 0, 0)",
                        minWidth: 1.5,
                        maxWidth: 3
                    });
                }
            });

            signatureModal.addEventListener('hidden.bs.modal', function() {
                if (signaturePad) {
                    signaturePad.clear();
                }
            });

            const clearBtn = document.getElementById('modal-clear-signature');
            if (clearBtn) {
                clearBtn.addEventListener('click', function() {
                    if (signaturePad) {
                        signaturePad.clear();
                    }
                });
            }

            const saveBtn = document.getElementById('modal-save-signature');
            if (saveBtn) {
                saveBtn.addEventListener('click', function() {
                    if (!signaturePad || signaturePad.isEmpty()) {
                        alert('Please draw your signature before saving.');
                        return;
                    }
                    var exportCanvas = document.createElement('canvas');
                    var canvas = document.getElementById('modal-signature-pad');
                    exportCanvas.width = canvas.offsetWidth;
                    exportCanvas.height = canvas.offsetHeight;
                    var exportCtx = exportCanvas.getContext('2d');
                    exportCtx.fillStyle = '#fff';
                    exportCtx.fillRect(0, 0, exportCanvas.width, exportCanvas.height);
                    exportCtx.drawImage(canvas, 0, 0);
                    document.getElementById('modal-signature-data').value = exportCanvas.toDataURL('image/jpeg', 0.7);
                    document.getElementById('signature-update-form').submit();
                });
            }
        }

        <?php if (!empty($successMessage)): ?>
            Swal.fire({
                icon: 'success',
                title: 'Success',
                text: <?php echo json_encode($successMessage); ?>,
                confirmButtonColor: '#198754'
            });
        <?php endif; ?>

        <?php if (!empty($errorMessage)): ?>
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: <?php echo json_encode($errorMessage); ?>,
                confirmButtonColor: '#198754'
            });
        <?php endif; ?>
    });
    </script>

</body>

</html>