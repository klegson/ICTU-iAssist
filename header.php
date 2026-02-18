<nav class="navbar navbar-expand-lg navbar-dark shadow-sm" style="background-color: #1a4d2e;">
    <div class="container-fluid px-4">

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center">

                <li class="nav-item me-3">
                    <span class="nav-link text-white fw-light">
                        Welcome, <span class="fw-bold"><?php echo htmlspecialchars($_SESSION['fullname'] ?? 'User'); ?></span>
                    </span>
                </li>

                <li class="nav-item">
                    <a class="nav-link btn btn-outline-light btn-sm px-3 text-white" href="logout.php" style="border-radius: 20px;">
                        Logout
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>