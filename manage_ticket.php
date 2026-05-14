<?php
session_start();
require_once 'db.php';
require_once 'credential_helper.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Officer', 'Technician'])) {
    header("Location: index.php");
    exit;
}

$ticketId = $_GET['id'] ?? null;
$message = '';

$sql = "SELECT t.*, u.firstName, u.lastName, d.departmentName, c.categoryType
        FROM ticket t
        JOIN users u ON t.userId = u.userId
        LEFT JOIN department d ON u.departmentId = d.departmentId
        LEFT JOIN category c ON t.categoryId = c.categoryId
        WHERE t.ticketId = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$ticketId]);
$ticket = $stmt->fetch();

if (!$ticket) {
    echo "<div class='text-center mt-5'><h3>Ticket not found</h3></div>";
    exit;
}

$canManageCreds = ($ticket['categoryType'] ?? '') === 'Account Services' && in_array($ticket['status'], ['Processing', 'Resolved']);

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_ticket'])) {
    $assignedTo = $_POST['assigned_to'];

    $updateStmt = $pdo->prepare("UPDATE ticket SET status = 'Processing', assignedTo = ? WHERE ticketId = ?");
    if ($updateStmt->execute([$assignedTo, $ticketId])) {
        header("Location: manage_ticket.php?id=" . $ticketId);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_ticket'])) {
    $remarks = trim($_POST['remarks']);

    $updateStmt = $pdo->prepare("UPDATE ticket SET status = 'Resolved', remarks = ?, resolvedBy = ?, resolvedAt = NOW() WHERE ticketId = ?");
    if ($updateStmt->execute([$remarks, $_SESSION['user_id'], $ticketId])) {
        if ($_SESSION['role'] === 'Officer') {
            header("Location: db_officer.php");
        } else {
            header("Location: db_technician.php");
        }
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_credential']) && $canManageCreds) {
    $username = trim($_POST['cred_username']);
    $password = encryptPassword(trim($_POST['cred_password']));
    $stmt = $pdo->prepare("INSERT INTO ticket_credentials (ticketId, system_name, username, password_encrypted) VALUES (?, ?, ?, ?)");
    foreach (($_POST['cred_system'] ?? []) as $sys) {
        $stmt->execute([$ticketId, trim($sys), $username, $password]);
    }
    header("Location: manage_ticket.php?id=" . $ticketId);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_credential']) && $canManageCreds) {
    $credId = (int)$_POST['credential_id'];
    $username = trim($_POST['cred_username']);
    $password = encryptPassword(trim($_POST['cred_password']));
    $stmt = $pdo->prepare("UPDATE ticket_credentials SET username = ?, password_encrypted = ?, updated_at = NOW() WHERE credentialId = ? AND ticketId = ?");
    $stmt->execute([$username, $password, $credId, $ticketId]);
    header("Location: manage_ticket.php?id=" . $ticketId);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_credential']) && $canManageCreds) {
    $credId = (int)$_POST['credential_id'];
    $stmt = $pdo->prepare("DELETE FROM ticket_credentials WHERE credentialId = ? AND ticketId = ?");
    $stmt->execute([$credId, $ticketId]);
    header("Location: manage_ticket.php?id=" . $ticketId);
    exit;
}

$techStmt = $pdo->query("SELECT userId, firstName, lastName FROM users WHERE role IN ('Technician', 'Officer') AND isApproved = 1 ORDER BY firstName ASC");
$technicians = $techStmt->fetchAll();

$credStmt = $pdo->prepare("SELECT * FROM ticket_credentials WHERE ticketId = ? ORDER BY system_name");
$credStmt->execute([$ticketId]);
$credentials = $credStmt->fetchAll();

$editCredId = isset($_GET['edit_cred']) ? (int)$_GET['edit_cred'] : 0;
$editCred = null;
if ($editCredId) {
    foreach ($credentials as $c) {
        if ($c['credentialId'] === $editCredId) {
            $editCred = $c;
            break;
        }
    }
}

$systemNameMap = [
    'Google Account' => 'Google Account',
    'MS 365 Account' => 'MS 365 Account',
    'HAPPISA' => 'HAPPISA Portal',
    'HAPPISA Portal' => 'HAPPISA Portal',
    'DTS' => 'DTS Account',
    'DTS Account' => 'DTS Account',
    'E-Permit' => 'E-PERMIT',
    'E-PERMIT' => 'E-PERMIT',
    'WIFI Portal' => 'WIFI Portal Access',
    'WIFI Portal Access' => 'WIFI Portal Access',
];

$allowedSystems = [];
if (preg_match('/Systems:\s*(.+)/i', $ticket['description'], $m)) {
    $parsed = array_map('trim', explode(',', $m[1]));
    foreach ($parsed as $name) {
        if (isset($systemNameMap[$name])) {
            $allowedSystems[] = $systemNameMap[$name];
        }
    }
}
$allowedSystems = array_unique($allowedSystems);
if (empty($allowedSystems)) {
    $allowedSystems = ['Google Account', 'MS 365 Account', 'HAPPISA Portal', 'DTS Account', 'E-PERMIT', 'WIFI Portal Access'];
}

$pageTitle = 'Manage Ticket #' . htmlspecialchars($ticketId);
include 'head.php';
?>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 bg-light main-content" style="min-height: 100vh; overflow-y: auto;">
            <div class="container-fluid py-5 px-5">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark mb-0">Manage Ticket #<?php echo htmlspecialchars($ticketId); ?></h2>
                    <button onclick="history.back()" class="btn btn-outline-secondary px-4 bg-white">Back</button>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">

                        <?php if ($canManageCreds): ?>
                        <div class="card border-0 shadow-sm rounded-4 mt-4" style="border-top: 4px solid #0d6efd !important;">
                            <div class="card-body p-4 p-md-5">
                                <h6 class="fw-bold text-dark mb-4"><i class="bi bi-key-fill me-2 text-primary"></i>Account Credentials</h6>

                                <?php if (empty($credentials)): ?>
                                <form method="POST">
                                    <input type="hidden" name="credential_id" value="<?= $editCred ? $editCred['credentialId'] : '' ?>">
                                    <div class="d-flex gap-2 align-items-end">
                                        <div class="flex-grow-1" style="min-width:0;">
                                            <label class="small text-muted fw-bold mb-1">SYSTEMS</label>
                                            <select name="cred_system[]" class="form-select form-select-sm" multiple required size="<?= min(count($allowedSystems), 4) ?>">
                                                <?php
                                                $selectedSystem = $editCred ? $editCred['system_name'] : '';
                                                foreach ($allowedSystems as $sys):
                                                ?>
                                                <option value="<?= $sys ?>" <?= $sys === $selectedSystem ? 'selected' : '' ?>><?= $sys ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                        </div>
                                        <div class="flex-grow-1" style="min-width:0;">
                                            <label class="small text-muted fw-bold mb-1">USERNAME</label>
                                            <input type="text" name="cred_username" class="form-control form-control-sm" value="<?= $editCred ? htmlspecialchars($editCred['username']) : '' ?>" required>
                                        </div>
                                        <div class="flex-grow-1" style="min-width:0;">
                                            <label class="small text-muted fw-bold mb-1">PASSWORD</label>
                                            <input type="password" name="cred_password" class="form-control form-control-sm" id="newCredPass" value="<?= $editCred ? htmlspecialchars(decryptPassword($editCred['password_encrypted'])) : '' ?>" required>
                                        </div>
                                        <div class="flex-shrink-0">
                                            <?php if ($editCred): ?>
                                            <a href="?id=<?= $ticketId ?>" class="btn btn-sm btn-light border me-1">Cancel</a>
                                            <button type="submit" name="update_credential" class="btn btn-sm btn-primary" title="Update"><i class="bi bi-check-lg"></i></button>
                                            <?php else: ?>
                                            <button type="submit" name="save_credential" class="btn btn-sm btn-success rounded-circle d-flex align-items-center justify-content-center" style="width:31px;height:31px;" title="Add Credential">
                                                <i class="bi bi-plus-lg"></i>
                                            </button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </form>
                                <?php endif; ?>

                                <?php if (!empty($credentials)): ?>
                                <div class="table-responsive mt-3">
                                    <table class="table table-sm table-striped align-middle mb-0">
                                        <thead>
                                            <tr>
                                                <th class="text-muted small fw-bold">SYSTEM</th>
                                                <th class="text-muted small fw-bold">USERNAME</th>
                                                <th class="text-muted small fw-bold">PASSWORD</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <?php foreach ($credentials as $cred): ?>
                                            <tr>
                                                <td class="fw-bold small"><?= htmlspecialchars($cred['system_name']) ?></td>
                                                <td class="small"><?= htmlspecialchars($cred['username']) ?></td>
                                                <td>
                                                    <div class="input-group input-group-sm">
                                                        <input type="password" class="form-control border-end-0" value="<?= htmlspecialchars(decryptPassword($cred['password_encrypted'])) ?>" readonly id="mpw-<?= $cred['credentialId'] ?>">
                                                        <button class="btn btn-white border" type="button" onclick="togglePassword('mpw-<?= $cred['credentialId'] ?>', this)"><i class="bi bi-eye"></i></button>
                                                    </div>
                                                </td>
                                            </tr>
                                            <?php endforeach; ?>
                                        </tbody>
                                    </table>
                                </div>
                                <?php endif; ?>

                                <small class="text-muted d-block mt-3"><i class="bi bi-info-circle me-1"></i>Credentials auto-expire 10 days after creation. Passwords are encrypted at rest.</small>
                            </div>
                        </div>
                        <?php endif; ?>

                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4" style="border-top: 4px solid #ffc107 !important;">
                            <h6 class="fw-bold text-dark border-bottom pb-3 mb-4">Action Panel</h6>

                            <div class="mb-4 pb-4 border-bottom">
                                <div class="d-flex justify-content-between mb-3">
                                    <div>
                                        <label class="small text-muted fw-bold">NAME</label>
                                        <div class="text-dark fw-bold small"><?php echo htmlspecialchars($ticket['firstName'] . ' ' . $ticket['lastName']); ?></div>
                                    </div>
                                    <div class="text-end">
                                        <label class="small text-muted fw-bold">DEPARTMENT</label>
                                        <div class="text-dark small"><?php echo htmlspecialchars($ticket['departmentName'] ?? 'No Department'); ?></div>
                                    </div>
                                </div>
                                <label class="small text-muted fw-bold mb-1">SUBJECT</label>
                                <div class="text-dark fw-bold small mb-1"><?php echo htmlspecialchars($ticket['subject']); ?></div>
                                <div class="p-3 bg-light rounded-3 border text-dark small" style="font-size: 0.85rem; line-height: 1.5; max-height: 180px; overflow-y: auto;">
                                    <?php echo nl2br(htmlspecialchars($ticket['description'])); ?>
                                </div>
                                <?php if ($ticket['priority'] === 'Express'): ?>
                                <div class="mt-3 p-2 bg-warning bg-opacity-10 border border-warning rounded-3 small">
                                    <div class="d-flex align-items-center text-warning fw-bold mb-1">
                                        <i class="bi bi-lightning-fill me-1"></i> Express Lane Ticket
                                    </div>
                                    <small class="text-muted">This ticket bypassed the officer queue and was directly available to technicians.</small>
                                </div>
                                <?php endif; ?>
                            </div>

                            <div class="mb-4">
                                <label class="small text-muted fw-bold mb-2">CURRENT STATUS</label>
                                <div>
                                    <?php
                                    $badgeClass = match ($ticket['status']) {
                                        'Pending' => 'bg-warning text-dark',
                                        'Processing' => 'bg-primary',
                                        'Resolved' => 'bg-success',
                                        'Completed' => 'bg-success',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?> fs-6 px-4 py-2 rounded-pill shadow-sm">
                                        <?php echo htmlspecialchars($ticket['status']); ?>
                                    </span>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="small text-muted fw-bold mb-2">PRIORITY</label>
                                <div>
                                    <?php
                                    $prioClass = match ($ticket['priority']) {
                                        'High' => 'bg-danger',
                                        'Express' => 'bg-express',
                                        'Medium' => 'bg-warning text-dark',
                                        'Low' => 'bg-success',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?php echo $prioClass; ?> fs-6 px-4 py-2 rounded-pill">
                                        <?php echo htmlspecialchars($ticket['priority']); ?>
                                    </span>
                                </div>
                            </div>

                            <?php if ($ticket['status'] === 'Pending'): ?>

                                <form method="POST" class="mb-4 pb-4 border-bottom">
                                    <div class="mb-3">
                                        <label class="small text-muted fw-bold mb-2">ASSIGN TO IT PERSONNEL</label>
                                        <select name="assigned_to" class="form-select bg-light" required>
                                            <option value="" disabled selected>-- Select Technician --</option>
                                            <?php foreach ($technicians as $tech): ?>
                                                <option value="<?php echo $tech['userId']; ?>" <?php echo ($tech['userId'] == $_SESSION['user_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" name="assign_ticket" class="btn btn-primary fw-bold w-100 py-2 shadow-sm rounded-3">
                                        <i class="bi bi-person-gear me-2"></i> ASSIGN TICKET
                                    </button>
                                </form>

                                <form method="POST">
                                    <div class="mb-3">
                                        <label class="small text-muted fw-bold mb-2">DIRECT RESOLVE (REMARKS)</label>
                                        <textarea name="remarks" class="form-control bg-light" rows="3" placeholder="Enter resolution details..." required></textarea>
                                    </div>
                                    <button type="submit" name="resolve_ticket" class="btn btn-success fw-bold w-100 py-2 shadow-sm rounded-3">
                                        <i class="bi bi-check-circle-fill me-2"></i> RESOLVE TICKET
                                    </button>
                                </form>

                            <?php elseif ($ticket['status'] === 'Processing'): ?>

                                <form method="POST">
                                    <div class="mb-4">
                                        <label class="small text-muted fw-bold mb-2">REMARKS / RESOLUTION NOTES</label>
                                        <textarea name="remarks" class="form-control bg-light" rows="5" placeholder="Enter details of how the issue was resolved..." required><?php echo htmlspecialchars($ticket['remarks'] ?? ''); ?></textarea>
                                    </div>
                                    <button type="submit" name="resolve_ticket" class="btn btn-success fw-bold w-100 py-3 shadow-sm rounded-3">
                                        <i class="bi bi-check-circle-fill me-2"></i> RESOLVE TICKET
                                    </button>
                                </form>

                            <?php else: ?>

                                <div class="mb-4">
                                    <label class="small text-muted fw-bold mb-2">RESOLUTION REMARKS</label>
                                    <div class="p-3 bg-light rounded-3 border text-dark small">
                                        <?php echo nl2br(htmlspecialchars($ticket['remarks'] ?? 'No remarks provided.')); ?>
                                    </div>
                                </div>
                                <div class="alert alert-success text-center mb-0 border-0 shadow-sm py-3">
                                    <i class="bi bi-check-circle-fill d-block fs-2 mb-2"></i>
                                    <strong><?php echo $ticket['status']; ?></strong><br>
                                    <small>This ticket is closed.</small>
                                </div>

                            <?php endif; ?>

                        </div>

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
