<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
session_start();
require 'db.php';

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

    $stmt = $pdo->prepare("INSERT INTO ticket (subject, categoryId, description, priority, status, userId) VALUES (?, ?, ?, ?, 'Pending', ?)");

    $subject = "Account Request (" . $systemList . ")";

    if ($stmt->execute([$subject, $serviceType, $finalDescription, $priority, $userId])) {
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

            <div class="col-lg-9 col-xl-10 py-4">

                <h4 class="fw-bold mb-4 text-success">DepEd Account Request Form</h4>

                <?php if ($msg): ?>
                    <div class="alert alert-danger"><?php echo $msg; ?></div>
                <?php endif; ?>

                <div class="card shadow-sm border-0 border-top border-4 border-success">
                    <div class="card-body p-4">
                        <form method="POST">

                            <h6 class="fw-bold text-black mb-3">1. WHAT DO YOU NEED?</h6>
                            <div class="row mb-4">
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">Action Required</label>
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
                                    <label class="form-label small fw-bold text-black">Transfer To</label>
                                    <input type="text" class="form-control" name="transfer_to" placeholder="Enter destination...">
                                </div>
                            </div>

                            <h6 class="fw-bold text-black mb-3">2. SELECT SYSTEMS (Check all that apply)</h6>
                            <div class="row mb-4 bg-light p-3 rounded mx-1">
                                <div class="col-md-4">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_google" id="c1">
                                        <label class="form-check-label" for="c1">Google Account</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_ms365" id="c2">
                                        <label class="form-check-label" for="c2">MS 365 Account</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_happisa" id="c3">
                                        <label class="form-check-label" for="c3">HAPPISA Portal</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_dts" id="c4">
                                        <label class="form-check-label" for="c4">DTS Account</label>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_epermit" id="c5">
                                        <label class="form-check-label" for="c5">E-PERMIT</label>
                                    </div>
                                    <div class="form-check mb-2">
                                        <input class="form-check-input" type="checkbox" name="sys_wifi" id="c6">
                                        <label class="form-check-label" for="c6">WIFI Portal Access</label>
                                    </div>
                                </div>
                            </div>

                            <h6 class="fw-bold text-black mb-3" id="reasonLabel">3. REASON / PURPOSE</h6>
                            <div class="mb-4" id="reasonDiv">
                                <textarea class="form-control" name="reason" rows="3" placeholder="E.g. Newly hired teacher, Forgot password, etc." required></textarea>
                            </div>

                            <div class="text-end">
                                <a href="db_user.php" class="btn btn-secondary px-4">Cancel</a>
                                <button type="submit" name="submit_account" class="btn btn-success px-4 fw-bold">Submit Request</button>
                            </div>
                        </form>
                    </div>
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