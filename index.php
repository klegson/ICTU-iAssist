<?php
session_start();
require 'db.php';

if (isset($_SESSION['user_id'])) {
    if ($_SESSION['role'] === 'Officer') {
        header("Location: db_officer.php");
    } elseif ($_SESSION['role'] === 'Technician') {
        header("Location: db_technician.php");
    } else {
        header("Location: db_user.php");
    }
    exit;
}

$error_message = "";

if (isset($_POST['login'])) {
    $input_email = trim($_POST['username']);
    $input_password = $_POST['password'];

    $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
    $stmt->execute([$input_email]);
    $user = $stmt->fetch();

    if ($user && password_verify($input_password, $user['password'])) {

        if (isset($user['isApproved']) && $user['isApproved'] == 0) {
            $error_message = "Your account is currently pending administrator approval.";
        } else {
            $_SESSION['user_id'] = $user['userId'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['fullname'] = formatName($user['firstName'], $user['middleName'], $user['lastName']);
            $_SESSION['department_id'] = $user['departmentId'];

            if (empty($user['signature'])) {
                header("Location: create_signature.php");
                exit;
            }

            if ($user['role'] === 'Officer') {
                header("Location: db_officer.php");
            } elseif ($user['role'] === 'Technician') {
                header("Location: db_technician.php");
            } else {
                header("Location: db_user.php");
            }
            exit;
        }
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<?php $pageTitle = 'DepEd Helpdesk - Login'; include 'head.php'; ?>

<body class="login-body">

    <div class="login-card text-center">
        <img src="deped_rov.jpg" alt="DepEd Logo" class="deped-logo">
        <h4 class="fw-bold mb-1" style="color: #003366;">ICT Helpdesk</h4>
        <p class="text-muted small mb-4">Regional Office V - Rawis, Legazpi</p>

        <?php if ($error_message): ?>
            <div class="alert <?php echo strpos($error_message, 'pending') !== false ? 'alert-warning' : 'alert-danger'; ?> py-2 small">
                <?php echo $error_message; ?>
            </div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3 text-start">
                <label class="form-label small fw-bold text-secondary">Email Address</label>
                <div class="position-relative">
                    <i class="bi bi-envelope-fill position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                    <input type="email" name="username" class="form-control ps-5" placeholder="Enter DepEd email" required>
                </div>
            </div>
            <div class="mb-4 text-start">
                <label class="form-label small fw-bold text-secondary">Password</label>
                <div class="position-relative">
                    <i class="bi bi-lock-fill position-absolute top-50 start-0 translate-middle-y ms-3 text-secondary"></i>
                    <input type="password" name="password" class="form-control ps-5" placeholder="Enter password" autocomplete="current-password" required>
                </div>
            </div>
            <button type="submit" name="login" class="btn btn-deped w-100">SIGN IN</button>
        </form>

        <div class="d-flex align-items-center my-4 mx-auto" style="max-width: 75%;">
            <hr class="flex-grow-1" style="border-color: #e0e0e0; opacity: 1;">
            <span class="mx-3 small" style="color: #999;">OR</span>
            <hr class="flex-grow-1" style="border-color: #e0e0e0; opacity: 1;">
        </div>

        <div class="mb-4">
            <button type="button" class="btn btn-deped-ghost w-100 d-flex align-items-center justify-content-center gap-2 py-2 rounded-3" style="font-weight: 500;">
                <svg width="20" height="20" viewBox="0 0 48 48">
                    <path fill="#EA4335" d="M24 9.5c3.54 0 6.71 1.22 9.21 3.6l6.85-6.85C35.9 2.38 30.47 0 24 0 14.62 0 6.51 5.38 2.56 13.22l7.98 6.19C12.43 13.72 17.74 9.5 24 9.5z"/>
                    <path fill="#4285F4" d="M46.98 24.55c0-1.57-.15-3.09-.38-4.55H24v9.02h12.94c-.58 2.96-2.26 5.48-4.78 7.18l7.73 6c4.51-4.18 7.09-10.36 7.09-17.65z"/>
                    <path fill="#FBBC05" d="M10.54 28.59A14.5 14.5 0 0 1 9.5 24c0-1.59.28-3.14.76-4.59l-7.98-6.19A23.99 23.99 0 0 0 0 24c0 3.77.87 7.35 2.56 10.56l7.98-5.97z"/>
                    <path fill="#34A853" d="M24 48c6.48 0 11.93-2.13 15.89-5.81l-7.73-6c-2.15 1.45-4.92 2.3-8.16 2.3-6.26 0-11.57-4.22-13.47-9.91l-7.98 5.97C6.51 42.62 14.62 48 24 48z"/>
                </svg>
                Continue to Google
            </button>
        </div>
    </div>

</body>

</html>