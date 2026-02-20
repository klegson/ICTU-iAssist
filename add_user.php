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

$msg = "";
$alertClass = "";

if (isset($_POST['save_user'])) {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $role = $_POST['role'];
    $deptId = !empty($_POST['departmentId']) ? $_POST['departmentId'] : null;
    $password = $_POST['password'];

    $check = $pdo->prepare("SELECT userId FROM users WHERE email = ?");
    $check->execute([$email]);

    if ($check->rowCount() > 0) {
        $msg = "Error: That email is already registered.";
        $alertClass = "alert-danger";
    } else {
        $hashedPassword = $password;

        $sql = "INSERT INTO users (firstName, lastName, email, password, role, departmentId) VALUES (?, ?, ?, ?, ?, ?)";
        $stmt = $pdo->prepare($sql);

        if ($stmt->execute([$firstName, $lastName, $email, $hashedPassword, $role, $deptId])) {
            header("Location: users.php");
            exit;
        } else {
            $msg = "Database Error: Could not save user.";
            $alertClass = "alert-danger";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Add User - DepEd Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <?php include 'header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-xl-2 d-none d-lg-block p-0">
                <?php $page = 'users';
                include 'sidebar_officer.php'; ?>
            </div>

            <div class="col-lg-9 col-xl-10 py-4">
                <div class="container">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-dark">Add New User</h4>
                        <a href="users.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to User List
                        </a>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-primary text-white fw-bold">
                                    <i class="bi bi-person-plus-fill me-2"></i> User Details
                                </div>
                                <div class="card-body p-4">

                                    <?php if ($msg): ?>
                                        <div class="alert <?php echo $alertClass; ?>"><?php echo $msg; ?></div>
                                    <?php endif; ?>

                                    <form method="POST">
                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">FIRST NAME</label>
                                                <input type="text" name="firstName" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">LAST NAME</label>
                                                <input type="text" name="lastName" class="form-control" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">EMAIL ADDRESS</label>
                                            <input type="email" name="email" class="form-control" required>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">PASSWORD</label>
                                                <input type="password" name="password" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">SYSTEM ROLE</label>
                                                <select name="role" class="form-select">
                                                    <option value="User">Regular User</option>
                                                    <option value="Technician">Technician</option>
                                                    <option value="Officer">ICT Officer (Admin)</option>
                                                </select>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">DEPARTMENT / OFFICE</label>
                                            <select name="departmentId" class="form-select">
                                                <option value="">-- No Department --</option>
                                                <?php
                                                $dStmt = $pdo->query("SELECT * FROM department ORDER BY departmentName");
                                                while ($d = $dStmt->fetch()) {
                                                    echo "<option value='" . $d['departmentId'] . "'>" . $d['departmentName'] . "</option>";
                                                }
                                                ?>
                                            </select>
                                        </div>

                                        <div class="d-grid mt-4">
                                            <button type="submit" name="save_user" class="btn btn-primary fw-bold py-2">
                                                Create Account
                                            </button>
                                        </div>
                                    </form>

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