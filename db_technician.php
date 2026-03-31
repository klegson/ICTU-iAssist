<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Technician') {
    header("Location: login.php");
    exit;
}

function formatTimeAgo($datetime)
{
    $time = strtotime($datetime);
    $diff = time() - $time;
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    return floor($diff / 86400) . 'd ago';
}

$techId = $_SESSION['user_id'];
$msg = "";
$alertClass = "";

if (isset($_POST['ticket_action'])) {
    $ticketId = $_POST['ticket_id'];
    $action = $_POST['ticket_action'];

    if ($action === 'accept') {
        $sql = "UPDATE ticket SET status = 'Processing', updatedAt = NOW() WHERE ticketId = ? AND assignedTo = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$ticketId, $techId])) {
            $_SESSION['flash_msg'] = "Ticket #$ticketId accepted. You may begin working on it.";
            $_SESSION['flash_type'] = "success";
            header("Location: db_technician.php");
            exit;
        }
    } elseif ($action === 'reject') {
        $sql = "UPDATE ticket SET assignedTo = NULL, status = 'Pending', updatedAt = NOW() WHERE ticketId = ? AND assignedTo = ?";
        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$ticketId, $techId])) {
            $_SESSION['flash_msg'] = "Ticket #$ticketId rejected and returned to Officer.";
            $_SESSION['flash_type'] = "warning";
            header("Location: db_technician.php");
            exit;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Technician Dashboard - DepEd Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="d-flex" style="min-height: 100vh;">
        <div style="width: 280px; flex-shrink: 0;">
            <?php
            $page = 'dashboard';
            include 'sidebar.php';
            ?>
        </div>

        <div class="flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">

                <div class="mb-5">
                    <h2 class="fw-bold text-dark mb-1"><i class="bi bi-tools text-primary me-2"></i>My Work Orders</h2>
                    <p class="text-muted">Manage your assigned tickets and active tasks.</p>
                </div>

                <div class="custom-card shadow-sm border-0 p-4 mb-5 border-top-info">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-pc-display me-2 text-info"></i>Technical Support Tasks</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead style="border-bottom: 2px solid #f0f2f5;">
                                <tr>
                                    <th class="text-muted small fw-bold pb-3">TICKET ID</th>
                                    <th class="text-muted small fw-bold pb-3">DATE REQUESTED</th>
                                    <th class="text-muted small fw-bold pb-3">SUBJECT</th>
                                    <th class="text-muted small fw-bold pb-3">CATEGORY</th>
                                    <th class="text-muted small fw-bold pb-3">DEPARTMENT</th>
                                    <th class="text-muted small fw-bold pb-3">PRIORITY</th>
                                    <th class="text-muted small fw-bold pb-3">STATUS</th>
                                    <th class="text-end text-muted small fw-bold pb-3">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sqlTech = "SELECT t.*, d.departmentName, c.categoryName
                                        FROM ticket t
                                        JOIN users u ON t.userId = u.userId
                                        LEFT JOIN department d ON u.departmentId = d.departmentId
                                        LEFT JOIN category c ON t.categoryId = c.categoryId
                                        WHERE t.assignedTo = ? AND t.status NOT IN ('Completed', 'Resolved')
                                        AND (c.categoryType != 'Account Services' OR c.categoryType IS NULL)
                                        ORDER BY 
                                            CASE t.priority WHEN 'High' THEN 1 WHEN 'Medium' THEN 2 WHEN 'Low' THEN 3 ELSE 4 END ASC, 
                                            t.updatedAt DESC";

                                $stmtTech = $pdo->prepare($sqlTech);
                                $stmtTech->execute([$techId]);

                                if ($stmtTech->rowCount() > 0) {
                                    while ($row = $stmtTech->fetch()) {
                                        $prioClass = match ($row['priority']) {
                                            'High' => 'bg-danger',
                                            'Medium' => 'bg-warning text-dark',
                                            'Low' => 'bg-success',
                                            default => 'bg-secondary'
                                        };
                                        $statusClass = match ($row['status']) {
                                            'Pending' => 'bg-warning text-dark',
                                            'Processing' => 'bg-primary',
                                            default => 'bg-secondary'
                                        };

                                        $exactDate = date("M d, Y", strtotime($row['createdAt']));
                                        $aging = formatTimeAgo($row['createdAt']);
                                        $agingColor = (strpos($aging, 'd') !== false) ? 'text-danger' : 'text-muted';

                                        echo "<tr style='border-bottom: 1px solid #f8f9fa;'>";
                                        echo "<td class='py-3 fw-bold text-muted'>#" . $row['ticketId'] . "</td>";
                                        echo "<td class='py-3'>
                                                <span class='d-block fs-6'>" . $exactDate . "</span>
                                                <small class='fw-bold " . $agingColor . "'><i class='bi bi-clock-history me-1'></i>" . $aging . "</small>
                                              </td>";
                                        echo "<td class='py-3 text-dark fw-bold'>" . htmlspecialchars(substr($row['subject'], 0, 30)) . "...</td>";
                                        echo "<td class='py-3'><span class='small text-muted'>" . htmlspecialchars($row['categoryName'] ?? 'General') . "</span></td>";
                                        echo "<td class='py-3'><span class='badge bg-light text-dark border'>" . htmlspecialchars($row['departmentName'] ?? 'Unknown') . "</span></td>";
                                        echo "<td class='py-3'><span class='badge " . $prioClass . "'>" . $row['priority'] . "</span></td>";
                                        echo "<td class='py-3'><span class='badge rounded-pill " . $statusClass . "'>" . $row['status'] . "</span></td>";
                                        echo "<td class='py-3 text-end'>";

                                        echo "<form method='POST' class='m-0 d-flex justify-content-end gap-1'>";
                                        echo "<input type='hidden' name='ticket_id' value='" . $row['ticketId'] . "'>";
                                        echo "<a href='manage_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-light border fw-bold text-primary'>View</a>";
                                        if ($row['status'] == 'Pending') {
                                            echo "<button type='submit' name='ticket_action' value='accept' class='btn btn-sm btn-success fw-bold'><i class='bi bi-check-lg'></i></button>";
                                            echo "<button type='button' class='btn btn-sm btn-outline-danger fw-bold btn-reject' data-id='" . $row['ticketId'] . "'><i class='bi bi-x-lg'></i></button>";
                                        } else {
                                            echo "<a href='manage_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-deped-primary fw-bold'>Resolve <i class='bi bi-arrow-right'></i></a>";
                                        }
                                        echo "</form></td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center py-4 text-muted'>No active technical support tasks.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="custom-card shadow-sm border-0 p-4 border-top-warning">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-person-badge me-2 text-warning"></i>Account Services Tasks</h5>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead style="border-bottom: 2px solid #f0f2f5;">
                                <tr>
                                    <th class="text-muted small fw-bold pb-3">TICKET ID</th>
                                    <th class="text-muted small fw-bold pb-3">DATE REQUESTED</th>
                                    <th class="text-muted small fw-bold pb-3">SUBJECT</th>
                                    <th class="text-muted small fw-bold pb-3">CATEGORY</th>
                                    <th class="text-muted small fw-bold pb-3">DEPARTMENT</th>
                                    <th class="text-muted small fw-bold pb-3">PRIORITY</th>
                                    <th class="text-muted small fw-bold pb-3">STATUS</th>
                                    <th class="text-end text-muted small fw-bold pb-3">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sqlAcc = "SELECT t.*, d.departmentName, c.categoryName
                                        FROM ticket t
                                        JOIN users u ON t.userId = u.userId
                                        LEFT JOIN department d ON u.departmentId = d.departmentId
                                        LEFT JOIN category c ON t.categoryId = c.categoryId
                                        WHERE t.assignedTo = ? AND t.status NOT IN ('Completed', 'Resolved')
                                        AND c.categoryType = 'Account Services'
                                        ORDER BY 
                                            CASE t.priority WHEN 'High' THEN 1 WHEN 'Medium' THEN 2 WHEN 'Low' THEN 3 ELSE 4 END ASC, 
                                            t.updatedAt DESC";

                                $stmtAcc = $pdo->prepare($sqlAcc);
                                $stmtAcc->execute([$techId]);

                                if ($stmtAcc->rowCount() > 0) {
                                    while ($row = $stmtAcc->fetch()) {
                                        $prioClass = match ($row['priority']) {
                                            'High' => 'bg-danger',
                                            'Medium' => 'bg-warning text-dark',
                                            'Low' => 'bg-success',
                                            default => 'bg-secondary'
                                        };
                                        $statusClass = match ($row['status']) {
                                            'Pending' => 'bg-warning text-dark',
                                            'Processing' => 'bg-primary',
                                            default => 'bg-secondary'
                                        };

                                        $exactDate = date("M d, Y", strtotime($row['createdAt']));
                                        $aging = formatTimeAgo($row['createdAt']);
                                        $agingColor = (strpos($aging, 'd') !== false) ? 'text-danger' : 'text-muted';

                                        echo "<tr style='border-bottom: 1px solid #f8f9fa;'>";
                                        echo "<td class='py-3 fw-bold text-muted'>#" . $row['ticketId'] . "</td>";
                                        echo "<td class='py-3'>
                                                <span class='d-block fs-6'>" . $exactDate . "</span>
                                                <small class='fw-bold " . $agingColor . "'><i class='bi bi-clock-history me-1'></i>" . $aging . "</small>
                                              </td>";
                                        echo "<td class='py-3 text-dark fw-bold'>" . htmlspecialchars(substr($row['subject'], 0, 30)) . "...</td>";
                                        echo "<td class='py-3'><span class='small text-muted'>" . htmlspecialchars($row['categoryName'] ?? 'General') . "</span></td>";
                                        echo "<td class='py-3'><span class='badge bg-light text-dark border'>" . htmlspecialchars($row['departmentName'] ?? 'Unknown') . "</span></td>";
                                        echo "<td class='py-3'><span class='badge " . $prioClass . "'>" . $row['priority'] . "</span></td>";
                                        echo "<td class='py-3'><span class='badge rounded-pill " . $statusClass . "'>" . $row['status'] . "</span></td>";
                                        echo "<td class='py-3 text-end'>";

                                        echo "<form method='POST' class='m-0 d-flex justify-content-end gap-1'>";
                                        echo "<input type='hidden' name='ticket_id' value='" . $row['ticketId'] . "'>";
                                        echo "<a href='manage_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-light border fw-bold text-primary'>View</a>";
                                        if ($row['status'] == 'Pending') {
                                            echo "<button type='submit' name='ticket_action' value='accept' class='btn btn-sm btn-success fw-bold'><i class='bi bi-check-lg'></i></button>";
                                            echo "<button type='button' class='btn btn-sm btn-outline-danger fw-bold btn-reject' data-id='" . $row['ticketId'] . "'><i class='bi bi-x-lg'></i></button>";
                                        } else {
                                            echo "<a href='manage_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-deped-primary fw-bold'>Resolve <i class='bi bi-arrow-right'></i></a>";
                                        }
                                        echo "</form></td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='8' class='text-center py-4 text-muted'>No active account service tasks.</td></tr>";
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
            const rejectButtons = document.querySelectorAll('.btn-reject');

            rejectButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Reject Ticket?',
                        text: "This will send the ticket back to the ICT Officer's queue.",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#d33',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, reject it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'ticket_action';
                            hiddenInput.value = 'reject';
                            form.appendChild(hiddenInput);
                            form.submit();
                        }
                    });
                });
            });
        });
    </script>

    <?php if (isset($_SESSION['flash_msg'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '<?php echo $_SESSION['flash_type']; ?>',
                    title: '<?php echo ($_SESSION['flash_type'] === 'success') ? 'Accepted!' : 'Rejected'; ?>',
                    text: '<?php echo addslashes($_SESSION['flash_msg']); ?>',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    position: 'top-end'
                });
            });
        </script>
        <?php
        unset($_SESSION['flash_msg']);
        unset($_SESSION['flash_type']);
        ?>
    <?php endif; ?>

</body>

</html>