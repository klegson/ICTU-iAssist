<?php
$currentPage = basename($_SERVER['PHP_SELF']);
?>

<div class="d-flex flex-column p-3 text-white h-100 shadow" style="background-color: #28282B; min-height: 100vh;">

    <a href="db_user.php" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto text-white text-decoration-none">
        <img src="deped_rov.jpg" alt="DepEd Logo" class="me-3 rounded-circle shadow-sm" style="width: 50px; height: 50px; object-fit: cover; border: 2px solid white;">
        <div class="d-flex flex-column">
            <span class="fs-5 fw-bold" style="letter-spacing: -0.5px;">DepEd Helpdesk</span>
            <span style="font-size: 10px; opacity: 0.7;">REGION V - BICOL</span>
        </div>
    </a>

    <hr class="text-white-50 my-3">

    <ul class="nav nav-pills flex-column mb-auto">

        <li class="nav-item mb-2">
            <a href="db_user.php" class="nav-link d-flex align-items-center <?php echo $currentPage == 'db_user.php' ? 'active-sidebar' : 'text-white'; ?>">
                <i class="bi bi-speedometer2 me-3 fs-5"></i>
                Dashboard
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="create_ticket.php" class="nav-link d-flex align-items-center <?php echo $currentPage == 'create_ticket.php' ? 'active-sidebar' : 'text-white'; ?>">
                <i class="bi bi-tools me-3 fs-5"></i>
                Submit Ticket
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="request_account.php" class="nav-link d-flex align-items-center <?php echo $currentPage == 'request_account.php' ? 'active-sidebar' : 'text-white'; ?>">
                <i class="bi bi-person-plus-fill me-3 fs-5"></i>
                Account Creation
            </a>
        </li>

        <li class="nav-item mb-2">
            <a href="request_account.php" class="nav-link d-flex align-items-center <?php echo $currentPage == 'request_account.php' ? 'active-sidebar' : 'text-white'; ?>">
                <i class="bi bi-box-seam me-3 fs-5"></i>
                Borrow Starlink
            </a>
        </li>
    </ul>

    <hr class="text-white-50">

    <div class="mt-auto">
        <a href="logout.php" class="nav-link text-white-50 d-flex align-items-center hover-danger ps-3">
            <i class="bi bi-box-arrow-left me-3 fs-5"></i>
            Sign Out
        </a>
    </div>
</div>