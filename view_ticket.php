<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$ticketId = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_ticket'])) {
    $_SESSION['pending_completion'] = $ticketId;
    header("Location: view_ticket.php?id=" . $ticketId);
    exit;
}

$showSurveyModal = isset($_SESSION['pending_completion']) && $_SESSION['pending_completion'] == $ticketId;

$sql = "SELECT t.*, u.firstName, u.lastName, u.email, d.departmentName, c.categoryName,
        tech.firstName AS techFirstName, tech.lastName AS techLastName
        FROM ticket t 
        JOIN users u ON t.userId = u.userId 
        LEFT JOIN department d ON u.departmentId = d.departmentId
        LEFT JOIN category c ON t.categoryId = c.categoryId
        LEFT JOIN users tech ON t.assignedTo = tech.userId
        WHERE t.ticketId = ?";

if ($role === 'User') {
    $sql .= " AND t.userId = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ticketId, $userId]);
} else {
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ticketId]);
}

$ticket = $stmt->fetch();

if (!$ticket) {
    echo "<div style='text-align:center; margin-top:50px; font-family:sans-serif;'>
            <h3>Ticket not found</h3>
            <p>This ticket does not exist or you do not have permission to view it.</p>
            <a href='index.php'>Return to Dashboard</a>
          </div>";
    exit;
}

