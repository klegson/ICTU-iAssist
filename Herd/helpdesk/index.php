<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DepEd Helpdesk - Login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body { 
            background-color: #f0f2f5; 
            height: 100vh; 
            display: flex; 
            align-items: center; 
            font-family: 'Segoe UI', sans-serif;
        }
        .login-card { 
            width: 100%; 
            max-width: 400px; 
            padding: 40px; 
            border-radius: 15px; 
            box-shadow: 0 8px 30px rgba(0,0,0,0.1); 
            background: white; 
            margin: auto; 
            border-top: 6px solid #1b5e20;
        }
        /* This forces your "deped logo.png" to be the correct size */
        .deped-logo {
            width: 130px; 
            height: auto;
            margin-bottom: 20px;
            display: block;
            margin-left: auto;
            margin-right: auto;
        }
        .btn-deped { 
            background-color: #1b5e20;
            color: white; 
            font-weight: 600;
            padding: 12px;
        }
        .btn-deped:hover { 
            background-color: #002244; 
            color: white; 
        }
    </style>
</head>
<body>

<div class="login-card text-center">
    <img src="deped logo.png" alt="DepEd Logo" class="deped-logo">
    
    <h4 class="fw-bold mb-1" style="color: #003366;">ICT Helpdesk</h4>
    <p class="text-muted small mb-4">Regional Office V - Rawis, Legazpi</p>
    
    <form action="login_process.php" method="POST">
        <div class="mb-3 text-start">
            <label class="form-label small fw-bold text-secondary">Username</label>
            <input type="text" name="username" class="form-control" placeholder="Enter username" required>
        </div>
        <div class="mb-4 text-start">
            <label class="form-label small fw-bold text-secondary">Password</label>
            <input type="password" name="password" class="form-control" placeholder="Enter password" required>
        </div>
        <button type="submit" name="login" class="btn btn-deped w-100">SIGN IN</button>
    </form>
    
    <div class="mt-4 pt-3 border-top">
        <p class="text-muted" style="font-size: 0.75rem;">© 2026 DepEd Bicol<br>Information Communication Technology Unit</p>
    </div>
</div>

</body>
</html>