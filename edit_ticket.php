<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$userId = $_SESSION['user_id'];

if (isset($_POST['update_ticket'])) {
    $ticketId = $_POST['ticketId'];
    $subject = $_POST['subject'];
    $description = $_POST['description'];
    $categoryId = $_POST['categoryId'];
    $manualCategory = trim($_POST['manual_category'] ?? '');

    $catStmt = $pdo->prepare("SELECT categoryType, categoryName FROM category WHERE categoryId = ?");
    $catStmt->execute([$categoryId]);
    $catInfo = $catStmt->fetch();

    if ($catInfo && $catInfo['categoryType'] === 'Others' && !empty($manualCategory)) {
        $description = "Custom Category: " . $manualCategory . "\n\n" . $description;
    }

    $sql = "UPDATE ticket SET subject = ?, categoryId = ?, description = ?, updatedAt = NOW()
            WHERE ticketId = ? AND userId = ? AND status = 'Pending'";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$subject, $categoryId, $description, $ticketId, $userId])) {
        header("Location: db_user.php?msg=updated");
        exit;
    }
}

if (isset($_GET['id'])) {
    $ticketId = $_GET['id'];
    $sql = "SELECT * FROM ticket WHERE ticketId = ? AND userId = ?";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$ticketId, $userId]);
    $ticket = $stmt->fetch();

    if (!$ticket || $ticket['status'] !== 'Pending') {
        header("Location: db_user.php");
        exit;
    }
}

$pageTitle = 'Edit Ticket #' . $ticketId;
include 'head.php';
?>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 main-content" style="min-height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">

                <div class="d-flex justify-content-between align-items-center mb-5">
                    <div>
                        <h2 class="fw-bold text-dark mb-1">Edit Ticket #<?php echo $ticketId; ?></h2>
                        <p class="text-muted">You can only edit tickets that are still in 'Pending' status.</p>
                    </div>
                    <a href="db_user.php" class="btn btn-outline-secondary px-4">
                        <i class="bi bi-arrow-left me-2"></i>Cancel
                    </a>
                </div>

                <div class="row">
                    <div class="col-xl-9">
                        <div class="custom-card p-5">
                            <form method="POST">
                                <input type="hidden" name="ticketId" value="<?php echo $ticketId; ?>">

                                <div class="row mb-4">
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="form-label small fw-bold">SUBJECT</label>
                                        <input type="text" name="subject" class="form-control"
                                            value="<?php echo htmlspecialchars($ticket['subject']); ?>" required>
                                    </div>
                                    <div class="col-md-6 mb-3 mb-md-0">
                                        <label class="form-label small fw-bold">CATEGORY</label>

                                        <select class="form-select" name="categoryId" id="categorySelect" required>
                                            <option value="" disabled>Select a Category...</option>
                                            <?php
                                            $sql = "SELECT * FROM category 
                                                    WHERE categoryType != 'Account Services' 
                                                    ORDER BY FIELD(categoryType, 'Hardware Problems', 'Software Problems', 'Network Problems', 'Others'), categoryName";
                                            $stmt = $pdo->query($sql);
                                            $currentGroup = "";

                                            while ($cat = $stmt->fetch(PDO::FETCH_ASSOC)) {
                                                if ($currentGroup !== $cat['categoryType']) {
                                                    if ($currentGroup !== "") echo "</optgroup>";
                                                    $currentGroup = $cat['categoryType'];
                                                    echo "<optgroup label='" . htmlspecialchars($currentGroup) . "'>";
                                                }
                                                $selected = ($cat['categoryId'] == $ticket['categoryId']) ? 'selected' : '';
                                                echo "<option value='{$cat['categoryId']}' $selected>" . htmlspecialchars($cat['categoryName']) . "</option>";
                                            }
                                            if ($currentGroup !== "") echo "</optgroup>";
                                            ?>
                                        </select>

                                        <div id="manualContainer" style="display: none;" class="mt-3">
                                            <label class="form-label small fw-bold">SPECIFY CATEGORY</label>
                                            <input type="text" class="form-control" name="manual_category" id="manualCategoryInput" placeholder="Type your custom category...">
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-5">
                                    <label class="form-label small fw-bold">DESCRIPTION</label>
                                    <textarea name="description" class="form-control" rows="6" required><?php echo htmlspecialchars($ticket['description']); ?></textarea>
                                    <div class="form-text mt-2">
                                        Note: If this was an Account Request, be careful editing the system list manually.
                                    </div>
                                </div>

                                <div class="text-end border-top pt-4">
                                    <button type="submit" name="update_ticket" class="btn btn-deped-primary px-5">
                                        <i class="bi bi-check2-circle me-2"></i>SAVE CHANGES
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const categorySelect = document.getElementById('categorySelect');
            const manualContainer = document.getElementById('manualContainer');
            const manualCategoryInput = document.getElementById('manualCategoryInput');

            function checkInitialCategory() {
                const selectedOption = categorySelect.options[categorySelect.selectedIndex];
                const optgroup = selectedOption.closest('optgroup');
                const groupLabel = optgroup ? optgroup.label : '';

                if (groupLabel === 'Others') {
                    manualContainer.style.display = 'block';
                    manualCategoryInput.setAttribute('required', 'required');
                }
            }

            checkInitialCategory();

            categorySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const optgroup = selectedOption.closest('optgroup');
                const groupLabel = optgroup ? optgroup.label : '';

                if (groupLabel === 'Others') {
                    manualContainer.style.display = 'block';
                    manualCategoryInput.setAttribute('required', 'required');
                    manualCategoryInput.focus();
                } else {
                    manualContainer.style.display = 'none';
                    manualCategoryInput.removeAttribute('required');
                    manualCategoryInput.value = '';
                }
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