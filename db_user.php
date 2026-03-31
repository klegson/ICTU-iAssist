<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'User') {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$stmtSig = $pdo->prepare("SELECT signature FROM users WHERE userId = ?");
$stmtSig->execute([$userId]);
$currentSignature = $stmtSig->fetchColumn();

if (empty($currentSignature)) {
    header("Location: create_signature.php");
    exit;
}

$statStmt = $pdo->prepare("SELECT 
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as p,
    SUM(CASE WHEN status = 'Processing' THEN 1 ELSE 0 END) as pr,
    SUM(CASE WHEN status IN ('Resolved', 'Closed', 'Completed') THEN 1 ELSE 0 END) as r
    FROM ticket WHERE userId = ?");
$statStmt->execute([$userId]);
$stats = $statStmt->fetch();

function formatTimeAgo($datetime)
{
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return date("M d, Y", $time);
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | DepEd Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="d-flex" style="min-height: 100vh;">
        <div style="width: 280px; flex-shrink: 0;">
            <?php include 'sidebar.php'; ?>
        </div>

        <div class="flex-grow-1 bg-light" style="height: 100vh; overflow-y: auto;">

            <div class="container-fluid py-5 px-5">

                <div class="row align-items-center mb-5">
                    <div class="col-md-8">
                        <h2 class="fw-bold text-dark mb-1">User Dashboard</h2>
                        <p class="text-muted">Manage your pending ICT support requests.</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="create_ticket.php" class="btn btn-deped-primary shadow-sm">
                            <i class="bi bi-plus-lg me-2"></i>SUBMIT NEW TICKET
                        </a>
                    </div>
                </div>

                <div class="row mb-5 g-4">
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 border-top border-warning border-4 h-100 p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="fw-bold text-muted small">PENDING</span>
                                <i class="bi bi-hourglass-split text-warning fs-4"></i>
                            </div>
                            <h1 class="display-4 fw-bold text-dark mb-0"><?php echo $stats['p'] ?? 0; ?></h1>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 border-top border-info border-4 h-100 p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="fw-bold text-muted small">PROCESSING</span>
                                <i class="bi bi-gear-fill text-info fs-4"></i>
                            </div>
                            <h1 class="display-4 fw-bold text-dark mb-0"><?php echo $stats['pr'] ?? 0; ?></h1>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="card shadow-sm border-0 border-top border-success border-4 h-100 p-4">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="fw-bold text-muted small">RESOLVED / COMPLETED</span>
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                            </div>
                            <h1 class="display-4 fw-bold text-dark mb-0"><?php echo $stats['r'] ?? 0; ?></h1>
                        </div>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 mb-5">
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-tools me-2 text-danger"></i>Pending Technical Support</h6>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead style="border-bottom: 2px solid #f0f2f5;">
                                <tr>
                                    <th class="text-muted small fw-bold pb-3">TICKET #</th>
                                    <th class="text-muted small fw-bold pb-3">DATE REQUESTED</th>
                                    <th class="text-muted small fw-bold pb-3">SUBJECT</th>
                                    <th class="text-muted small fw-bold pb-3">CATEGORY</th>
                                    <th class="text-muted small fw-bold pb-3">STATUS</th>
                                    <th class="text-end text-muted small fw-bold pb-3">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT t.*, c.categoryName 
                                        FROM ticket t 
                                        LEFT JOIN category c ON t.categoryId = c.categoryId 
                                        WHERE t.userId = ? AND t.status = 'Pending'
                                        AND (c.categoryType != 'Account Services' OR c.categoryType IS NULL) 
                                        ORDER BY t.createdAt DESC";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$userId]);

                                if ($stmt->rowCount() > 0) {
                                    while ($row = $stmt->fetch()) {
                                        $exactDate = date("M d, Y", strtotime($row['createdAt']));
                                        $aging = formatTimeAgo($row['createdAt']);

                                        echo "<tr style='border-bottom: 1px solid #f8f9fa;'>";
                                        echo "<td class='py-3 fw-bold text-muted'>#" . $row['ticketId'] . "</td>";
                                        echo "<td class='py-3'><div class='text-dark fw-medium'>" . $exactDate . "</div><small class='text-muted'><i class='bi bi-clock-history me-1'></i>" . $aging . "</small></td>";
                                        echo "<td class='py-3'>" . htmlspecialchars(substr($row['subject'], 0, 35)) . "...</td>";
                                        echo "<td class='py-3'><span class='small text-muted'>" . htmlspecialchars($row['categoryName'] ?? 'General') . "</span></td>";
                                        echo "<td class='py-3'><span class='badge rounded-pill bg-warning text-dark'>" . $row['status'] . "</span></td>";
                                        echo "<td class='py-3 text-end'>";
                                        echo "<a href='view_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-light border me-1'>View</a>";
                                        echo "<a href='edit_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-light border me-1'>Edit</a>";
                                        echo "<a href='delete_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-outline-danger btn-cancel-ticket'><i class='bi bi-trash'></i></a>";
                                        echo "</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center py-5 text-muted small'>No pending support tickets found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 mb-5">
                    <div class="mb-4">
                        <h6 class="fw-bold text-dark mb-3"><i class="bi bi-person-badge me-2 text-primary"></i>Pending Account Requests</h6>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead style="border-bottom: 2px solid #f0f2f5;">
                                <tr>
                                    <th class="text-muted small fw-bold pb-3">REF #</th>
                                    <th class="text-muted small fw-bold pb-3">DATE REQUESTED</th>
                                    <th class="text-muted small fw-bold pb-3">REQUEST TYPE</th>
                                    <th class="text-muted small fw-bold pb-3">STATUS</th>
                                    <th class="text-end text-muted small fw-bold pb-3">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT t.*, c.categoryName 
                                        FROM ticket t 
                                        LEFT JOIN category c ON t.categoryId = c.categoryId 
                                        WHERE t.userId = ? AND t.status = 'Pending' AND c.categoryType = 'Account Services' 
                                        ORDER BY t.createdAt DESC";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$userId]);

                                if ($stmt->rowCount() > 0) {
                                    while ($row = $stmt->fetch()) {
                                        $exactDate = date("M d, Y", strtotime($row['createdAt']));
                                        $aging = formatTimeAgo($row['createdAt']);

                                        echo "<tr style='border-bottom: 1px solid #f8f9fa;'>";
                                        echo "<td class='py-3 fw-bold text-primary'>#" . $row['ticketId'] . "</td>";
                                        echo "<td class='py-3'><div class='text-dark fw-medium'>" . $exactDate . "</div><small class='text-muted'><i class='bi bi-clock-history me-1'></i>" . $aging . "</small></td>";
                                        echo "<td class='py-3 fw-bold'>" . htmlspecialchars($row['categoryName']) . "</td>";
                                        echo "<td class='py-3'><span class='badge rounded-pill bg-warning text-dark'>" . $row['status'] . "</span></td>";
                                        echo "<td class='py-3 text-end'>";
                                        echo "<a href='view_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-light border me-1'>View</a>";
                                        echo "<a href='edit_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-light border me-1'>Edit</a>";
                                        echo "<a href='delete_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-outline-danger btn-cancel-ticket'><i class='bi bi-trash'></i></a>";
                                        echo "</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center py-5 text-muted small'>No pending account requests found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert Delete Logic
            const cancelButtons = document.querySelectorAll('.btn-cancel-ticket');
            cancelButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const cancelUrl = this.getAttribute('href');
                    Swal.fire({
                        title: 'Cancel this request?',
                        text: "You won't be able to undo this action!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, cancel it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = cancelUrl;
                        }
                    });
                });
            });
        });
    </script>
</body>

</html>