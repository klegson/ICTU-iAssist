<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'User') {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];

$statStmt = $pdo->prepare("SELECT 
    SUM(CASE WHEN status = 'Pending' THEN 1 ELSE 0 END) as p,
    SUM(CASE WHEN status = 'Processing' THEN 1 ELSE 0 END) as pr,
    SUM(CASE WHEN status IN ('Resolved', 'Closed', 'Completed') THEN 1 ELSE 0 END) as r
    FROM ticket WHERE userId = ?");
$statStmt->execute([$userId]);
$stats = $statStmt->fetch();

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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Dashboard | DepEd Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="d-flex" style="min-height: 100vh;">
        <div style="width: 280px; flex-shrink: 0;">
            <?php include 'sidebar_user.php'; ?>
        </div>

        <div class="flex-grow-1" style="height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">

                <div class="row align-items-center mb-5">
                    <div class="col-md-8">
                        <h2 class="fw-bold text-dark mb-1">User Dashboard</h2>
                        <p class="text-muted">Manage and monitor your ICT support requests.</p>
                    </div>
                    <div class="col-md-4 text-end">
                        <a href="create_ticket.php" class="btn btn-deped-primary shadow-sm">
                            <i class="bi bi-plus-lg me-2"></i>SUBMIT NEW TICKET
                        </a>
                    </div>
                </div>

                <div class="row mb-5 g-4">
                    <div class="col-md-4">
                        <div class="stat-card border-top-warning h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="fw-bold text-muted small">PENDING</span>
                                <i class="bi bi-hourglass-split text-warning fs-4"></i>
                            </div>
                            <h1 class="display-4 fw-bold text-dark mb-0"><?php echo $stats['p'] ?? 0; ?></h1>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stat-card border-top-info h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="fw-bold text-muted small">PROCESSING</span>
                                <i class="bi bi-gear-fill text-info fs-4"></i>
                            </div>
                            <h1 class="display-4 fw-bold text-dark mb-0"><?php echo $stats['pr'] ?? 0; ?></h1>
                        </div>
                    </div>

                    <div class="col-md-4">
                        <div class="stat-card border-top-success h-100">
                            <div class="d-flex justify-content-between align-items-start mb-2">
                                <span class="fw-bold text-muted small">RESOLVED</span>
                                <i class="bi bi-check-circle-fill text-success fs-4"></i>
                            </div>
                            <h1 class="display-4 fw-bold text-dark mb-0"><?php echo $stats['r'] ?? 0; ?></h1>
                        </div>
                    </div>
                </div>

                <div class="custom-card p-4 mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold text-dark m-0"><i class="bi bi-tools me-2 text-danger"></i>Technical Support History</h6>

                        <div class="d-flex gap-3">
                            <div class="btn-group shadow-sm" role="group">
                                <button type="button" class="btn btn-sm btn-secondary active tech-filter" data-status="all">All</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary tech-filter" data-status="Pending">Pending</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary tech-filter" data-status="Processing">Processing</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary tech-filter" data-status="Completed">Completed</button>
                            </div>
                            <div class="input-group input-group-sm shadow-sm" style="width: 250px;">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="techSearch" class="form-control border-start-0" placeholder="Search tickets...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0" id="techTable">
                            <thead style="border-bottom: 2px solid #f0f2f5;">
                                <tr>
                                    <th class="text-muted small fw-bold pb-3">TICKET #</th>
                                    <th class="text-muted small fw-bold pb-3">DATE REQUESTED</th>
                                    <th class="text-muted small fw-bold pb-3">SUBJECT</th>
                                    <th class="text-muted small fw-bold pb-3">CATEGORY</th>
                                    <th class="text-muted small fw-bold pb-3">STATUS</th>
                                    <th class="text-end text-muted small fw-bold pb-3">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT t.*, c.categoryName, c.categoryType 
                                        FROM ticket t 
                                        LEFT JOIN category c ON t.categoryId = c.categoryId 
                                        WHERE t.userId = ? 
                                        AND (c.categoryType != 'Account Services' OR c.categoryType IS NULL) 
                                        ORDER BY t.createdAt DESC";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$userId]);

                                if ($stmt->rowCount() > 0) {
                                    while ($row = $stmt->fetch()) {
                                        $badgeClass = match ($row['status']) {
                                            'Pending' => 'bg-warning text-dark',
                                            'Approved by IT' => 'bg-info text-dark',
                                            'Processing' => 'bg-primary',
                                            'Completed', 'Resolved' => 'bg-success',
                                            default => 'bg-secondary'
                                        };

                                        $normalizedStatus = in_array($row['status'], ['Completed', 'Resolved']) ? 'Completed' : $row['status'];

                                        $exactDate = date("M d, Y", strtotime($row['createdAt']));
                                        $aging = formatTimeAgo($row['createdAt']);

                                        echo "<tr class='tech-row' data-status='" . htmlspecialchars($normalizedStatus) . "' style='border-bottom: 1px solid #f8f9fa;'>";
                                        echo "<td class='py-3 fw-bold text-muted'>#" . $row['ticketId'] . "</td>";

                                        echo "<td class='py-3'>";
                                        echo "  <div class='text-dark fw-medium'>" . $exactDate . "</div>";
                                        echo "  <small class='text-muted'><i class='bi bi-clock-history me-1'></i>" . $aging . "</small>";
                                        echo "</td>";

                                        echo "<td class='py-3'>" . htmlspecialchars(substr($row['subject'], 0, 35)) . "...</td>";
                                        echo "<td class='py-3'><span class='small text-muted'>" . htmlspecialchars($row['categoryName'] ?? 'General') . "</span></td>";
                                        echo "<td class='py-3'><span class='badge rounded-pill " . $badgeClass . "'>" . $row['status'] . "</span></td>";
                                        echo "<td class='py-3 text-end'>";
                                        echo "<a href='view_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-light border me-1'>View</a>";
                                        if ($row['status'] == 'Pending') {
                                            echo "<a href='edit_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-light border me-1'>Edit</a>";
                                            echo "<a href='delete_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-outline-danger btn-cancel-ticket'><i class='bi bi-trash'></i></a>";
                                        }
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='6' class='text-center py-5 text-muted small'>No support tickets found.</td></tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                </div>

                <div class="custom-card p-4 mb-5">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h6 class="fw-bold text-dark m-0"><i class="bi bi-person-badge me-2 text-primary"></i>Account & Service Requests</h6>

                        <div class="d-flex gap-3">
                            <div class="btn-group shadow-sm" role="group">
                                <button type="button" class="btn btn-sm btn-secondary active acct-filter" data-status="all">All</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary acct-filter" data-status="Pending">Pending</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary acct-filter" data-status="Processing">Processing</button>
                                <button type="button" class="btn btn-sm btn-outline-secondary acct-filter" data-status="Completed">Completed</button>
                            </div>
                            <div class="input-group input-group-sm shadow-sm" style="width: 250px;">
                                <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                                <input type="text" id="acctSearch" class="form-control border-start-0" placeholder="Search requests...">
                            </div>
                        </div>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0" id="acctTable">
                            <thead style="border-bottom: 2px solid #f0f2f5;">
                                <tr>
                                    <th class="text-muted small fw-bold pb-3">REF #</th>
                                    <th class="text-muted small fw-bold pb-3">DATE REQUESTED</th>
                                    <th class="text-muted small fw-bold pb-3">REQUEST TYPE</th>
                                    <th class="text-muted small fw-bold pb-3">STATUS</th>
                                    <th class="text-end text-muted small fw-bold pb-3">ACTION</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $sql = "SELECT t.*, c.categoryName, c.categoryType 
                                        FROM ticket t 
                                        LEFT JOIN category c ON t.categoryId = c.categoryId 
                                        WHERE t.userId = ? 
                                        AND c.categoryType = 'Account Services' 
                                        ORDER BY t.createdAt DESC";
                                $stmt = $pdo->prepare($sql);
                                $stmt->execute([$userId]);

                                if ($stmt->rowCount() > 0) {
                                    while ($row = $stmt->fetch()) {
                                        $badgeClass = match ($row['status']) {
                                            'Pending' => 'bg-warning text-dark',
                                            'Approved by IT' => 'bg-info text-dark',
                                            'Processing' => 'bg-primary',
                                            'Completed', 'Resolved' => 'bg-success',
                                            default => 'bg-secondary'
                                        };

                                        $normalizedStatus = in_array($row['status'], ['Completed', 'Resolved']) ? 'Completed' : $row['status'];

                                        $exactDate = date("M d, Y", strtotime($row['createdAt']));
                                        $aging = formatTimeAgo($row['createdAt']);

                                        echo "<tr class='acct-row' data-status='" . htmlspecialchars($normalizedStatus) . "' style='border-bottom: 1px solid #f8f9fa;'>";
                                        echo "<td class='py-3 fw-bold text-primary'>#" . $row['ticketId'] . "</td>";

                                        echo "<td class='py-3'>";
                                        echo "  <div class='text-dark fw-medium'>" . $exactDate . "</div>";
                                        echo "  <small class='text-muted'><i class='bi bi-clock-history me-1'></i>" . $aging . "</small>";
                                        echo "</td>";

                                        echo "<td class='py-3 fw-bold'>" . htmlspecialchars($row['categoryName']) . "</td>";
                                        echo "<td class='py-3'><span class='badge rounded-pill " . $badgeClass . "'>" . $row['status'] . "</span></td>";
                                        echo "<td class='py-3 text-end'>";

                                        echo "<a href='view_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-light border me-1'>View</a>";

                                        if ($row['status'] == 'Pending') {
                                            echo "<a href='edit_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-light border me-1'>Edit</a>";
                                            echo "<a href='delete_ticket.php?id=" . $row['ticketId'] . "' class='btn btn-sm btn-outline-danger btn-cancel-ticket'><i class='bi bi-trash'></i></a>";
                                        }
                                        echo "</td>";
                                        echo "</tr>";
                                    }
                                } else {
                                    echo "<tr><td colspan='5' class='text-center py-5 text-muted small'>No account requests found.</td></tr>";
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

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {

            function setupTableFilter(searchInputId, filterBtnClass, rowClass) {
                const searchInput = document.getElementById(searchInputId);
                const filterBtns = document.querySelectorAll('.' + filterBtnClass);
                const tableRows = document.querySelectorAll('.' + rowClass);
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
            }

            setupTableFilter('techSearch', 'tech-filter', 'tech-row');
            setupTableFilter('acctSearch', 'acct-filter', 'acct-row');


            const urlParams = new URLSearchParams(window.location.search);

            if (urlParams.get('msg') === 'success') {
                Swal.fire({
                    icon: 'success',
                    title: 'Ticket Submitted!',
                    text: 'Your support request has been successfully created.',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    position: 'top-end'
                });
                window.history.replaceState(null, null, window.location.pathname);
            } else if (urlParams.get('msg') === 'updated') {
                Swal.fire({
                    icon: 'success',
                    title: 'Changes Saved!',
                    text: 'Your ticket has been updated successfully.',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    position: 'top-end'
                });
                window.history.replaceState(null, null, window.location.pathname);
            } else if (urlParams.get('msg') === 'deleted') {
                Swal.fire({
                    icon: 'info',
                    title: 'Ticket Cancelled',
                    text: 'Your support request has been deleted.',
                    showConfirmButton: false,
                    timer: 3000,
                    toast: true,
                    position: 'top-end'
                });
                window.history.replaceState(null, null, window.location.pathname);
            }

            const cancelButtons = document.querySelectorAll('.btn-cancel-ticket');

            cancelButtons.forEach(button => {
                button.addEventListener('click', function(e) {
                    e.preventDefault();
                    const cancelUrl = this.getAttribute('href');

                    Swal.fire({
                        title: 'Cancel this request?',
                        text: "You won't be able to undo this action!",
                        icon: 'warning',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#d33',
                        confirmButtonText: 'Yes, cancel it!'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = cancelUrl;
                        }
                    });
                });
            });

        });
    </script>
</body>

</html>