<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || !in_array($_SESSION['role'], ['Officer', 'Technician'])) {
    header("Location: login.php");
    exit;
}

$requestId = $_GET['id'] ?? null;
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['assign_request'])) {
    $assignedTo = $_POST['assigned_to'];

    $updateStmt = $pdo->prepare("UPDATE account_request SET status = 'Processing', assignedTo = ? WHERE accountRequestId = ?");
    if ($updateStmt->execute([$assignedTo, $requestId])) {
        header("Location: manage_account_request.php?id=" . $requestId);
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['resolve_request'])) {
    $remarks = trim($_POST['remarks']);

    $updateStmt = $pdo->prepare("UPDATE account_request SET status = 'Resolved', remarks = ?, resolvedBy = ?, updatedAt = NOW() WHERE accountRequestId = ?");
    if ($updateStmt->execute([$remarks, $_SESSION['user_id'], $requestId])) {
        if ($_SESSION['role'] === 'Officer') {
            header("Location: db_officer.php");
        } else {
            header("Location: account_requests.php");
        }
        exit;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['complete_request'])) {
    $updateStmt = $pdo->prepare("UPDATE account_request SET status = 'Completed', updatedAt = NOW() WHERE accountRequestId = ?");
    if ($updateStmt->execute([$requestId])) {
        header("Location: account_requests.php");
        exit;
    }
}

$sql = "SELECT ar.*, u.firstName, u.lastName, d.departmentName, c.categoryName
        FROM account_request ar
        JOIN users u ON ar.userId = u.userId
        LEFT JOIN department d ON u.departmentId = d.departmentId
        JOIN category c ON ar.categoryId = c.categoryId
        WHERE ar.accountRequestId = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$requestId]);
$request = $stmt->fetch();

if (!$request) {
    echo "<div class='text-center mt-5'><h3>Request not found</h3></div>";
    exit;
}

$techStmt = $pdo->query("SELECT userId, firstName, lastName FROM users WHERE role = 'Technician' AND isApproved = 1 ORDER BY firstName ASC");
$technicians = $techStmt->fetchAll();

