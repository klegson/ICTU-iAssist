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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>All Tickets Archive - DepEd Helpdesk</title>
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
                <?php $page = 'all_tickets';
                include 'sidebar_officer.php'; ?>
            </div>

            <div class="col-lg-9 col-xl-10 py-4">
                <div class="container">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-dark"><i class="bi bi-collection-fill me-2"></i>All Ticket History</h4>
                        <div class="input-group" style="width: 300px;">
                            <input type="text" class="form-control" placeholder="Search tickets...">
                            <button class="btn btn-primary"><i class="bi bi-search"></i></button>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <div class="table-responsive">
                                <table class="table table-hover table-striped mb-0 align-middle">
                                    <thead class="bg-dark text-white">
                                        <tr>
                                            <th class="ps-4">ID</th>
                                            <th>Subject</th>
                                            <th>Requested By</th>
                                            <th>Category</th>
                                            <th>Status</th>
                                            <th>Date</th>
                                            <th class="text-end pe-4">Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $sql = "SELECT t.*, u.firstName, u.lastName, c.categoryName 
                                                FROM ticket t 
                                                JOIN users u ON t.userId = u.userId 
                                                LEFT JOIN category c ON t.categoryId = c.categoryId 
                                                ORDER BY t.createdAt DESC";

                                        $stmt = $pdo->query($sql);

                                        while ($row = $stmt->fetch()) {
                                            $exactDate = date("M d, Y", strtotime($row['createdAt']));
                                            $aging = timeAgo($row['createdAt']);
                                            $name = htmlspecialchars($row['firstName'] . ' ' . $row['lastName']);
                                            $cat = htmlspecialchars($row['categoryName'] ?? 'General');

                                            $badgeClass = 'bg-secondary';
                                            if ($row['status'] == 'Pending') $badgeClass = 'bg-warning text-dark';
                                            if ($row['status'] == 'Processing') $badgeClass = 'bg-primary';
                                            if ($row['status'] == 'Resolved') $badgeClass = 'bg-success';
                                            if ($row['status'] == 'Closed') $badgeClass = 'bg-dark';

                                            echo "<tr>";
                                            echo "<td class='ps-4 fw-bold'>#" . $row['ticketId'] . "</td>";
                                            echo "<td>" . htmlspecialchars(substr($row['subject'], 0, 30)) . "...</td>";
                                            echo "<td>" . $name . "</td>";
                                            echo "<td><small class='text-muted'>" . $cat . "</small></td>";

                                            echo "<td><span class='badge " . $badgeClass . "'>" . $row['status'] . "</span></td>";

                                            echo "<td>
                                            <span class='d-block'>" . $exactDate . "</span>
                                            <span class='badge bg-light border text-dark mt-1'><i class='bi bi-clock me-1'></i>" . $aging . "</span>
                                            </td>";

                                            echo "<td class='text-end pe-4'>
                                                        <a href='manage_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-outline-dark'>
                                                            Manage
                                                        </a>
                                                      </td>";
                                            echo "</tr>";
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