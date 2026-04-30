<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Technician') {
    header("Location: login.php");
    exit;
}
$page = 'starlink';

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
        $updateStmt = $pdo->prepare("UPDATE starlink SET status = ? WHERE eventId = ?");
        if ($updateStmt->execute([$newStatus, $id])) {
            $msg = "Request successfully marked as " . $newStatus;
            $msgType = "alert-success";
        } else {
            $msg = "Error updating status.";
            $msgType = "alert-danger";
        }
    }
}

$sql = "SELECT s.* FROM starlink s ORDER BY s.event_date DESC";
$stmt = $pdo->query($sql);

function formatTimeAgo($datetime)
{
    $time = strtotime($datetime);
    $diff = time() - $time;
    if ($diff < 60) return 'Just now';
    if ($diff < 3600) return floor($diff / 60) . 'm ago';
    if ($diff < 86400) return floor($diff / 3600) . 'h ago';
    return date("M d, Y", $time);
}

$pageTitle = 'Starlink Requests - DepEd Helpdesk';
include 'head.php';
?>

<body class="bg-light">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">
        <div style="width: 280px; flex-shrink: 0;"></div>
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 main-content" style="min-height: 100vh; overflow-y: auto;">

            <?php require 'header.php'; ?>

            <div class="container-fluid py-5 px-5">

                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="fw-bold text-dark mb-1"><i class="bi bi-router-fill me-2"></i>Starlink Borrowing Requests</h2>
                        <p class="text-muted">Manage and track all Starlink equipment borrowing requests.</p>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="btn-group shadow-sm" role="group">
                            <button type="button" class="btn btn-secondary active status-filter" data-status="all">All</button>
                            <button type="button" class="btn btn-outline-secondary status-filter" data-status="Pending">Pending</button>
                            <button type="button" class="btn btn-outline-secondary status-filter" data-status="Approved">Approved</button>
                            <button type="button" class="btn btn-outline-secondary status-filter" data-status="Returned">Returned</button>
                            <button type="button" class="btn btn-outline-secondary status-filter" data-status="Rejected">Rejected</button>
                        </div>

                        <div class="input-group shadow-sm" style="width: 300px;">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="requestSearch" class="form-control border-start-0" placeholder="Search requests...">
                        </div>
                    </div>
                </div>

                <?php if ($msg): ?>
                    <div class="alert <?php echo $msgType; ?> alert-dismissible fade show" role="alert">
                        <?php echo $msg; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <div class="custom-card p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead style="border-bottom: 2px solid #f0f2f5;">
                                <tr>
                                    <th class="text-muted small fw-bold pb-3">EVENT NAME</th>
                                    <th class="text-muted small fw-bold pb-3">EVENT DATE</th>
                                    <th class="text-muted small fw-bold pb-3">LOCATION</th>
                                    <th class="text-muted small fw-bold pb-3">AGING</th>
                                    <th class="text-end text-muted small fw-bold pb-3">ACTIONS</th>
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

                                        $createdDate = new DateTime($row['created_at'] ?? 'now');
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
                                        <tr class="request-row" data-status="<?php echo $status; ?>">
                                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['event_name']); ?></td>
                                            <td class="text-dark"><?php echo $dateFormatted; ?></td>
                                            <td class="text-muted"><?php echo htmlspecialchars($row['location']); ?></td>
                                            <td>
                                                <small class="<?php echo $agingClass; ?>">
                                                    <i class="bi bi-clock-history me-1"></i><?php echo $aging; ?>
                                                </small>
                                            </td>
                                            <td class="text-end">
                                                <span class="badge rounded-pill <?php echo $badgeClass; ?> me-2"><?php echo $status; ?></span>
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="id" value="<?php echo $row['eventId']; ?>">

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
                                        <td colspan="5" class="text-center py-5 text-muted">No borrowing requests found.</td>
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
    <script>
        // Status filter
        document.querySelectorAll('.status-filter').forEach(button => {
            button.addEventListener('click', function() {
                document.querySelectorAll('.status-filter').forEach(btn => {
                    btn.classList.remove('active');
                    btn.classList.add('btn-outline-secondary');
                    btn.classList.remove('btn-secondary');
                });
                this.classList.add('active');
                this.classList.remove('btn-outline-secondary');
                this.classList.add('btn-secondary');

                const status = this.dataset.status;
                document.querySelectorAll('.request-row').forEach(row => {
                    if (status === 'all' || row.dataset.status === status) {
                        row.style.display = '';
                    } else {
                        row.style.display = 'none';
                    }
                });
            });
        });

        // Search functionality
        document.getElementById('requestSearch').addEventListener('keyup', function() {
            const searchTerm = this.value.toLowerCase();
            document.querySelectorAll('.request-row').forEach(row => {
                const text = row.textContent.toLowerCase();
                if (text.includes(searchTerm)) {
                    row.style.display = '';
                } else {
                    row.style.display = 'none';
                }
            });
        });

        // Sidebar toggle
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarContainer = document.querySelector('.sidebar-container');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle && sidebarContainer && sidebarOverlay) {
                sidebarToggle.addEventListener('click', function() {
                    sidebarContainer.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });

                sidebarOverlay.addEventListener('click', function() {
                    sidebarContainer.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }

            if (window.innerWidth <= 768) {
                document.querySelectorAll('.sidebar-container .nav-link').forEach(function(link) {
                    link.addEventListener('click', function() {
                        sidebarContainer.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                    });
                });
            }
        });
    </script>
</body>

</html>
