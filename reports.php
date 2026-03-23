<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

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

$trendSql = "SELECT 
                DATE_FORMAT(createdAt, '%Y-%m') as sort_date,
                DATE_FORMAT(createdAt, '%M %Y') as month_label,
                COUNT(*) as received,
                SUM(CASE WHEN status = 'Processing' THEN 1 ELSE 0 END) as processing,
                SUM(CASE WHEN status IN ('Resolved', 'Completed') THEN 1 ELSE 0 END) as completed
            FROM ticket 
            GROUP BY sort_date, month_label 
            ORDER BY sort_date ASC 
            LIMIT 12";
$trendStmt = $pdo->query($trendSql);

$months = [];
$dataReceived = [];
$dataProcessing = [];
$dataCompleted = [];

while ($row = $trendStmt->fetch()) {
    $months[] = $row['month_label'];
    $dataReceived[] = $row['received'];
    $dataProcessing[] = $row['processing'];
    $dataCompleted[] = $row['completed'];
}

$selectedMonth = isset($_GET['month']) ? $_GET['month'] : 'all';
$selectedYear = isset($_GET['year']) ? $_GET['year'] : 'all';

$divSql = "SELECT 
                DATE_FORMAT(t.createdAt, '%M %Y') as month_label,
                d.departmentName,
                COUNT(*) as total_received,
                SUM(CASE WHEN t.status = 'Processing' THEN 1 ELSE 0 END) as total_processing,
                SUM(CASE WHEN t.status IN ('Resolved', 'Completed') THEN 1 ELSE 0 END) as total_completed
            FROM ticket t
            LEFT JOIN users u ON t.userId = u.userId
            LEFT JOIN department d ON t.departmentId = d.departmentId
            WHERE 1=1";

$divParams = [];

if ($selectedMonth !== 'all') {
    $divSql .= " AND DATE_FORMAT(t.createdAt, '%m') = ?";
    $divParams[] = $selectedMonth;
}

if ($selectedYear !== 'all') {
    $divSql .= " AND DATE_FORMAT(t.createdAt, '%Y') = ?";
    $divParams[] = $selectedYear;
}

$divSql .= " GROUP BY DATE_FORMAT(t.createdAt, '%Y-%m'), month_label, d.departmentName
             ORDER BY DATE_FORMAT(t.createdAt, '%Y-%m') DESC, d.departmentName ASC";

$divStmt = $pdo->prepare($divSql);
$divStmt->execute($divParams);

// Helper arrays for the UI
$monthsArr = [
    '01' => 'January',
    '02' => 'February',
    '03' => 'March',
    '04' => 'April',
    '05' => 'May',
    '06' => 'June',
    '07' => 'July',
    '08' => 'August',
    '09' => 'September',
    '10' => 'October',
    '11' => 'November',
    '12' => 'December'
];

