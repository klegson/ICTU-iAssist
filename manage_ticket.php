<?php
session_start();
require_once 'db.php';

if (!isset($_SESSION['user_id']) || ($_SESSION['role'] !== 'Officer' && $_SESSION['role'] !== 'Technician')) {
    header("Location: login.php");
    exit;
}

$ticketId = $_GET['id'] ?? null;
$msg = "";
$alertClass = "";

// MOVE QUERY UP: We need to know the ticket's original details (like who created it) BEFORE we update it, so we know who to notify!
$sql = "SELECT t.*, u.firstName, u.lastName, u.email, d.departmentName, c.categoryName 
        FROM ticket t JOIN users u ON t.userId = u.userId 
        LEFT JOIN department d ON u.departmentId = d.departmentId
        LEFT JOIN category c ON t.categoryId = c.categoryId WHERE t.ticketId = ?";
$stmt = $pdo->prepare($sql);
$stmt->execute([$ticketId]);
$ticket = $stmt->fetch();

if (isset($_POST['update_ticket']) || isset($_POST['mark_completed']) || isset($_POST['accept_ticket']) || isset($_POST['resolve_ticket'])) {

    $newPriority = $_POST['priority'] ?? 'Medium';
    $remarks = $_POST['remarks'];
    $assignedTo = !empty($_POST['assignedTo']) ? $_POST['assignedTo'] : null;
    $newStatus = $_POST['current_status'];

    $signatureData = null;

    if (isset($_POST['mark_completed'])) {
        $newStatus = 'Completed';
    } elseif (isset($_POST['accept_ticket'])) {
        $newStatus = 'Processing';
    } elseif (isset($_POST['resolve_ticket'])) {
        $newStatus = 'Resolved';
        if (!empty($_POST['signature_data'])) {
            $signatureData = $_POST['signature_data'];
        }
    }

    if ($signatureData) {
        $sql = "UPDATE ticket SET status = ?, priority = ?, remarks = ?, assignedTo = ?, technician_signature = ?, updatedAt = NOW() WHERE ticketId = ?";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([$newStatus, $newPriority, $remarks, $assignedTo, $signatureData, $ticketId]);
    } else {
        $sql = "UPDATE ticket SET status = ?, priority = ?, remarks = ?, assignedTo = ?, updatedAt = NOW() WHERE ticketId = ?";
        $stmt = $pdo->prepare($sql);
        $success = $stmt->execute([$newStatus, $newPriority, $remarks, $assignedTo, $ticketId]);
    }

    if ($success) {
        // --- NOTIFICATION GENERATOR ---

        // 1. If Officer completes the ticket -> Notify the User
        if (isset($_POST['mark_completed'])) {
            $notifMsg = "Your Ticket #{$ticketId} has been successfully completed and closed.";
            $pdo->prepare("INSERT INTO notification (message, userId) VALUES (?, ?)")->execute([$notifMsg, $ticket['userId']]);
        }

        // 2. If Tech resolves the ticket -> Notify the Officers
        elseif (isset($_POST['resolve_ticket'])) {
            $notifMsg = "Ticket #{$ticketId} was resolved by a technician and awaits your final approval.";
            $officers = $pdo->query("SELECT userId FROM users WHERE role = 'Officer'")->fetchAll();
            foreach ($officers as $off) {
                $pdo->prepare("INSERT INTO notification (message, userId) VALUES (?, ?)")->execute([$notifMsg, $off['userId']]);
            }
        }

        // 3. If Officer assigns a new Tech -> Notify that Technician
        elseif (isset($_POST['update_ticket']) && $_SESSION['role'] === 'Officer' && !empty($assignedTo) && $assignedTo != $ticket['assignedTo']) {
            $notifMsg = "You have been assigned to a new task: Ticket #{$ticketId}.";
            $pdo->prepare("INSERT INTO notification (message, userId) VALUES (?, ?)")->execute([$notifMsg, $assignedTo]);
        }

        // 4. If Tech accepts the ticket -> Notify the User it's being worked on
        elseif (isset($_POST['accept_ticket'])) {
            $notifMsg = "Good news! Your Ticket #{$ticketId} is now being processed by our technicians.";
            $pdo->prepare("INSERT INTO notification (message, userId) VALUES (?, ?)")->execute([$notifMsg, $ticket['userId']]);
        }
        // ------------------------------

        $_SESSION['flash_msg'] = "Ticket #$ticketId updated successfully!";
        $_SESSION['flash_type'] = "alert-success";
        header("Location: " . ($_SESSION['role'] === 'Officer' ? "db_officer.php" : "db_technician.php"));
        exit;
    } else {
        $msg = "Error updating ticket.";
        $alertClass = "alert-danger";
    }
}

