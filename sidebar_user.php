<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="d-flex flex-column p-3 sidebar-wrapper" style="width: 280px; min-height: 100vh;">

    <a href="db_user.php" class="d-flex align-items-center mb-5 mt-2 text-white text-decoration-none px-2">
        <img src="deped_rov.jpg" alt="DepEd Logo" class="me-3 bg-white rounded-circle p-1" style="width: 50px; height: 50px; object-fit: cover;">
        <div class="d-flex flex-column">
            <span class="fs-6 fw-bold" style="letter-spacing: -0.5px;">DepEd Helpdesk</span>
            <span style="font-size: 10px; color: #adb5bd; letter-spacing: 0.5px;">REGION V - BICOL</span>
        </div>
    </a>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="db_user.php" class="sidebar-link <?php echo $currentPage == 'db_user.php' ? 'active-sidebar' : ''; ?>">
                <i class="bi bi-speedometer2"></i> Dashboard
            </a>
        </li>
        <li class="nav-item">
            <a href="create_ticket.php" class="sidebar-link <?php echo $currentPage == 'create_ticket.php' ? 'active-sidebar' : ''; ?>">
                <i class="bi bi-tools"></i> Submit Ticket
            </a>
        </li>
        <li class="nav-item">
            <a href="request_account.php" class="sidebar-link <?php echo $currentPage == 'request_account.php' ? 'active-sidebar' : ''; ?>">
                <i class="bi bi-person-plus-fill"></i> Account Creation
            </a>
        </li>
        <li class="nav-item">
            <a href="starlink_user.php" class="sidebar-link <?php echo $currentPage == 'starlink_user.php' ? 'active-sidebar' : ''; ?>">
                <i class="bi bi-box-seam"></i> Borrow Starlink
            </a>
        </li>
    </ul>

    <div class="mt-auto mb-3">
        <a href="logout.php" class="sidebar-link">
            <i class="bi bi-box-arrow-left"></i> Sign Out
        </a>
    </div>
</div>