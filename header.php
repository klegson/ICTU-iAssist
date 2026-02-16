<nav class="navbar navbar-expand-lg navbar-dark bg-dark shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold" href="#">DepEd Helpdesk</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto">
                <li class="nav-item">
                    <span class="nav-link text-light">Welcome, <?php echo htmlspecialchars($_SESSION['fullname']); ?></span>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-danger btn-sm ms-lg-3 px-3 text-white" href="logout.php">Logout</a>
                </li>
            </ul>
        </div>
    </div>
</nav>