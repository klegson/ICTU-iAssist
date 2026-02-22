<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$page = 'starlink';

$msg = "";
$alertClass = "";

$refNumber = "SL-" . date("Ym") . "-" . rand(1000, 9999);

if (isset($_POST['save_event'])) {
    $eventName = trim($_POST['event_name']);
    $description = trim($_POST['description']);
    $eventDate = $_POST['event_date'];
    $location = trim($_POST['location']);
    $refNumber = $_POST['ref_number'];
    $userId = $_SESSION['user_id'];

    $sql = "INSERT INTO starlink (reference_number, userId, event_name, description, event_date, location) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    if ($stmt->execute([$refNumber, $userId, $eventName, $description, $eventDate, $location])) {
        $msg = "Success! Starlink event submitted. Your Reference Number is: <strong>" . $refNumber . "</strong>";
        $alertClass = "alert-success";
    } else {
        $msg = "Error: Could not submit the event.";
        $alertClass = "alert-danger";
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Submit Starlink Event - DepEd Helpdesk</title>
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
                        <h4 class="fw-bold text-dark"><i class="bi bi-hdd-network-fill me-2 text-primary"></i>Record Starlink Event</h4>
                        <a href="db_user.php" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-2"></i>Back to Dashboard
                        </a>
                    </div>

                    <div class="row justify-content-center">
                        <div class="col-lg-8">
                            <div class="card shadow-sm border-0">
                                <div class="card-header bg-primary text-white fw-bold">
                                    <i class="bi bi-pencil-square me-2"></i> Event Details
                                </div>
                                <div class="card-body p-4">

                                    <?php if ($msg): ?>
                                        <div class="alert <?php echo $alertClass; ?>"><?php echo $msg; ?></div>
                                    <?php endif; ?>

                                    <div class="alert alert-info border-info mb-4">
                                        <div class="d-flex align-items-center">
                                            <i class="bi bi-file-earmark-pdf-fill fs-3 text-danger me-3"></i>
                                            <div>
                                                <h6 class="fw-bold mb-1">Required: Starlink Borrowing Agreement</h6>
                                                <p class="mb-2 small">You must download, read, and sign the official agreement form prior to your event.</p>
                                                <a href="Links/ICT-STARLINK-AGREEMENT-FORM.pdf" class="btn btn-sm btn-outline-primary fw-bold" download>
                                                    <i class="bi bi-download me-1"></i> Download Form
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <form method="POST">
                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">EVENT NAME</label>
                                            <input type="text" name="event_name" class="form-control" placeholder="e.g., School Setup / Orientation" required>
                                        </div>

                                        <div class="row mb-3">
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">EVENT DATE</label>
                                                <input type="date" name="event_date" class="form-control" required>
                                            </div>
                                            <div class="col-md-6">
                                                <label class="form-label small fw-bold">LOCATION</label>
                                                <input type="text" name="location" class="form-control" placeholder="e.g., Computer Laboratory" required>
                                            </div>
                                        </div>

                                        <div class="mb-3">
                                            <label class="form-label small fw-bold">DESCRIPTION / REMARKS</label>
                                            <textarea name="description" class="form-control" rows="4" placeholder="Provide details about the event..." required></textarea>
                                        </div>

                                        <div class="d-grid mt-4">
                                            <button type="submit" name="save_event" class="btn btn-primary fw-bold py-2">
                                                <i class="bi bi-send-fill me-2"></i> Submit Event
                                            </button>
                                        </div>
                                    </form>

                                </div>
                            </div>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>