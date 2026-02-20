<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px; height: 100vh; position: fixed;">
    <a href="db_technician.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <i class="bi bi-tools fs-4 me-2"></i>
        <span class="fs-5 fw-bold">Technician</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-2">
            <a href="db_technician.php" class="nav-link active d-flex align-items-center">
                <i class="bi bi-list-task me-3 fs-5"></i>
                My Assigned Tasks
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="users.php" class="nav-link d-flex align-items-center <?php echo ($page == 'users') ? 'active' : 'text-white'; ?>">
                <i class="bi bi-people-fill me-3 fs-5"></i>
                Manage Users
            </a>
        </li>
    </ul>
    <hr>
    <div class="dropdown">
        <a href="logout.php" class="d-flex align-items-center text-white text-decoration-none">
            <i class="bi bi-box-arrow-right me-2"></i>
            <strong>Logout</strong>
        </a>
    </div>
</div>