$monthLabel = ($selectedMonth === 'all') ? 'All Months' : $monthsArr[$selectedMonth];
$yearLabel = ($selectedYear === 'all') ? 'All Years' : $selectedYear;
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Monthly Reports - DepEd Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        /* Custom styling for the dropdown buttons to match the old select look */
        .filter-btn {
            background-color: #fff;
            border: 1px solid #dee2e6;
            color: #212529;
            text-align: left;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .filter-btn:focus {
            box-shadow: 0 0 0 0.25rem rgba(13, 110, 253, 0.25);
            border-color: #86b7fe;
        }

        .dropdown-menu {
            max-height: 250px;
            overflow-y: auto;
        }
    </style>
</head>

<body class="bg-light">

    <div style="position: fixed; top: 0; left: 0; height: 100vh; width: 280px; z-index: 1000; overflow-y: auto;">
        <?php include 'sidebar_officer.php'; ?>
    </div>

    <div style="margin-left: 280px;">

        <?php include 'header.php'; ?>

        <div class="container-fluid py-5 px-5">

            <div class="d-flex justify-content-between align-items-center mb-5">
                <div>
                    <h2 class="fw-bold text-dark mb-1"><i class="bi bi-graph-up-arrow me-2"></i>Performance Reports</h2>
                    <p class="text-muted">Monthly breakdown of ticket volume and resolution status.</p>
                </div>
                <button onclick="window.print()" class="btn btn-outline-dark fw-bold">
                    <i class="bi bi-printer me-2"></i> Print Report
                </button>
            </div>

            <div class="card shadow-sm border-0 mb-5">
                <div class="card-header bg-white py-3">
                    <h6 class="fw-bold m-0 text-dark">Overall Tickets per Month (All Divisions)</h6>
                </div>
                <div class="card-body">
                    <div style="height: 400px;">
                        <canvas id="trendChart"></canvas>
                    </div>
                </div>
            </div>

            <div class="card shadow-sm border-0 mb-5">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold m-0 text-dark">Detailed Report: Division per Month</h6>

                    <div class="d-flex align-items-center m-0">
                        <label class="me-2 text-muted small fw-bold mb-0 text-nowrap">Filter:</label>

                        <input type="hidden" id="monthInput" value="<?php echo $selectedMonth; ?>">
                        <input type="hidden" id="yearInput" value="<?php echo $selectedYear; ?>">

                        <div class="btn-group dropup me-2">
                            <button type="button" class="btn btn-sm filter-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="monthBtn" style="width: 130px;">
                                <?php echo $monthLabel; ?>
                            </button>
                            <ul class="dropdown-menu shadow-sm">
                                <li><a class="dropdown-item month-opt <?php echo ($selectedMonth === 'all') ? 'active' : ''; ?>" href="#" data-val="all">All Months</a></li>
                                <?php foreach ($monthsArr as $num => $name): ?>
                                    <li><a class="dropdown-item month-opt <?php echo ($selectedMonth === $num) ? 'active' : ''; ?>" href="#" data-val="<?php echo $num; ?>"><?php echo $name; ?></a></li>
                                <?php endforeach; ?>
                            </ul>
                        </div>

                        <div class="btn-group dropup">
                            <button type="button" class="btn btn-sm filter-btn dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false" id="yearBtn" style="width: 100px;">
                                <?php echo $yearLabel; ?>
                            </button>
                            <ul class="dropdown-menu shadow-sm">
                                <li><a class="dropdown-item year-opt <?php echo ($selectedYear === 'all') ? 'active' : ''; ?>" href="#" data-val="all">All Years</a></li>
                                <?php
                                $currentYear = date('Y');
                                for ($y = 2024; $y <= $currentYear + 2; $y++): ?>
                                    <li><a class="dropdown-item year-opt <?php echo ($selectedYear == $y) ? 'active' : ''; ?>" href="#" data-val="<?php echo $y; ?>"><?php echo $y; ?></a></li>
                                <?php endfor; ?>
                            </ul>
                        </div>
                    </div>

                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover align-middle mb-0 text-center">
                            <thead class="table-light">
                                <tr>
                                    <th class="py-3 text-start ps-4">Month</th>
                                    <th class="py-3 text-start">Division / Department</th>
                                    <th class="py-3 bg-light text-secondary">Received</th>
                                    <th class="py-3 bg-light text-primary">Processing</th>
                                    <th class="py-3 bg-light text-success">Completed</th>
                                </tr>
                            </thead>
                            <tbody id="reportTableBody">
                                <?php if ($divStmt->rowCount() > 0): ?>
                                    <?php while ($row = $divStmt->fetch()): ?>
                                        <tr>
                                            <td class="text-start ps-4 fw-bold"><?php echo htmlspecialchars($row['month_label']); ?></td>
                                            <td class="text-start text-muted"><?php echo htmlspecialchars($row['departmentName'] ?? 'Unassigned'); ?></td>

                                            <td class="fw-bold fs-5"><?php echo $row['total_received']; ?></td>
                                            <td class="text-primary fw-bold"><?php echo $row['total_processing']; ?></td>
                                            <td class="text-success fw-bold"><?php echo $row['total_completed']; ?></td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="5" class="py-5 text-muted">No data available for the selected filters.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const ctx = document.getElementById('trendChart');

        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                        label: 'Received (Total)',
                        data: <?php echo json_encode($dataReceived); ?>,
                        borderColor: '#6c757d',
                        backgroundColor: 'rgba(108, 117, 125, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Processing',
                        data: <?php echo json_encode($dataProcessing); ?>,
                        borderColor: '#0d6efd',
                        backgroundColor: 'rgba(13, 110, 253, 0.1)',
                        tension: 0.3,
                        fill: true
                    },
                    {
                        label: 'Completed',
                        data: <?php echo json_encode($dataCompleted); ?>,
                        borderColor: '#198754',
                        backgroundColor: 'rgba(25, 135, 84, 0.1)',
                        tension: 0.3,
                        fill: true
                    }
                ]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'top'
                    },
                    tooltip: {
                        mode: 'index',
                        intersect: false
                    }
                },
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

        document.addEventListener('DOMContentLoaded', function() {
            const monthInput = document.getElementById('monthInput');
            const yearInput = document.getElementById('yearInput');
            const monthBtn = document.getElementById('monthBtn');
            const yearBtn = document.getElementById('yearBtn');
            const tableBody = document.getElementById('reportTableBody');

            // Handle Month Selection
            document.querySelectorAll('.month-opt').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.month-opt').forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                    monthBtn.innerHTML = this.innerHTML;
                    monthInput.value = this.getAttribute('data-val');
                    updateTableSilently();
                });
            });

            // Handle Year Selection
            document.querySelectorAll('.year-opt').forEach(item => {
                item.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.querySelectorAll('.year-opt').forEach(opt => opt.classList.remove('active'));
                    this.classList.add('active');
                    yearBtn.innerHTML = this.innerHTML;
                    yearInput.value = this.getAttribute('data-val');
                    updateTableSilently();
                });
            });

            function updateTableSilently() {
                const month = monthInput.value;
                const year = yearInput.value;

                tableBody.style.opacity = '0.4';
                tableBody.style.transition = 'opacity 0.2s';

                const url = `reports.php?month=${month}&year=${year}`;

                fetch(url)
                    .then(response => response.text())
                    .then(html => {
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');

                        const newTableBody = doc.getElementById('reportTableBody');

                        if (newTableBody) {
                            tableBody.innerHTML = newTableBody.innerHTML;
                        }

                        tableBody.style.opacity = '1';
                        window.history.pushState({}, '', url);
                    })
                    .catch(error => {
                        console.error('Error fetching data:', error);
                        tableBody.style.opacity = '1';
                    });
            }
        });
    </script>

</body>

</html>