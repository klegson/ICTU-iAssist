<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
session_start();

require_once 'db.php';
require_once 'credential_helper.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'User') {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$msg = "";

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

if (isset($_POST['submit_account'])) {
    $serviceType = $_POST['categoryId'];
    $reason = trim($_POST['reason']);
    $priority = "Medium";

    $systems = [];
    if (isset($_POST['sys_google'])) $systems[] = "Google Account";
    if (isset($_POST['sys_ms365'])) $systems[] = "MS 365 Account";
    if (isset($_POST['sys_happisa'])) $systems[] = "HAPPISA Portal";
    if (isset($_POST['sys_dts'])) $systems[] = "DTS Account";
    if (isset($_POST['sys_epermit'])) $systems[] = "E-PERMIT";
    if (isset($_POST['sys_wifi'])) $systems[] = "WIFI Portal Access";

    $systemList = implode(", ", $systems);

    if (empty($systemList)) {
        $msg = "Please select at least one system.";
    } else {
        $extraInfo = "";
        if (!empty($_POST['transfer_to'])) {
            $extraInfo = "\nTRANSFER TO: " . trim($_POST['transfer_to']);
        }

        $finalDescription = "REQUEST DETAILS:\n" .
            "Systems: " . $systemList . "\n\n" .
            "REASON/PURPOSE:\n" . $reason;

        $deptStmt = $pdo->prepare("SELECT departmentId FROM users WHERE userId = ?");
        $deptStmt->execute([$userId]);
        $userDeptId = $deptStmt->fetchColumn();

        if (!$userDeptId) {
            $userDeptId = 1;
        }

        $stmt = $pdo->prepare("INSERT INTO ticket (subject, categoryId, description, priority, status, departmentId, userId) VALUES (?, ?, ?, ?, 'Pending', ?, ?)");

        $subject = "Account Request (" . $systemList . ")";

        if ($stmt->execute([$subject, $serviceType, $finalDescription, $priority, $_SESSION['department_id'], $userId])) {
            header("Location: request_account.php?success=1");
            exit;
        } else {
            $msg = "Error submitting request.";
        }
    }
}

$showModal = !empty($msg);

$limit = 10;
$page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
$offset = ($page - 1) * $limit;
$filter = $_GET['filter'] ?? 'all';
$search = trim($_GET['search'] ?? '');

