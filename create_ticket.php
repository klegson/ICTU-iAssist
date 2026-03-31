<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
session_start();
require 'db.php';

$msg = "";

if (isset($_POST['submit_ticket'])) {
    $subject = $_POST['subject'];
    $description = $_POST['description'];

    if ($_POST['is_manual'] === '1') {
        $manualCategory = trim($_POST['manual_category']);
        $description = "Custom Category Specified: " . $manualCategory . "\n\n" . $description;

        $fallbackStmt = $pdo->query("SELECT categoryId FROM category WHERE categoryType = 'Others' LIMIT 1");
        $categoryId = $fallbackStmt->fetchColumn();
        if (!$categoryId) $categoryId = 0;
    } else {
        $categoryId = $_POST['categoryId'];
    }

    $priority = 'Medium';
    $userId = $_SESSION['user_id'];

    $deptStmt = $pdo->prepare("SELECT departmentId FROM users WHERE userId = ?");
    $deptStmt->execute([$userId]);
    $userDeptId = $deptStmt->fetchColumn();

    $sql = "INSERT INTO ticket (subject, categoryId, description, priority, status, userId, departmentId) 
            VALUES (?, ?, ?, ?, 'Pending', ?, ?)";

    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$subject, $categoryId, $description, $priority, $userId, $userDeptId])) {

        $newTicketId = $pdo->lastInsertId();
        $notifMsg = "A new ticket (#{$newTicketId}) has been submitted and requires review.";

        $officers = $pdo->query("SELECT userId FROM users WHERE role = 'Officer'")->fetchAll();
        foreach ($officers as $off) {
            $pdo->prepare("INSERT INTO notification (message, userId) VALUES (?, ?)")->execute([$notifMsg, $off['userId']]);
        }

        header("Location: db_user.php?msg=success");
        exit;
    } else {
        $msg = "Error creating ticket.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Submit New Ticket</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">
</head>

<body>

    <div class="d-flex" style="min-height: 100vh;">
        <div style="width: 280px; flex-shrink: 0;">
            <?php include 'sidebar.php'; ?>
        </div>

        <div class="flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">
                <h2 class="fw-bold text-dark mb-1">Submit a Support Request</h2>
                <p class="text-muted mb-4">Please provide detailed information about the issue.</p>

                <?php if ($msg): ?>
                    <div class="alert alert-danger"><?php echo $msg; ?></div>
                <?php endif; ?>

                <div class="custom-card p-5 col-xl-9">
                    <form method="POST">
                        <div class="row mb-4">
                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label small fw-bold">SUBJECT</label>
                                <input type="text" class="form-control" name="subject" placeholder="e.g. PC won't turn on" required>
                            </div>

                            <div class="col-md-6 mb-3 mb-md-0">
                                <label class="form-label small fw-bold">CATEGORY</label>

                                <div id="dropdownContainer">
                                    <select class="form-select" name="categoryId" id="categorySelect" required>
                                        <option value="" selected disabled>Select a Category...</option>
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
                                            echo "<option value='" . $cat['categoryId'] . "'>" . htmlspecialchars($cat['categoryName']) . "</option>";
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
                            <label class="form-label small fw-bold">DETAILED DESCRIPTION</label>
                            <textarea class="form-control" name="description" rows="5" placeholder="Please describe the issue in detail..." required></textarea>
                        </div>

                        <div class="text-end">
                            <a href="db_user.php" class="btn btn-light px-4 me-2 border">Cancel</a>
                            <button type="submit" name="submit_ticket" class="btn btn-deped-primary px-4">
                                <i class="bi bi-send-fill me-2"></i>SUBMIT TICKET
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