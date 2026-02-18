<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DepEd Helpdesk - User Dashboard</title>
    <link rel="icon" href="deped_rovtab.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=2">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>

    <style>
        .list-group-item-action:hover {
            background-color: rgba(255, 255, 255, 0.1) !important;
            color: #fff !important;
        }

        .btn-deped {
            background-color: #1a4d2e;
            color: white;
        }

        .btn-deped:hover {
            background-color: #143d24;
            color: white;
        }
    </style>
</head>

<body class="bg-light">
    <?php include 'header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-xl-2 d-none d-lg-block p-0">
                <?php include 'sidebar_user.php'; ?>
            </div>

            <div class="col-lg-9 col-xl-10 py-4">

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
                    <?php if ($_GET['msg'] == 'success'): ?>
                        <div class="alert alert-success"><i class="bi bi-check-circle me-2"></i>Ticket submitted successfully!</div>
                    <?php elseif ($_GET['msg'] == 'deleted'): ?>
                        <div class="alert alert-warning"><i class="bi bi-trash me-2"></i>Ticket deleted.</div>
                    <?php endif; ?>
                <?php endif; ?>

                <div class="row mb-4">
                    <div class="col-12">
                        <div class="card shadow-sm border-0">
                            <div class="card-header bg-white py-3">
                                <h5 class="mb-0 fw-bold text-success"><i class="bi bi-bar-chart-fill me-2"></i>Ticket Overview</h5>
                            </div>
                            <div class="card-body">
                                <canvas id="myTicketChart" style="max-height: 300px;"></canvas>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold text-success"><i class="bi bi-clock-history me-2"></i>My Ticket History</h5>
                        <a href="create_ticket.php" class="btn btn-deped btn-sm fw-bold"><i class="bi bi-plus-lg"></i> New Ticket</a>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Ticket #</th>
                                        <th>Subject</th>
                                        <th>Category</th>
                                        <th>Status</th>
                                        <th>Last Update</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php

                                    $sql = "SELECT t.*, c.categoryName 
                                    FROM ticket t 
                                    LEFT JOIN category c ON t.categoryId = c.categoryId 
                                    WHERE t.userId = ? 
                                    ORDER BY t.createdAt DESC";

                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([$_SESSION['user_id']]);

                                    if ($stmt->rowCount() > 0) {
                                        while ($row = $stmt->fetch()) {

                                            $statusColor = 'bg-secondary';
                                            if ($row['status'] == 'Pending') $statusColor = 'bg-warning text-dark';
                                            if ($row['status'] == 'Processing') $statusColor = 'bg-primary';
                                            if ($row['status'] == 'Resolved') $statusColor = 'bg-success';
                                            if ($row['status'] == 'Closed') $statusColor = 'bg-dark';

                                            $rawDate = !empty($row['updatedAt']) ? $row['updatedAt'] : $row['createdAt'];
                                            $date = date("M d, Y • h:i A", strtotime($rawDate));

                                            echo "<tr>";
                                            echo "<td class='ps-4 fw-bold text-muted'>#" . $row['ticketId'] . "</td>";

                                            echo "<td>" . htmlspecialchars(substr($row['subject'], 0, 40)) . "...</td>";

                                            echo "<td>" . htmlspecialchars($row['categoryName'] ?? 'General') . "</td>";

                                            echo "<td><span class='badge rounded-pill " . $statusColor . "'>" . $row['status'] . "</span></td>";

                                            echo "<td class='text-muted small'>" . $date . "</td>";

                                            echo "<td class='text-end pe-4'>";

                                            echo "<a href='view_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-outline-success me-1'>View</a>";

                                            if ($row['status'] == 'Pending') {
                                                echo "<a href='edit_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-outline-primary me-1'>Edit</a>";

                                                echo "<a href='delete_ticket.php?id=" . $row['ticketId'] . "' 
                                                class='btn btn-sm btn-outline-danger' 
                                                onclick='return confirm(\"Are you sure you want to delete this ticket? This cannot be undone.\")'>
                                                Delete
                                            </a>";
                                            }

                                            echo "</td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center py-4 text-muted'>No tickets found. Click 'New Ticket' to start!</td></tr>";
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

    <script>
        const ctx = document.getElementById('myTicketChart').getContext('2d');
        const myTicketChart = new Chart(ctx, {
            type: 'bar',
            data: {
                labels: ['Pending', 'Processing', 'Resolved'],
                datasets: [{
                    label: 'Tickets',
                    data: [
                        <?php echo $pendingCount; ?>,
                        <?php echo $processingCount; ?>,
                        <?php echo $completedCount; ?>
                    ],
                    backgroundColor: [
                        'rgba(255, 193, 7, 0.7)', // Yellow
                        'rgba(13, 202, 240, 0.7)', // Blue
                        'rgba(26, 77, 46, 0.8)' // DepEd Dark Green
                    ],
                    borderColor: [
                        'rgba(255, 193, 7, 1)',
                        'rgba(13, 202, 240, 1)',
                        'rgba(26, 77, 46, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1
                        }
                    }
                },
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    } // Hide legend for cleaner look
                }
            }
        });
    </script>
</body>

</html>