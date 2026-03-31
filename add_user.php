<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Officer') {
    header("Location: login.php");
    exit;
}

$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['approve_user'])) {
    $userIdToApprove = $_POST['user_id'];
    $stmt = $pdo->prepare("UPDATE users SET isApproved = 1 WHERE userId = ?");
    if ($stmt->execute([$userIdToApprove])) {
        $message = "User successfully approved!";
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['create_user'])) {
    $firstName = trim($_POST['firstName']);
    $lastName = trim($_POST['lastName']);
    $email = trim($_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $role = $_POST['role'];
    $departmentId = $_POST['departmentId'];

    $checkStmt = $pdo->prepare("SELECT userId FROM users WHERE email = ?");
    $checkStmt->execute([$email]);
    if ($checkStmt->rowCount() > 0) {
        $message = "Error: An account with that email already exists.";
    } else {
        $stmt = $pdo->prepare("INSERT INTO users (firstName, lastName, email, password, role, departmentId, isApproved) VALUES (?, ?, ?, ?, ?, ?, 1)");
        if ($stmt->execute([$firstName, $lastName, $email, $password, $role, $departmentId])) {
            $message = "New user account created successfully!";
        }
    }
}

$deptStmt = $pdo->query("SELECT * FROM department ORDER BY departmentCode ASC");
$departments = $deptStmt->fetchAll();

$pendingStmt = $pdo->query("SELECT * FROM users WHERE isApproved = 0 ORDER BY createdAt DESC");
$pendingUsers = $pendingStmt->fetchAll();

$activeStmt = $pdo->query("
    SELECT u.*, d.departmentCode, d.section_unit 
    FROM users u 
    LEFT JOIN department d ON u.departmentId = d.departmentId 
    WHERE u.isApproved = 1 
    ORDER BY u.lastName ASC
");
$activeUsers = $activeStmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Users | DepEd Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>
    <div class="d-flex" style="min-height: 100vh;">
        <div style="width: 280px; flex-shrink: 0;">
            <?php include 'sidebar.php'; ?>
        </div>

        <div class="flex-grow-1 bg-light" style="max-height: 100vh; overflow-y: auto;">
            <div class="container-fluid py-5 px-5">

                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="fw-bold text-dark mb-0">User Management</h2>
                        <p class="text-muted mt-1">Approve pending accounts and manage active employees.</p>
                    </div>
                    <button class="btn btn-deped-primary shadow-sm" data-bs-toggle="modal" data-bs-target="#createUserModal">
                        <i class="bi bi-person-plus-fill me-2"></i>Create New User
                    </button>
                </div>

                <?php if ($message): ?>
                    <div class="alert <?php echo strpos($message, 'Error') !== false ? 'alert-danger' : 'alert-success'; ?> alert-dismissible fade show shadow-sm" role="alert">
                        <i class="bi <?php echo strpos($message, 'Error') !== false ? 'bi-exclamation-triangle' : 'bi-check-circle'; ?> me-2"></i>
                        <?php echo $message; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <?php if (count($pendingUsers) > 0): ?>
                    <div class="card border-0 shadow-sm rounded-4 p-4 mb-5" style="border-top: 4px solid #ffc107 !important;">
                        <h5 class="fw-bold text-dark mb-4"><i class="bi bi-clock-history text-warning me-2"></i>Pending Approvals</h5>
                        <div class="table-responsive">
                            <table class="table align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Name</th>
                                        <th>Email</th>
                                        <th>Requested Role</th>
                                        <th>Date Requested</th>
                                        <th class="text-end">Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($pendingUsers as $user): ?>
                                        <tr>
                                            <td class="fw-bold"><?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></td>
                                            <td><?php echo htmlspecialchars($user['email']); ?></td>
                                            <td><span class="badge bg-secondary"><?php echo htmlspecialchars($user['role']); ?></span></td>
                                            <td class="text-muted small"><?php echo date("M d, Y", strtotime($user['createdAt'])); ?></td>
                                            <td class="text-end">
                                                <form method="POST" class="d-inline">
                                                    <input type="hidden" name="user_id" value="<?php echo $user['userId']; ?>">
                                                    <button type="submit" name="approve_user" class="btn btn-sm btn-success shadow-sm">
                                                        <i class="bi bi-check2-circle me-1"></i> Approve
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                <?php endif; ?>

                <div class="card border-0 shadow-sm rounded-4 p-4">
                    <h5 class="fw-bold text-dark mb-4"><i class="bi bi-people-fill text-primary me-2"></i>Active Personnel</h5>
                    <div class="table-responsive">
                        <table class="table table-hover align-middle">
                            <thead class="table-light">
                                <tr>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Role</th>
                                    <th>Division / Unit</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php foreach ($activeUsers as $user): ?>
                                    <tr>
                                        <td class="fw-bold text-dark"><?php echo htmlspecialchars($user['firstName'] . ' ' . $user['lastName']); ?></td>
                                        <td class="text-muted"><?php echo htmlspecialchars($user['email']); ?></td>
                                        <td>
                                            <?php
                                            $roleColor = match ($user['role']) {
                                                'Officer' => 'bg-danger',
                                                'Technician' => 'bg-info text-dark',
                                                default => 'bg-secondary'
                                            };
                                            ?>
                                            <span class="badge <?php echo $roleColor; ?>"><?php echo htmlspecialchars($user['role']); ?></span>
                                        </td>
                                        <td>
                                            <div class="fw-bold" style="font-size: 0.9rem;"><?php echo htmlspecialchars($user['departmentCode'] ?? 'Unassigned'); ?></div>
                                            <div class="text-muted" style="font-size: 0.8rem;"><?php echo htmlspecialchars($user['section_unit'] ?? ''); ?></div>
                                        </td>
                                        <td><span class="badge bg-success bg-opacity-25 text-success border border-success border-opacity-50">Approved</span></td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <div class="modal fade" id="createUserModal" tabindex="-1">
        <div class="modal-dialog modal-lg">
            <div class="modal-content rounded-4 border-0 shadow-lg">
                <div class="modal-header bg-light border-bottom-0 pb-0">
                    <h5 class="modal-title fw-bold text-dark p-2">Create New User Account</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form method="POST" action="">
                    <div class="modal-body p-4">
                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">First Name</label>
                                <input type="text" name="firstName" class="form-control" required>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label small fw-bold text-muted">Last Name</label>
                                <input type="text" name="lastName" class="form-control" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">DepEd Email Address</label>
                            <input type="email" name="email" class="form-control" required>
                        </div>

                        <div class="mb-3">
                            <label class="form-label small fw-bold text-muted">Temporary Password</label>
                            <input type="text" name="password" class="form-control" value="depedrov123" required>
                            <small class="text-muted">Default is set to: <strong>depedrov123</strong></small>
                        </div>

                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small fw-bold text-muted">System Role</label>
                                <select name="role" class="form-select" required>
                                    <option value="User" selected>Staff / User</option>
                                    <option value="Technician">ICT Technician</option>
                                    <option value="Officer">ICT Officer</option>
                                </select>
                            </div>
                            <div class="col-md-8">
                                <label class="form-label small fw-bold text-muted">Department / Division Assignment</label>
                                <select name="departmentId" class="form-select" required>
                                    <option value="" disabled selected>-- Select a Division/Unit --</option>
                                    <?php foreach ($departments as $dept): ?>
                                        <option value="<?php echo $dept['departmentId']; ?>">
                                            <?php
                                            $displayName = $dept['departmentCode'];
                                            if (!empty($dept['section_unit'])) {
                                                $displayName .= " - " . $dept['section_unit'];
                                            } else {
                                                $displayName .= " - " . $dept['departmentName'];
                                            }
                                            echo htmlspecialchars($displayName);
                                            ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>
                    </div>
                    <div class="modal-footer bg-light border-top-0 pt-0">
                        <button type="button" class="btn btn-light border" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="create_user" class="btn btn-deped-primary fw-bold px-4">Create Account</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>