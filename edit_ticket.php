<?php
// DISABLE BROWSER CACHE
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$msg = "";

if (isset($_GET['id'])) {
    $ticketId = $_GET['id'];

    $stmt = $pdo->prepare("SELECT * FROM ticket WHERE ticketId = ? AND userId = ?");
    $stmt->execute([$ticketId, $userId]);
    $ticket = $stmt->fetch();

    if (!$ticket) {
        die("Error: Ticket not found or access denied.");
    }
    if ($ticket['status'] !== 'Pending') {
        die("Error: You cannot edit a ticket that is already being processed.");
    }
} else {
    header("Location: db_user.php");
    exit;
}

if (isset($_POST['update_ticket'])) {
    $subject = $_POST['subject'];
    $categoryId = $_POST['categoryId'];
    $description = $_POST['description'];

    $sql = "UPDATE ticket SET subject = ?, categoryId = ?, description = ?, updatedAt = NOW() WHERE ticketId = ?";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$subject, $categoryId, $description, $ticketId])) {
        header("Location: db_user.php?msg=updated");
        exit;
    } else {
        $msg = "Error updating ticket.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Edit Ticket - DepEd Helpdesk</title>
    <link rel="icon" href="deped_logo.png" type="image/png">
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

            <div class="col-lg-9 col-xl-10 py-4">
                <div class="container">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold text-primary">Edit Ticket #<?php echo $ticketId; ?></h4>
                    </div>

                    <?php if ($msg): ?>
                        <div class="alert alert-danger"><?php echo $msg; ?></div>
                    <?php endif; ?>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <form method="POST">

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Subject</label>
                                    <input type="text" class="form-control" name="subject"
                                        value="<?php echo htmlspecialchars($ticket['subject']); ?>" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Category</label>
                                    <select class="form-select" name="categoryId" required>
                                        <?php
                                        $catStmt = $pdo->query("SELECT * FROM category");
                                        while ($cat = $catStmt->fetch()) {
                                            $selected = ($cat['categoryId'] == $ticket['categoryId']) ? 'selected' : '';

                                            echo "<option value='" . $cat['categoryId'] . "' $selected>"
                                                . htmlspecialchars($cat['categoryName']) .
                                                "</option>";
                                        }
                                        ?>
                                    </select>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label fw-bold">Description</label>
                                    <textarea class="form-control" name="description" rows="6" required><?php echo htmlspecialchars($ticket['description']); ?></textarea>
                                    <div class="form-text">
                                        Note: If this was an Account Request, be careful editing the system list above.
                                    </div>
                                </div>

                                <div class="text-end mt-4">
                                    <a href="db_user.php" class="btn btn-secondary me-2">Cancel</a>
                                    <button type="submit" name="update_ticket" class="btn btn-primary fw-bold">Save Changes</button>
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
        let isFormDirty = false;

        const inputs = document.querySelectorAll('input, select, textarea');
        inputs.forEach(input => {
            input.addEventListener('input', () => {
                isFormDirty = true;
            });
        });

        window.addEventListener('beforeunload', function(e) {
            if (isFormDirty) {
                e.preventDefault();
                e.returnValue = '';
            }
        });

        document.querySelector('form').addEventListener('submit', function() {
            isFormDirty = false;
        });
    </script>
</body>

</html>