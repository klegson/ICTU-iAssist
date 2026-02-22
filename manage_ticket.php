<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Officer' && $_SESSION['role'] !== 'Technician')) {
    header("Location: login.php");
    exit;
}

$officerId = $_SESSION['user_id'];
$ticketId = $_GET['id'] ?? null;

if (!$ticketId) {
    header("Location: db_officer.php");
    exit;
}

if (isset($_POST['update_ticket'])) {
    $newStatus = $_POST['status'];
    $newPriority = $_POST['priority'];
    $remarks = $_POST['remarks'];
    $assignedTo = $_POST['assignedTo'] ?? null;
    $ticketId = $_GET['id'];

    $assignedTo = !empty($_POST['assignedTo']) ? $_POST['assignedTo'] : null;

    $sql = "UPDATE ticket SET status = ?, priority = ?, remarks = ?, assignedTo = ?, updatedAt = NOW() WHERE ticketId = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$newStatus, $newPriority, $remarks, $assignedTo, $ticketId])) {
        $msg = "Ticket updated successfully!";
        $msgClass = "alert-success";

        header("Refresh:0");
    } else {
        $msg = "Error updating ticket.";
        $msgClass = "alert-danger";
    }
}

$sql = "SELECT t.*, u.firstName, u.lastName, u.email, d.departmentName, c.categoryName 
        FROM ticket t 
        JOIN users u ON t.userId = u.userId 
        LEFT JOIN department d ON u.departmentId = d.departmentId
        LEFT JOIN category c ON t.categoryId = c.categoryId
        WHERE t.ticketId = ?";

$stmt = $pdo->prepare($sql);
$stmt->execute([$ticketId]);
$ticket = $stmt->fetch();

if (!$ticket) die("Ticket not found.");

$techSql = "SELECT userId, firstName, lastName FROM users WHERE role = 'Technician'";
$techStmt = $pdo->query($techSql);
$technicians = $techStmt->fetchAll();

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Ticket #<?php echo $ticketId; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <?php include 'header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-xl-2 d-none d-lg-block p-0">
                <?php include 'sidebar_officer.php'; ?>
            </div>

            <div class="col-lg-9 col-xl-10 py-4">
                <div class="container">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-dark">Processing Ticket #<?php echo $ticketId; ?></h4>
                        <a href="db_officer.php" class="btn btn-secondary btn-sm"><i class="bi bi-arrow-left"></i> Back to Dashboard</a>
                    </div>

                    <?php if (isset($msg)): ?>
                        <div class="alert <?php echo $msgClass; ?>"><?php echo $msg; ?></div>
                    <?php endif; ?>

                    <div class="row">
                        <div class="col-lg-7">
                            <div class="card shadow-sm border-0 mb-4">
                                <div class="card-header bg-white fw-bold">
                                    <i class="bi bi-person-circle me-2 text-primary"></i> Requestor Details
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label class="small text-muted fw-bold">NAME</label>
                                            <div class="fw-bold"><?php echo htmlspecialchars($ticket['firstName'] . ' ' . $ticket['lastName']); ?></div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label class="small text-muted fw-bold">DEPARTMENT</label>
                                            <div><?php echo htmlspecialchars($ticket['departmentName'] ?? 'N/A'); ?></div>
                                        </div>
                                        <div class="col-md-12">
                                            <label class="small text-muted fw-bold">EMAIL</label>
                                            <div><?php echo htmlspecialchars($ticket['email']); ?></div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-white fw-bold">
                                    <i class="bi bi-file-text me-2 text-primary"></i> Ticket Content
                                </div>
                                <div class="card-body">
                                    <h5 class="fw-bold text-primary mb-3"><?php echo htmlspecialchars($ticket['subject']); ?></h5>

                                    <label class="small text-muted fw-bold">DESCRIPTION / ISSUE:</label>
                                    <div class="p-3 bg-light rounded border mt-1">
                                        <p class="mb-0"><?php echo nl2br(htmlspecialchars($ticket['description'])); ?></p>
                                    </div>

                                    <div class="mt-3">
                                        <span class="badge bg-secondary">Category: <?php echo htmlspecialchars($ticket['categoryName'] ?? 'General'); ?></span>
                                        <span class="text-muted small ms-2">Created: <?php echo date("M d, Y • h:i A", strtotime($ticket['createdAt'])); ?></span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-5">
                            <form method="POST">
                                <div class="card shadow border-0">
                                    <div class="card-header bg-primary text-white fw-bold">
                                        <i class="bi bi-sliders me-2"></i> Action Panel
                                    </div>
                                    <div class="card-body bg-white">

                                        <?php if ($_SESSION['role'] === 'Officer'): ?>
                                            <div class="mb-3">
                                                <label class="form-label fw-bold small">ASSIGN TO TECHNICIAN</label>
                                                <select class="form-select" name="assignedTo">
                                                    <option value="">-- Unassigned --</option>

                                                    <?php foreach ($technicians as $tech): ?>
                                                        <option value="<?php echo $tech['userId']; ?>" <?php if ($ticket['assignedTo'] == $tech['userId']) echo 'selected'; ?>>
                                                            🔧 <?php echo htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']); ?>
                                                        </option>
                                                    <?php endforeach; ?>

                                                </select>
                                            </div>

                                        <?php else: ?>
                                            <input type="hidden" name="assignedTo" value="<?php echo htmlspecialchars($ticket['assignedTo']); ?>">
                                        <?php endif; ?>
                                        <label class="form-label fw-bold small">UPDATE STATUS</label>
                                        <select class="form-select form-select-lg fw-bold" name="status">
                                            <option value="Pending" <?php if ($ticket['status'] == 'Pending') echo 'selected'; ?>>🟡 Pending</option>
                                            <option value="Approved by IT" <?php if ($ticket['status'] == 'Approved by IT') echo 'selected'; ?>>🔵 Approved by IT</option>
                                            <option value="Processing" <?php if ($ticket['status'] == 'Processing') echo 'selected'; ?>>⚙️ Processing</option>
                                            <option value="Completed" <?php if ($ticket['status'] == 'Completed') echo 'selected'; ?>>✅ Completed</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">SET PRIORITY</label>
                                        <select class="form-select" name="priority">
                                            <option value="Low" <?php if ($ticket['priority'] == 'Low') echo 'selected'; ?>>Low Priority</option>
                                            <option value="Medium" <?php if ($ticket['priority'] == 'Medium') echo 'selected'; ?>>Medium Priority</option>
                                            <option value="High" <?php if ($ticket['priority'] == 'High') echo 'selected'; ?>>🔥 High Priority</option>
                                        </select>
                                    </div>

                                    <div class="mb-3">
                                        <label class="form-label fw-bold small">OFFICER REMARKS / RESOLUTION NOTES</label>
                                        <textarea class="form-control" name="remarks" rows="5" placeholder="Describe what you did to fix the issue..."><?php echo htmlspecialchars($ticket['remarks'] ?? ''); ?></textarea>
                                    </div>

                                    <div class="d-grid">
                                        <button type="submit" name="update_ticket" class="btn btn-primary fw-bold py-2">
                                            <i class="bi bi-check-circle-fill me-2"></i> Update Ticket
                                        </button>
                                    </div>
                                </div>
                        </div>
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