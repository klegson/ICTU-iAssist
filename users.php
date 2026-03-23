<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Officer' && $_SESSION['role'] !== 'Technician')) {
    header("Location: login.php");
    exit;
}
$page = 'users';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Users - DepEd Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <div style="position: fixed; top: 0; left: 0; height: 100vh; width: 280px; z-index: 1000; overflow-y: auto;">
        <?php
        if ($_SESSION['role'] === 'Technician') {
            include 'sidebar_tech.php';
        } else {
            include 'sidebar_officer.php';
        }
        ?>
    </div>

    <div style="margin-left: 280px;">

        <?php include 'header.php'; ?>

        <div class="container-fluid py-5 px-5">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <h2 class="fw-bold text-dark m-0"><i class="bi bi-people-fill me-2"></i>User Directory</h2>

                <div class="d-flex align-items-center">
                    <div class="btn-group me-4 shadow-sm" role="group" aria-label="Role Filter">
                        <button type="button" class="btn btn-secondary active filter-btn" data-filter="all">All</button>
                        <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="Officer">Officers</button>
                        <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="Technician">Technicians</button>
                        <button type="button" class="btn btn-outline-secondary filter-btn" data-filter="User">Users</button>
                    </div>

                    <a href="add_user.php" class="btn btn-success fw-bold shadow-sm">
                        <i class="bi bi-person-plus-fill me-2"></i> Add New User
                    </a>
                </div>
            </div>

            <div class="card shadow-sm border-0">
                <div class="card-body p-0">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="bg-light">
                            <tr>
                                <th class="ps-4 py-3">Name</th>
                                <th>Email</th>
                                <th>Role</th>
                                <th>Department</th>
                                <th class="text-end pe-4">Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $sql = "SELECT u.*, d.departmentName 
                                    FROM users u 
                                    LEFT JOIN department d ON u.departmentId = d.departmentId 
                                    ORDER BY u.role, u.lastName";
                            $stmt = $pdo->query($sql);

                            while ($row = $stmt->fetch()) {
                                $roleBadge = 'bg-secondary';
                                if ($row['role'] == 'Officer') $roleBadge = 'bg-dark';
                                if ($row['role'] == 'Technician') $roleBadge = 'bg-info text-dark';

                                // ADDED: class='user-row' and data-role attribute so JS can filter it
                                echo "<tr class='user-row' data-role='" . htmlspecialchars($row['role']) . "'>";
                                echo "<td class='ps-4 fw-bold'>
                                        <div class='d-flex align-items-center'>
                                            <div class='rounded-circle bg-light border d-flex align-items-center justify-content-center me-3' style='width: 40px; height: 40px;'>
                                                <i class='bi bi-person text-secondary'></i>
                                            </div>
                                            " . htmlspecialchars($row['firstName'] . ' ' . $row['lastName']) . "
                                        </div>
                                      </td>";
                                echo "<td>" . htmlspecialchars($row['email']) . "</td>";
                                echo "<td><span class='badge " . $roleBadge . "'>" . $row['role'] . "</span></td>";
                                echo "<td>" . htmlspecialchars($row['departmentName'] ?? 'N/A') . "</td>";
                                echo "<td class='text-end pe-4'>
                                        <a href='edit_user.php?id=" . $row['userId'] . "' class='btn btn-sm btn-outline-secondary me-1'><i class='bi bi-pencil'></i></a>
                                        " . ($row['userId'] != $_SESSION['user_id'] ?
                                    "<a href='delete_user.php?id=" . $row['userId'] . "' class='btn btn-sm btn-outline-danger' onclick='return confirm(\"Delete this user?\")'><i class='bi bi-trash'></i></a>"
                                    : "") . "
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const filterBtns = document.querySelectorAll('.filter-btn');
            const userRows = document.querySelectorAll('.user-row');

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {

                    filterBtns.forEach(b => {
                        b.classList.remove('active', 'btn-secondary');
                        b.classList.add('btn-outline-secondary');
                    });

                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('active', 'btn-secondary');

                    const filterValue = this.getAttribute('data-filter');

                    userRows.forEach(row => {
                        const rowRole = row.getAttribute('data-role');

                        if (filterValue === 'all' || rowRole === filterValue) {
                            row.style.display = '';
                        } else {
                            row.style.display = 'none';
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>