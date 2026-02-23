<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'User') {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

// Fetch Statistics
$statStmt = $pdo->prepare("SELECT 
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as p,
    SUM(CASE WHEN status = 'Processing' THEN 1 ELSE 0 END) as pr,
    SUM(CASE WHEN status IN ('Resolved', 'Closed') THEN 1 ELSE 0 END) as r
    FROM ticket WHERE userId = ?");
$statStmt->execute([$userId]);
$stats = $statStmt->fetch();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard | DepEd ICT Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>
<body>

<div class="main-wrapper">
    <?php include 'sidebar_user.php'; ?>

    <div class="main-content">
        
        <?php include 'header.php'; ?>

        <div class="dashboard-container">
            
            <div class="row align-items-center mb-5">
                <div class="col-md-8">
                    <h1 class="fw-bold text-dark mb-1">User Dashboard</h1>
                    <p class="text-muted">Manage and monitor your ICT support requests.</p>
                </div>
                <div class="col-md-4 text-end">
                    <a href="create_ticket.php" class="btn-deped shadow-sm">
                        <i class="bi bi-plus-lg me-2"></i>SUBMIT NEW TICKET
                    </a>
                </div>
            </div>

            <div class="row g-4 mb-5">
                <div class="col-md-4">
                    <div class="stat-card border-warning">
                        <div class="d-flex justify-content-between">
                            <h6 class="text-muted fw-bold small">PENDING</h6>
                            <i class="bi bi-hourglass-split text-warning fs-4"></i>
                        </div>
                        <h1 class="display-3 fw-bold mt-2"><?php echo $stats['p'] ?? 0; ?></h1>
                    </div>
                </div>
                <div class="col-md-4">
                    <div class="stat-card border-info">
                        <div class="d-flex justify-content-between">
                            <h6 class="text-muted fw-bold small">PROCESSING</h6>
                            <i class="bi bi-gear-fill text-info fs-4"></i>
                        </div>
                        <h1 class="display-3 fw-bold mt-2"><?php echo $stats['pr'] ?? 0; ?></h1>
                    </div>
                </div>
<<<<<<< HEAD
                <div class="col-md-4">
                    <div class="stat-card border-success">
                        <div class="d-flex justify-content-between">
                            <h6 class="text-muted fw-bold small">RESOLVED</h6>
                            <i class="bi bi-check-circle-fill text-success fs-4"></i>
=======

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
                                            $badgeClass = 'bg-secondary';
                                            if ($row['status'] == 'Pending') $badgeClass = 'bg-warning text-dark';
                                            if ($row['status'] == 'Approved by IT') $badgeClass = 'bg-info text-dark';
                                            if ($row['status'] == 'Processing') $badgeClass = 'bg-primary';
                                            if ($row['status'] == 'Completed') $badgeClass = 'bg-success';

                                            $rawDate = !empty($row['updatedAt']) ? $row['updatedAt'] : $row['createdAt'];
                                            $exactDate = date("M d, Y", strtotime($row['createdAt']));
                                            $aging = timeAgo($row['createdAt']);

                                            echo "<tr>";
                                            echo "<td class='ps-4 fw-bold text-muted'>#" . $row['ticketId'] . "</td>";

                                            echo "<td>" . htmlspecialchars(substr($row['subject'], 0, 40)) . "...</td>";

                                            echo "<td>" . htmlspecialchars($row['categoryName'] ?? 'General') . "</td>";

                                            echo "<td><span class='badge rounded-pill " . $badgeClass . "'>" . $row['status'] . "</span></td>";

                                            echo "<td>
                                            <span class='d-block'>" . $exactDate . "</span>
                                            <span class='badge bg-light border text-dark mt-1'><i class='bi bi-clock me-1'></i>" . $aging . "</span>
                                            </td>";

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
>>>>>>> 0084d58 (backend done)
                        </div>
                        <h1 class="display-3 fw-bold mt-2"><?php echo $stats['r'] ?? 0; ?></h1>
                    </div>
                </div>
            </div>

            <div class="card stat-card shadow-sm border-0 p-0 overflow-hidden">
                <div class="card-header bg-white py-3 px-4 border-0">
                    <h5 class="mb-0 fw-bold text-dark"><i class="bi bi-clock-history me-2 text-success"></i>Ticket History</h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="bg-light small text-uppercase fw-bold">
                                <tr>
                                    <th class="ps-4 py-3">Reference</th>
                                    <th>Subject</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td colspan="4" class="text-center py-5 text-muted">
                                        No tickets found in your history.
                                    </td>
                                </tr>
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
                    }
                }
            }
        });
    </script>
</body>
</html>git add .