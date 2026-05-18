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
    $manualCategory = trim($_POST['manual_category'] ?? '');
    $remarks = trim($_POST['remarks']);

    if (empty($userId) || empty($categoryId) || empty($subject) || empty($remarks)) {
        $msg = "All fields are required.";
    } else {
        $description = $subject;

        $catStmt = $pdo->prepare("SELECT categoryId, categoryType, categoryName FROM category WHERE categoryId = ?");
        $catStmt->execute([$categoryId]);
        $catInfo = $catStmt->fetch();

        if ($catInfo && $catInfo['categoryId'] == 26 && !empty($manualCategory)) {
            $description = "Custom Category: " . $manualCategory . "\n\n" . $description;
        }

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

$users = $pdo->query("SELECT u.userId, u.firstName, u.middleName, u.lastName, u.employeeId,
                    d.departmentName, d.departmentCode
                    FROM users u
                    LEFT JOIN department d ON u.departmentId = d.departmentId
                    WHERE u.role = 'User' AND u.isApproved = 1
                    ORDER BY d.departmentName ASC, u.lastName ASC")->fetchAll();
$categories = $pdo->query("SELECT * FROM category WHERE categoryType != 'Account Services' ORDER BY FIELD(categoryType, 'Hardware Problems', 'Software Problems', 'Network Problems', 'Others'), categoryName")->fetchAll();

// Build user data map for JS info card
$userData = [];
foreach ($users as $u) {
    $userData[$u['userId']] = [
        'name' => formatName($u['firstName'], $u['middleName'] ?? '', $u['lastName']),
        'emp'  => $u['employeeId'],
        'dept' => $u['departmentName'] ?? 'No Department'
    ];
}

$pageTitle = 'Create Express Ticket - DepEd Helpdesk';
include 'head.php';
?>

<style>
.ts-wrapper .ts-control,
.ts-wrapper.single .ts-control {
    padding: 12px 15px;
    height: auto;
    min-height: auto;
}
@media (max-width: 576px) {
    .express-title-area { margin-bottom: 0.75rem !important; }
    .express-title-area h2 { font-size: 1.15rem; }
    .express-title-area p { font-size: 0.8rem; }
    .express-section { margin-bottom: 1rem !important; }
    .express-section h6 { font-size: 0.8rem; padding-bottom: 0.4rem !important; }
    .express-actions .btn { width: 100%; min-height: 44px; }
    #userInfoCard { padding: 0.75rem !important; }
    #userInfoCard .btn { min-height: 36px; }
}
</style>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 main-content" style="min-height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">

                <div class="mb-5 express-title-area">
                    <h2 class="fw-bold text-dark mb-1"><i class="bi bi-lightning-fill text-warning me-2"></i>Create Express Ticket</h2>
                    <p class="text-muted mb-0">Create and resolve Express tickets on the spot. This bypasses the dispatcher queue.</p>
                </div>

                <?php if ($msg): ?>
                    <div class="alert alert-danger"><?php echo $msg; ?></div>
                <?php endif; ?>

                <div class="custom-card p-4 col-xl-8 mx-auto">
                    <form method="POST">

                        <!-- Requestor Section -->
                        <div class="mb-4 express-section">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="bi bi-person-fill me-2 text-secondary"></i>Requestor
                            </h6>
                            <div class="mb-3">
                                <label class="form-label small fw-bold">SELECT USER</label>
                                <select class="form-select" name="user_id" id="userSelect" required placeholder="Search by Employee ID or name...">
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
                                        $display = htmlspecialchars($user['employeeId'] . ' - ' . $user['lastName'] . ', ' . $user['firstName']);
                                        echo "<option value='" . $user['userId'] . "'>" . $display . "</option>";
                                    }
                                    if ($currentDept !== "") echo "</optgroup>";
                                    ?>
                                </select>
                                <small class="text-muted">Type an Employee ID or name to search across all departments.</small>
                            </div>

                            <!-- User Info Card -->
                            <div id="userInfoCard" class="d-none border rounded-3 p-3 bg-light">
                                <div class="d-flex align-items-center justify-content-between flex-wrap gap-2">
                                    <div>
                                        <div class="mb-1">
                                            <span class="fw-bold" id="userInfoName"></span>
                                            <span class="badge bg-secondary ms-2" id="userInfoEmpId"></span>
                                        </div>
                                        <div class="small text-muted">
                                            <i class="bi bi-building me-1"></i><span id="userInfoDept"></span>
                                        </div>
                                    </div>
                                    <button type="button" class="btn btn-sm btn-outline-secondary" id="changeUserBtn">
                                        <i class="bi bi-arrow-repeat me-1"></i>Change
                                    </button>
                                </div>
                            </div>
                        </div>

                        <!-- Issue Details Section -->
                        <div class="mb-4 express-section">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="bi bi-bug-fill me-2 text-secondary"></i>Issue Details
                            </h6>
                            <div class="row mb-3">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label small fw-bold">CATEGORY</label>
                                    <select class="form-select" name="categoryId" id="categorySelect" required>
                                        <option value="" selected disabled>Select a Category...</option>
                                        <?php
                                        $currentGroup = "";
                                        foreach ($categories as $cat) {
                                            if ($currentGroup !== $cat['categoryType']) {
                                                if ($currentGroup !== "") echo "</optgroup>";
                                                $currentGroup = $cat['categoryType'];
                                                echo "<optgroup label='" . htmlspecialchars($currentGroup) . "'>";
                                            }
                                            echo "<option value='" . $cat['categoryId'] . "'>" . htmlspecialchars($cat['categoryName']) . "</option>";
                                        }
                                        if ($currentGroup !== "") echo "</optgroup>";
                                        ?>
                                    </select>
                                    <div id="manualContainer" style="display: none;" class="mt-3">
                                        <label class="form-label small fw-bold">SPECIFY CATEGORY</label>
                                        <input type="text" class="form-control" name="manual_category" id="manualCategoryInput" placeholder="Type your custom category...">
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">SUBJECT</label>
                                    <input type="text" class="form-control" name="subject" id="subjectInput"
                                           placeholder="e.g. Printer jam in Room 301" required>
                                </div>
                            </div>
                        </div>

                        <!-- Resolution Section -->
                        <div class="mb-4 express-section">
                            <h6 class="fw-bold text-dark border-bottom pb-2 mb-3">
                                <i class="bi bi-check-circle-fill me-2 text-secondary"></i>Resolution
                            </h6>
                            <div>
                                <label class="form-label small fw-bold">RESOLUTION REMARKS</label>
                                <textarea class="form-control" name="remarks" rows="3"
                                          placeholder="Describe how the issue was resolved..." required></textarea>
                                <small class="text-muted">The ticket will be auto-resolved with these remarks.</small>
                            </div>
                        </div>

                        <!-- Actions -->
                        <div class="d-flex flex-column flex-sm-row justify-content-sm-end gap-2 border-top pt-3 express-actions">
                            <a href="db_technician.php" class="btn btn-light px-4 border">Cancel</a>
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
    <script src="https://cdn.jsdelivr.net/npm/tom-select@2.4.3/dist/js/tom-select.complete.min.js"></script>

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // ---- Sidebar ----
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

        // ---- Category Manual Input toggle ----
        var categorySelect = document.getElementById('categorySelect');
        var manualContainer = document.getElementById('manualContainer');
        var manualCategoryInput = document.getElementById('manualCategoryInput');

        if (categorySelect && manualContainer && manualCategoryInput) {
            categorySelect.addEventListener('change', function() {
                if (this.value == 26) {
                    manualContainer.style.display = 'block';
                    manualCategoryInput.setAttribute('required', 'required');
                    manualCategoryInput.focus();
                } else {
                    manualContainer.style.display = 'none';
                    manualCategoryInput.removeAttribute('required');
                    manualCategoryInput.value = '';
                }
            });
        }

        // ---- User data map (from PHP) ----
        var userData = <?php echo json_encode($userData, JSON_UNESCAPED_UNICODE); ?>;

        // ---- TomSelect enhanced select ----
        var userSelect = document.getElementById('userSelect');
        var userInfoCard = document.getElementById('userInfoCard');
        var changeUserBtn = document.getElementById('changeUserBtn');
        var ts = null;

        if (userSelect) {
            try {
                ts = new TomSelect('#userSelect', {
                    maxItems: 1,
                    placeholder: 'Search by Employee ID or name...',
                    closeAfterSelect: true,
                    onChange: function(value) {
                        if (value && userData[value]) {
                            var d = userData[value];
                            document.getElementById('userInfoName').textContent = d.name;
                            document.getElementById('userInfoEmpId').textContent = d.emp;
                            document.getElementById('userInfoDept').textContent = d.dept;
                            userInfoCard.classList.remove('d-none');
                            setTimeout(function() {
                                document.getElementById('subjectInput').focus();
                            }, 100);
                        } else {
                            userInfoCard.classList.add('d-none');
                        }
                    }
                });
            } catch(e) {
                console.error('TomSelect init failed:', e);
            }
        }

        // Change user button (works even as native fallback)
        if (changeUserBtn) {
            changeUserBtn.addEventListener('click', function() {
                if (ts) {
                    ts.clear();
                    ts.focus();
                } else {
                    userSelect.value = '';
                }
                userInfoCard.classList.add('d-none');
            });
        }

        // ---- Ctrl+Enter to submit ----
        document.querySelectorAll('textarea').forEach(function(ta) {
            ta.addEventListener('keydown', function(e) {
                if (e.ctrlKey && e.key === 'Enter') {
                    document.querySelector('button[name="submit_express"]').click();
                }
            });
        });
    });
    </script>

</body>
</html>
