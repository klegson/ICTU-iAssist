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
            if ($newStatus === 'Approved') {
                $userStmt = $pdo->prepare("SELECT userId, reference_number FROM starlink WHERE eventId = ?");
                $userStmt->execute([$id]);
                $request = $userStmt->fetch();
                if ($request) {
                    $notifMsg = "Your Starlink request (Ref: {$request['reference_number']}) has been approved.";
                    $pdo->prepare("INSERT INTO notification (message, userId) VALUES (?, ?)")->execute([$notifMsg, $request['userId']]);
                }
            }
            $msg = "Request successfully marked as " . $newStatus;
            $msgType = "alert-success";
        } else {
            $msg = "Error updating status.";
            $msgType = "alert-danger";
        }
    }
}

$sql = "SELECT s.*, u.firstName, u.lastName, d.departmentName, d.section_unit
        FROM starlink s 
        JOIN users u ON s.userId = u.userId 
        LEFT JOIN department d ON u.departmentId = d.departmentId 
        ORDER BY s.event_date DESC";
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
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 main-content" style="min-height: 100vh; overflow-y: auto;">

            <?php require 'header.php'; ?>

            <div class="container-fluid py-5 px-5">

                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="fw-bold text-dark mb-1"><i class="bi bi-router-fill me-2"></i>Starlink Borrowing Requests</h2>
                        <p class="text-muted">Manage and track all Starlink equipment borrowing requests.</p>
                    </div>

                    <div class="input-group shadow-sm" style="width: 300px;">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="requestSearch" class="form-control border-start-0" placeholder="Search requests...">
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
                                    <th class="text-muted small fw-bold pb-3">NAME</th>
                                    <th class="text-muted small fw-bold pb-3">DEPARTMENT</th>
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
                                        <tr class="request-row">
                                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['firstName'] . ' ' . $row['lastName']); ?></td>
                                            <td class="text-dark"><?php echo htmlspecialchars($row['departmentName'] ?? 'N/A') . ($row['section_unit'] ? ' / ' . $row['section_unit'] : ''); ?></td>
                                            <td class="fw-bold text-dark"><?php echo htmlspecialchars($row['event_name']); ?></td>
                                            <td class="text-dark"><?php echo $dateFormatted; ?></td>
                                            <td class="text-muted"><?php echo htmlspecialchars($row['location']); ?></td>
                                            <td>
                                                <small class="<?php echo $agingClass; ?>">
                                                    <i class="bi bi-clock-history me-1"></i><?php echo $aging; ?>
                                                </small>
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-outline-primary view-btn" 
                                                    data-bs-toggle="modal" data-bs-target="#viewStarlinkModal"
                                                    data-id="<?php echo $row['eventId']; ?>"
                                                    data-ref="<?php echo htmlspecialchars($row['reference_number']); ?>"
                                                    data-event="<?php echo htmlspecialchars($row['event_name']); ?>"
                                                    data-location="<?php echo htmlspecialchars($row['location']); ?>"
                                                    data-date="<?php echo htmlspecialchars($row['event_date']); ?>"
                                                    data-desc="<?php echo htmlspecialchars($row['description']); ?>">
                                                    <i class="bi bi-eye me-1"></i>View
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endwhile; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="6" class="text-center py-5 text-muted">No borrowing requests found.</td>
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        // View button modal
        let currentEventId = null;
        document.querySelectorAll('.view-btn').forEach(button => {
            button.addEventListener('click', function() {
                currentEventId = this.dataset.id;
                document.getElementById('modalRef').textContent = this.dataset.ref;
                document.getElementById('modalEvent').textContent = this.dataset.event;
                document.getElementById('modalDate').textContent = new Date(this.dataset.date).toLocaleDateString('en-US', { year: 'numeric', month: 'long', day: 'numeric' });
                document.getElementById('modalLocation').textContent = this.dataset.location;
                document.getElementById('modalDesc').textContent = this.dataset.desc || 'No description provided.';
            });
        });

        // Accept button
        document.getElementById('modalAcceptBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Accept this request?',
                text: 'The request will be marked as Approved.',
                icon: 'question',
                showCancelButton: true,
                confirmButtonColor: '#198754',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, accept it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formAction').value = 'approve';
                    document.getElementById('formId').value = currentEventId;
                    document.getElementById('actionForm').submit();
                }
            });
        });

        // Reject button
        document.getElementById('modalRejectBtn').addEventListener('click', function() {
            Swal.fire({
                title: 'Reject this request?',
                text: 'The request will be marked as Rejected.',
                icon: 'warning',
                showCancelButton: true,
                confirmButtonColor: '#dc3545',
                cancelButtonColor: '#6c757d',
                confirmButtonText: 'Yes, reject it!'
            }).then((result) => {
                if (result.isConfirmed) {
                    document.getElementById('formAction').value = 'reject';
                    document.getElementById('formId').value = currentEventId;
                    document.getElementById('actionForm').submit();
                }
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

    <!-- View Starlink Modal -->
    <div class="modal fade" id="viewStarlinkModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-router-fill me-2"></i>Starlink Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase">Reference Number</label>
                        <div id="modalRef" class="fw-bold text-dark"></div>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase">Event Name</label>
                        <div id="modalEvent" class="fw-bold text-dark"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase">Event Date</label>
                            <div id="modalDate" class="text-dark"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase">Location</label>
                            <div id="modalLocation" class="text-dark"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase">Description / Remarks</label>
                        <div id="modalDesc" class="p-3 bg-light border rounded text-dark" style="min-height: 80px;"></div>
                    </div>
                </div>
                <div class="modal-footer border-0">
                    <button type="button" class="btn btn-success" id="modalAcceptBtn">
                        <i class="bi bi-check-circle me-1"></i>Accept
                    </button>
                    <button type="button" class="btn btn-danger" id="modalRejectBtn">
                        <i class="bi bi-x-circle me-1"></i>Reject
                    </button>
                </div>
            </div>
        </div>
    </div>

    <form id="actionForm" method="POST" style="display: none;">
        <input type="hidden" name="action" id="formAction">
        <input type="hidden" name="id" id="formId">
    </form>

</body>

</html>
