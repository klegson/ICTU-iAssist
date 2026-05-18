<?php
session_start();
require_once 'db.php';
require_once 'credential_helper.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Officer', 'Technician'])) {
    header("Location: index.php");
    exit;
}

$ticketId = (string)($_GET['id'] ?? '');
if ($ticketId === '') {
    header("Location: all_tickets.php?error=invalid_id");
    exit;
}
$message = '';

$sql = "SELECT t.*, u.firstName, u.lastName, u.middleName, d.departmentName, d.departmentHead, p.positionTitle, c.categoryType,
               e.firstName AS h_fn, e.middleName AS h_mn, e.lastName AS h_ln, e.extension AS h_ext, e.positionTitle AS h_pos
        FROM ticket t
        JOIN users u ON t.userId = u.userId
        LEFT JOIN department d ON u.departmentId = d.departmentId
        LEFT JOIN employees e ON d.departmentHead = e.employeeID
        LEFT JOIN position p ON u.positionID = p.positionID
        LEFT JOIN category c ON t.categoryId = c.categoryId
        WHERE t.ticketId = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$ticketId]);
$ticket = $stmt->fetch();

if (!$ticket) {
    header("Location: all_tickets.php?error=not_found");
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

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['save_all_credentials']) && $canManageCreds) {
    $systems = $_POST['cred_system'] ?? [];
    $usernames = $_POST['cred_username'] ?? [];
    $passwords = $_POST['cred_password'] ?? [];
    foreach ($systems as $sys) {
        $u = trim($usernames[$sys] ?? '');
        $p = trim($passwords[$sys] ?? '');
        if ($u !== '' && $p !== '') {
            $stmt = $pdo->prepare("INSERT INTO ticket_credentials (ticketId, system_name, username, password_encrypted) VALUES (?, ?, ?, ?)");
            $stmt->execute([$ticketId, $sys, $u, encryptPassword($p)]);
        }
    }
    header("Location: manage_ticket.php?id=" . $ticketId);
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_priority'])) {
    $newPriority = $_POST['priority'];
    $stmt = $pdo->prepare("UPDATE ticket SET priority = ? WHERE ticketId = ?");
    if ($stmt->execute([$newPriority, $ticketId])) {
        header("Location: manage_ticket.php?id=" . $ticketId);
        exit;
    }
}

$techStmt = $pdo->query("SELECT userId, firstName, middleName, lastName FROM users WHERE role IN ('Technician', 'Officer') AND isApproved = 1 ORDER BY firstName ASC");
$technicians = $techStmt->fetchAll();

$credStmt = $pdo->prepare("SELECT * FROM ticket_credentials WHERE ticketId = ? ORDER BY system_name");
$credStmt->execute([$ticketId]);
$credentials = $credStmt->fetchAll();

$existingBySystem = [];
foreach ($credentials as $c) {
    $existingBySystem[$c['system_name']] = $c;
}

$systemLabels = [
    'Google Account' => 'Gmail Address',
    'MS 365 Account' => 'Outlook/MS User',
    'HAPPISA Portal' => 'Username',
    'DTS Account' => 'Username',
    'E-PERMIT' => 'Username',
    'WIFI Portal Access' => 'Username',
];

$systemIcons = [
    'Google Account' => 'bi-google',
    'MS 365 Account' => 'bi-microsoft',
    'HAPPISA Portal' => 'bi-building',
    'DTS Account' => 'bi-file-earmark-text',
    'E-PERMIT' => 'bi-file-check',
    'WIFI Portal Access' => 'bi-wifi',
];

