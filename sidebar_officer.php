<div class="d-flex flex-column flex-shrink-0 p-3 text-white bg-dark" style="width: 280px; height: 100vh; position: fixed;">
    <a href="db_officer.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <img src="deped_rov.jpg" alt="Logo" class="me-3 rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover; border: 2px solid white;">
        <span class="fs-5 fw-bold">ICT Officer</span>
    </a>
    <hr>
    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item mb-2">
            <a href="db_officer.php" class="nav-link d-flex align-items-center <?php echo ($page == 'dashboard') ? 'active' : 'text-white'; ?>">
                <i class="bi bi-speedometer2 me-3 fs-5"></i>
                Dashboard
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="all_tickets.php" class="nav-link d-flex align-items-center <?php echo ($page == 'all_tickets') ? 'active' : 'text-white'; ?>">
                <i class="bi bi-ticket-detailed me-3 fs-5"></i>
                All Tickets
            </a>
        </li>
        <li class="nav-item mb-2">
            <a href="reports.php" class="nav-link d-flex align-items-center <?php echo ($page == 'reports') ? 'active' : 'text-white'; ?>">
                <i class="bi bi-bar-chart-line me-3 fs-5"></i>
                Reports
            </a>
        <li class="nav-item mb-2">
            <a href="starlink_officer.php" class="nav-link d-flex align-items-center <?php echo ($page == 'starlink') ? 'active' : 'text-white'; ?>">
                <i class="bi bi-people-fill me-3 fs-5"></i>
                Starlink Inventory
            </a>
        </li>
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