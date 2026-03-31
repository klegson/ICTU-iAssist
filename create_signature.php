<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user_id'];
$role = $_SESSION['role'] ?? 'User';

if (isset($_POST['save_signature']) && !empty($_POST['signature_data'])) {
    $signatureData = $_POST['signature_data'];

    $stmt = $pdo->prepare("UPDATE users SET signature = ? WHERE userId = ?");
    if ($stmt->execute([$signatureData, $userId])) {

        if ($role === 'Officer') {
            header("Location: db_officer.php");
        } elseif ($role === 'Technician') {
            header("Location: db_technician.php");
        } else {
            header("Location: db_user.php");
        }
        exit;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Setup E-Signature | DepEd Helpdesk</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    <link rel="stylesheet" href="style.css?v=<?php echo time(); ?>">

    <style>
        body {
            background-color: #f4f6f9;
        }

        .signature-container {
            position: relative;
            width: 100%;
            height: 250px;
            background-color: #fff;
            border: 2px dashed #ced4da;
            border-radius: 0.5rem;
            overflow: hidden;
            cursor: crosshair;
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

<body class="d-flex align-items-center justify-content-center" style="min-height: 100vh;">

    <div class="container" style="max-width: 600px;">
        <div class="text-center mb-4">
            <img src="deped_rov.jpg" alt="DepEd Logo" style="width: 80px; height: 80px; object-fit: contain;" class="mb-3 shadow-sm rounded-circle p-2 bg-white">
            <h3 class="fw-bold text-dark">One Last Step!</h3>
            <p class="text-muted">Please provide your e-signature. This will be used to automatically sign off on completed tickets.</p>
        </div>

        <div class="card shadow-lg border-0 rounded-4 p-4 p-md-5">
            <form method="POST" id="signature-form">

                <div class="mb-4">
                    <div class="d-flex justify-content-between align-items-end mb-2">
                        <label class="fw-bold text-dark mb-0">Draw your signature below:</label>
                        <button type="button" class="btn btn-sm btn-light border text-secondary" id="clear-signature">
                            <i class="bi bi-eraser-fill me-1"></i> Clear
                        </button>
                    </div>

                    <div class="signature-container shadow-inner">
                        <canvas id="signature-pad" class="signature-pad"></canvas>
                    </div>
                    <input type="hidden" name="signature_data" id="signature_data">
                </div>

                <button type="submit" name="save_signature" id="btn-save" class="btn btn-deped-primary w-100 py-3 fw-bold fs-5 rounded-3">
                    <i class="bi bi-check-circle-fill me-2"></i> Save Signature & Continue
                </button>
                <div class="text-center mt-4">
                    <a href="logout.php" class="text-muted text-decoration-none small fw-bold">
                        <i class="bi bi-box-arrow-left me-1"></i> Cancel and return to login
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script src="https://cdn.jsdelivr.net/npm/signature_pad@4.1.7/dist/signature_pad.umd.min.js"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const canvas = document.getElementById('signature-pad');

            function resizeCanvas() {
                const ratio = Math.max(window.devicePixelRatio || 1, 1);
                canvas.width = canvas.offsetWidth * ratio;
                canvas.height = canvas.offsetHeight * ratio;
                canvas.getContext("2d").scale(ratio, ratio);
            }
            window.onresize = resizeCanvas;
            resizeCanvas();

            const signaturePad = new SignaturePad(canvas, {
                penColor: "rgb(0, 0, 0)",
                minWidth: 1.5,
                maxWidth: 3
            });

            document.getElementById('clear-signature').addEventListener('click', function() {
                signaturePad.clear();
            });

            document.getElementById('btn-save').addEventListener('click', function(e) {
                if (signaturePad.isEmpty()) {
                    e.preventDefault();
                    Swal.fire({
                        icon: 'warning',
                        title: 'Signature Required',
                        text: 'Please draw your signature before continuing.',
                        confirmButtonColor: '#1a5c28'
                    });
                } else {
                    document.getElementById('signature_data').value = signaturePad.toDataURL();
                }
            });
        });
    </script>
</body>

</html>