$systemColors = [
    'Google Account' => '#ea4335',
    'MS 365 Account' => '#0078d4',
    'HAPPISA Portal' => '#6f42c1',
    'DTS Account' => '#198754',
    'E-PERMIT' => '#fd7e14',
    'WIFI Portal Access' => '#0dcaf0',
];

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
            <?php include 'header.php'; ?>
            <div class="container-fluid py-5 px-5">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark mb-0">Manage Ticket #<?php echo htmlspecialchars($ticketId); ?></h2>
                    <button onclick="history.back()" class="btn btn-outline-secondary px-4 bg-white">Back</button>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">

                        <div class="card border-0 shadow-sm rounded-4 mb-4" style="border-top: 4px solid #0dcaf0 !important;">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-dark mb-3">Requestor Details</h6>
                                <div class="row">
                                    <div class="col-md-4">
                                        <label class="small text-muted fw-bold">NAME</label>
                                        <div class="text-dark"><?php echo htmlspecialchars(formatName($ticket['firstName'], $ticket['middleName'], $ticket['lastName'])); ?></div>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted fw-bold">DEPARTMENT</label>
                                        <div class="text-dark"><?php echo htmlspecialchars($ticket['departmentName'] ?? 'No Department'); ?></div>
                                        <?php
                                        $headName = trim(($ticket['h_fn'] ?? '') . ' ' . ($ticket['h_mn'] ?? '') . ' ' . ($ticket['h_ln'] ?? '') . ' ' . ($ticket['h_ext'] ?? ''));
                                        $headName = trim(preg_replace('/\s+/', ' ', $headName));
                                        if ($headName): ?>
                                            <div class="small text-muted mt-1">Dept Head: <?php echo htmlspecialchars($headName . ' — ' . ($ticket['h_pos'] ?? 'N/A')); ?></div>
                                        <?php endif; ?>
                                    </div>
                                    <div class="col-md-4">
                                        <label class="small text-muted fw-bold">POSITION</label>
                                        <div class="text-dark"><?php echo htmlspecialchars($ticket['positionTitle'] ?? 'N/A'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4 mb-4" style="border-top: 4px solid #198754 !important;">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-dark mb-3">Subject / Problem Description</h6>
                                <div class="text-dark fw-bold small mb-2"><?php echo htmlspecialchars($ticket['subject']); ?></div>
                                <div class="p-3 bg-light rounded-3 border text-dark small" style="font-size:0.85rem;line-height:1.5;max-height:200px;overflow-y:auto;">
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
                        </div>

                        <?php if ($canManageCreds): ?>
                            <form method="POST">
                                <div class="mt-4">
                                    <?php foreach ($allowedSystems as $sys):
                                        $hasCred = isset($existingBySystem[$sys]);
                                        $color = $systemColors[$sys] ?? '#0d6efd';
                                        $icon = $systemIcons[$sys] ?? 'bi-key-fill';
                                        $label = $systemLabels[$sys] ?? 'Username';
                                    ?>
                                        <div class="card border-0 shadow-sm rounded-4 mb-3" style="border-top: 3px solid <?= $color ?>;">
                                            <div class="card-body p-4">
                                                <?php if ($hasCred):
                                                    $cred = $existingBySystem[$sys]; ?>
                                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                                        <h6 class="fw-bold text-dark mb-0"><i class="bi <?= $icon ?> me-2" style="color:<?= $color ?>"></i><?= $sys ?> Credentials</h6>
                                                        <span class="badge bg-success rounded-pill"><i class="bi bi-check-lg me-1"></i>Saved</span>
                                                    </div>
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="small text-muted fw-bold"><?= $label ?></label>
                                                            <div class="text-dark fw-bold"><?= htmlspecialchars($cred['username']) ?></div>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="small text-muted fw-bold">Password</label>
                                                            <div class="input-group input-group-sm">
                                                                <input type="password" class="form-control border-end-0" value="<?= htmlspecialchars(decryptPassword($cred['password_encrypted'])) ?>" readonly id="pw-<?= $cred['credentialId'] ?>">
                                                                <button class="btn btn-white border" type="button" onclick="togglePassword('pw-<?= $cred['credentialId'] ?>', this)"><i class="bi bi-eye"></i></button>
                                                            </div>
                                                        </div>
                                                    </div>
                                                <?php else: ?>
                                                    <h6 class="fw-bold text-dark mb-3"><i class="bi <?= $icon ?> me-2" style="color:<?= $color ?>"></i><?= $sys ?> Credentials</h6>
                                                    <input type="hidden" name="cred_system[]" value="<?= $sys ?>">
                                                    <div class="row g-3">
                                                        <div class="col-md-6">
                                                            <label class="small text-muted fw-bold mb-1"><?= $label ?></label>
                                                            <input type="text" name="cred_username[<?= $sys ?>]" class="form-control form-control-sm" required>
                                                        </div>
                                                        <div class="col-md-6">
                                                            <label class="small text-muted fw-bold mb-1">Password</label>
                                                            <input type="password" name="cred_password[<?= $sys ?>]" class="form-control form-control-sm" required>
                                                        </div>
                                                    </div>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    <?php endforeach; ?>
                                    <div class="text-end mt-3">
                                        <button type="submit" name="save_all_credentials" class="btn btn-success px-5 py-2 shadow-sm rounded-3">
                                            <i class="bi bi-check-lg me-2"></i>Save All Credentials
                                        </button>
                                    </div>
                                    <small class="text-muted d-block mt-2"><i class="bi bi-info-circle me-1"></i>Credentials auto-expire 10 days after creation. Passwords are encrypted at rest.</small>
                                </div>
                            </form>
                        <?php endif; ?>

                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4" style="border-top: 4px solid #ffc107 !important;">
                            <h6 class="fw-bold text-dark border-bottom pb-3 mb-4">Action Panel</h6>

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
                                <form method="POST" class="d-flex gap-2">
                                    <select name="priority" class="form-select form-select-sm bg-light" style="flex:1;">
                                        <option value="Low" <?= $ticket['priority'] === 'Low' ? 'selected' : '' ?>>Low</option>
                                        <option value="Medium" <?= $ticket['priority'] === 'Medium' ? 'selected' : '' ?>>Medium</option>
                                        <option value="High" <?= $ticket['priority'] === 'High' ? 'selected' : '' ?>>High</option>
                                    </select>
                                    <button type="submit" name="update_priority" class="btn btn-sm btn-outline-success px-3">
                                        <i class="bi bi-check-lg"></i>
                                    </button>
                                </form>
                            </div>

                            <?php if ($ticket['status'] === 'Pending'): ?>

                                <form method="POST" class="mb-4 pb-4 border-bottom">
                                    <div class="mb-3">
                                        <label class="small text-muted fw-bold mb-2">ASSIGN TO IT PERSONNEL</label>
                                        <select name="assigned_to" class="form-select bg-light" required>
                                            <option value="" disabled selected>-- Select Technician --</option>
                                            <?php foreach ($technicians as $tech): ?>
                                                <option value="<?php echo $tech['userId']; ?>" <?php echo ($tech['userId'] == $_SESSION['user_id']) ? 'selected' : ''; ?>>
                                                    <?php echo htmlspecialchars(formatName($tech['firstName'], $tech['middleName'], $tech['lastName'])); ?>
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