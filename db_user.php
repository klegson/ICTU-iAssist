<?php
session_start();
require 'db.php';


if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'User') {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$statStmt = $pdo->prepare("SELECT 
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as pending_count,
    SUM(CASE WHEN status = 'Processing' THEN 1 ELSE 0 END) as processing_count,
    SUM(CASE WHEN status = 'Completed' OR status = 'Closed' THEN 1 ELSE 0 END) as completed_count
    FROM ticket WHERE userId = ?");
$statStmt->execute([$userId]);
$stats = $statStmt->fetch();

$pendingCount = $stats['pending_count'] ?? 0;
$processingCount = $stats['processing_count'] ?? 0;
$completedCount = $stats['completed_count'] ?? 0;


if (isset($_GET['delete_id'])) {
    $deleteId = $_GET['delete_id'];

    $check = $pdo->prepare("SELECT status FROM ticket WHERE ticketId = ? AND userId = ?");
    $check->execute([$deleteId, $userId]);
    $ticket = $check->fetch();

    if ($ticket && $ticket['status'] === 'Pending') {
        $pdo->prepare("DELETE FROM ticket WHERE ticketId = ?")->execute([$deleteId]);
        header("Location: db_user.php?msg=deleted");
    } else {
        header("Location: db_user.php?msg=error");
    }
    exit;
}


if (isset($_POST['submit_ticket'])) {
    $subject = trim($_POST['subject']);
    $description = trim($_POST['description']);
    $priority = $_POST['priority'];


    $stmt = $pdo->prepare("SELECT departmentId FROM users WHERE userId = ?");
    $stmt->execute([$userId]);
    $userDep = $stmt->fetch();
    $deptId = $userDep['departmentId'];

    $sql = "INSERT INTO ticket (subject, description, priority, userId, departmentId, status) 
            VALUES (?, ?, ?, ?, ?, 'Pending')";

    if ($pdo->prepare($sql)->execute([$subject, $description, $priority, $userId, $deptId])) {
        header("Location: db_user.php?msg=success");
    } else {
        header("Location: db_user.php?msg=failed");
    }
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DepEd Helpdesk - User Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=2">
</head>

<body class="bg-light">
    <?php include 'header.php'; ?>

    <div class="container-fluid mt-4">
        <div class="row">

            <div class="col-lg-3 col-xl-2 d-none d-lg-block">
                <?php include 'sidebar_user.php'; ?>
            </div>
            <div class="col-lg-9 col-xl-10">
                <div class="row mb-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 border-start border-4 border-warning h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase text-muted small fw-bold mb-1">Pending</h6>
                                        <h2 class="mb-0 fw-bold text-dark"><?php echo $pendingCount; ?></h2>
                                    </div>
                                    <div class="bg-warning bg-opacity-10 p-3 rounded">
                                        <i class="bi bi-hourglass-split text-warning fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 border-start border-4 border-info h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase text-muted small fw-bold mb-1">Processing</h6>
                                        <h2 class="mb-0 fw-bold text-dark"><?php echo $processingCount; ?></h2>
                                    </div>
                                    <div class="bg-info bg-opacity-10 p-3 rounded">
                                        <i class="bi bi-gear-fill text-info fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 border-start border-4 border-success h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="text-uppercase text-muted small fw-bold mb-1">Resolved</h6>
                                        <h2 class="mb-0 fw-bold text-dark"><?php echo $completedCount; ?></h2>
                                    </div>
                                    <div class="bg-success bg-opacity-10 p-3 rounded">
                                        <i class="bi bi-check-circle-fill text-success fs-3"></i>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <?php if (isset($_GET['msg'])): ?>
                <?php endif; ?>

                <div class="card mb-4 shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold text-primary"><i class="bi bi-plus-circle me-2"></i>Submit a New Ticket</h5>
                    </div>
                    <div class="card-body">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-8 mb-3">
                                    <label class="form-label small fw-bold">Subject</label>
                                    <input type="text" class="form-control" name="subject" required>
                                </div>
                                <div class="col-md-4 mb-3">
                                    <label class="form-label small fw-bold">Priority</label>
                                    <select class="form-select" name="priority">
                                        <option>Low</option>
                                        <option selected>Medium</option>
                                        <option>High</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">Description</label>
                                <textarea class="form-control" name="description" rows="3" required></textarea>
                            </div>
                            <div class="text-end">
                                <button type="submit" name="submit_ticket" class="btn btn-primary px-4">Submit Ticket</button>
                            </div>
                        </form>
                    </div>
                </div>

                <div class="card shadow-sm">
                    <div class="card-header bg-white py-3">
                        <h5 class="mb-0 fw-bold"><i class="bi bi-clock-history me-2"></i>My Ticket History</h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Ticket #</th>
                                        <th>Subject</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $stmt = $pdo->prepare("SELECT * FROM ticket WHERE userId = ? ORDER BY createdAt DESC");
                                    $stmt->execute([$userId]);

                                    while ($row = $stmt->fetch()) {
                                        $badge = match ($row['status']) {
                                            'Pending' => 'bg-warning text-dark',
                                            'Processing' => 'bg-info',
                                            'Completed', 'Closed' => 'bg-success',
                                            default => 'bg-secondary'
                                        };
                                        echo "<tr>
                        <td class='ps-4 fw-bold'>#{$row['ticketId']}</td>
                        <td>" . htmlspecialchars($row['subject']) . "</td>
                        <td><span class='badge {$badge}'>{$row['status']}</span></td>
                        <td class='text-end pe-4'>";
                                        if ($row['status'] === 'Pending') {
                                            echo "<a href='?delete_id={$row['ticketId']}' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Cancel?\")'>Cancel</a>";
                                        }
                                        echo "</td></tr>";
                                    }
                                    ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>