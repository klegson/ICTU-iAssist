<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
require 'db.php';

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
        die("Error: Ticket not found or access denied.");
    }
} else {
    header("Location: db_user.php");
    exit;
}

$statusColor = 'bg-secondary';
if ($row['status'] == 'Pending') $badgeClass = 'bg-warning text-dark';
if ($row['status'] == 'Approved by IT') $badgeClass = 'bg-info text-dark';
if ($row['status'] == 'Processing') $badgeClass = 'bg-primary';
if ($row['status'] == 'Completed') $badgeClass = 'bg-success';

$created = date("F d, Y • h:i A", strtotime($ticket['createdAt']));
$updated = $ticket['updatedAt'] ? date("F d, Y • h:i A", strtotime($ticket['updatedAt'])) : 'No updates yet';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Ticket #<?php echo $ticketId; ?> - DepEd Helpdesk</title>
    <link rel="icon" href="deped_logo.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <?php include 'header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-xl-2 d-none d-lg-block p-0">
                <?php include 'sidebar_user.php'; ?>
            </div>

            <div class="col-lg-9 col-xl-10 py-4">
                <div class="container">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div>
                            <h4 class="fw-bold text-dark mb-1">Ticket #<?php echo $ticketId; ?></h4>
                            <span class="text-muted">Created on <?php echo $created; ?></span>
                        </div>
                        <a href="db_user.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i> Back to Dashboard
                        </a>
                    </div>

                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body p-4">
                                <h5 class="fw-bold text-primary mb-3"><?php echo htmlspecialchars($ticket['subject']); ?></h5>

                                <div class="p-3 bg-light rounded border">
                                    <p class="mb-0" style="white-space: pre-line;"><?php echo htmlspecialchars($ticket['description']); ?></p>
                                </div>
                            </div>
                        </div> <?php if (!empty($ticket['remarks'])): ?>
                            <div class="card mt-4 border-primary shadow-sm">
                                <div class="card-header bg-primary text-white fw-bold">
                                    <i class="bi bi-chat-dots-fill me-2"></i> Officer's Response
                                </div>
                                <div class="card-body bg-light">
                                    <p class="mb-0 text-dark" style="white-space: pre-line;">
                                        <?php echo htmlspecialchars($ticket['remarks']); ?>
                                    </p>
                                    <div class="text-end mt-2">
                                        <small class="text-muted fst-italic">
                                            - Updated by ICT Officer on <?php echo date("M d, Y • h:i A", strtotime($ticket['updatedAt'])); ?>
                                        </small>
                                    </div>
                                </div>
                            </div>
                        <?php endif; ?>
                    </div>
                    <div class="col-lg-4">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white fw-bold py-3">
                                Ticket Information
                            </div>
                            <div class="card-body">
                                <div class="mb-3">
                                    <label class="small text-muted fw-bold d-block">STATUS</label>
                                    <span class="badge <?php echo $statusColor; ?> px-3 py-2 mt-1">
                                        <?php echo $ticket['status']; ?>
                                    </span>
                                </div>

                                <div class="mb-3">
                                    <label class="small text-muted fw-bold d-block">CATEGORY</label>
                                    <span class="fs-6"><?php echo htmlspecialchars($ticket['categoryName'] ?? 'General'); ?></span>
                                </div>

                                <div class="mb-3">
                                    <label class="small text-muted fw-bold d-block">LAST UPDATED</label>
                                    <span class="small text-dark"><?php echo $updated; ?></span>
                                </div>

                                <?php if ($ticket['status'] == 'Pending'): ?>
                                    <hr>
                                    <div class="d-grid gap-2">
                                        <a href="edit_ticket.php?id=<?php echo $ticketId; ?>" class="btn btn-outline-primary btn-sm">
                                            <i class="bi bi-pencil me-1"></i> Edit Ticket
                                        </a>
                                        <a href="delete_ticket.php?id=<?php echo $ticketId; ?>"
                                            class="btn btn-outline-danger btn-sm"
                                            onclick="return confirm('Are you sure?')">
                                            <i class="bi bi-trash me-1"></i> Cancel Request
                                        </a>
                                    </div>
                                <?php endif; ?>

                            </div>
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