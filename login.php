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


        $_SESSION['user_id'] = $user['userId'];
        $_SESSION['role'] = $user['role'];
        $_SESSION['fullname'] = $user['firstName'] . ' ' . $user['lastName'];

        if ($user['role'] === 'Officer') {
            header("Location: db_officer.php");
        } elseif ($user['role'] === 'Technician') {
            header("Location: db_technician.php");
        } else {
            header("Location: db_user.php");
        }
        exit;
    } else {
        $error_message = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>DepEd Helpdesk - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
</head>

<body class="login-body">

    <div class="login-card text-center">
        <img src="deped logo.png" alt="DepEd Logo" class="deped-logo">
        <h4 class="fw-bold mb-1" style="color: #003366;">ICT Helpdesk</h4>
        <p class="text-muted small mb-4">Regional Office V - Rawis, Legazpi</p>

        <?php if ($error_message): ?>
            <div class="alert alert-danger py-2 small"><?php echo $error_message; ?></div>
        <?php endif; ?>

        <form action="" method="POST">
            <div class="mb-3 text-start">
                <label class="form-label small fw-bold text-secondary">Email Address</label>
                <input type="email" name="username" class="form-control" placeholder="Enter DepEd email" required>
            </div>
            <div class="mb-4 text-start">
                <label class="form-label small fw-bold text-secondary">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required>
            </div>
            <button type="submit" name="login" class="btn btn-deped w-100">SIGN IN</button>
        </form>
    </div>

</body>

</html>