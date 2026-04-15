<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$ticketId = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_ticket'])) {

    $updateStmt = $pdo->prepare("UPDATE ticket SET status = 'Completed', completedAt = NOW() WHERE ticketId = ?");
    $updateStmt->execute([$ticketId]);

    $ratingFormUrl = "https://forms.office.com/pages/responsepage.aspx?id=gKvjQCQgo0W_dnoHYaJNKZVrGLcKRchGg0_5vlA39MhURDc2OU5GTENEVEw2WlJPU1JYSDRXWVZBVi4u&fbclid=IwY2xjawQ4BhJleHRuA2FlbQIxMQBzcnRjBmFwcF9pZAEwAAEedg_x-eXFRIhH_vGN-i5EcJPnnK3SJsm-pas3RiutNgoLpQXl3qs5X9SiMPo_aem_d4c3vnysQcAzOumLodpZcA";
    header("Location: " . $ratingFormUrl);
    exit;
}

$sql = "SELECT t.*, u.firstName, u.lastName, u.email, d.departmentName, c.categoryName,
        tech.firstName AS techFirstName, tech.lastName AS techLastName
        FROM ticket t 
        JOIN users u ON t.userId = u.userId 
        LEFT JOIN department d ON u.departmentId = d.departmentId
        LEFT JOIN category c ON t.categoryId = c.categoryId 
        LEFT JOIN users tech ON t.resolvedBy = tech.userId
        WHERE t.ticketId = ?";

if ($role === 'User') {
    $sql .= " AND t.userId = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ticketId, $userId]);
} else {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ticketId]);
}

$ticket = $stmt->fetch();

