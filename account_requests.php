<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Technician') {
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

$pageTitle = 'Account Requests - DepEd Helpdesk';
include 'head.php';
?>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">
        <div style="width: 280px; flex-shrink: 0;"></div>
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 main-content" style="min-height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">

                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">Account Requests</h2>
                        <p class="text-muted">Manage account service requests from employees.</p>
                    </div>

                    <div class="d-flex gap-3">
                        <div class="btn-group shadow-sm" role="group">
                            <button type="button" class="btn btn-secondary active status-filter" data-status="all">All</button>
                            <button type="button" class="btn btn-outline-secondary status-filter" data-status="Pending">Pending</button>
                            <button type="button" class="btn btn-outline-secondary status-filter" data-status="Processing">Processing</button>
                            <button type="button" class="btn btn-outline-secondary status-filter" data-status="Resolved">Resolved</button>
                            <button type="button" class="btn btn-outline-secondary status-filter" data-status="Completed">Completed</button>
                        </div>

                        <div class="input-group shadow-sm" style="width: 300px;">
                            <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                            <input type="text" id="ticketSearch" class="form-control border-start-0" placeholder="Search account requests...">
                        </div>
                    </div>
                </div>

                <div class="custom-card p-4">
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
                                $sql = "SELECT t.*, u.firstName, u.lastName, c.categoryName 
                                        FROM ticket t 
                                        JOIN users u ON t.userId = u.userId 
                                        LEFT JOIN category c ON t.categoryId = c.categoryId
                                        WHERE c.categoryType = 'Account Services'
                                        ORDER BY t.createdAt DESC";

                                $stmt = $pdo->query($sql);

                                if ($stmt->rowCount() > 0) {
                                    while ($row = $stmt->fetch()) {
                                        $exactDate = date("M d, Y", strtotime($row['createdAt']));
                                        $aging = formatTimeAgo($row['createdAt']);
                                        $agingColor = (strpos($aging, 'd') !== false) ? 'text-danger' : 'text-muted';

                                        $name = htmlspecialchars($row['firstName'] . ' ' . $row['lastName']);
                                        $cat = htmlspecialchars($row['categoryName'] ?? 'General');

                                        $badgeClass = match ($row['status']) {
                                            'Pending' => 'bg-warning text-dark',
                                            'Processing' => 'bg-primary',
                                            'Resolved' => 'bg-info text-dark',
                                            'Completed' => 'bg-success',
                                            default => 'bg-secondary'
                                        };

                                        echo "<tr class='ticket-row' data-status='" . htmlspecialchars($row['status']) . "' style='border-bottom: 1px solid #f8f9fa;'>";
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
                                    echo "<tr><td colspan='7' class='text-center py-5 text-muted'>No account requests found.</td></tr>";
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

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const searchInput = document.getElementById('ticketSearch');
            const filterBtns = document.querySelectorAll('.status-filter');
            const tableRows = document.querySelectorAll('.ticket-row');
            let currentFilter = 'all';

            function filterData() {
                const searchTerm = searchInput.value.toLowerCase();

                tableRows.forEach(row => {
                    const textContent = row.textContent.toLowerCase();
                    const rowStatus = row.getAttribute('data-status');

                    const matchesSearch = textContent.includes(searchTerm);
                    const matchesStatus = (currentFilter === 'all' || rowStatus === currentFilter);

                    row.style.display = (matchesSearch && matchesStatus) ? '' : 'none';
                });
            }

            searchInput.addEventListener('keyup', filterData);

            filterBtns.forEach(btn => {
                btn.addEventListener('click', function() {
                    filterBtns.forEach(b => {
                        b.classList.remove('active', 'btn-secondary');
                        b.classList.add('btn-outline-secondary');
                    });
                    this.classList.remove('btn-outline-secondary');
                    this.classList.add('active', 'btn-secondary');

                    currentFilter = this.getAttribute('data-status');
                    filterData();
                });
            });
        });
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