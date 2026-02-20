<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Officer') {
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

    <?php include 'header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-xl-2 d-none d-lg-block p-0">
                <?php include 'sidebar_officer.php'; ?>
            </div>

            <div class="col-lg-9 col-xl-10 py-4">
                <div class="container">

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-dark"><i class="bi bi-people-fill me-2"></i>User Directory</h4>
                        <a href="add_user.php" class="btn btn-primary fw-bold">
                            <i class="bi bi-person-plus-fill me-2"></i> Add New User
                        </a>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-light">
                                    <tr>
                                        <th class="ps-4">Name</th>
                                        <th>Email</th>
                                        <th>Role</th>
                                        <th>Department</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    // Fetch Users + Department Name
                                    $sql = "SELECT u.*, d.departmentName 
                                            FROM users u 
                                            LEFT JOIN department d ON u.departmentId = d.departmentId 
                                            ORDER BY u.role, u.lastName";
                                    $stmt = $pdo->query($sql);

                                    while ($row = $stmt->fetch()) {
                                        // Role Badge Color
                                        $roleBadge = 'bg-secondary';
                                        if ($row['role'] == 'Officer') $roleBadge = 'bg-dark';
                                        if ($row['role'] == 'Technician') $roleBadge = 'bg-info text-dark';

                                        echo "<tr>";
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
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>