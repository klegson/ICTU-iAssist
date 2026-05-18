<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
require 'db.php';
if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Officer' && $_SESSION['role'] !== 'Technician')) {
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
    if ($diff < 604800) return floor($diff / 86400) . 'd ago';
    return date("M d, Y", $time);
}

$limit = 10;

$techPage = isset($_GET['tech_page']) ? max(1, (int)$_GET['tech_page']) : 1;
$techOffset = ($techPage - 1) * $limit;
$techFilter = $_GET['tech_filter'] ?? 'all';
$techSearch = trim($_GET['tech_search'] ?? '');

$acctPage = isset($_GET['acct_page']) ? max(1, (int)$_GET['acct_page']) : 1;
$acctOffset = ($acctPage - 1) * $limit;
$acctFilter = $_GET['acct_filter'] ?? 'all';
$acctSearch = trim($_GET['acct_search'] ?? '');

$activeTab = $_GET['tab'] ?? 'tech';

$pageTitle = 'All Tickets Overview - DepEd Helpdesk';
include 'head.php';
?>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 main-content" style="min-height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">

                <div class="row align-items-center mb-5">
                    <div class="col-md-8">
                        <h2 class="fw-bold text-dark mb-1">All Tickets Overview</h2>
                        <p class="text-muted">Comprehensive list of all active and past system requests.</p>
                    </div>
                </div>

                <div class="card border-0 shadow-sm rounded-4 p-4 mb-5">
                    <ul class="nav nav-tabs border-0 mb-4" id="ticketTabs" role="tablist">
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold <?= $activeTab === 'tech' ? 'active' : '' ?>" id="tech-tab" data-bs-toggle="tab" data-bs-target="#tech-tickets" type="button" role="tab">
                                <i class="bi bi-tools me-2 text-danger"></i>Technical Support
                            </button>
                        </li>
                        <li class="nav-item" role="presentation">
                            <button class="nav-link fw-semibold <?= $activeTab === 'acct' ? 'active' : '' ?>" id="acct-tab" data-bs-toggle="tab" data-bs-target="#acct-tickets" type="button" role="tab">
                                <i class="bi bi-person-badge me-2 text-primary"></i>Account Requests
                            </button>
                        </li>
                    </ul>

                    <div class="tab-content" id="ticketTabsContent">
                        <div class="tab-pane fade <?= $activeTab === 'tech' ? 'show active' : '' ?>" id="tech-tickets" role="tabpanel">
                            <div class="mb-4">
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="btn-group shadow-sm" role="group">
                                        <a href="?tab=tech&tech_page=1&tech_filter=all&tech_search=<?= urlencode($techSearch) ?>" class="btn btn-sm <?= $techFilter === 'all' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">All</a>
                                        <a href="?tab=tech&tech_page=1&tech_filter=Pending&tech_search=<?= urlencode($techSearch) ?>" class="btn btn-sm <?= $techFilter === 'Pending' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">Pending</a>
                                        <a href="?tab=tech&tech_page=1&tech_filter=Processing&tech_search=<?= urlencode($techSearch) ?>" class="btn btn-sm <?= $techFilter === 'Processing' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">Processing</a>
                                        <a href="?tab=tech&tech_page=1&tech_filter=Resolved&tech_search=<?= urlencode($techSearch) ?>" class="btn btn-sm <?= $techFilter === 'Resolved' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">Resolved</a>
                                        <a href="?tab=tech&tech_page=1&tech_filter=Completed&tech_search=<?= urlencode($techSearch) ?>" class="btn btn-sm <?= $techFilter === 'Completed' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">Completed</a>
                                    </div>
                                    <form method="GET" class="d-flex">
                                        <input type="hidden" name="tab" value="tech">
                                        <input type="hidden" name="tech_page" value="1">
                                        <input type="hidden" name="tech_filter" value="<?= htmlspecialchars($techFilter) ?>">
                                        <div class="input-group shadow-sm" style="width: 250px;">
                                            <input type="text" name="tech_search" class="form-control border-end-0" placeholder="Search tickets..." value="<?= htmlspecialchars($techSearch) ?>">
                                            <button class="btn btn-white border border-start-0" type="submit"><i class="bi bi-search text-muted"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead style="border-bottom: 2px solid #f0f2f5;">
                                        <tr>
                                            <th class="text-muted small fw-bold pb-3">ID</th>
                                            <th class="text-muted small fw-bold pb-3">DATE REQUESTED</th>
                                            <th class="text-muted small fw-bold pb-3">SUBJECT</th>
                                            <th class="text-muted small fw-bold pb-3">REQUESTED BY</th>
                                            <th class="text-muted small fw-bold pb-3">CATEGORY</th>
                                            <th class="text-muted small fw-bold pb-3">STATUS</th>
                                            <th class="text-end text-muted small fw-bold pb-3">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $techSearchParam = "%$techSearch%";
                                        $countSql = "SELECT COUNT(*) FROM ticket t
                                                JOIN users u ON t.userId = u.userId
                                                LEFT JOIN category c ON t.categoryId = c.categoryId
                                                WHERE (c.categoryType != 'Account Services' OR c.categoryType IS NULL)
                                                AND (? = 'all' OR t.status = ?)
                                                AND (? = '' OR t.subject LIKE ?)";
                                        $countStmt = $pdo->prepare($countSql);
                                        $countStmt->execute([$techFilter, $techFilter, $techSearchParam, $techSearchParam]);
                                        $techTotal = $countStmt->fetchColumn();
                                        $techTotalPages = max(1, ceil($techTotal / $limit));

                                        $dataSql = "SELECT t.*, u.firstName, u.middleName, u.lastName, c.categoryName
                                                FROM ticket t
                                                JOIN users u ON t.userId = u.userId
                                                LEFT JOIN category c ON t.categoryId = c.categoryId
                                                WHERE (c.categoryType != 'Account Services' OR c.categoryType IS NULL)
                                                AND (? = 'all' OR t.status = ?)
                                                AND (? = '' OR t.subject LIKE ?)
                                                ORDER BY t.createdAt DESC LIMIT ? OFFSET ?";
                                        $stmt = $pdo->prepare($dataSql);
                                        $stmt->execute([$techFilter, $techFilter, $techSearchParam, $techSearchParam, $limit, $techOffset]);

                                        if ($stmt->rowCount() > 0) {
                                            while ($row = $stmt->fetch()) {
                                                $exactDate = date("M d, Y", strtotime($row['createdAt']));
                                                $aging = formatTimeAgo($row['createdAt']);
                                                $agingColor = (strpos($aging, 'd') !== false) ? 'text-danger' : 'text-muted';

                                                $name = htmlspecialchars(formatName($row['firstName'], $row['middleName'], $row['lastName']));
                                                $cat = htmlspecialchars($row['categoryName'] ?? 'General');

                                                $badgeClass = match ($row['status']) {
                                                    'Pending' => 'bg-warning text-dark',
                                                    'Processing' => 'bg-primary',
                                                    'Resolved' => 'bg-info text-dark',
                                                    'Completed' => 'bg-success',
                                                    default => 'bg-secondary'
                                                };

                                                echo "<tr style='border-bottom: 1px solid #f8f9fa;'>";
                                                echo "<td class='py-3 fw-bold'>#" . $row['ticketId'] . "</td>";
                                                echo "<td class='py-3'>
                                                        <span class='d-block fs-6'>" . $exactDate . "</span>
                                                        <small class='fw-bold " . $agingColor . "'><i class='bi bi-clock-history me-1'></i>" . $aging . "</small>
                                                      </td>";
                                                echo "<td class='py-3'>" . htmlspecialchars(substr($row['subject'], 0, 30)) . "...</td>";
                                                echo "<td class='py-3'>" . $name . "</td>";
                                                echo "<td class='py-3'><small class='text-muted'>" . $cat . "</small></td>";
                                                echo "<td class='py-3'><span class='badge rounded-pill " . $badgeClass . "'>" . $row['status'] . "</span></td>";
                                                echo "<td class='py-3 text-end'>
                                                        <a href='manage_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-outline-dark px-3'>Manage</a>
                                                      </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7' class='text-center py-5 text-muted'>No tickets found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if ($techTotalPages > 1): ?>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <small class="text-muted">Page <?= $techPage ?> of <?= $techTotalPages ?> (<?= $techTotal ?> total)</small>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item <?= $techPage <= 1 ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?tab=tech&tech_page=<?= $techPage - 1 ?>&tech_filter=<?= urlencode($techFilter) ?>&tech_search=<?= urlencode($techSearch) ?>">Previous</a>
                                        </li>
                                        <?php for ($i = 1; $i <= $techTotalPages; $i++): ?>
                                            <li class="page-item <?= $i === $techPage ? 'active' : '' ?>">
                                                <a class="page-link" href="?tab=tech&tech_page=<?= $i ?>&tech_filter=<?= urlencode($techFilter) ?>&tech_search=<?= urlencode($techSearch) ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?= $techPage >= $techTotalPages ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?tab=tech&tech_page=<?= $techPage + 1 ?>&tech_filter=<?= urlencode($techFilter) ?>&tech_search=<?= urlencode($techSearch) ?>">Next</a>
                                        </li>
                                    </ul>
                                </nav>
                            </div>
                            <?php endif; ?>
                        </div>

                        <div class="tab-pane fade <?= $activeTab === 'acct' ? 'show active' : '' ?>" id="acct-tickets" role="tabpanel">
                            <div class="mb-4">
                                <div class="d-flex flex-wrap gap-3">
                                    <div class="btn-group shadow-sm" role="group">
                                        <a href="?tab=acct&acct_page=1&acct_filter=all&acct_search=<?= urlencode($acctSearch) ?>" class="btn btn-sm <?= $acctFilter === 'all' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">All</a>
                                        <a href="?tab=acct&acct_page=1&acct_filter=Pending&acct_search=<?= urlencode($acctSearch) ?>" class="btn btn-sm <?= $acctFilter === 'Pending' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">Pending</a>
                                        <a href="?tab=acct&acct_page=1&acct_filter=Processing&acct_search=<?= urlencode($acctSearch) ?>" class="btn btn-sm <?= $acctFilter === 'Processing' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">Processing</a>
                                        <a href="?tab=acct&acct_page=1&acct_filter=Resolved&acct_search=<?= urlencode($acctSearch) ?>" class="btn btn-sm <?= $acctFilter === 'Resolved' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">Resolved</a>
                                        <a href="?tab=acct&acct_page=1&acct_filter=Completed&acct_search=<?= urlencode($acctSearch) ?>" class="btn btn-sm <?= $acctFilter === 'Completed' ? 'btn-secondary active' : 'btn-outline-secondary' ?>">Completed</a>
                                    </div>
                                    <form method="GET" class="d-flex">
                                        <input type="hidden" name="tab" value="acct">
                                        <input type="hidden" name="acct_page" value="1">
                                        <input type="hidden" name="acct_filter" value="<?= htmlspecialchars($acctFilter) ?>">
                                        <div class="input-group shadow-sm" style="width: 250px;">
                                            <input type="text" name="acct_search" class="form-control border-end-0" placeholder="Search tickets..." value="<?= htmlspecialchars($acctSearch) ?>">
                                            <button class="btn btn-white border border-start-0" type="submit"><i class="bi bi-search text-muted"></i></button>
                                        </div>
                                    </form>
                                </div>
                            </div>

                            <div class="table-responsive">
                                <table class="table table-borderless align-middle mb-0">
                                    <thead style="border-bottom: 2px solid #f0f2f5;">
                                        <tr>
                                            <th class="text-muted small fw-bold pb-3">ID</th>
                                            <th class="text-muted small fw-bold pb-3">DATE REQUESTED</th>
                                            <th class="text-muted small fw-bold pb-3">SUBJECT</th>
                                            <th class="text-muted small fw-bold pb-3">REQUESTED BY</th>
                                            <th class="text-muted small fw-bold pb-3">CATEGORY</th>
                                            <th class="text-muted small fw-bold pb-3">STATUS</th>
                                            <th class="text-end text-muted small fw-bold pb-3">ACTION</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php
                                        $acctSearchParam = "%$acctSearch%";
                                        $countSql = "SELECT COUNT(*) FROM ticket t
                                                JOIN users u ON t.userId = u.userId
                                                LEFT JOIN category c ON t.categoryId = c.categoryId
                                                WHERE c.categoryType = 'Account Services'
                                                AND (? = 'all' OR t.status = ?)
                                                AND (? = '' OR t.subject LIKE ?)";
                                        $countStmt = $pdo->prepare($countSql);
                                        $countStmt->execute([$acctFilter, $acctFilter, $acctSearchParam, $acctSearchParam]);
                                        $acctTotal = $countStmt->fetchColumn();
                                        $acctTotalPages = max(1, ceil($acctTotal / $limit));

                                        $dataSql = "SELECT t.*, u.firstName, u.middleName, u.lastName, c.categoryName
                                                FROM ticket t
                                                JOIN users u ON t.userId = u.userId
                                                LEFT JOIN category c ON t.categoryId = c.categoryId
                                                WHERE c.categoryType = 'Account Services'
                                                AND (? = 'all' OR t.status = ?)
                                                AND (? = '' OR t.subject LIKE ?)
                                                ORDER BY t.createdAt DESC LIMIT ? OFFSET ?";
                                        $stmt = $pdo->prepare($dataSql);
                                        $stmt->execute([$acctFilter, $acctFilter, $acctSearchParam, $acctSearchParam, $limit, $acctOffset]);

                                        if ($stmt->rowCount() > 0) {
                                            while ($row = $stmt->fetch()) {
                                                $exactDate = date("M d, Y", strtotime($row['createdAt']));
                                                $aging = formatTimeAgo($row['createdAt']);
                                                $agingColor = (strpos($aging, 'd') !== false) ? 'text-danger' : 'text-muted';

                                                $name = htmlspecialchars(formatName($row['firstName'], $row['middleName'], $row['lastName']));
                                                $cat = htmlspecialchars($row['categoryName'] ?? 'General');

                                                $badgeClass = match ($row['status']) {
                                                    'Pending' => 'bg-warning text-dark',
                                                    'Processing' => 'bg-primary',
                                                    'Resolved' => 'bg-info text-dark',
                                                    'Completed' => 'bg-success',
                                                    default => 'bg-secondary'
                                                };

                                                echo "<tr style='border-bottom: 1px solid #f8f9fa;'>";
                                                echo "<td class='py-3 fw-bold'>#" . $row['ticketId'] . "</td>";
                                                echo "<td class='py-3'>
                                                        <span class='d-block fs-6'>" . $exactDate . "</span>
                                                        <small class='fw-bold " . $agingColor . "'><i class='bi bi-clock-history me-1'></i>" . $aging . "</small>
                                                      </td>";
                                                echo "<td class='py-3'>" . htmlspecialchars(substr($row['subject'], 0, 30)) . "...</td>";
                                                echo "<td class='py-3'>" . $name . "</td>";
                                                echo "<td class='py-3'><small class='text-muted'>" . $cat . "</small></td>";
                                                echo "<td class='py-3'><span class='badge rounded-pill " . $badgeClass . "'>" . $row['status'] . "</span></td>";
                                                echo "<td class='py-3 text-end'>
                                                        <a href='manage_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-outline-dark px-3'>Manage</a>
                                                      </td>";
                                                echo "</tr>";
                                            }
                                        } else {
                                            echo "<tr><td colspan='7' class='text-center py-5 text-muted'>No tickets found.</td></tr>";
                                        }
                                        ?>
                                    </tbody>
                                </table>
                            </div>

                            <?php if ($acctTotalPages > 1): ?>
                            <div class="d-flex justify-content-between align-items-center mt-4">
                                <small class="text-muted">Page <?= $acctPage ?> of <?= $acctTotalPages ?> (<?= $acctTotal ?> total)</small>
                                <nav>
                                    <ul class="pagination pagination-sm mb-0">
                                        <li class="page-item <?= $acctPage <= 1 ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?tab=acct&acct_page=<?= $acctPage - 1 ?>&acct_filter=<?= urlencode($acctFilter) ?>&acct_search=<?= urlencode($acctSearch) ?>">Previous</a>
                                        </li>
                                        <?php for ($i = 1; $i <= $acctTotalPages; $i++): ?>
                                            <li class="page-item <?= $i === $acctPage ? 'active' : '' ?>">
                                                <a class="page-link" href="?tab=acct&acct_page=<?= $i ?>&acct_filter=<?= urlencode($acctFilter) ?>&acct_search=<?= urlencode($acctSearch) ?>"><?= $i ?></a>
                                            </li>
                                        <?php endfor; ?>
                                        <li class="page-item <?= $acctPage >= $acctTotalPages ? 'disabled' : '' ?>">
                                            <a class="page-link" href="?tab=acct&acct_page=<?= $acctPage + 1 ?>&acct_filter=<?= urlencode($acctFilter) ?>&acct_search=<?= urlencode($acctSearch) ?>">Next</a>
                                        </li>
                                    </ul>
                                </nav>
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