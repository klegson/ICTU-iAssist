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
$swalData = null;
$refNumber = "SL-" . date("Ym") . "-" . rand(1000, 9999);

if (isset($_POST['save_event'])) {
    $eventName = trim($_POST['event_name']);
    $description = trim($_POST['description']);
    $eventDate = $_POST['event_date'];
    $location = trim($_POST['location']);
    $refNumberPost = $_POST['ref_number'];
    $userId = $_SESSION['user_id'];

    $sql = "INSERT INTO starlink (reference_number, userId, event_name, description, event_date, location) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);

    try {
        if ($stmt->execute([$refNumberPost, $userId, $eventName, $description, $eventDate, $location])) {
            $swalData = [
                'icon' => 'success',
                'title' => 'Event Submitted!',
                'html' => "Your Reference Number is: <b>" . htmlspecialchars($refNumberPost) . "</b><br><br>
                           <div class='p-3 bg-light border border-success rounded text-center mt-3 shadow-sm'>
                               <h6 class='fw-bold text-dark mb-2'>Next Steps:</h6>
                               <p class='small text-muted mb-0'>Please ensure you sign the agreement form you downloaded and submit it to the ICT Office.</p>
                           </div>"
            ];
            $refNumber = "SL-" . date("Ym") . "-" . rand(1000, 9999);
        }
    } catch (PDOException $e) {
        if ($e->getCode() == 23000 || $e->getCode() == 1062) {
            $swalData = [
                'icon' => 'info',
                'title' => 'Already Submitted',
                'html' => 'This Starlink request (Ref: <b>' . htmlspecialchars($refNumberPost) . '</b>) has already been saved.'
            ];
        } else {
            $swalData = [
                'icon' => 'error',
                'title' => 'System Error',
                'html' => 'There was an issue connecting to the database. Please try again later.'
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Submit Starlink Event - DepEd Helpdesk</title>
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
                <h2 class="fw-bold text-dark mb-1">Borrow Starlink Device</h2>
                <p class="text-muted mb-4">Submit an event request to reserve the regional Starlink equipment.</p>

                <div class="col-xl-9">
                    <div class="alert alert-info border-info mb-4 shadow-sm" style="background-color: white;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill fs-1 text-primary me-4"></i>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Borrowing Procedure</h6>
                                <p class="mb-0 small text-muted">Please fill out the event details below. You must generate and download your agreement form first before the system allows you to submit the event.</p>
                            </div>
                        </div>
                    </div>

                    <div class="custom-card p-5">
                        <form method="POST" id="starlinkForm">
                            <input type="hidden" id="refNumber" name="ref_number" value="<?php echo $refNumber; ?>">

                            <div class="mb-4">
                                <label class="form-label small fw-bold">EVENT NAME</label>
                                <input type="text" id="eventName" name="event_name" class="form-control" placeholder="e.g., School Setup / Orientation" required>
                            </div>

                            <div class="row mb-4">
                                <div class="col-md-6 mb-3 mb-md-0">
                                    <label class="form-label small fw-bold">EVENT DATE</label>
                                    <input type="date" id="eventDate" name="event_date" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label small fw-bold">LOCATION</label>
                                    <input type="text" id="location" name="location" class="form-control" placeholder="e.g., Computer Laboratory" required>
                                </div>
                            </div>

                            <div class="mb-4">
                                <label class="form-label small fw-bold">DESCRIPTION / REMARKS</label>
                                <textarea name="description" class="form-control" rows="4" placeholder="Provide details about the event..." required></textarea>
                            </div>

                            <div class="form-check mb-4 p-3 border rounded bg-light" style="padding-left: 2.5rem !important;">
                                <input class="form-check-input border-secondary" type="checkbox" id="agreementCheck" style="transform: scale(1.2); margin-top: 0.25rem;" disabled>
                                <label class="form-check-label small fw-bold text-dark" for="agreementCheck">
                                    I confirm that I have downloaded the official Starlink Agreement Form.
                                </label>
                            </div>

                            <div class="d-flex justify-content-end align-items-center border-top pt-4">
                                <a href="db_user.php" class="btn btn-light px-4 me-3 border">Cancel</a>

                                <button type="button" id="btnDownloadPdf" class="btn btn-primary px-4 me-3">
                                    <i class="bi bi-file-earmark-pdf-fill me-2" id="downloadIcon"></i>1. DOWNLOAD FORM
                                </button>

                                <button type="submit" name="save_event" id="submitBtn" class="btn btn-secondary px-4" disabled>
                                    <i class="bi bi-lock-fill me-2" id="submitIcon"></i>2. SUBMIT EVENT
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const btnDownloadPdf = document.getElementById('btnDownloadPdf');
            const downloadIcon = document.getElementById('downloadIcon');
            const agreementCheck = document.getElementById('agreementCheck');
            const submitBtn = document.getElementById('submitBtn');
            const submitIcon = document.getElementById('submitIcon');

            const swalData = <?php echo json_encode($swalData); ?>;
            if (swalData) {
                Swal.fire({
                    icon: swalData.icon,
                    title: swalData.title,
                    html: swalData.html,
                    confirmButtonColor: '#198754',
                    allowOutsideClick: false
                }).then((result) => {
                    if (result.isConfirmed && swalData.icon === 'success') {
                        window.location.href = 'db_user.php';
                    }
                });
            }

            btnDownloadPdf.addEventListener('click', function(e) {
                e.preventDefault();

                const nameVal = document.getElementById('eventName').value.trim();
                const dateVal = document.getElementById('eventDate').value;
                const locationVal = document.getElementById('location').value.trim();
                const refVal = document.getElementById('refNumber').value;

                if (!nameVal || !dateVal || !locationVal) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Missing Information',
                        text: 'Please fill out the Event Name, Date, and Location before downloading the form.',
                        confirmButtonColor: '#0056b3'
                    });
                    return;
                }

                const pdfUrl = `generate_pdf.php?ref=${encodeURIComponent(refVal)}&name=${encodeURIComponent(nameVal)}&date=${encodeURIComponent(dateVal)}&location=${encodeURIComponent(locationVal)}`;
                window.open(pdfUrl, '_blank');

                agreementCheck.disabled = false;

                btnDownloadPdf.classList.remove('btn-primary');
                btnDownloadPdf.classList.add('btn-outline-primary');
                downloadIcon.classList.replace('bi-file-earmark-pdf-fill', 'bi-check-circle-fill');
                btnDownloadPdf.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>FORM DOWNLOADED';
            });

            agreementCheck.addEventListener('change', function() {
                if (this.checked) {
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('btn-secondary');
                    submitBtn.classList.add('btn-deped-primary');
                    submitIcon.classList.remove('bi-lock-fill');
                    submitIcon.classList.add('bi-calendar-check');
                } else {
                    submitBtn.disabled = true;
                    submitBtn.classList.remove('btn-deped-primary');
                    submitBtn.classList.add('btn-secondary');
                    submitIcon.classList.remove('bi-calendar-check');
                    submitIcon.classList.add('bi-lock-fill');
                }
            });
        });
    </script>
</body>

</html>