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
$page = 'inventory';

$msg = "";
$msgType = "";

if (isset($_POST['action']) && isset($_POST['id'])) {
    $action = $_POST['action'];
    $id = $_POST['id'];
    $newStatus = "";

    if ($action === 'approve') $newStatus = 'Approved';
    if ($action === 'reject') $newStatus = 'Rejected';
    if ($action === 'return') $newStatus = 'Returned';

    if ($newStatus) {
        $updateStmt = $pdo->prepare("UPDATE starlink SET status = ? WHERE id = ?");
        if ($updateStmt->execute([$newStatus, $id])) {
            $msg = "Request successfully marked as " . $newStatus;
            $msgType = "alert-success";
        } else {
            $msg = "Error updating status.";
            $msgType = "alert-danger";
        }
    }
}

$sql = "SELECT s.*, u.firstName, u.lastName, u.role, d.departmentName 
        FROM starlink s 
        JOIN users u ON s.userId = u.userId 
        LEFT JOIN department d ON u.departmentId = d.departmentId 
        ORDER BY s.event_date DESC";
$stmt = $pdo->query($sql);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Starlink Inventory - DepEd Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <div style="position: fixed; top: 0; left: 0; height: 100vh; width: 280px; z-index: 1000; overflow-y: auto;">
        <?php include 'sidebar_officer.php'; ?>
    </div>

    <div style="margin-left: 280px;">

        <?php require 'header.php'; ?>

        <div class="container-fluid py-5 px-5">

            <div class="mb-4">
                <h2 class="fw-bold text-dark mb-1"><i class="bi bi-router-fill me-2"></i>Starlink Inventory Manager</h2>
                <p class="text-muted">Manage borrowing requests and track equipment status.</p>
            </div>

            <?php if ($msg): ?>
                <div class="alert <?php echo $msgType; ?> alert-dismissible fade show" role="alert">
                    <?php echo $msg; ?>
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            <?php endif; ?>

            <div class="card shadow-sm border-0">
                <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                    <h6 class="fw-bold m-0 text-dark">Borrowing Requests</h6>
                    <span class="badge bg-dark">Total Records: <?php echo $stmt->rowCount(); ?></span>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover align-middle mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th class="ps-4">Ref # & Aging</th>
                                    <th>Requestor</th>
                                    <th>Position & Dept</th>
                                    <th>Event</th>
                                    <th>When</th>
                                    <th>Status</th>
                                    <th class="text-end pe-4">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if ($stmt->rowCount() > 0): ?>
                                    <?php while ($row = $stmt->fetch()): ?>
                                        <?php
                                        $status = $row['status'] ?? 'Pending';
                                        $badgeClass = 'bg-secondary';
                                        if ($status == 'Pending') $badgeClass = 'bg-warning text-dark';
                                        if ($status == 'Approved') $badgeClass = 'bg-primary';
                                        if ($status == 'Returned') $badgeClass = 'bg-success';
                                        if ($status == 'Rejected') $badgeClass = 'bg-danger';

                                        $dateFormatted = date("M d, Y", strtotime($row['event_date']));

                                        $createdDate = new DateTime($row['createdAt'] ?? 'now');
                                        $now = new DateTime();
                                        $interval = $now->diff($createdDate);

                                        if ($interval->y > 0) {
                                            $aging = $interval->y . ' yr' . ($interval->y > 1 ? 's' : '') . ' ago';
                                        } elseif ($interval->m > 0) {
                                            $aging = $interval->m . ' mo' . ($interval->m > 1 ? 's' : '') . ' ago';
                                        } elseif ($interval->d > 0) {
                                            $aging = $interval->d . ' day' . ($interval->d > 1 ? 's' : '') . ' ago';
                                        } elseif ($interval->h > 0) {
                                            $aging = $interval->h . ' hr' . ($interval->h > 1 ? 's' : '') . ' ago';
                                        } elseif ($interval->i > 0) {
                                            $aging = $interval->i . ' min' . ($interval->i > 1 ? 's' : '') . ' ago';
                                        } else {
                                            $aging = 'Just now';
                                        }

                                        $agingClass = ($status == 'Pending' && $interval->d >= 2) ? 'text-danger fw-bold' : 'text-muted';
                                        ?>
                                        <tr>
                                            <td class="ps-4">
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['reference_number']); ?></div>
                                                <small class="<?php echo $agingClass; ?>" title="Time since requested">
                                                    <i class="bi bi-clock-history me-1"></i><?php echo $aging; ?>
                                                </small>
                                            </td>

                                            <td>
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?></div>
                                            </td>

                                            <td>
                                                <div class="text-dark small fw-bold"><?php echo htmlspecialchars($row['position'] ?? 'Staff'); ?></div>
                                                <small class="text-muted"><?php echo htmlspecialchars($row['departmentName'] ?? 'N/A'); ?></small>
                                            </td>

                                            <td>
                                                <div class="fw-bold text-dark"><?php echo htmlspecialchars($row['event_name']); ?></div>
                                                <small class="text-muted"><i class="bi bi-geo-alt me-1"></i><?php echo htmlspecialchars($row['location']); ?></small>
                                            </td>

                                            <td class="text-dark fw-medium"><?php echo $dateFormatted; ?></td>

                                            <td><span class="badge rounded-pill <?php echo $badgeClass; ?>"><?php echo $status; ?></span></td>

                                            <td class="text-end pe-4">
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?php echo $row['id'] ?? $row['reference_number']; ?>">

                                                    <?php if ($status == 'Pending'): ?>
                                                        <button type="submit" name="action" value="approve" class="btn btn-sm btn-success me-1" onclick="return confirm('Approve this request?')">Approve</button>
                                                        <button type="submit" name="action" value="reject" class="btn btn-sm btn-outline-danger" onclick="return confirm('Reject this request?')">Reject</button>
                                                    <?php elseif ($status == 'Approved'): ?>
                                                        <button type="submit" name="action" value="return" class="btn btn-sm btn-dark" onclick="return confirm('Mark equipment as returned?')">Mark Returned</button>
                                                    <?php else: ?>
                                                        <button disabled class="btn btn-sm btn-light border text-muted">Archived</button>
                                                    <?php endif; ?>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">No borrowing records found.</td>
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
</body>

</html>