if (!$ticket) {
    echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
            <h3>Ticket not found</h3>
            <p>This ticket does not exist or you do not have permission to view it.</p>
            <a href='index.php'>Return to Dashboard</a>
          </div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>View Ticket #<?php echo htmlspecialchars($ticketId); ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="d-flex" style="min-height: 100vh;">
        <div style="width: 280px; flex-shrink: 0;">
            <?php include 'sidebar.php'; ?>
        </div>

        <div class="flex-grow-1 bg-light" style="max-height: 100vh; overflow-y: auto;">

            <div class="container-fluid py-5 px-5">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="fw-bold text-dark mb-0">Ticket #<?php echo htmlspecialchars($ticketId); ?></h2>
                        <div class="text-muted small mt-1">Created on <?php echo date("F d, Y \• h:i A", strtotime($ticket['createdAt'])); ?></div>
                    </div>
                    <a href="ticket_history.php" class="btn btn-outline-secondary px-4 bg-white"><i class="bi bi-arrow-left me-2"></i>Back to Dashboard</a>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">

                        <div class="card border-0 shadow-sm rounded-4 mb-4" style="border-top: 4px solid #0dcaf0 !important;">
                            <div class="card-body p-4 p-md-5">
                                <h4 class="fw-bold text-dark mb-4"><?php echo htmlspecialchars($ticket['subject']); ?></h4>
                                <div class="p-4 bg-light rounded-3 border text-dark" style="font-size: 1rem; line-height: 1.6;">
                                    <?php echo nl2br(htmlspecialchars($ticket['description'])); ?>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($ticket['remarks'])): ?>
                            <div class="card border-0 shadow-sm rounded-4" style="border-top: 4px solid #198754 !important;">
                                <div class="card-body p-4 p-md-5">
                                    <h6 class="fw-bold mb-4 text-success"><i class="bi bi-chat-dots-fill me-2"></i>Officer's Response</h6>

                                    <div class="p-4 border rounded-3 bg-white mb-3 text-dark" style="min-height: 100px;">
                                        <?php echo nl2br(htmlspecialchars($ticket['remarks'])); ?>
                                    </div>

                                    <?php if ($ticket['resolvedAt']): ?>
                                        <div class="text-end mt-4">
                                            <div class="small text-muted fst-italic">Remarks posted on:</div>
                                            <div class="fw-bold text-dark"><?php echo date("F d, Y \a\\t h:i A", strtotime($ticket['resolvedAt'])); ?></div>
                                        </div>
                                    <?php endif; ?>

                                </div>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                            <h6 class="fw-bold mb-4 text-dark border-bottom pb-3">Ticket Information</h6>

                            <div class="mb-4">
                                <label class="small text-muted fw-bold text-uppercase mb-2">Status</label>
                                <div>
                                    <?php
                                    $statusBadge = 'bg-secondary text-white';
                                    if ($ticket['status'] == 'Pending') $statusBadge = 'bg-warning text-dark';
                                    if ($ticket['status'] == 'Processing') $statusBadge = 'bg-primary text-white';
                                    if ($ticket['status'] == 'Resolved') $statusBadge = 'bg-success bg-opacity-25 text-success border border-success border-opacity-50';
                                    if ($ticket['status'] == 'Completed') $statusBadge = 'bg-success text-white';
                                    ?>
                                    <span class="badge <?php echo $statusBadge; ?> fs-6 px-4 py-2 rounded-pill shadow-sm">
                                        <?php echo htmlspecialchars($ticket['status']); ?>
                                    </span>
                                </div>
                            </div>

                            <?php if (!empty($ticket['techFirstName'])): ?>
                                <div class="mb-4">
                                    <label class="small text-muted fw-bold text-uppercase mb-1">Resolved By</label>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($ticket['techFirstName'] . ' ' . $ticket['techLastName']); ?></div>
                                    <?php if ($ticket['resolvedAt']): ?>
                                        <div class="small text-muted">At <?php echo date("M d, Y h:i A", strtotime($ticket['resolvedAt'])); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($ticket['resolvedAt']): ?>
                                <div class="mb-4">
                                    <label class="small text-muted fw-bold text-uppercase mb-1">Days Resolved</label>
                                    <div class="text-dark fw-bold">
                                        <?php
                                        $created = new DateTime($ticket['createdAt']);
                                        $resolved = new DateTime($ticket['resolvedAt']);
                                        $diff = $created->diff($resolved);
                                        echo $diff->days . " Day" . ($diff->days != 1 ? 's' : '');
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($ticket['completedAt']): ?>
                                <div class="mb-4">
                                    <label class="small text-muted fw-bold text-uppercase mb-1">Confirmed Completed</label>
                                    <div class="text-success fw-medium small">
                                        <i class="bi bi-check-circle-fill me-1"></i> <?php echo date("M d, Y \a\\t h:i A", strtotime($ticket['completedAt'])); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="mb-4">
                                <label class="small text-muted fw-bold text-uppercase mb-2">Category</label>
                                <div class="text-dark fs-6"><?php echo htmlspecialchars($ticket['categoryName'] ?? 'Uncategorized'); ?></div>
                            </div>

                            <div class="mb-2">
                                <label class="small text-muted fw-bold text-uppercase mb-2">Requestor</label>
                                <div class="text-dark"><?php echo htmlspecialchars($ticket['firstName'] . ' ' . $ticket['lastName']); ?></div>
                                <div class="small text-muted"><?php echo htmlspecialchars($ticket['departmentName'] ?? 'No Department'); ?></div>
                            </div>
                        </div>

                        <?php if ($ticket['status'] === 'Resolved' && $role === 'User'): ?>
                            <div class="card border-0 shadow-sm rounded-4 p-4 text-center" style="background-color: #f8f9fa; border: 2px dashed #198754 !important;">
                                <div class="mb-3 text-success">
                                    <i class="bi bi-check2-circle" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-2">Is it working now?</h5>
                                <p class="text-muted small mb-4">Please confirm that your issue has been fully resolved by the ICT team.</p>

                                <form method="POST">
                                    <button type="submit" name="complete_ticket" class="btn btn-success fw-bold w-100 py-3 shadow-sm rounded-3">
                                        Mark as Completed
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>

                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>