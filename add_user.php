<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Officer') {
    header("Location: login.php");
    exit;
}
$page = 'users';
$msg = "";
$msgType = "";

if (isset($_POST['save_user'])) {
    $first = trim($_POST['firstName']);
    $last = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $rawPass = $_POST['password'];
    $role = $_POST['role'];
    $deptId = $_POST['departmentId'];

    if (empty($first) || empty($last) || empty($email) || empty($rawPass)) {
        $msg = "Please fill in all required fields.";
        $msgType = "alert-danger";
    } else {
        $check = $pdo->prepare("SELECT userId FROM users WHERE email = ?");
        $check->execute([$email]);
        if ($check->rowCount() > 0) {
            $msg = "Error: That email address is already taken.";
            $msgType = "alert-danger";
        } else {
            $hashedPass = password_hash($rawPass, PASSWORD_DEFAULT);
            $sql = "INSERT INTO users (firstName, lastName, email, password, role, departmentId, status) 
                    VALUES (?, ?, ?, ?, ?, ?, 'Active')";
            $stmt = $pdo->prepare($sql);

            if ($stmt->execute([$first, $last, $email, $hashedPass, $role, $deptId])) {
                header("Location: manage_users.php?msg=created");
                exit;
            } else {
                $msg = "Database Error: Could not save user.";
                $msgType = "alert-danger";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add New User - DepEd Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <div style="position: fixed; top: 0; left: 0; height: 100vh; width: 280px; z-index: 1000; overflow-y: auto;">
        <?php include 'sidebar_officer.php'; ?>
    </div>

    <div style="margin-left: 280px;">

        <?php include 'header.php'; ?>

        <div class="container-fluid py-5 px-5">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark mb-0">Add New User</h2>
                <a href="manage_users.php" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left me-2"></i>Back to User List
                </a>
            </div>

            <?php if ($msg): ?>
                <div class="alert <?php echo $msgType; ?> alert-dismissible fade show" role="alert">
                    <?php echo $msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-success text-white py-3">
                    <h6 class="mb-0 fw-bold"><i class="bi bi-person-plus-fill me-2"></i>User Details</h6>
                </div>
                <div class="card-body p-5">

                    <form method="POST">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">FIRST NAME</label>
                                <input type="text" name="firstName" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">LAST NAME</label>
                                <input type="text" name="lastName" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold text-muted">EMAIL ADDRESS</label>
                            <input type="email" name="email" class="form-control" placeholder="employee@deped.gov.ph" required>
                        </div>

                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">PASSWORD</label>
                                <input type="password" name="password" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">SYSTEM ROLE</label>
                                <select name="role" class="form-select">
                                    <option value="User">Regular User</option>
                                    <option value="Technician">Technician</option>
                                    <option value="Officer">IT Officer (Admin)</option>
                                </select>
                            </div>
                        </div>

                        <div class="mb-5">
                            <label class="form-label small fw-bold text-muted">DEPARTMENT / OFFICE</label>
                            <select name="departmentId" class="form-select" required>
                                <option value="" selected disabled>-- Select Department --</option>
                                <?php
                                $deptSql = "SELECT * FROM department ORDER BY departmentName ASC";
                                $deptStmt = $pdo->query($deptSql);
                                while ($d = $deptStmt->fetch()) {
                                    echo "<option value='" . $d['departmentId'] . "'>" . htmlspecialchars($d['departmentName']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="d-grid">
                            <button type="submit" name="save_user" class="btn btn-success fw-bold py-2">
                                Create Account
                            </button>
                        </div>
                    </form>

                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>