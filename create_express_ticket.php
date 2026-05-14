<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Technician') {
    header("Location: index.php");
    exit;
}

$techId = $_SESSION['user_id'];
$msg = "";

if (isset($_POST['submit_express'])) {
    $userId = $_POST['user_id'];
    $categoryId = $_POST['categoryId'];
    $subject = trim($_POST['subject']);
    $description = trim($_POST['description']);
    $remarks = trim($_POST['remarks']);

    if (empty($userId) || empty($categoryId) || empty($subject) || empty($description) || empty($remarks)) {
        $msg = "All fields are required.";
    } else {
        $deptStmt = $pdo->prepare("SELECT departmentId FROM users WHERE userId = ?");
        $deptStmt->execute([$userId]);
        $deptId = $deptStmt->fetchColumn();

        $sql = "INSERT INTO ticket (subject, categoryId, description, priority, status, userId, departmentId, assignedTo, resolvedBy, resolvedAt, remarks)
                VALUES (?, ?, ?, 'Express', 'Resolved', ?, ?, ?, ?, NOW(), ?)";

        $stmt = $pdo->prepare($sql);
        if ($stmt->execute([$subject, $categoryId, $description, $userId, $deptId, $techId, $techId, $remarks])) {
            $_SESSION['flash_msg'] = "Express ticket created and resolved successfully!";
            $_SESSION['flash_type'] = "success";
            header("Location: db_technician.php");
            exit;
        } else {
            $msg = "Error creating Express ticket.";
        }
    }
}

$users = $pdo->query("SELECT u.userId, u.firstName, u.lastName, u.employeeId,
                    d.departmentName, d.departmentCode
                    FROM users u
                    LEFT JOIN department d ON u.departmentId = d.departmentId
                    WHERE u.isApproved = 1
                    ORDER BY d.departmentName ASC, u.firstName ASC")->fetchAll();
$categories = $pdo->query("SELECT * FROM category WHERE is_express_eligible = 1 ORDER BY categoryName ASC")->fetchAll();

$pageTitle = 'Create Express Ticket - DepEd Helpdesk';
include 'head.php';
?>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 main-content" style="min-height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">

                <div class="mb-5">
                    <h2 class="fw-bold text-dark mb-1"><i class="bi bi-lightning-fill text-warning me-2"></i>Create Express Ticket</h2>
                    <p class="text-muted">Create and resolve Express tickets on the spot. This bypasses the dispatcher queue.</p>
                </div>

                <?php if ($msg): ?>
                    <div class="alert alert-danger"><?php echo $msg; ?></div>
                <?php endif; ?>

                <div class="custom-card p-5 col-xl-9">
                    <form method="POST">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label small fw-bold">SELECT USER</label>
                                <input type="text" id="userSearch" class="form-control mb-2"
                                       placeholder="Search by Employee ID or Department...">
                                <select class="form-select" name="user_id" id="userSelect" required>
                                    <option value="" selected disabled>-- Select User --</option>
                                    <?php
                                    $currentDept = "";
                                    foreach ($users as $user) {
                                        $deptName = $user['departmentName'] ?? 'No Department';
                                        if ($currentDept !== $deptName) {
                                            if ($currentDept !== "") echo "</optgroup>";
                                            $currentDept = $deptName;
                                            echo "<optgroup label='" . htmlspecialchars($deptName) . "'>";
                                        }
                                        $display = htmlspecialchars($user['employeeId'] . ' - ' . $user['firstName'] . ' ' . $user['lastName']);
                                        echo "<option value='" . $user['userId'] . "'>" . $display . "</option>";
                                    }
                                    if ($currentDept !== "") echo "</optgroup>";
                                    ?>
                                </select>
                                <small class="text-muted">Search by Employee ID (key) or department to filter.</small>
                            </div>

                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label small fw-bold">CATEGORY</label>
                                <select class="form-select" name="categoryId" required>
                                    <option value="" selected disabled>-- Select Express Category --</option>
                                    <?php foreach ($categories as $cat): ?>
                                        <option value="<?php echo $cat['categoryId']; ?>">
                                            <?php echo htmlspecialchars($cat['categoryName']); ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                                <small class="text-muted">Only Express-eligible categories shown.</small>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">SUBJECT</label>
                            <input type="text" class="form-control" name="subject" placeholder="e.g. Printer jam in Room 301" required>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">DESCRIPTION</label>
                            <textarea class="form-control" name="description" rows="4" placeholder="Describe the issue..." required></textarea>
                        </div>

                        <div class="mb-5">
                            <label class="form-label small fw-bold">RESOLUTION REMARKS</label>
                            <textarea class="form-control" name="remarks" rows="4" placeholder="Describe how the issue was resolved..." required></textarea>
                            <small class="text-muted">Ticket will be auto-resolved with these remarks.</small>
                        </div>

                        <div class="text-end">
                            <a href="db_technician.php" class="btn btn-light px-4 me-2 border">Cancel</a>
                            <button type="submit" name="submit_express" class="btn btn-warning px-4 fw-bold">
                                <i class="bi bi-lightning-fill me-2"></i>CREATE & RESOLVE
                            </button>
                        </div>
                    </form>
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
        const userSearch = document.getElementById('userSearch');
        const userSelect = document.getElementById('userSelect');

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

        if (userSearch && userSelect) {
            userSearch.addEventListener('input', function() {
                const searchTerm = this.value.toLowerCase();
                const options = userSelect.querySelectorAll('option');
                const optgroups = userSelect.querySelectorAll('optgroup');

                options.forEach(option => {
                    const text = option.text.toLowerCase();
                    option.style.display = text.includes(searchTerm) ? '' : 'none';
                });

                optgroups.forEach(group => {
                    const visibleOptions = Array.from(group.querySelectorAll('option'))
                        .filter(opt => opt.style.display !== 'none');
                    group.style.display = visibleOptions.length > 0 ? '' : 'none';
                });
            });
        }
    });
    </script>

</body>
</html>
