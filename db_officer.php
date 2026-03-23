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

$resolvedSql = "SELECT COUNT(*) FROM ticket WHERE status = 'Resolved' OR status = 'Completed'";
$resolvedCount = $pdo->query($resolvedSql)->fetchColumn();

function formatTimeAgo($datetime)
{
    $time = strtotime($datetime);
    $diff = time() - $time;
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    return floor($diff / 86400) . 'd ago';
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Officer Dashboard - DepEd Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="d-flex" style="min-height: 100vh;">
        <div style="width: 280px; flex-shrink: 0;">
            <?php
            $page = 'dashboard';
            include 'sidebar_officer.php';
            ?>
        </div>

        <div class="flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">

                <div class="mb-5">
                    <h2 class="fw-bold text-dark mb-1">Officer Dashboard</h2>
                    <p class="text-muted">Overview of all system requests and active tickets.</p>
                </div>

                <div class="row mb-5 g-4">
                    <div class="col-md-4">
                        <div class="stat-card border-top-warning h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="fw-bold text-muted" style="font-size: 0.85rem; letter-spacing: 0.5px;">NEW REQUESTS</span>
                                <i class="bi bi-envelope-exclamation-fill text-warning fs-4"></i>
                            </div>
                            <h1 class="display-4 fw-bold text-dark mb-0"><?php echo $pendingCount; ?></h1>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card border-top-info h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="fw-bold text-muted" style="font-size: 0.85rem; letter-spacing: 0.5px;">IN PROGRESS</span>
                                <i class="bi bi-tools text-info fs-4"></i>
                            </div>
                            <h1 class="display-4 fw-bold text-dark mb-0"><?php echo $processCount; ?></h1>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-card border-top-success h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="fw-bold text-muted" style="font-size: 0.85rem; letter-spacing: 0.5px;">RESOLVED TOTAL</span>
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                            </div>
                            <h1 class="display-4 fw-bold text-dark mb-0"><?php echo $resolvedCount; ?></h1>
                        </div>
                    </div>
                </div>

                <div class="custom-card p-4 mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-person-badge-fill text-primary me-2"></i>New Account Requests</h6>
                        <span class="badge bg-primary">Service Request</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead style="border-bottom: 2px solid #f0f2f5;">
                                <tr>
                                    <th class="text-muted small fw-bold pb-3">TICKET ID</th>
                                    <th class="text-muted small fw-bold pb-3">DATE REQUESTED</th>
                                    <th class="text-muted small fw-bold pb-3">REQUEST TYPE</th>
                                    <th class="text-muted small fw-bold pb-3">REQUESTOR</th>
                                    <th class="text-end text-muted small fw-bold pb-3">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT t.*, u.firstName, u.lastName, d.departmentName, c.categoryName
                                        FROM ticket t 
                                        JOIN users u ON t.userId = u.userId
                                        LEFT JOIN department d ON t.departmentId = d.departmentId
                                        JOIN category c ON t.categoryId = c.categoryId
                                        WHERE t.status = 'Pending' AND c.categoryType = 'Account Services'
                                        ORDER BY t.createdAt ASC";

                                $stmt = $pdo->query($sql);

                                if ($stmt->rowCount() > 0) {
                                    while ($row = $stmt->fetch()) {
                                        $exactDate = date("M d, Y", strtotime($row['createdAt']));
                                        $aging = formatTimeAgo($row['createdAt']);
                                        $agingColor = (strpos($aging, 'd') !== false) ? 'text-danger' : 'text-muted';

                                        $name = htmlspecialchars($row['firstName'] . ' ' . $row['lastName']);
                                        $dept = htmlspecialchars($row['departmentName'] ?? 'N/A');

                                        echo "<tr style='border-bottom: 1px solid #f8f9fa;'>";
                                        echo "<td class='py-3 fw-bold text-primary'>#" . $row['ticketId'] . "</td>";
                                        echo "<td class='py-3'>
                                                <span class='d-block fs-6'>" . $exactDate . "</span>
                                                <small class='fw-bold " . $agingColor . "'><i class='bi bi-clock-history me-1'></i>" . $aging . "</small>
                                              </td>";
                                        echo "<td class='py-3 fw-bold'>" . htmlspecialchars($row['categoryName']) . "</td>";
                                        echo "<td class='py-3'>
                                                <span class='d-block text-dark fw-bold'>" . $name . "</span>
                                                <small class='text-muted'>" . $dept . "</small>
                                              </td>";
                                        echo "<td class='py-3 text-end'>
                                                <a href='manage_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-deped-primary'>
                                                    Review <i class='bi bi-arrow-right ms-1'></i>
                                                </a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center py-4 text-muted'>No pending account requests.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="custom-card p-4 mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold text-dark mb-0"><i class="bi bi-tools text-danger me-2"></i>Incoming Technical Support</h6>
                        <span class="badge bg-warning text-dark">Pending Repairs</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead style="border-bottom: 2px solid #f0f2f5;">
                                <tr>
                                    <th class="text-muted small fw-bold pb-3">TICKET ID</th>
                                    <th class="text-muted small fw-bold pb-3">DATE REQUESTED</th>
                                    <th class="text-muted small fw-bold pb-3">SUBJECT</th>
                                    <th class="text-muted small fw-bold pb-3">CATEGORY</th>
                                    <th class="text-muted small fw-bold pb-3">REQUESTOR</th>
                                    <th class="text-end text-muted small fw-bold pb-3">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT t.*, u.firstName, u.lastName, d.departmentName, c.categoryName
                                        FROM ticket t 
                                        JOIN users u ON t.userId = u.userId 
                                        LEFT JOIN department d ON t.departmentId = d.departmentId 
                                        LEFT JOIN category c ON t.categoryId = c.categoryId
                                        WHERE t.status = 'Pending' AND (c.categoryType != 'Account Services' OR c.categoryType IS NULL)
                                        ORDER BY t.createdAt ASC";

                                $stmt = $pdo->query($sql);

                                if ($stmt->rowCount() > 0) {
                                    while ($row = $stmt->fetch()) {
                                        $exactDate = date("M d, Y", strtotime($row['createdAt']));
                                        $aging = formatTimeAgo($row['createdAt']);
                                        $agingColor = (strpos($aging, 'd') !== false) ? 'text-danger' : 'text-muted';

                                        $name = htmlspecialchars($row['firstName'] . ' ' . $row['lastName']);
                                        $dept = htmlspecialchars($row['departmentName'] ?? 'N/A');
                                        $catName = htmlspecialchars($row['categoryName'] ?? 'General');

                                        echo "<tr style='border-bottom: 1px solid #f8f9fa;'>";
                                        echo "<td class='py-3 fw-bold text-muted'>#" . $row['ticketId'] . "</td>";
                                        echo "<td class='py-3'>
                                                <span class='d-block fs-6'>" . $exactDate . "</span>
                                                <small class='fw-bold " . $agingColor . "'><i class='bi bi-clock-history me-1'></i>" . $aging . "</small>
                                              </td>";
                                        echo "<td class='py-3'>" . htmlspecialchars(substr($row['subject'], 0, 40)) . "...</td>";
                                        echo "<td class='py-3'><span class='small text-muted'>" . $catName . "</span></td>";
                                        echo "<td class='py-3'>
                                                <span class='d-block fw-bold text-dark'>" . $name . "</span>
                                                <small class='text-muted'>" . $dept . "</small>
                                              </td>";
                                        echo "<td class='py-3 text-end'>
                                                <a href='manage_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-deped-primary'>
                                                    Process <i class='bi bi-arrow-right ms-1'></i>
                                                </a>
                                              </td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center py-4 text-muted'>No pending technical tickets.</td></tr>";
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

    <?php if (isset($_SESSION['flash_msg'])): ?>
        <script>
            document.addEventListener('DOMContentLoaded', function() {
                Swal.fire({
                    icon: '<?php echo (strpos($_SESSION['flash_type'], 'success') !== false) ? 'success' : 'info'; ?>',
                    title: 'Update Successful',
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