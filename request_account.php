<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
session_start();

require_once 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'User') {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$msg = "";

if (isset($_POST['submit_account'])) {
    $serviceType = $_POST['categoryId'];
    $reason = trim($_POST['reason']);
    $priority = "Medium";

    $systems = [];
    if (isset($_POST['sys_google'])) $systems[] = "Google Account";
    if (isset($_POST['sys_ms365'])) $systems[] = "MS 365 Account";
    if (isset($_POST['sys_happisa'])) $systems[] = "HAPPISA";
    if (isset($_POST['sys_dts'])) $systems[] = "DTS";
    if (isset($_POST['sys_epermit'])) $systems[] = "E-Permit";
    if (isset($_POST['sys_wifi'])) $systems[] = "WIFI Portal";

    $systemList = implode(", ", $systems);

    $extraInfo = "";
    if (!empty($_POST['transfer_to'])) {
        $extraInfo = "\nTRANSFER TO: " . trim($_POST['transfer_to']);
    }

    $finalDescription = "REQUEST DETAILS:\n" .
        "Systems: " . $systemList . "\n\n" .
        "REASON/PURPOSE:\n" . $reason;

    $deptStmt = $pdo->prepare("SELECT departmentId FROM users WHERE userId = ?");
    $deptStmt->execute([$userId]);
    $userDeptId = $deptStmt->fetchColumn();

    if (!$userDeptId) {
        $userDeptId = 1;
    }

    $stmt = $pdo->prepare("INSERT INTO ticket (subject, categoryId, description, priority, status, departmentId, userId) VALUES (?, ?, ?, ?, 'Pending', ?, ?)");

    $subject = "Account Request (" . $systemList . ")";

    if ($stmt->execute([$subject, $serviceType, $finalDescription, $priority, $_SESSION['department_id'], $userId])) {
        header("Location: db_user.php?msg=success");
        exit;
    } else {
        $msg = "Error submitting request.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Request Account Service</title>
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
                <h2 class="fw-bold text-dark mb-1">Account Creation Request</h2>
                <p class="text-muted mb-4">Request official system access credentials for Region V personnel.</p>

                <?php if ($msg): ?>
                    <div class="alert alert-danger"><?php echo $msg; ?></div>
                <?php endif; ?>

                <div class="custom-card p-5 col-xl-9">
                    <form method="POST">
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <label class="form-label small fw-bold">ACTION REQUIRED</label>
                                <select class="form-select" name="categoryId" id="actionSelect" onchange="toggleTransfer()" required>
                                    <option value="" selected disabled>Select Action...</option>
                                    <?php
                                    $sql = "SELECT * FROM category WHERE categoryType = 'Account Services'";
                                    $stmt = $pdo->query($sql);
                                    while ($cat = $stmt->fetch()) {
                                        echo "<option value='" . $cat['categoryId'] . "'>" . htmlspecialchars($cat['categoryName']) . "</option>";
                                    }
                                    ?>
                                </select>
                            </div>
                            <div class="col-md-6" id="transferBox" style="display: none;">
                                <label class="form-label small fw-bold">TRANSFER TO</label>
                                <input type="text" class="form-control" name="transfer_to" placeholder="Enter destination...">
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label small fw-bold">SELECT SYSTEMS</label>
                            <div class="row bg-light rounded p-3 m-0" style="border: 1px solid #e9ecef;">
                                <div class="col-md-4">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_google" id="c1">
                                        <label class="form-check-label small" for="c1">Google Account</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_ms365" id="c2">
                                        <label class="form-check-label small" for="c2">MS 365 Account</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_happisa" id="c3">
                                        <label class="form-check-label small" for="c3">HAPPISA Portal</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_dts" id="c4">
                                        <label class="form-check-label small" for="c4">DTS Account</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_epermit" id="c5">
                                        <label class="form-check-label small" for="c5">E-PERMIT</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_wifi" id="c6">
                                        <label class="form-check-label small" for="c6">WIFI Portal Access</label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-5" id="reasonDiv">
                            <label class="form-label small fw-bold" id="reasonLabel">REASON FOR REQUEST</label>
                            <textarea class="form-control" name="reason" rows="3" placeholder="Briefly explain why this account is needed..." required></textarea>
                        </div>

                        <div class="text-end">
                            <a href="db_user.php" class="btn btn-light px-4 me-2 border">Cancel</a>
                            <button type="submit" name="submit_account" class="btn btn-deped-primary px-4">
                                <i class="bi bi-shield-check me-2"></i>SUBMIT REQUEST
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function toggleTransfer() {
            var selectBox = document.getElementById("actionSelect");
            var hiddenBox = document.getElementById("transferBox");
            var reasonDiv = document.getElementById("reasonDiv");
            var reasonInput = reasonDiv.querySelector('textarea');
            var reasonLabel = document.getElementById("reasonLabel");
            var selectedText = selectBox.options[selectBox.selectedIndex].text;

            if (selectedText.includes("Transfer")) {
                hiddenBox.style.display = "block";
                hiddenBox.querySelector('input').required = true;
            } else {
                hiddenBox.style.display = "none";
                hiddenBox.querySelector('input').required = false;
                hiddenBox.querySelector('input').value = "";
            }
            if (selectedText.includes("Reset")) {
                reasonDiv.style.display = "none";
                reasonLabel.style.display = "none";
                reasonInput.required = false;
                reasonInput.value = "Password Reset Request";
            } else {
                reasonDiv.style.display = "block";
                reasonLabel.style.display = "block";
                reasonInput.required = true;
                if (reasonInput.value === "Password Reset Request") {
                    reasonInput.value = "";
                }
            }
        }
    </script>
</body>

</html>