// Fetch technicians list for the dropdown
$techSql = "SELECT userId, firstName, lastName FROM users WHERE role = 'Technician'";
$technicians = $pdo->query($techSql)->fetchAll();
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Manage Ticket #<?php echo $ticketId; ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

    <style>
        .signature-container {
            position: relative;
            width: 100%;
            height: 150px;
            background-color: #fff;
            border: 2px dashed #ced4da;
            border-radius: 0.375rem;
            overflow: hidden;
        }

        .signature-pad {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            touch-action: none;
        }
    </style>
</head>

<body>
    <div class="d-flex" style="min-height: 100vh;">
        <div style="width: 280px; flex-shrink: 0;">
            <?php include($_SESSION['role'] === 'Officer' ? 'sidebar_officer.php' : 'sidebar_tech.php'); ?>
        </div>
        <div class="flex-grow-1" style="max-height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">
                <div class="d-flex justify-content-between align-items-center mb-5">
                    <h2 class="fw-bold text-dark">Processing Ticket #<?php echo $ticketId; ?></h2>
                    <a href="<?php echo ($_SESSION['role'] === 'Officer') ? 'db_officer.php' : 'db_technician.php'; ?>" class="btn btn-outline-secondary px-4">Back</a>
                </div>

                <div class="row g-4">
                    <div class="col-lg-7">
                        <div class="custom-card p-4 mb-4 border-top-info">
                            <h6 class="fw-bold mb-4">Requestor Details</h6>
                            <div class="row">
                                <div class="col-md-6"><label class="small text-muted fw-bold">NAME</label>
                                    <div><?php echo htmlspecialchars($ticket['firstName'] . ' ' . $ticket['lastName']); ?></div>
                                </div>
                                <div class="col-md-6"><label class="small text-muted fw-bold">DEPARTMENT</label>
                                    <div><?php echo htmlspecialchars($ticket['departmentName'] ?? 'N/A'); ?></div>
                                </div>
                            </div>
                        </div>
                        <div class="custom-card p-4">
                            <h5 class="fw-bold text-dark mb-4"><?php echo htmlspecialchars($ticket['subject']); ?></h5>
                            <div class="p-4 bg-light rounded border">
                                <p><?php echo nl2br(htmlspecialchars($ticket['description'])); ?></p>
                            </div>
                        </div>
                    </div>

                    <div class="col-lg-5">
                        <form method="POST" id="ticket-form">
                            <div class="custom-card p-4 border-top-warning">
                                <h6 class="fw-bold mb-4">Action Panel</h6>

                                <?php if ($_SESSION['role'] === 'Officer'): ?>
                                    <div class="mb-4"><label class="form-label small fw-bold">ASSIGN TO</label>
                                        <select class="form-select" name="assignedTo" <?php echo ($ticket['status'] === 'Completed') ? 'disabled' : ''; ?>>
                                            <option value="">Unassigned</option>
                                            <?php foreach ($technicians as $tech): ?>
                                                <option value="<?php echo $tech['userId']; ?>" <?php if ($ticket['assignedTo'] == $tech['userId']) echo 'selected'; ?>>
                                                    <?php echo htmlspecialchars($tech['firstName'] . ' ' . $tech['lastName']); ?>
                                                </option>
                                            <?php endforeach; ?>
                                        </select>
                                    </div>

                                    <div class="mb-4"><label class="form-label small fw-bold">PRIORITY</label>
                                        <select class="form-select" name="priority" <?php echo ($ticket['status'] === 'Completed') ? 'disabled' : ''; ?>>
                                            <option value="Low" <?php echo (($ticket['priority'] ?? '') === 'Low') ? 'selected' : ''; ?>>Low</option>
                                            <option value="Medium" <?php echo (($ticket['priority'] ?? 'Medium') === 'Medium') ? 'selected' : ''; ?>>Medium</option>
                                            <option value="High" <?php echo (($ticket['priority'] ?? '') === 'High') ? 'selected' : ''; ?>>High</option>
                                        </select>
                                    </div>
                                <?php else: ?>
                                    <input type="hidden" name="assignedTo" value="<?php echo htmlspecialchars($ticket['assignedTo'] ?? ''); ?>">
                                    <input type="hidden" name="priority" value="<?php echo htmlspecialchars($ticket['priority'] ?? 'Medium'); ?>">
                                <?php endif; ?>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold">CURRENT STATUS</label>
                                    <div>
                                        <?php
                                        $statusBadge = 'bg-secondary';
                                        if ($ticket['status'] == 'Pending') $statusBadge = 'bg-warning text-dark';
                                        if ($ticket['status'] == 'Processing') $statusBadge = 'bg-primary';
                                        if ($ticket['status'] == 'Resolved') $statusBadge = 'bg-info text-dark';
                                        if ($ticket['status'] == 'Completed') $statusBadge = 'bg-success';
                                        ?>
                                        <span class="badge <?php echo $statusBadge; ?> fs-6 px-3 py-2 shadow-sm">
                                            <?php echo htmlspecialchars($ticket['status']); ?>
                                        </span>
                                    </div>
                                    <input type="hidden" name="current_status" value="<?php echo htmlspecialchars($ticket['status']); ?>">
                                </div>

                                <div class="mb-4">
                                    <label class="form-label small fw-bold">REMARKS</label>
                                    <textarea class="form-control" name="remarks" rows="4" <?php echo ($ticket['status'] === 'Completed') ? 'readonly' : ''; ?> placeholder="Add notes for this ticket..."><?php echo htmlspecialchars($ticket['remarks'] ?? ''); ?></textarea>
                                </div>

                                <?php if (!empty($ticket['technician_signature'])): ?>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold">TECHNICIAN SIGNATURE</label>
                                        <div class="border rounded p-2 bg-white text-center shadow-sm">
                                            <img src="<?php echo htmlspecialchars($ticket['technician_signature']); ?>" alt="Signature" style="max-height: 100px; max-width: 100%;">
                                        </div>
                                    </div>
                                <?php endif; ?>

                                <?php if ($_SESSION['role'] === 'Technician' && $ticket['status'] === 'Processing'): ?>
                                    <div class="mb-4">
                                        <label class="form-label small fw-bold text-danger">SIGN TO RESOLVE TICKET</label>
                                        <div class="signature-container shadow-sm">
                                            <canvas id="signature-pad" class="signature-pad"></canvas>
                                        </div>
                                        <div class="text-end mt-2">
                                            <button type="button" class="btn btn-sm btn-outline-secondary" id="clear-signature">Clear Signature</button>
                                        </div>
                                        <input type="hidden" name="signature_data" id="signature_data">
                                    </div>
                                <?php endif; ?>

                                <?php if ($ticket['status'] !== 'Completed'): ?>
                                    <div class="d-flex flex-column gap-2 mt-2">

                                        <?php if ($ticket['status'] === 'Pending'): ?>
                                            <?php if ($_SESSION['role'] === 'Technician'): ?>
                                                <button type="submit" name="accept_ticket" class="btn btn-deped-primary w-100 py-3 fw-bold">
                                                    <i class="bi bi-tools me-2"></i> ACCEPT TICKET
                                                </button>
                                            <?php else: ?>
                                                <button type="submit" name="update_ticket" class="btn btn-outline-secondary w-100 py-3 fw-bold">
                                                    <i class="bi bi-save me-2"></i> SAVE ASSIGNMENT
                                                </button>
                                            <?php endif; ?>

                                        <?php elseif ($ticket['status'] === 'Processing'): ?>
                                            <button type="submit" name="update_ticket" class="btn btn-outline-secondary w-100 py-3 fw-bold">
                                                <i class="bi bi-save me-2"></i> SAVE REMARKS
                                            </button>
                                            <?php if ($_SESSION['role'] === 'Technician'): ?>
                                                <button type="submit" name="resolve_ticket" id="btn-resolve" class="btn btn-success w-100 py-3 fw-bold">
                                                    <i class="bi bi-check2-circle me-2"></i> SIGN & RESOLVE
                                                </button>
                                            <?php endif; ?>

                                        <?php elseif ($ticket['status'] === 'Resolved'): ?>
                                            <button type="submit" name="update_ticket" class="btn btn-outline-secondary w-100 py-3 fw-bold">
                                                <i class="bi bi-save me-2"></i> SAVE REMARKS
                                            </button>
                                            <?php if ($_SESSION['role'] === 'Officer'): ?>
                                                <button type="button" id="btn-approve-complete" class="btn btn-success w-100 py-3 fw-bold">
                                                    <i class="bi bi-check-circle-fill me-2"></i> APPROVE & COMPLETE
                                                </button>
                                            <?php endif; ?>
                                        <?php endif; ?>

                                    </div>
                                <?php else: ?>
                                    <div class="alert alert-success text-center mb-0 mt-3">
                                        <i class="bi bi-check-circle-fill me-2"></i> This ticket is completed.
                                    </div>
                                <?php endif; ?>

                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('signature-pad');

            if (canvas) {
                function resizeCanvas() {
                    const ratio = Math.max(window.devicePixelRatio || 1, 1);
                    canvas.width = canvas.offsetWidth * ratio;
                    canvas.height = canvas.offsetHeight * ratio;
                    canvas.getContext("2d").scale(ratio, ratio);
                }

                window.onresize = resizeCanvas;
                resizeCanvas();

                const signaturePad = new SignaturePad(canvas, {
                    penColor: "rgb(0, 0, 0)"
                });

                document.getElementById('clear-signature').addEventListener('click', function() {
                    signaturePad.clear();
                });

                document.getElementById('btn-resolve').addEventListener('click', function(e) {
                    if (signaturePad.isEmpty()) {
                        e.preventDefault();
                        Swal.fire({
                            icon: 'warning',
                            title: 'Signature Required',
                            text: 'Please provide your digital signature before resolving this ticket.',
                            confirmButtonColor: '#198754'
                        });
                    } else {
                        document.getElementById('signature_data').value = signaturePad.toDataURL();
                    }
                });
            }
            const btnApproveComplete = document.getElementById('btn-approve-complete');
            if (btnApproveComplete) {
                btnApproveComplete.addEventListener('click', function(e) {
                    e.preventDefault();
                    const form = this.closest('form');

                    Swal.fire({
                        title: 'Approve & Close Ticket?',
                        text: "Have you verified the technician's signature and remarks?",
                        icon: 'question',
                        showCancelButton: true,
                        confirmButtonColor: '#198754',
                        cancelButtonColor: '#6c757d',
                        confirmButtonText: 'Yes, Complete Ticket'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            const hiddenInput = document.createElement('input');
                            hiddenInput.type = 'hidden';
                            hiddenInput.name = 'mark_completed';
                            hiddenInput.value = '1';
                            form.appendChild(hiddenInput);
                            form.submit();
                        }
                    });
                });
            }
        });
    </script>
</body>

</html>