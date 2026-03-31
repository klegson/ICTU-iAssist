<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$notifCount = 0;
$notifications = [];
$actionRequired = [];

if (isset($_SESSION['user_id']) && isset($pdo)) {
    $userId = $_SESSION['user_id'];

    $nSql = "SELECT notifId, message, isRead, createdAt FROM notification WHERE userId = ? ORDER BY createdAt DESC LIMIT 8";
    $nStmt = $pdo->prepare($nSql);
    $nStmt->execute([$userId]);
    $notifications = $nStmt->fetchAll();

    $cStmt = $pdo->prepare("SELECT COUNT(*) FROM notification WHERE userId = ? AND isRead = 0");
    $cStmt->execute([$userId]);
    $notifCount = $cStmt->fetchColumn();

    $arStmt = $pdo->prepare("SELECT ticketId, updatedAt FROM ticket WHERE userId = ? AND status = 'Resolved' ORDER BY updatedAt DESC");
    $arStmt->execute([$userId]);
    $actionRequired = $arStmt->fetchAll();

    $totalAlerts = $notifCount + count($actionRequired);
}
?>
<nav class="navbar navbar-expand-lg header-deped shadow-sm">
    <div class="container-fluid px-4">
        <button class="navbar-toggler border-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon" style="filter: invert(1);"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav ms-auto align-items-center py-2">

                <li class="nav-item dropdown me-4">
                    <a class="nav-link text-white position-relative d-flex align-items-center" href="#" id="notifDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-bell-fill fs-5"></i>
                        <?php if (isset($totalAlerts) && $totalAlerts > 0): ?>
                            <span class="position-absolute top-25 start-75 translate-middle badge rounded-pill bg-danger border border-light" style="font-size: 0.65rem;">
                                <?php echo $totalAlerts > 99 ? '99+' : $totalAlerts; ?>
                            </span>
                        <?php endif; ?>
                    </a>

                    <ul class="dropdown-menu dropdown-menu-end shadow-lg border-0 mt-2" aria-labelledby="notifDropdown" style="width: 350px; max-height: 400px; overflow-y: auto;">
                        <li>
                            <div class="dropdown-header d-flex justify-content-between align-items-center fw-bold text-dark border-bottom pb-2">
                                <span>Notifications</span>
                                <?php if (isset($totalAlerts) && $totalAlerts > 0): ?>
                                    <span class="badge bg-primary rounded-pill"><?php echo $totalAlerts; ?> New</span>
                                <?php endif; ?>
                            </div>
                        </li>

                        <?php if (count($actionRequired) > 0): ?>
                            <?php foreach ($actionRequired as $ar): ?>
                                <li>
                                    <a class="dropdown-item py-3 border-bottom text-wrap custom-notif-hover bg-warning bg-opacity-10" href="view_ticket.php?id=<?php echo $ar['ticketId']; ?>">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="mt-1">
                                                <i class="bi bi-exclamation-circle-fill text-warning fs-5"></i>
                                            </div>
                                            <div>
                                                <div class="small fw-bold text-dark mb-1" style="line-height: 1.4; white-space: normal;">
                                                    Action Required: Ticket #<?php echo $ar['ticketId']; ?> resolved! Please review and confirm completion.
                                                </div>
                                                <div class="text-secondary" style="font-size: 0.7rem;"><i class="bi bi-hourglass-split me-1"></i>Waiting for your confirmation</div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (count($notifications) > 0): ?>
                            <?php foreach ($notifications as $n): ?>
                                <?php
                                $timeAgo = date("M d, Y h:i A", strtotime($n['createdAt']));
                                $bgClass = ($n['isRead'] == 0) ? 'bg-light' : 'bg-white';
                                $textWeight = ($n['isRead'] == 0) ? 'fw-bold text-dark' : 'text-muted';

                                $ticketId = null;
                                if (preg_match('/#(\d+)/', $n['message'], $matches)) {
                                    $ticketId = $matches[1];
                                }
                                if ($ticketId) {
                                    $finalUrl = ($_SESSION['role'] === 'Officer' || $_SESSION['role'] === 'Technician')
                                        ? "manage_ticket.php?id=" . $ticketId
                                        : "view_ticket.php?id=" . $ticketId;
                                } else {
                                    $finalUrl = "#";
                                }

                                $targetUrl = "read_notif.php?id=" . $n['notifId'] . "&url=" . urlencode($finalUrl);
                                ?>
                                <li>
                                    <a class="dropdown-item py-3 border-bottom text-wrap custom-notif-hover <?php echo $bgClass; ?>" href="<?php echo $targetUrl; ?>">
                                        <div class="d-flex align-items-start gap-3">
                                            <div class="mt-1">
                                                <?php if ($n['isRead'] == 0): ?>
                                                    <i class="bi bi-circle-fill text-primary" style="font-size: 0.6rem;"></i>
                                                <?php else: ?>
                                                    <i class="bi bi-check2-all text-success fs-5"></i>
                                                <?php endif; ?>
                                            </div>
                                            <div>
                                                <div class="small <?php echo $textWeight; ?> mb-1" style="line-height: 1.4; white-space: normal;">
                                                    <?php echo htmlspecialchars($n['message']); ?>
                                                </div>
                                                <div class="text-secondary" style="font-size: 0.7rem;"><i class="bi bi-clock me-1"></i><?php echo $timeAgo; ?></div>
                                            </div>
                                        </div>
                                    </a>
                                </li>
                            <?php endforeach; ?>
                        <?php elseif (count($actionRequired) == 0): ?>
                            <li>
                                <div class="dropdown-item text-muted text-center py-4 small"><i class="bi bi-bell-slash fs-3 d-block mb-2 text-light"></i>No new notifications.</div>
                            </li>
                        <?php endif; ?>
                    </ul>
                </li>
                <li class="nav-item">
                    <a class="nav-link btn btn-logout fw-bold px-3 py-1" href="logout.php">
                        LOGOUT
                    </a>
                </li>
            </ul>
        </div>
    </div>
</nav>

<style>
    .custom-notif-hover:hover {
        background-color: #f1f3f5 !important;
    }
</style>