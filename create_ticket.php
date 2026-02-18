<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
session_start();
require 'db.php';

$msg = "";

if (isset($_POST['submit_ticket'])) {
    $subject = $_POST['subject'];
    $categoryId = $_POST['categoryId'];
    $description = $_POST['description'];
    $priority = $_POST['priority'];
    $userId = $_SESSION['user_id'];

    $deptStmt = $pdo->prepare("SELECT departmentId FROM users WHERE userId = ?");
    $deptStmt->execute([$userId]);
    $userDeptId = $deptStmt->fetchColumn();

    $sql = "INSERT INTO ticket (subject, categoryId, description, priority, status, userId, departmentId) 
            VALUES (?, ?, ?, ?, 'Pending', ?, ?)";

    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$subject, $categoryId, $description, $priority, $userId, $userDeptId])) {

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
    <link rel="icon" href="deped_rovtab.png" type="image/png">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css">
</head>

<body class="bg-light">

    <?php include 'header.php'; ?>

    <div class="container-fluid">
        <div class="row">
            <div class="col-lg-3 col-xl-2 d-none d-lg-block p-0">
                <?php include 'sidebar_user.php'; ?>
            </div>

            <div class="col-lg-9 col-xl-10 y-4 p-4">

                <h4 class="fw-bold mb-4" style="color: #008000;">Submit a Support Request</h4>
                <?php if ($msg): ?>
                    <div class="alert alert-danger"><?php echo $msg; ?></div>
                <?php endif; ?>

                <div class="card shadow-sm border-0">
                    <div class="card-body p-4">
                        <form method="POST">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label small fw-bold">Subject</label>
                                    <input type="text" class="form-control" name="subject" placeholder="e.g. PC won't turn on" required>
                                </div>

                                <div class="col-md-4 mb-3">
                                    <label class="form-label small fw-bold">Category</label>
                                    <select class="form-select" name="categoryId" required>
                                        <option value="" selected disabled>Select a Category...</option>
                                        <?php
                                        $sql = "SELECT * FROM category WHERE categoryType != 'Account Services' ORDER BY categoryType, categoryName";
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
                                </div>

                                <div class="col-md-2 mb-3">
                                    <label class="form-label small fw-bold">Priority</label>
                                    <select class="form-select" name="priority">
                                        <option value="Low">Low</option>
                                        <option value="Medium" selected>Medium</option>
                                        <option value="High">High</option>
                                    </select>
                                </div>
                            </div>

                            <div class="mb-3">
                                <label class="form-label small fw-bold">Detailed Description</label>
                                <textarea class="form-control" name="description" rows="5" placeholder="Please describe the issue in detail..." required></textarea>
                            </div>

                            <div class="text-end">
                                <a href="db_user.php" class="btn btn-secondary px-4">Cancel</a>
                                <button type="submit" name="submit_ticket" class="btn btn-primary px-4 fw-bold">Submit Ticket</button>
                            </div>
                        </form>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>