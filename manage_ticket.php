<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Officer', 'Technician'])) {
    header("Location: index.php");
    exit;
}

$ticketId = $_GET['id'] ?? null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_ticket'])) {
    $remarks = trim($_POST['remarks']);

    $updateStmt = $pdo->prepare("UPDATE ticket SET status = 'Resolved', remarks = ? WHERE ticketId = ?");

    if ($updateStmt->execute([$remarks, $ticketId])) {
        if ($_SESSION['role'] === 'Officer') {
            header("Location: db_officer.php");
        } else {
            header("Location: db_technician.php");
        }
        exit;
    }
}

$sql = "SELECT t.*, u.firstName, u.lastName, d.departmentName 
        FROM ticket t 
        JOIN users u ON t.userId = u.userId 
        LEFT JOIN department d ON u.departmentId = d.departmentId
        WHERE t.ticketId = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$ticketId]);
$ticket = $stmt->fetch();

if (!$ticket) {
    echo "<div class='text-center mt-5'><h3>Ticket not found</h3></div>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Ticket #<?php echo htmlspecialchars($ticketId); ?></title>
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

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark mb-0">Processing Ticket #<?php echo htmlspecialchars($ticketId); ?></h2>
                    <button onclick="history.back()" class="btn btn-outline-secondary px-4 bg-white">Back</button>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">

                        <div class="card border-0 shadow-sm rounded-4 mb-4" style="border-top: 4px solid #0dcaf0 !important;">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-dark mb-3">Requestor Details</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="small text-muted fw-bold">NAME</label>
                                        <div class="text-dark"><?php echo htmlspecialchars($ticket['firstName'] . ' ' . $ticket['lastName']); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-muted fw-bold">DEPARTMENT</label>
                                        <div class="text-dark"><?php echo htmlspecialchars($ticket['departmentName'] ?? 'No Department'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4 p-md-5">
                                <h5 class="fw-bold text-dark mb-4"><?php echo htmlspecialchars($ticket['subject']); ?></h5>
                                <div class="p-4 bg-light rounded-3 border text-dark" style="font-size: 1rem; line-height: 1.6;">
                                    <?php echo nl2br(htmlspecialchars($ticket['description'])); ?>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4" style="border-top: 4px solid #ffc107 !important;">
                            <h6 class="fw-bold text-dark border-bottom pb-3 mb-4">Action Panel</h6>

                            <div class="mb-4">
                                <label class="small text-muted fw-bold mb-2">CURRENT STATUS</label>
                                <div>
                                    <span class="badge bg-primary fs-6 px-4 py-2 rounded-pill shadow-sm">
                                        <?php echo htmlspecialchars($ticket['status']); ?>
                                    </span>
                                </div>
                            </div>

                            <form method="POST">
                                <div class="mb-4">
                                    <label class="small text-muted fw-bold mb-2">REMARKS</label>
                                    <textarea name="remarks" class="form-control bg-light" rows="5" placeholder="Enter resolution details or notes here..." required><?php echo htmlspecialchars($ticket['remarks'] ?? ''); ?></textarea>
                                </div>

                                <button type="submit" name="resolve_ticket" class="btn btn-success fw-bold w-100 py-3 shadow-sm rounded-3">
                                    <i class="bi bi-check-circle-fill me-2"></i> RESOLVE TICKET
                                </button>
                            </form>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>