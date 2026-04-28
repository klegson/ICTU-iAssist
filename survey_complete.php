<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$ticketId = $_GET['ticket_id'] ?? null;
$success = $_GET['success'] ?? false;

if ($ticketId) {
    $stmt = $pdo->prepare("SELECT * FROM ticket WHERE ticketId = ? AND userId = ?");
    $stmt->execute([$ticketId, $_SESSION['user_id']]);
    $ticket = $stmt->fetch();

    if ($ticket && $success === '1') {
        $updateStmt = $pdo->prepare("UPDATE ticket SET status = 'Completed', completedAt = NOW() WHERE ticketId = ?");
        $updateStmt->execute([$ticketId]);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Survey Complete - DepEd Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body class="bg-light">
    <div class="container py-5">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <div class="card shadow-lg border-0 rounded-4">
                    <div class="card-body text-center p-5">
                        <?php if ($success === '1'): ?>
                            <div class="mb-4">
                                <i class="bi bi-check-circle-fill text-success" style="font-size: 5rem;"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-3">Thank You!</h3>
                            <p class="text-muted mb-4">Your feedback has been submitted successfully. Your ticket #<?php echo htmlspecialchars($ticketId); ?> has been marked as completed.</p>
                        <?php else: ?>
                            <div class="mb-4">
                                <i class="bi bi-exclamation-circle text-warning" style="font-size: 5rem;"></i>
                            </div>
                            <h3 class="fw-bold text-dark mb-3">Survey Skipped</h3>
                            <p class="text-muted mb-4">Your ticket #<?php echo htmlspecialchars($ticketId); ?> will remain as resolved until you complete the survey.</p>
                        <?php endif; ?>
                        <a href="index.php" class="btn btn-deped-primary px-5 py-2">
                            <i class="bi bi-house me-2"></i>Return to Dashboard
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>