$pageTitle = 'Account Requests History | DepEd Helpdesk';
include 'head.php';
?>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 main-content" style="min-height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">Account Creation Requests</h2>
                        <p class="text-muted mb-0">View your account service requests and submit new ones.</p>
                    </div>
                    <button class="btn btn-deped-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#accountRequestModal">
                        <i class="bi bi-plus-lg me-2"></i>NEW REQUEST
                    </button>
                </div>

                <?php if (isset($_GET['success'])): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({ icon: 'success', title: 'Submitted!', text: 'Your account request has been submitted.', timer: 3000, showConfirmButton: false });
                    });
                </script>
                <?php elseif (!empty($msg)): ?>
                <script>
                    document.addEventListener('DOMContentLoaded', function() {
                        Swal.fire({ icon: 'error', title: 'Error', text: <?= json_encode($msg) ?> });
                    });
                </script>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-4 p-4 mb-5">
                    <div class="mb-4">
                        <div class="d-flex flex-wrap gap-3">
                            <div class="btn-group shadow-sm" role="group">
                                <a href="?page=1&filter=all&search=<?= urlencode($search) ?>" class="btn btn-sm <?= $filter === 'all' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">All</a>
                                <a href="?page=1&filter=Pending&search=<?= urlencode($search) ?>" class="btn btn-sm <?= $filter === 'Pending' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">Pending</a>
                                <a href="?page=1&filter=Processing&search=<?= urlencode($search) ?>" class="btn btn-sm <?= $filter === 'Processing' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">Processing</a>
                                <a href="?page=1&filter=Resolved&search=<?= urlencode($search) ?>" class="btn btn-sm <?= $filter === 'Resolved' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">Resolved</a>
                                <a href="?page=1&filter=Completed&search=<?= urlencode($search) ?>" class="btn btn-sm <?= $filter === 'Completed' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">Completed</a>
                            </div>
                            <form method="GET" class="d-flex">
                                <input type="hidden" name="page" value="1">
                                <input type="hidden" name="filter" value="<?= htmlspecialchars($filter) ?>">
                                <div class="input-group input-group-sm shadow-sm" style="width: 250px;">
                                    <input type="text" name="search" class="form-control border-end-0" placeholder="Search requests..." value="<?= htmlspecialchars($search) ?>">
                                    <button class="btn btn-white border border-start-0" type="submit"><i class="bi bi-search text-muted"></i></button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead style="border-bottom: 2px solid #f0f2f5;">
                                <tr>
                                    <th class="text-muted small fw-bold pb-3">REF #</th>
                                    <th class="text-muted small fw-bold pb-3">DATE REQUESTED</th>
                                    <th class="text-muted small fw-bold pb-3">SYSTEMS</th>
                                    <th class="text-muted small fw-bold pb-3">ACTION</th>
                                    <th class="text-muted small fw-bold pb-3">STATUS</th>
                                    <th class="text-center text-muted small fw-bold pb-3">CREDENTIALS</th>
                                    <th class="text-end text-muted small fw-bold pb-3">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $ticketsWithCreds = [];
                                $credAllStmt = $pdo->query("SELECT ticketId, created_at FROM ticket_credentials");
                                while ($c = $credAllStmt->fetch()) {
                                    $tid = $c['ticketId'];
                                    $ticketsWithCreds[$tid] = ($ticketsWithCreds[$tid] ?? false) || !credentialsExpired($c['created_at']);
                                }

                                $searchParam = "%$search%";
                                $countSql = "SELECT COUNT(*) FROM ticket t
                                        LEFT JOIN category c ON t.categoryId = c.categoryId
                                        WHERE t.userId = ? AND c.categoryType = 'Account Services'
                                        AND (? = 'all' OR t.status = ?)
                                        AND (? = '' OR t.subject LIKE ?)";
                                $countStmt = $pdo->prepare($countSql);
                                $countStmt->execute([$userId, $filter, $filter, $searchParam, $searchParam]);
                                $total = $countStmt->fetchColumn();
                                $totalPages = max(1, ceil($total / $limit));

                                $dataSql = "SELECT t.*, c.categoryName
                                        FROM ticket t
                                        LEFT JOIN category c ON t.categoryId = c.categoryId
                                        WHERE t.userId = ? AND c.categoryType = 'Account Services'
                                        AND (? = 'all' OR t.status = ?)
                                        AND (? = '' OR t.subject LIKE ?)
                                        ORDER BY t.createdAt DESC LIMIT ? OFFSET ?";
                                $stmt = $pdo->prepare($dataSql);
                                $stmt->execute([$userId, $filter, $filter, $searchParam, $searchParam, $limit, $offset]);

                                if ($stmt->rowCount() > 0) {
                                    while ($row = $stmt->fetch()) {
                                        $badgeClass = match ($row['status']) {
                                            'Pending' => 'bg-warning text-dark',
                                            'Processing' => 'bg-primary',
                                            'Resolved' => 'bg-success bg-opacity-75',
                                            'Completed' => 'bg-success',
                                            default => 'bg-secondary'
                                        };

                                        $exactDate = date("M d, Y", strtotime($row['createdAt']));
                                        $aging = formatTimeAgo($row['createdAt']);

                                        echo "<tr style='border-bottom: 1px solid #f8f9fa;'>";
                                        echo "<td class='py-3 fw-bold text-primary'>#" . $row['ticketId'] . "</td>";
                                        echo "<td class='py-3'><div class='text-dark fw-medium'>" . $exactDate . "</div><small class='text-muted'><i class='bi bi-clock-history me-1'></i>" . $aging . "</small></td>";
                                        echo "<td class='py-3'>" . htmlspecialchars($row['subject']) . "</td>";
                                        echo "<td class='py-3 fw-bold'>" . htmlspecialchars($row['categoryName']) . "</td>";
                                        echo "<td class='py-3'><span class='badge rounded-pill " . $badgeClass . "'>" . $row['status'] . "</span></td>";
                                        $hasActiveCreds = $ticketsWithCreds[$row['ticketId']] ?? false;
                                        echo "<td class='py-3 text-center'>";
                                        if (isset($ticketsWithCreds[$row['ticketId']])) {
                                            if ($hasActiveCreds) {
                                                echo "<a href='view_ticket.php?id=" . $row['ticketId'] . "#credentials' class='btn btn-sm btn-outline-primary'><i class='bi bi-key me-1'></i>View</a>";
                                            } else {
                                                echo "<span class='text-muted small'><i class='bi bi-clock-history me-1'></i>Expired</span>";
                                            }
                                        } else {
                                            echo "<span class='text-muted small'>--</span>";
                                        }
                                        echo "</td>";
                                        echo "<td class='py-3 text-end'>";
                                        echo "<a href='view_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-light border'>View</a>";
                                        echo "</td></tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='7' class='text-center py-5 text-muted small'>No account requests found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <?php if ($totalPages > 1): ?>
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <small class="text-muted">Page <?= $page ?> of <?= $totalPages ?> (<?= $total ?> total)</small>
                        <nav>
                            <ul class="pagination pagination-sm mb-0">
                                <li class="page-item <?= $page <= 1 ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page - 1 ?>&filter=<?= urlencode($filter) ?>&search=<?= urlencode($search) ?>">Previous</a>
                                </li>
                                <?php for ($i = 1; $i <= $totalPages; $i++): ?>
                                    <li class="page-item <?= $i === $page ? 'active' : '' ?>">
                                        <a class="page-link" href="?page=<?= $i ?>&filter=<?= urlencode($filter) ?>&search=<?= urlencode($search) ?>"><?= $i ?></a>
                                    </li>
                                <?php endfor; ?>
                                <li class="page-item <?= $page >= $totalPages ? 'disabled' : '' ?>">
                                    <a class="page-link" href="?page=<?= $page + 1 ?>&filter=<?= urlencode($filter) ?>&search=<?= urlencode($search) ?>">Next</a>
                                </li>
                            </ul>
                        </nav>
                    </div>
                    <?php endif; ?>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="accountRequestModal" tabindex="-1" aria-labelledby="accountModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title fw-bold" id="accountModalLabel">New Account Creation Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form method="POST" id="accountForm">
                    <div class="modal-body">
                        <div class="mb-4">
                            <label class="form-label small fw-bold">ACTION REQUIRED</label>
                            <select class="form-select" name="categoryId" id="actionSelect" onchange="toggleTransfer()" required>
                                <option value="" selected disabled>Select Action...</option>
                                <?php
                                $catStmt = $pdo->query("SELECT * FROM category WHERE categoryType = 'Account Services'");
                                while ($cat = $catStmt->fetch()) {
                                    echo "<option value='" . $cat['categoryId'] . "'>" . htmlspecialchars($cat['categoryName']) . "</option>";
                                }
                                ?>
                            </select>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">SELECT SYSTEMS</label>
                            <div class="row bg-light rounded p-3 m-0" style="border: 1px solid #e9ecef;">
                                <div class="col-md-4">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_google" id="c1">
                                        <label class="form-check-label small" for="c1">Google Account</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_ms365" id="c2">
                                        <label class="form-check-label small" for="c2">MS 365 Account</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_happisa" id="c3">
                                        <label class="form-check-label small" for="c3">HAPPISA Portal</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_dts" id="c4">
                                        <label class="form-check-label small" for="c4">DTS Account</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_epermit" id="c5">
                                        <label class="form-check-label small" for="c5">E-PERMIT</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_wifi" id="c6">
                                        <label class="form-check-label small" for="c6">WIFI Portal Access</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4" id="transferBox" style="display: none;">
                            <label class="form-label small fw-bold">TRANSFER TO</label>
                            <input type="text" class="form-control" name="transfer_to" placeholder="Enter the name or details of the transfer recipient...">
                        </div>

                        <div class="mb-4" id="reasonDiv">
                            <label class="form-label small fw-bold" id="reasonLabel">REASON FOR REQUEST</label>
                            <textarea class="form-control" name="reason" rows="4" placeholder="Briefly explain why this account is needed..." required></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="submit_account" class="btn btn-deped-primary px-4">
                            <i class="bi bi-shield-check me-2"></i>SUBMIT REQUEST
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function toggleTransfer() {
            var selectBox = document.getElementById("actionSelect");
            var hiddenBox = document.getElementById("transferBox");
            var reasonDiv = document.getElementById("reasonDiv");
            var reasonInput = reasonDiv.querySelector('textarea');
            var reasonLabel = document.getElementById("reasonLabel");
            var selectedText = selectBox.options[selectBox.selectedIndex].text;

            if (selectedText.includes("Transfer")) {
                hiddenBox.style.display = "block";
                hiddenBox.querySelector('input').required = true;
            } else {
                hiddenBox.style.display = "none";
                hiddenBox.querySelector('input').required = false;
                hiddenBox.querySelector('input').value = "";
            }
            if (selectedText.includes("Reset")) {
                reasonDiv.style.display = "none";
                reasonLabel.style.display = "none";
                reasonInput.required = false;
                reasonInput.value = "Password Reset Request";
            } else {
                reasonDiv.style.display = "block";
                reasonLabel.style.display = "block";
                reasonInput.required = true;
                if (reasonInput.value === "Password Reset Request") {
                    reasonInput.value = "";
                }
            }
        }
    </script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        <?php if ($showModal): ?>
        var modalEl = document.getElementById('accountRequestModal');
        if (modalEl) {
            var modal = new bootstrap.Modal(modalEl);
            modal.show();
        }
        <?php endif; ?>

        var modalEl = document.getElementById('accountRequestModal');
        if (modalEl) {
            modalEl.addEventListener('hidden.bs.modal', function() {
                var form = document.getElementById('accountForm');
                if (form) form.reset();
                toggleTransfer();
            });
        }

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