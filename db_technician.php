<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
require 'db.php';

$msg = "";
$alertClass = "";
if (isset($_POST['ticket_action'])) {
    $ticketId = $_POST['ticket_id'];
    $action = $_POST['ticket_action'];

    if ($action === 'accept') {
        $sql = "UPDATE ticket SET status = 'Processing' WHERE ticketId = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ticketId]);

        $msg = "Success! You accepted Ticket #$ticketId. You may now begin working on it.";
        $alertClass = "alert-success";
    } elseif ($action === 'reject') {
        $sql = "UPDATE ticket SET assignedTo = NULL, status = 'Pending' WHERE ticketId = ?";
        $stmt = $pdo->prepare($sql);
        $stmt->execute([$ticketId]);

        $msg = "Ticket #$ticketId was rejected and returned to the ICT Officer's queue.";
        $alertClass = "alert-warning";
    }
}

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Technician') {
    header("Location: login.php");
    exit;
}

$techId = $_SESSION['user_id'];
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Technician Dashboard</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <?php include 'header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-xl-2 d-none d-lg-block p-0">
                <?php if ($_SESSION['role'] === 'Technician') {
                    include 'sidebar_tech.php';
                } else {
                    include 'sidebar_officer.php';
                }
                ?>
            </div>

            <div class="col-lg-9 col-xl-10 py-4">
                <div class="container">

                    <h3 class="fw-bold text-dark mb-4">🔧 My Work Orders</h3>
                    <?php if ($msg): ?>
                        <div class="alert <?php echo $alertClass; ?> alert-dismissible fade show" role="alert">
                            <?php echo $msg; ?>
                            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                        </div>
                    <?php endif; ?>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th class="ps-4">Ticket ID</th>
                                        <th>Priority</th>
                                        <th>Issue / Subject</th>
                                        <th>Department</th>
                                        <th>Status</th>
                                        <th class="text-end pe-4">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT t.*, d.departmentName
                                    FROM ticket t
                                    JOIN users u ON t.userId = u.userId
                                    LEFT JOIN department d ON u.departmentId = d.departmentId
                                    WHERE t.assignedTo = ?
                                    ORDER BY t.updatedAt DESC";

                                    $stmt = $pdo->prepare($sql);
                                    $stmt->execute([$techId]);

                                    if ($stmt->rowCount() > 0) {
                                        while ($row = $stmt->fetch()) {
                                            $prioClass = 'bg-secondary';
                                            if ($row['priority'] == 'High') $prioClass = 'bg-danger';
                                            if ($row['priority'] == 'Medium') $prioClass = 'bg-warning text-dark';
                                            if ($row['priority'] == 'Low') $prioClass = 'bg-success';

                                            $statusClass = 'bg-secondary';
                                            if ($row['status'] == 'Processing') $statusClass = 'bg-primary';
                                            if ($row['status'] == 'Completed') $statusClass = 'bg-success';

                                            $deptName = htmlspecialchars($row['departmentName'] ?? 'Unknown');

                                            echo "<tr>";
                                            echo "<td class='ps-4 fw-bold'>#" . $row['ticketId'] . "</td>";
                                            echo "<td><span class='badge " . $prioClass . "'>" . $row['priority'] . "</span></td>";

                                            echo "<td><span class='badge bg-light text-dark border border-secondary'>" . $deptName . "</span></td>";

                                            echo "<td>" . htmlspecialchars($row['subject']) . "</td>";
                                            echo "<td><span class='badge " . $statusClass . "'>" . $row['status'] . "</span></td>";

                                            echo "<td class='text-end pe-4'>";

                                            if ($row['status'] == 'Pending') {
                                                echo "<form method='POST' class='m-0 d-flex justify-content-end gap-1'>";
                                                echo "<input type='hidden' name='ticket_id' value='" . $row['ticketId'] . "'>";

                                                echo "<a href='manage_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-outline-info fw-bold' title='View Details'>
                                               <i class='bi bi-eye'></i> View
                                               </a>";

                                                echo "<button type='submit' name='ticket_action' value='accept' class='btn btn-sm btn-success fw-bold' title='Accept Ticket'>
                                                <i class='bi bi-check-lg'></i> Accept
                                                </button>";

                                                echo "<button type='submit' name='ticket_action' value='reject' class='btn btn-sm btn-danger fw-bold' title='Reject Ticket' onclick='return confirm(\"Are you sure you want to reject this ticket?\");'>
                                                <i class='bi bi-x-lg'></i> Reject
                                                </button>";
                                                echo "</form>";
                                            } else {
                                                echo "<a href='manage_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-primary fw-bold'>
                                                Update Ticket <i class='bi bi-arrow-right'></i>
                                                </a>";
                                            }

                                            echo "</td>";

                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center py-5 text-muted'>
                                        <h5>No active tasks.</h5>
                                        <p>Wait for the ICT Officer to assign you new tickets.</p>
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

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>