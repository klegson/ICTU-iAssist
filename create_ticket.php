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
    $categoryId = $_POST['categoryId'];
    $manualCategory = trim($_POST['manual_category'] ?? '');

    $catStmt = $pdo->prepare("SELECT categoryType, categoryName FROM category WHERE categoryId = ?");
    $catStmt->execute([$categoryId]);
    $catInfo = $catStmt->fetch();

    if ($catInfo && $catInfo['categoryType'] === 'Others' && !empty($manualCategory)) {
        $description = "Custom Category: " . $manualCategory . "\n\n" . $description;
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

                                <div id="manualContainer" style="display: none;" class="mt-3">
                                    <label class="form-label small fw-bold">SPECIFY CATEGORY</label>
                                    <input type="text" class="form-control" name="manual_category" id="manualCategoryInput" placeholder="Type your custom category...">
                                </div>
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
            const categorySelect = document.getElementById('categorySelect');
            const manualContainer = document.getElementById('manualContainer');
            const manualCategoryInput = document.getElementById('manualCategoryInput');

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
</body>

</html>