$pageTitle = 'View Ticket #' . htmlspecialchars($ticketId);
include 'head.php';
?>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <?php if (isset($showSurveyModal) && $showSurveyModal): ?>
    <div class="modal fade" id="surveyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-star-fill text-warning me-2"></i>Rate Our Service</h5>
                </div>
                <div class="modal-body text-center py-4">
                    <p class="text-muted mb-4">Please take a moment to complete the survey. Your feedback helps us improve.</p>
                    <div class="mb-4">
                        <a href="https://forms.office.com/pages/responsepage.aspx?id=gKvjQCQgo0W_dnoHYaJNKZVrGLcKRchGg0_5vlA39MhURDc2OU5GTENEVEw2WlJPU1JYSDRXWVZBVi4u" target="_blank" class="btn btn-success fw-bold px-5 py-2 rounded-3">
                            <i class="bi bi-clipboard-check me-2"></i>Complete Survey
                        </a>
                    </div>
                    <p class="text-muted small mb-3">After completing the survey, click the button below to confirm.</p>
                    <a href="complete_ticket.php?ticket_id=<?php echo $ticketId; ?>" class="btn btn-outline-success px-4">
                        <i class="bi bi-check-circle me-2"></i>Confirm Completion
                    </a>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('surveyModal'));
            modal.show();
        });
    </script>
    <?php endif; ?>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">
        <div style="width: 280px; flex-shrink: 0;"></div>
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 bg-light main-content" style="min-height: 100vh; overflow-y: auto;">

            <div class="container-fluid py-5 px-5">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="fw-bold text-dark mb-0">Ticket #<?php echo htmlspecialchars($ticketId); ?></h2>
                        <div class="text-muted small mt-1">Created on <?php echo date("F d, Y \• h:i A", strtotime($ticket['createdAt'])); ?></div>
                    </div>
                    <a href="ticket_history.php" class="btn btn-outline-secondary px-4 bg-white"><i class="bi bi-arrow-left me-2"></i>Back to Dashboard</a>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">

                        <div class="card border-0 shadow-sm rounded-4 mb-4" style="border-top: 4px solid #0dcaf0 !important;">
                            <div class="card-body p-4 p-md-5">
                                <h4 class="fw-bold text-dark mb-4"><?php echo htmlspecialchars($ticket['subject']); ?></h4>
                                <div class="p-4 bg-light rounded-3 border text-dark" style="font-size: 1rem; line-height: 1.6;">
                                    <?php echo nl2br(htmlspecialchars($ticket['description'])); ?>
                                </div>
                            </div>
                        </div>

                        <?php if (!empty($ticket['remarks'])): ?>
                            <div class="card border-0 shadow-sm rounded-4" style="border-top: 4px solid #198754 !important;">
                                <div class="card-body p-4 p-md-5">
                                    <h6 class="fw-bold mb-4 text-success"><i class="bi bi-chat-dots-fill me-2"></i>Officer's Response</h6>

                                    <div class="p-4 border rounded-3 bg-white mb-3 text-dark" style="min-height: 100px;">
                                        <?php echo nl2br(htmlspecialchars($ticket['remarks'])); ?>
                                    </div>

                                    <?php if ($ticket['resolvedAt']): ?>
                                        <div class="text-end mt-4">
                                            <div class="small text-muted fst-italic">Remarks posted on:</div>
                                            <div class="fw-bold text-dark"><?php echo date("F d, Y \a\\t h:i A", strtotime($ticket['resolvedAt'])); ?></div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                        <?php endif; ?>

                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4 mb-4">
                            <h6 class="fw-bold mb-4 text-dark border-bottom pb-3">Ticket Information</h6>

                            <div class="mb-4">
                                <label class="small text-muted fw-bold text-uppercase mb-2">Status</label>
                                <div>
                                    <?php
                                    $statusBadge = 'bg-secondary text-white';
                                    if ($ticket['status'] == 'Pending') $statusBadge = 'bg-warning text-dark';
                                    if ($ticket['status'] == 'Processing') $statusBadge = 'bg-primary text-white';
                                    if ($ticket['status'] == 'Resolved') $statusBadge = 'bg-success bg-opacity-25 text-success border border-success border-opacity-50';
                                    if ($ticket['status'] == 'Completed') $statusBadge = 'bg-success text-white';
                                    ?>
                                    <span class="badge <?php echo $statusBadge; ?> fs-6 px-4 py-2 rounded-pill shadow-sm">
                                        <?php echo htmlspecialchars($ticket['status']); ?>
                                    </span>
                                </div>
                            </div>

                            <?php if (!empty($ticket['techFirstName'])): ?>
                                <div class="mb-4">
                                    <label class="small text-muted fw-bold text-uppercase mb-1">Resolved By</label>
                                    <div class="fw-bold text-dark"><?php echo htmlspecialchars($ticket['techFirstName'] . ' ' . $ticket['techLastName']); ?></div>
                                    <?php if ($ticket['resolvedAt']): ?>
                                        <div class="small text-muted">At <?php echo date("M d, Y h:i A", strtotime($ticket['resolvedAt'])); ?></div>
                                    <?php endif; ?>
                                </div>
                            <?php endif; ?>

                            <?php if ($ticket['resolvedAt']): ?>
                                <div class="mb-4">
                                    <label class="small text-muted fw-bold text-uppercase mb-1">Days Resolved</label>
                                    <div class="text-dark fw-bold">
                                        <?php
                                        $created = new DateTime($ticket['createdAt']);
                                        $resolved = new DateTime($ticket['resolvedAt']);
                                        $diff = $created->diff($resolved);
                                        echo $diff->days . " Day" . ($diff->days != 1 ? 's' : '');
                                        ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <?php if ($ticket['completedAt']): ?>
                                <div class="mb-4">
                                    <label class="small text-muted fw-bold text-uppercase mb-1">Confirmed Completed</label>
                                    <div class="text-success fw-medium small">
                                        <i class="bi bi-check-circle-fill me-1"></i> <?php echo date("M d, Y \a\\t h:i A", strtotime($ticket['completedAt'])); ?>
                                    </div>
                                </div>
                            <?php endif; ?>

                            <div class="mb-4">
                                <label class="small text-muted fw-bold text-uppercase mb-2">Category</label>
                                <div class="text-dark fs-6"><?php echo htmlspecialchars($ticket['categoryName'] ?? 'Uncategorized'); ?></div>
                            </div>

                            <div class="mb-2">
                                <label class="small text-muted fw-bold text-uppercase mb-2">Requestor</label>
                                <div class="text-dark"><?php echo htmlspecialchars($ticket['firstName'] . ' ' . $ticket['lastName']); ?></div>
                                <div class="small text-muted"><?php echo htmlspecialchars($ticket['departmentName'] ?? 'No Department'); ?></div>
                            </div>
                        </div>

                         <?php if ($ticket['status'] === 'Resolved' && $role === 'User'): ?>
                            <div class="card border-0 shadow-sm rounded-4 p-4 text-center" style="background-color: #f8f9fa; border: 2px dashed #198754 !important;">
                                <div class="mb-3 text-success">
                                    <i class="bi bi-check2-circle" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-2">Is it working now?</h5>
                                <p class="text-muted small mb-4">Please confirm that your issue has been fully resolved by the ICT team.</p>

                                <form method="POST">
                                    <button type="submit" name="complete_ticket" class="btn btn-success fw-bold w-100 py-3 shadow-sm rounded-3">
                                        Mark as Completed
                                    </button>
                                </form>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
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
