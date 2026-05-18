<?php
session_start();
require_once 'db.php';
require_once 'credential_helper.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: index.php");
    exit;
}

$ticketId = $_GET['id'] ?? null;
$userId = $_SESSION['user_id'];
$role = $_SESSION['role'];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['confirm_completion'])) {
    $update = $pdo->prepare("UPDATE ticket SET status = 'Completed', completedAt = NOW() WHERE ticketId = ? AND userId = ?");
    $update->execute([$ticketId, $userId]);
    header("Location: view_ticket.php?id=" . $ticketId . "&completed=1");
    exit;
}

$sql = "SELECT t.*, u.firstName, u.lastName, u.middleName, u.email, d.departmentName, d.departmentHead, c.categoryName, c.categoryType,
        tech.firstName AS techFirstName, tech.lastName AS techLastName,
        e.firstName AS h_fn, e.middleName AS h_mn, e.lastName AS h_ln, e.extension AS h_ext, e.positionTitle AS h_pos
        FROM ticket t 
        JOIN users u ON t.userId = u.userId 
        LEFT JOIN department d ON u.departmentId = d.departmentId
        LEFT JOIN employees e ON d.departmentHead = e.employeeID
        LEFT JOIN category c ON t.categoryId = c.categoryId
        LEFT JOIN users tech ON t.resolvedBy = tech.userId
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

    <div class="modal fade" id="surveyModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered modal-xl">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-star-fill text-warning me-2"></i>Rate Our Service</h5>
                </div>
                <div class="modal-body py-4">
                    <iframe src="https://forms.office.com/Pages/ResponsePage.aspx?id=gKvjQCQgo0W_dnoHYaJNKZVrGLcKRchGg0_5vlA39MhURDc2OU5GTENEVEw2WlJPU1JYSDRXWVZBVi4u&embed=true"
                            id="survey-iframe"
                            width="100%" height="500" frameborder="0"
                            style="border-radius: 8px; border: 1px solid #dee2e6;">
                    </iframe>

                    <div id="survey-status" class="text-center py-3 text-muted">
                        <i class="bi bi-info-circle me-1"></i> Please complete the survey above, then click confirm below.
                    </div>

                    <form method="POST" action="view_ticket.php?id=<?php echo $ticketId; ?>" class="text-center">
                        <button type="submit" name="confirm_completion" id="confirm-completion-btn"
                                class="btn btn-outline-success px-5 py-2 rounded-3" disabled>
                            <i class="bi bi-check-circle me-2"></i>Confirm Completion
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            var modal = new bootstrap.Modal(document.getElementById('surveyModal'), { backdrop: 'static', keyboard: false });

            var btn = document.getElementById('confirm-completion-btn');
            var status = document.getElementById('survey-status');
            var isFinished = false;

            function enableCompletion() {
                if (isFinished) return;
                isFinished = true;
                btn.disabled = false;
                btn.className = 'btn btn-success px-5 py-2 rounded-3';
                status.innerHTML = '<i class="bi bi-check-circle-fill text-success me-1"></i> Ready to complete — click Confirm below.';
                status.className = 'text-center py-3 text-success';
            }

            window.addEventListener('message', function(e) {
                try {
                    var d = typeof e.data === 'string' ? JSON.parse(e.data) : e.data;
                    if (d.eventType === 'onSubmitForm' ||
                        (d.eventName && d.eventName.indexOf('Submit') !== -1) ||
                        d.type === 'submit') {
                        enableCompletion();
                    }
                } catch(err) {}
            });

            var iframe = document.getElementById('survey-iframe');
            iframe.onload = function() {
                setTimeout(function() {
                    if (btn.disabled && !isFinished) {
                        status.innerHTML = '<i class="bi bi-exclamation-circle text-warning me-1"></i> If you\'ve completed the survey, ' +
                            '<a href="#" id="fallback-enable" class="fw-bold">click here to continue</a>.';
                        document.getElementById('fallback-enable').onclick = function(e) {
                            e.preventDefault();
                            enableCompletion();
                        };
                    }
                }, 25000);
            };
        });
    </script>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 bg-light main-content" style="min-height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>
            <div class="container-fluid py-5 px-5">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="fw-bold text-dark mb-0">Ticket #<?php echo htmlspecialchars($ticketId); ?></h2>
                        <div class="text-muted small mt-1">Created on <?php echo date("F d, Y \• h:i A", strtotime($ticket['createdAt'])); ?></div>
                    </div>
                    <a href="<?= ($ticket['categoryType'] ?? '') === 'Account Services' ? 'request_account.php' : 'ticket_history.php' ?>" class="btn btn-outline-secondary px-4 bg-white"><i class="bi bi-arrow-left me-2"></i>Back to Dashboard</a>
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

                        <?php
                        $credStmt = $pdo->prepare("SELECT * FROM ticket_credentials WHERE ticketId = ? ORDER BY system_name");
                        $credStmt->execute([$ticketId]);
                        $credentials = $credStmt->fetchAll();

                        if (!empty($credentials)):
                            $allExpired = true;
                            foreach ($credentials as $cred) {
                                if (!credentialsExpired($cred['created_at'])) {
                                    $allExpired = false;
                                    break;
                                }
                            }
                        ?>
                            <div class="card border-0 shadow-sm rounded-4 mt-4" id="credentials" style="border-top: 4px solid #0d6efd !important;">
                                <div class="card-body p-4 p-md-5">
                                    <?php if ($allExpired): ?>
                                        <div class="text-center py-4">
                                            <i class="bi bi-clock-history text-muted" style="font-size: 2.5rem;"></i>
                                            <p class="text-muted mt-3 mb-0">These credentials have expired. Please contact the help desk for assistance.</p>
                                        </div>
                                    <?php else: ?>
                                        <h6 class="fw-bold mb-4 text-primary"><i class="bi bi-key-fill me-2"></i>Account Credentials</h6>
                                        <div class="alert alert-warning py-2 mb-3 small"><i class="bi bi-exclamation-triangle me-1"></i>Credentials auto-expire <strong>10 days</strong> after creation. Click the eye icon to reveal passwords.</div>
                                        <?php foreach ($credentials as $cred):
                                            if (credentialsExpired($cred['created_at'])) continue;
                                        ?>
                                            <div class="border rounded-3 p-3 mb-3 bg-light">
                                                <div class="row align-items-center g-2">
                                                    <div class="col-md-3 fw-bold small"><?= htmlspecialchars($cred['system_name']) ?></div>
                                                    <div class="col-md-4 small"><?= htmlspecialchars($cred['username']) ?></div>
                                                    <div class="col-md-3">
                                                        <div class="input-group input-group-sm">
                                                            <input type="password" class="form-control border-end-0" value="<?= htmlspecialchars(decryptPassword($cred['password_encrypted'])) ?>" readonly id="vpw-<?= $cred['credentialId'] ?>">
                                                            <button class="btn btn-white border" type="button" onclick="togglePassword('vpw-<?= $cred['credentialId'] ?>', this)"><i class="bi bi-eye"></i></button>
                                                        </div>
                                                    </div>
                                                    <div class="col-md-2 text-end">
                                                        <span class="badge rounded-pill bg-secondary bg-opacity-10 text-dark fw-normal px-2">
                                                            Exp: <?= date("M d", strtotime($cred['created_at'] . ' +10 days')) ?>
                                                        </span>
                                                    </div>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
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
                                <div class="text-dark"><?php echo htmlspecialchars(formatName($ticket['firstName'], $ticket['middleName'], $ticket['lastName'])); ?></div>
                                <div class="small text-muted"><?php echo htmlspecialchars($ticket['departmentName'] ?? 'No Department'); ?></div>
                                <?php
                                $headName = trim(($ticket['h_fn'] ?? '') . ' ' . ($ticket['h_mn'] ?? '') . ' ' . ($ticket['h_ln'] ?? '') . ' ' . ($ticket['h_ext'] ?? ''));
                                $headName = trim(preg_replace('/\s+/', ' ', $headName));
                                if ($headName): ?>
                                    <div class="small text-muted mt-1"><i class="bi bi-person-badge me-1"></i>Dept Head: <?php echo htmlspecialchars($headName . ' — ' . ($ticket['h_pos'] ?? 'N/A')); ?></div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <?php if ($ticket['status'] === 'Resolved' && $role === 'User'): ?>
                            <div class="card border-0 shadow-sm rounded-4 p-4 text-center" style="background-color: #f8f9fa; border: 2px dashed #198754 !important;">
                                <div class="mb-3 text-success">
                                    <i class="bi bi-check2-circle" style="font-size: 3rem;"></i>
                                </div>
                                <h5 class="fw-bold text-dark mb-2">Is it working now?</h5>
                                <p class="text-muted small mb-4">Please confirm that your issue has been fully resolved by the ICT team.</p>

                                <button type="button" data-bs-toggle="modal" data-bs-target="#surveyModal" class="btn btn-success fw-bold w-100 py-3 shadow-sm rounded-3">
                                    Mark as Completed
                                </button>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function togglePassword(inputId, btn) {
            var input = document.getElementById(inputId);
            var icon = btn.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.className = 'bi bi-eye-slash';
            } else {
                input.type = 'password';
                icon.className = 'bi bi-eye';
            }
        }
    </script>
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