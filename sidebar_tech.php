<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="d-flex flex-column p-3 sidebar-wrapper" style="width: 280px; min-height: 100vh;">

    <a href="db_technician.php" class="d-flex align-items-center mb-5 mt-2 text-white text-decoration-none px-2">
        <img src="deped_rov.jpg" alt="DepEd Logo" class="me-3 bg-white rounded-circle p-1" style="width: 50px; height: 50px; object-fit: cover;">
        <div class="d-flex flex-column">
            <span class="fs-6 fw-bold" style="letter-spacing: -0.5px;">ICT Technician</span>
            <span style="font-size: 10px; color: #adb5bd; letter-spacing: 0.5px;">REGION V - BICOL</span>
        </div>
    </a>

    <ul class="nav nav-pills flex-column mb-auto">
        <li class="nav-item">
            <a href="db_technician.php" class="sidebar-link <?php echo $currentPage == 'db_technician.php' ? 'active-sidebar' : ''; ?>">
                <i class="bi bi-speedometer2"></i> My Tasks
            </a>
        </li>
        <li class="nav-item">
            <a href="all_tickets.php" class="sidebar-link <?php echo $currentPage == 'all_tickets.php' ? 'active-sidebar' : ''; ?>">
                <i class="bi bi-ticket-detailed"></i> All Tickets
            </a>
        </li>
        <li class="nav-item">
            <a href="all_tickets.php" class="sidebar-link <?php echo $currentPage == 'all_tickets.php' ? 'active-sidebar' : ''; ?>">
                <i class="bi bi-person-plus"></i> Account Requests
            </a>
        <li class="nav-item">
            <a href="starlink_officer.php" class="sidebar-link <?php echo $currentPage == 'starlink_officer.php' ? 'active-sidebar' : ''; ?>">
                <i class="bi bi-hdd-network-fill"></i> Starlink Inventory
            </a>
        </li>
        </li>
    </ul>

    <div class="mt-auto mb-3">
        <a href="logout.php" class="sidebar-link">
            <i class="bi bi-box-arrow-left"></i> Sign Out
        </a>
    </div>
</div>