$pageTitle = 'Manage Account Request #' . htmlspecialchars($requestId);
include 'head.php';
?>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">

        <div class="sidebar-wrapper">
            <?php include 'sidebar.php'; ?>
        </div>

        <div class="flex-grow-1 bg-light main-content" style="min-height: 100vh; overflow-y: auto;">
            <div class="container-fluid py-5 px-5">

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold text-dark mb-0">Account Request #<?php echo htmlspecialchars($requestId); ?></h2>
                    <button onclick="history.back()" class="btn btn-outline-secondary px-4 bg-white">Back</button>
                </div>

                <div class="row g-4">
                    <div class="col-lg-8">

                        <div class="card border-0 shadow-sm rounded-4 mb-4" style="border-top: 4px solid #0dcaf0 !important;">
                            <div class="card-body p-4">
                                <h6 class="fw-bold text-dark mb-3">Requestor Details</h6>
                                <div class="row">
                                    <div class="col-md-6">
                                        <label class="small text-muted fw-bold">NAME</label>
                                        <div class="text-dark"><?php echo htmlspecialchars($request['firstName'] . ' ' . $request['lastName']); ?></div>
                                    </div>
                                    <div class="col-md-6">
                                        <label class="small text-muted fw-bold">DEPARTMENT</label>
                                        <div class="text-dark"><?php echo htmlspecialchars($request['departmentName'] ?? 'No Department'); ?></div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card border-0 shadow-sm rounded-4">
                            <div class="card-body p-4 p-md-5">
                                <h5 class="fw-bold text-dark mb-4"><?php echo htmlspecialchars($request['categoryName']); ?></h5>
                                
                                <div class="mb-4">
                                    <label class="small text-muted fw-bold">SYSTEMS REQUESTED</label>
                                    <div class="p-3 bg-light rounded-3 border text-dark">
                                        <?php echo nl2br(htmlspecialchars($request['systems'])); ?>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="small text-muted fw-bold">REASON / PURPOSE</label>
                                    <div class="p-3 bg-light rounded-3 border text-dark">
                                        <?php echo nl2br(htmlspecialchars($request['reason'] ?? 'No reason provided.')); ?>
                                    </div>
                                </div>

                                <?php if ($request['remarks']): ?>
                                <div class="mb-4">
                                    <label class="small text-muted fw-bold">REMARKS</label>
                                    <div class="p-3 bg-success bg-opacity-10 rounded-3 border border-success text-dark">
                                        <?php echo nl2br(htmlspecialchars($request['remarks'])); ?>
                                    </div>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>

                    </div>

                    <div class="col-lg-4">
                        <div class="card border-0 shadow-sm rounded-4 p-4" style="border-top: 4px solid #ffc107 !important;">
                            <h6 class="fw-bold text-dark border-bottom pb-3 mb-4">Action Panel</h6>

                            <div class="mb-4">
                                <label class="small text-muted fw-bold mb-2">CURRENT STATUS</label>
                                <div>
                                    <?php
                                    $badgeClass = match ($request['status']) {
                                        'Pending' => 'bg-warning text-dark',
                                        'Processing' => 'bg-primary',
                                        'Resolved' => 'bg-success',
                                        'Completed' => 'bg-success',
                                        default => 'bg-secondary'
                                    };
                                    ?>
                                    <span class="badge <?php echo $badgeClass; ?> fs-6 px-4 py-2 rounded-pill shadow-sm">
                                        <?php echo htmlspecialchars($request['status']); ?>
                                    </span>
                                </div>
                            </div>

                            <?php if ($request['status'] === 'Pending'): ?>

                                <form method="POST" class="mb-4 pb-4 border-bottom">
                                    <div class="mb-3">
                                        <label class="small text-muted fw-bold mb-2">ASSIGN TO TECHNICIAN</label>
                                        <select name="assigned_to" class="form-select bg-light" required>
                                            <option value="" disabled selected>-- Select Technician --</option>
                                            <?php foreach ($technicians as $tech): ?>
                                                <option value="<?php echo $tech['userId']; ?>">
                                                    <?php echo htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>
                                    <button type="submit" name="assign_request" class="btn btn-primary fw-bold w-100 py-2 shadow-sm rounded-3">
                                        <i class="bi bi-person-gear me-2"></i> ASSIGN REQUEST
                                    </button>
                                </form>

                                <form method="POST" class="mb-4">
                                    <div class="mb-3">
                                        <label class="small text-muted fw-bold mb-2">RESOLVED (REMARKS)</label>
                                        <textarea name="remarks" class="form-control bg-light" rows="3" placeholder="Enter resolution details..." required></textarea>
                                    </div>
                                    <button type="submit" name="resolve_request" class="btn btn-success fw-bold w-100 py-2 shadow-sm rounded-3">
                                        <i class="bi bi-check-circle-fill me-2"></i> RESOLVE REQUEST
                                    </button>
                                </form>

                            <?php elseif ($request['status'] === 'Processing'): ?>

                                <form method="POST">
                                    <div class="mb-4">
                                        <label class="small text-muted fw-bold mb-2">RESOLUTION REMARKS</label>
                                        <textarea name="remarks" class="form-control bg-light" rows="4" placeholder="Enter details of how the request was fulfilled..." required><?php echo htmlspecialchars($request['remarks'] ?? ''); ?></textarea>
                                    </div>
                                    <button type="submit" name="resolve_request" class="btn btn-success fw-bold w-100 py-3 shadow-sm rounded-3 mb-2">
                                        <i class="bi bi-check-circle-fill me-2"></i> RESOLVE REQUEST
                                    </button>
                                </form>

                            <?php elseif ($request['status'] === 'Resolved'): ?>

                                <div class="mb-4">
                                    <label class="small text-muted fw-bold mb-2">RESOLUTION REMARKS</label>
                                    <div class="p-3 bg-light rounded-3 border text-dark small">
                                        <?php echo nl2br(htmlspecialchars($request['remarks'] ?? 'No remarks provided.')); ?>
                                    </div>
                                </div>
                                <form method="POST">
                                    <button type="submit" name="complete_request" class="btn btn-deped-primary fw-bold w-100 py-3 shadow-sm rounded-3">
                                        <i class="bi bi-check-all me-2"></i> MARK AS COMPLETED
                                    </button>
                                </form>

                            <?php else: ?>

                                <div class="alert alert-success text-center mb-0 border-0 shadow-sm py-3">
                                    <i class="bi bi-check-circle-fill d-block fs-2 mb-2"></i>
                                    <strong><?php echo $request['status']; ?></strong><br>
                                    <small>This request is closed.</small>
                                </div>

                            <?php endif; ?>

                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>