<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$userId = $_SESSION['user_id'];

if (isset($_GET['id'])) {
    $ticketId = $_GET['id'];
    $sql = "SELECT t.*, c.categoryName 
            FROM ticket t 
            LEFT JOIN category c ON t.categoryId = c.categoryId 
            WHERE t.ticketId = ? AND t.userId = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ticketId, $userId]);
    $ticket = $stmt->fetch();

    if (!$ticket) {
        header("Location: db_user.php");
        exit;
    }
} else {
    header("Location: db_user.php");
    exit;
}

$statusColor = 'bg-secondary';
if ($ticket['status'] == 'Pending') $statusColor = 'bg-warning text-dark';
if ($ticket['status'] == 'Approved by IT') $statusColor = 'bg-info text-dark';
if ($ticket['status'] == 'Processing') $statusColor = 'bg-primary';
if ($ticket['status'] == 'Completed' || $ticket['status'] == 'Resolved') $statusColor = 'bg-success';

$created = date("F d, Y • h:i A", strtotime($ticket['createdAt']));
$updated = $ticket['updatedAt'] ? date("F d, Y • h:i A", strtotime($ticket['updatedAt'])) : 'No updates yet';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Ticket #<?php echo $ticketId; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="d-flex" style="min-height: 100vh;">
        <div style="width: 280px; flex-shrink: 0;">
            <?php include 'sidebar_user.php'; ?>
        </div>

        <div class="flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">Ticket #<?php echo $ticketId; ?></h2>
                        <p class="text-muted">Created on <?php echo $created; ?></p>
                    </div>
                    <a href="db_user.php" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="custom-card p-5 mb-4 border-top-info">
                            <h5 class="fw-bold text-dark mb-4"><?php echo htmlspecialchars($ticket['subject']); ?></h5>
                            <div class="p-4 bg-light rounded border">
                                <p class="mb-0 text-dark" style="white-space: pre-line;"><?php echo htmlspecialchars($ticket['description']); ?></p>
                            </div>
                        </div>

                        <?php if (!empty($ticket['remarks'])): ?>
                            <div class="custom-card p-5 border-top-success">
                                <h6 class="fw-bold text-dark mb-4"><i class="bi bi-chat-dots-fill me-2 text-success"></i> Officer's Response</h6>
                                <div class="p-4 bg-light rounded border border-success border-opacity-25">
                                    <p class="mb-0 text-dark" style="white-space: pre-line;">
                                        <?php echo htmlspecialchars($ticket['remarks']); ?>
                                    </p>
                                    <div class="text-end mt-3 pt-3 border-top">
                                        <small class="text-muted fst-italic">
                                            Updated by ICT Officer on <?php echo date("M d, Y • h:i A", strtotime($ticket['updatedAt'])); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-lg-4">
                        <div class="custom-card p-4">
                            <h6 class="fw-bold text-dark border-bottom pb-3 mb-4">Ticket Information</h6>
                            <div class="mb-4">
                                <label class="small text-muted fw-bold d-block mb-2">STATUS</label>
                                <span class="badge <?php echo $statusColor; ?> px-3 py-2 fs-6 rounded-pill">
                                    <?php echo $ticket['status']; ?>
                                </span>
                            </div>
                            <div class="mb-4">
                                <label class="small text-muted fw-bold d-block mb-1">CATEGORY</label>
                                <span class="text-dark fw-bold"><?php echo htmlspecialchars($ticket['categoryName'] ?? 'General'); ?></span>
                            </div>
                            <div class="mb-4">
                                <label class="small text-muted fw-bold d-block mb-1">LAST UPDATED</label>
                                <span class="text-dark"><?php echo $updated; ?></span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>