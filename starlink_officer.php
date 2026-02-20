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

$page = 'starlink';
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Starlink Requests - DepEd Helpdesk</title>
    <link rel="icon" href="deped_logo.png" type="image/png">
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
                        <h4 class="fw-bold text-dark"><i class="bi bi-hdd-network-fill me-2 text-primary"></i>Starlink Borrowing Requests</h4>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-0">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="bg-dark text-white">
                                    <tr>
                                        <th class="ps-4">Reference No.</th>
                                        <th>Event Name</th>
                                        <th>Location</th>
                                        <th>Event Date</th>
                                        <th>Submitted By</th>
                                        <th class="text-end pe-4">Agreement Form</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    $sql = "SELECT s.*, u.firstName, u.lastName 
                                            FROM starlink s 
                                            LEFT JOIN users u ON s.userId = u.userId 
                                            ORDER BY s.created_at DESC";
                                    $stmt = $pdo->query($sql);

                                    if ($stmt->rowCount() > 0) {
                                        while ($row = $stmt->fetch()) {
                                            $formattedDate = date("M d, Y", strtotime($row['event_date']));
                                            $submitterName = htmlspecialchars($row['firstName'] . ' ' . $row['lastName']);

                                            echo "<tr>";
                                            echo "<td class='ps-4 fw-bold text-primary'>" . htmlspecialchars($row['reference_number']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['event_name']) . "</td>";
                                            echo "<td>" . htmlspecialchars($row['location']) . "</td>";
                                            echo "<td><span class='badge bg-light text-dark border'>" . $formattedDate . "</span></td>";
                                            echo "<td>" . $submitterName . "</td>";

                                            echo "<td class='text-end pe-4'>
                                                        <a href='generate_pdf.php?ref=" . $row['reference_number'] . "' class='btn btn-sm btn-outline-danger fw-bold' target='_blank' title='Download PDF'>
                                                            <i class='bi bi-file-earmark-pdf-fill me-1'></i> Print Form
                                                        </a>
                                                      </td>";
                                            echo "</tr>";
                                        }
                                    } else {
                                        echo "<tr><td colspan='6' class='text-center py-5 text-muted'>
                                            <i class='bi bi-inbox fs-1 d-block mb-3'></i>
                                            <h5>No Starlink requests found.</h5>
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