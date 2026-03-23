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

    if (isset($_POST['is_manual']) && $_POST['is_manual'] === '1') {
        $manualCategory = trim($_POST['manual_category']);
        $description = "Custom Category Specified: " . $manualCategory . "\n\n" . $description;

        $fallbackStmt = $pdo->query("SELECT categoryId FROM category WHERE categoryType = 'Others' LIMIT 1");
        $categoryId = $fallbackStmt->fetchColumn();
        if (!$categoryId) $categoryId = 0;
    } else {
        $categoryId = $_POST['categoryId'];
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
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Ticket #<?php echo $ticketId; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="d-flex" style="min-height: 100vh;">
        <div style="width: 280px; flex-shrink: 0;">
            <?php include 'sidebar_user.php'; ?>
        </div>

        <div class="flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
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

                                        <div id="dropdownContainer">
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

                                            <div class="mt-2 text-end">
                                                <a href="#" id="showManualBtn" class="small text-decoration-none text-primary fw-bold">
                                                    <i class="bi bi-pencil-square me-1"></i>Can't find your issue? Type it manually.
                                                </a>
                                            </div>
                                        </div>

                                        <div id="manualContainer" style="display: none;">
                                            <input type="text" class="form-control border-primary shadow-sm" name="manual_category" id="manualCategoryInput" placeholder="Please specify your category...">

                                            <div class="mt-2 text-end">
                                                <a href="#" id="showDropdownBtn" class="small text-decoration-none text-secondary fw-bold">
                                                    <i class="bi bi-list-ul me-1"></i>Back to category list
                                                </a>
                                            </div>
                                        </div>

                                        <input type="hidden" name="is_manual" id="isManualFlag" value="0">
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
            const dropdownContainer = document.getElementById('dropdownContainer');
            const categorySelect = document.getElementById('categorySelect');

            const manualContainer = document.getElementById('manualContainer');
            const manualCategoryInput = document.getElementById('manualCategoryInput');

            const isManualFlag = document.getElementById('isManualFlag');

            document.getElementById('showManualBtn').addEventListener('click', function(e) {
                e.preventDefault();
                dropdownContainer.style.display = 'none';
                categorySelect.removeAttribute('required');

                manualContainer.style.display = 'block';
                manualCategoryInput.setAttribute('required', 'required');
                manualCategoryInput.focus();

                isManualFlag.value = '1';
            });

            document.getElementById('showDropdownBtn').addEventListener('click', function(e) {
                e.preventDefault();
                manualContainer.style.display = 'none';
                manualCategoryInput.removeAttribute('required');
                manualCategoryInput.value = '';

                dropdownContainer.style.display = 'block';
                categorySelect.setAttribute('required', 'required');

                isManualFlag.value = '0';
            });
        });
    </script>
</body>

</html>