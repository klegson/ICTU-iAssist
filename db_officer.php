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

$pendingSql = "SELECT COUNT(*) FROM ticket WHERE status = 'Pending'";
$pendingCount = $pdo->query($pendingSql)->fetchColumn();

$processSql = "SELECT COUNT(*) FROM ticket WHERE status = 'Processing'";
$processCount = $pdo->query($processSql)->fetchColumn();

$resolvedSql = "SELECT COUNT(*) FROM ticket WHERE status = 'Resolved'";
$resolvedCount = $pdo->query($resolvedSql)->fetchColumn();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Officer Dashboard - DepEd Helpdesk</title>
    <link rel="icon" href="deped_rovtab.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <?php include 'header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-xl-2 d-none d-lg-block p-0">
                <?php $page = 'dashboard';
                include 'sidebar_officer.php'; ?>
            </div>

            <div class="col-lg-9 col-xl-10 py-4">
                <div class="container">

                    <h3 class="fw-bold text-dark mb-4">Officer Dashboard</h3>

                    <div class="row mb-4">
                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 border-start border-4 border-danger h-100">
                                <div class="card-body">
                                    <h6 class="text-muted fw-bold">NEW REQUESTS</h6>
                                    <h2 class="display-4 fw-bold text-danger mb-0"><?php echo $pendingCount; ?></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 border-start border-4 border-primary h-100">
                                <div class="card-body">
                                    <h6 class="text-muted fw-bold">IN PROGRESS</h6>
                                    <h2 class="display-4 fw-bold text-primary mb-0"><?php echo $processCount; ?></h2>
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="card shadow-sm border-0 border-start border-4 border-success h-100">
                                <div class="card-body">
                                    <h6 class="text-muted fw-bold">RESOLVED TOTAL</h6>
                                    <h2 class="display-4 fw-bold text-success mb-0"><?php echo $resolvedCount; ?></h2>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm">
                        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                            <h5 class="mb-0 fw-bold text-danger"><i class="bi bi-bell-fill me-2"></i>Incoming Priority Queue</h5>
                            <span class="badge bg-danger">Pending Only</span>
                        </div>
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover mb-0 align-middle">
                                    <thead class="bg-light">
                                        <tr>
                                            <th class="ps-4">Ticket ID</th>
                                            <th>Subject</th>
                                            <th>Requested By</th>
                                            <th>Department</th>
                                            <th>Date Created</th>
                                            <th class="text-end pe-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT t.*, u.firstName, u.lastName, d.departmentName 
                                        FROM ticket t 
                                        JOIN users u ON t.userId = u.userId 
                                        LEFT JOIN department d ON t.departmentId = d.departmentId 
                                        WHERE t.status = 'Pending' 
                                        ORDER BY t.createdAt ASC";

                                        $stmt = $pdo->query($sql);

                                        if ($stmt->rowCount() > 0) {
                                            while ($row = $stmt->fetch()) {

                                                $statusColor = 'bg-secondary';
                                                $badgeClass = 'bg-secondary';
                                                if ($row['status'] == 'Pending') $badgeClass = 'bg-warning text-dark';
                                                if ($row['status'] == 'Approved by IT') $badgeClass = 'bg-info text-dark';
                                                if ($row['status'] == 'Processing') $badgeClass = 'bg-primary';
                                                if ($row['status'] == 'Completed') $badgeClass = 'bg-success';

                                                $exactDate = date("M d, g:i A", strtotime($row['createdAt']));
                                                $aging = timeAgo($row['createdAt']);
                                                $agingColor = strpos($aging, 'days') !== false || strpos($aging, 'weeks') !== false ? 'text-danger' : 'text-muted';
                                                $name = htmlspecialchars($row['firstName'] . ' ' . $row['lastName']);
                                                $dept = htmlspecialchars($row['departmentName'] ?? 'N/A');

                                                echo "<tr>";
                                                echo "<td class='ps-4 fw-bold text-muted'>#" . $row['ticketId'] . "</td>";
                                                echo "<td>" . htmlspecialchars(substr($row['subject'], 0, 40)) . "</td>";

                                                echo "<td>" . $name . "</td>";

                                                echo "<td><span class='badge bg-light text-dark border'>" . $dept . "</span></td>";

                                                echo "<td>
                                                <span>" . $exactDate . "</span><br>
                                                <small class='fw-bold " . $agingColor . "'><i class='bi bi-clock-history me-1'></i>" . $aging . "</small>
                                                </td>";

                                                echo "<td class='text-end pe-4'>
                                                <a href='manage_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-primary fw-bold'>
                                                Process Ticket <i class='bi bi-arrow-right'></i>
                                                </a>
                                                </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='6' class='text-center py-5 text-muted'>
                                        <i class='bi bi-check-circle fs-1 text-success d-block mb-2'></i>
                                        All caught up! No pending tickets.
                                        </td></tr>";
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
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>