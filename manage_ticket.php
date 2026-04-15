<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Officer', 'Technician'])) {
    header("Location: index.php");
    exit;
}

$ticketId = $_GET['id'] ?? null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_ticket'])) {
    $assignedTo = $_POST['assigned_to'];

    $updateStmt = $pdo->prepare("UPDATE ticket SET status = 'Processing', assignedTo = ? WHERE ticketId = ?");
    if ($updateStmt->execute([$assignedTo, $ticketId])) {
        header("Location: manage_ticket.php?id=" . $ticketId);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_ticket'])) {
    $remarks = trim($_POST['remarks']);

    $updateStmt = $pdo->prepare("UPDATE ticket SET status = 'Resolved', remarks = ?, resolvedBy = ?, resolvedAt = NOW() WHERE ticketId = ?");
    if ($updateStmt->execute([$remarks, $_SESSION['user_id'], $ticketId])) {
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

$techStmt = $pdo->query("SELECT userId, firstName, lastName FROM users WHERE role IN ('Technician', 'Officer') AND isApproved = 1 ORDER BY firstName ASC");
$technicians = $techStmt->fetchAll();
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
                    <h2 class="fw-bold text-dark mb-0">Manage Ticket #<?php echo htmlspecialchars($ticketId); ?></h2>
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
                                    <?php
                                    $badgeClass = match ($ticket['status']) {
                                        'Pending' => 'bg-warning text-dark',
                                        'Processing' => 'bg-primary',
                                        'Resolved' => 'bg-success',
                                        'Completed' => 'bg-success',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?> fs-6 px-4 py-2 rounded-pill shadow-sm">
                                        <?php echo htmlspecialchars($ticket['status']); ?>
                                    </span>
                                </div>
                            </div>

                            <?php if ($ticket['status'] === 'Pending'): ?>

                                <form method="POST" class="mb-4 pb-4 border-bottom">
                                    <div class="mb-3">
                                        <label class="small text-muted fw-bold mb-2">ASSIGN TO IT PERSONNEL</label>
                                        <select name="assigned_to" class="form-select bg-light" required>
                                            <option value="" disabled selected>-- Select Technician --</option>
                                            <?php foreach ($technicians as $tech): ?>
                                                <option value="<?php echo $tech['userId']; ?>" <?php echo ($tech['userId'] == $_SESSION['user_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" name="assign_ticket" class="btn btn-primary fw-bold w-100 py-2 shadow-sm rounded-3">
                                        <i class="bi bi-person-gear me-2"></i> ASSIGN TICKET
                                    </button>
                                </form>

                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="small text-muted fw-bold mb-2">DIRECT RESOLVE (REMARKS)</label>
                                        <textarea name="remarks" class="form-control bg-light" rows="3" placeholder="Enter resolution details..." required></textarea>
                                    </div>
                                    <button type="submit" name="resolve_ticket" class="btn btn-success fw-bold w-100 py-2 shadow-sm rounded-3">
                                        <i class="bi bi-check-circle-fill me-2"></i> RESOLVE TICKET
                                    </button>
                                </form>

                            <?php elseif ($ticket['status'] === 'Processing'): ?>

                                <form method="POST">
                                    <div class="mb-4">
                                        <label class="small text-muted fw-bold mb-2">REMARKS / RESOLUTION NOTES</label>
                                        <textarea name="remarks" class="form-control bg-light" rows="5" placeholder="Enter details of how the issue was resolved..." required><?php echo htmlspecialchars($ticket['remarks'] ?? ''); ?></textarea>
                                    </div>
                                    <button type="submit" name="resolve_ticket" class="btn btn-success fw-bold w-100 py-3 shadow-sm rounded-3">
                                        <i class="bi bi-check-circle-fill me-2"></i> RESOLVE TICKET
                                    </button>
                                </form>

                            <?php else: ?>

                                <div class="mb-4">
                                    <label class="small text-muted fw-bold mb-2">RESOLUTION REMARKS</label>
                                    <div class="p-3 bg-light rounded-3 border text-dark small">
                                        <?php echo nl2br(htmlspecialchars($ticket['remarks'] ?? 'No remarks provided.')); ?>
                                    </div>
                                </div>
                                <div class="alert alert-success text-center mb-0 border-0 shadow-sm py-3">
                                    <i class="bi bi-check-circle-fill d-block fs-2 mb-2"></i>
                                    <strong><?php echo $ticket['status']; ?></strong><br>
                                    <small>This ticket is closed.</small>
                                </div>

                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>