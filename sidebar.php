<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once 'db.php';

$role = $_SESSION['role'] ?? 'User';

$displayName = htmlspecialchars($_SESSION['fullname'] ?? 'User');

$currentPage = basename($_SERVER['PHP_SELF']);

function isActive($pageName, $currentPage)
{
    return ($currentPage === $pageName) ? 'active bg-success text-white' : 'text-light opacity-75 custom-hover';
}
?>

<style>
    .sidebar-container {
        width: 280px;
        height: 100vh;
        background-color: #1e2125;
        position: fixed;
        display: flex;
        flex-direction: column;
        z-index: 1000;
    }

    .custom-hover:hover {
        opacity: 1 !important;
        background-color: rgba(255, 255, 255, 0.08);
        border-radius: 6px;
    }

    .nav-link {
        padding: 12px 20px;
        font-size: 0.95rem;
        font-weight: 500;
        border-radius: 6px;
        margin-bottom: 5px;
        transition: all 0.2s ease;
    }

    .sidebar-nav-scroll::-webkit-scrollbar {
        display: none;
    }

    .sidebar-nav-scroll {
        -ms-overflow-style: none;
        scrollbar-width: none;
    }
</style>

<div class="sidebar-container text-white shadow-lg">

    <div class="p-4 d-flex align-items-center gap-3 border-bottom border-secondary border-opacity-25 mb-3">
        <img src="deped_rov.jpg" alt="Logo" class="bg-white rounded-circle p-1 shadow-sm" style="width: 48px; height: 48px; object-fit: contain;">
        <div class="lh-1">
            <div class="fw-bold fs-6 mb-1 text-white">DepEd Helpdesk</div>
            <div style="font-size: 0.65rem;" class="text-uppercase text-light opacity-75">Region V - Bicol</div>
        </div>
    </div>

    <div class="px-3 mb-2">
        <span class="badge bg-secondary bg-opacity-40 border border-secondary border-opacity-50 text-light w-100 py-2 fs-7" style="font-size: 0.8rem;">
            <?php
            if ($role === 'Officer') echo 'ICT Officer';
            elseif ($role === 'Technician') echo 'ICT Technician';
            else echo 'Staff / User';
            ?>
        </span>
    </div>

    <div class="flex-grow-1 overflow-auto sidebar-nav-scroll px-3 py-2">
        <ul class="nav flex-column mb-auto">

            <?php if ($role === 'Officer'): ?>
                <li class="nav-item">
                    <a href="db_officer.php" class="nav-link <?= isActive('db_officer.php', $currentPage) ?>">
                        <i class="bi bi-speedometer2 me-3 fs-5 align-middle"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="all_tickets.php" class="nav-link <?= isActive('all_tickets.php', $currentPage) ?>">
                        <i class="bi bi-ticket-detailed me-3 fs-5 align-middle"></i> All Tickets
                    </a>
                </li>
                <li class="nav-item">
                    <a href="reports.php" class="nav-link <?= isActive('reports.php', $currentPage) ?>">
                        <i class="bi bi-bar-chart-line me-3 fs-5 align-middle"></i> Reports
                    </a>
                </li>
                <li class="nav-item">
                    <a href="starlink.php" class="nav-link <?= isActive('starlink.php', $currentPage) ?>">
                        <i class="bi bi-router me-3 fs-5 align-middle"></i> Starlink Inventory
                    </a>
                </li>
                <li class="nav-item">
                    <a href="add_user.php" class="nav-link <?= isActive('add_user.php', $currentPage) ?>">
                        <i class="bi bi-people me-3 fs-5 align-middle"></i> Manage Users
                    </a>
                </li>

            <?php elseif ($role === 'Technician'): ?>
                <li class="nav-item">
                    <a href="db_technician.php" class="nav-link <?= isActive('db_technician.php', $currentPage) ?>">
                        <i class="bi bi-tools me-3 fs-5 align-middle"></i> My Tasks
                    </a>
                </li>
                <li class="nav-item">
                    <a href="all_tickets.php" class="nav-link <?= isActive('all_tickets.php', $currentPage) ?>">
                        <i class="bi bi-ticket-detailed me-3 fs-5 align-middle"></i> All Tickets
                    </a>
                </li>
                <li class="nav-item">
                    <a href="account_requests.php" class="nav-link <?= isActive('account_requests.php', $currentPage) ?>">
                        <i class="bi bi-person-badge me-3 fs-5 align-middle"></i> Account Requests
                    </a>
                </li>
                <li class="nav-item">
                    <a href="starlink.php" class="nav-link <?= isActive('starlink.php', $currentPage) ?>">
                        <i class="bi bi-router me-3 fs-5 align-middle"></i> Starlink Inventory
                    </a>
                </li>

            <?php else: ?>
                <li class="nav-item">
                    <a href="db_user.php" class="nav-link <?= isActive('db_user.php', $currentPage) ?>">
                        <i class="bi bi-speedometer2 me-3 fs-5 align-middle"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a href="ticket_history.php" class="nav-link <?= isActive('ticket_history.php', $currentPage) ?>">
                        <i class="bi bi-ticket-detailed me-3 fs-5 align-middle"></i> Ticket History
                    </a>
                </li>
                <li class="nav-item">
                    <a href="create_ticket.php" class="nav-link <?= isActive('create_ticket.php', $currentPage) ?>">
                        <i class="bi bi-plus-circle me-3 fs-5 align-middle"></i> Submit Ticket
                    </a>
                </li>
                <li class="nav-item">
                    <a href="request_account.php" class="nav-link <?= isActive('request_account.php', $currentPage) ?>">
                        <i class="bi bi-person-plus me-3 fs-5 align-middle"></i> Account Request
                    </a>
                </li>
                <li class="nav-item">
                    <a href="starlink_user.php" class="nav-link <?= isActive('starlink_user.php', $currentPage) ?>">
                        <i class="bi bi-router me-3 fs-5 align-middle"></i> Borrow Starlink
                    </a>
                </li>
            <?php endif; ?>

        </ul>
    </div>

    <div class="border-top border-secondary border-opacity-25 mt-auto bg-dark bg-opacity-50 shadow-inner">
        <div class="p-3 d-flex align-items-center justify-content-between">

            <div class="d-flex align-items-center gap-3">
                <div class="rounded-circle border border-secondary border-opacity-75 d-flex align-items-center justify-content-center text-secondary bg-secondary bg-opacity-20 shadow-inner" style="width: 42px; height: 42px;">
                    <i class="bi bi-person fs-5"></i>
                </div>
                <div class="fw-bold text-white fs-6" style="font-size: 0.95rem;">
                    <?php echo $displayName; ?>
                </div>
            </div>

            <a href="logout.php" class="text-light opacity-75 custom-hover p-2 d-inline-block rounded-circle" title="Sign Out">
                <i class="bi bi-box-arrow-left fs-5 align-middle"></i>
            </a>
        </div>
    </div>
</div>