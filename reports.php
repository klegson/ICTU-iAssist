<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Officer') {
    header("Location: login.php");
    exit;
}
$page = 'reports';

$statuses = ['Pending', 'Approved by IT', 'Processing', 'Completed'];
$statusCounts = [];

foreach ($statuses as $s) {
    $stmt = $pdo->prepare("SELECT COUNT(*) FROM ticket WHERE status = ?");
    $stmt->execute([$s]);
    $statusCounts[] = $stmt->fetchColumn();
}

$catLabels = [];
$catCounts = [];

$sql = "SELECT c.categoryName, COUNT(t.ticketId) as count 
        FROM ticket t 
        JOIN category c ON t.categoryId = c.categoryId 
        GROUP BY c.categoryName";
$stmt = $pdo->query($sql);

while ($row = $stmt->fetch()) {
    $catLabels[] = $row['categoryName'];
    $catCounts[] = $row['count'];
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>System Reports - DepEd Helpdesk</title>
    <link rel="icon" href="deped_rovtab.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
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
                        <h4 class="fw-bold text-dark"><i class="bi bi-bar-chart-fill me-2"></i>System Analytics</h4>
                        <button onclick="window.print()" class="btn btn-outline-dark">
                            <i class="bi bi-printer me-2"></i> Print Report
                        </button>
                    </div>

                    <div class="row">

                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white fw-bold">Ticket Status Overview</div>
                                <div class="card-body">
                                    <canvas id="statusChart"></canvas>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-6 mb-4">
                            <div class="card shadow-sm border-0 h-100">
                                <div class="card-header bg-white fw-bold">Common Issues by Category</div>
                                <div class="card-body">
                                    <canvas id="categoryChart"></canvas>
                                </div>
                            </div>
                        </div>

                    </div>

                    <div class="card shadow-sm border-0 mt-2">
                        <div class="card-header bg-white fw-bold">Detailed Summary</div>
                        <div class="card-body p-0">
                            <table class="table table-striped mb-0">
                                <thead class="table-dark">
                                    <tr>
                                        <th>Metric</th>
                                        <th>Count</th>
                                        <th>Description</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Total Tickets</td>
                                        <td class="fw-bold"><?php echo array_sum($statusCounts); ?></td>
                                        <td class="text-muted">All tickets received since system start.</td>
                                    </tr>
                                    <tr>
                                        <td>Pending Requests</td>
                                        <td class="fw-bold text-danger"><?php echo $statusCounts[0]; ?></td>
                                        <td class="text-muted">Tickets waiting for action.</td>
                                    </tr>
                                    <tr>
                                        <td>Completed Jobs</td>
                                        <td class="fw-bold text-success"><?php echo $statusCounts[3]; ?></td>
                                        <td class="text-muted">Successfully resolved issues.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script>
        const ctx1 = document.getElementById('statusChart');
        new Chart(ctx1, {
            type: 'doughnut',
            data: {
                labels: ['Pending', 'Approved by IT', 'Processing', 'Completed'],
                datasets: [{
                    label: '# of Tickets',
                    data: <?php echo json_encode($statusCounts); ?>,
                    backgroundColor: [
                        '#ffc107',
                        '#0dcaf0',
                        '#0d6efd',
                        '#198754'
                    ],
                    borderWidth: 1
                }]
            }
        });
        const ctx2 = document.getElementById('categoryChart');
        new Chart(ctx2, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($catLabels); ?>,
                datasets: [{
                    label: 'Number of Incidents',
                    data: <?php echo json_encode($catCounts); ?>,
                    backgroundColor: '#0d6efd',
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
                }
            }
        });
    </script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>