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

// Handle redirect success message
if (isset($_SESSION['starlink_success'])) {
    $swalData = [
        'icon' => 'success',
        'title' => 'Event Submitted!',
        'html' => "Your Reference Number is: <b>" . htmlspecialchars($_SESSION['starlink_success']) . "</b><br><br>
                    <div class='p-3 bg-light border border-success rounded text-center mt-3 shadow-sm'>
                        <h6 class='fw-bold text-dark mb-2'>Next Steps:</h6>
                        <p class='small text-muted mb-0'>Please ensure you sign the agreement form you downloaded and submit it to the ICT Office.</p>
                    </div>"
    ];
    unset($_SESSION['starlink_success']);
}

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
            $_SESSION['starlink_success'] = $refNumberPost;
            header("Location: starlink_user.php");
            exit;
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

// Fetch user's starlink history
$historyStmt = $pdo->prepare("SELECT * FROM starlink WHERE userId = ? ORDER BY created_at DESC");
$historyStmt->execute([$_SESSION['user_id']]);
$starlinkHistory = $historyStmt->fetchAll();

// Helper function
if (!function_exists('formatTimeAgo')) {
    function formatTimeAgo($datetime) {
        $time = strtotime($datetime);
        $diff = time() - $time;
        if ($diff < 60) return 'Just now';
        if ($diff < 3600) return floor($diff / 60) . 'm ago';
        if ($diff < 86400) return floor($diff / 3600) . 'h ago';
        return date("M d, Y", $time);
    }
}

$pageTitle = 'Borrow Starlink Device - DepEd Helpdesk';
include 'head.php';
?>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 main-content" style="min-height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">
                <h2 class="fw-bold text-dark mb-1">Borrow Starlink Device</h2>
                <p class="text-muted mb-4">Submit an event request to reserve the regional Starlink equipment.</p>

                <button type="button" class="btn btn-primary px-4 mb-5" data-bs-toggle="modal" data-bs-target="#submitStarlinkModal">
                    <i class="bi bi-plus-circle me-2"></i>Submit Starlink Request
                </button>

                <!-- History Section -->
                <h4 class="fw-bold text-dark mb-3">My Starlink Requests</h4>

                <div class="d-flex justify-content-between align-items-center mb-4">
                    <div class="btn-group shadow-sm" role="group">
                        <button type="button" class="btn btn-secondary active status-filter" data-status="all">All</button>
                        <button type="button" class="btn btn-outline-secondary status-filter" data-status="Pending">Pending</button>
                        <button type="button" class="btn btn-outline-secondary status-filter" data-status="Approved">Approved</button>
                        <button type="button" class="btn btn-outline-secondary status-filter" data-status="Rejected">Rejected</button>
                        <button type="button" class="btn btn-outline-secondary status-filter" data-status="Returned">Returned</button>
                    </div>

                    <div class="input-group shadow-sm" style="width: 300px;">
                        <span class="input-group-text bg-white border-end-0"><i class="bi bi-search text-muted"></i></span>
                        <input type="text" id="requestSearch" class="form-control border-start-0" placeholder="Search requests...">
                    </div>
                </div>

                <div class="custom-card p-4">
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead style="border-bottom: 2px solid #f0f2f5;">
                                <tr>
                                    <th class="text-muted small fw-bold pb-3">REF #</th>
                                    <th class="text-muted small fw-bold pb-3">EVENT NAME</th>
                                    <th class="text-muted small fw-bold pb-3">EVENT DATE</th>
                                    <th class="text-muted small fw-bold pb-3">LOCATION</th>
                                    <th class="text-muted small fw-bold pb-3">STATUS</th>
                                    <th class="text-muted small fw-bold pb-3">AGING</th>
                                    <th class="text-end text-muted small fw-bold pb-3">ACTIONS</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($starlinkHistory)): ?>
                                    <?php foreach ($starlinkHistory as $row): ?>
                                        <?php
                                        $status = $row['status'];
                                        $badgeClass = match($status) {
                                            'Pending' => 'bg-warning text-dark',
                                            'Approved' => 'bg-primary',
                                            'Rejected' => 'bg-danger',
                                            'Returned' => 'bg-success',
                                            default => 'bg-secondary'
                                        };
                                        $aging = formatTimeAgo($row['created_at']);
                                        $agingClass = ($status == 'Pending' && (time() - strtotime($row['created_at']) > 172800)) ? 'text-danger fw-bold' : 'text-muted';
                                        ?>
                                        <tr class="request-row" data-status="<?= $status ?>">
                                            <td class="fw-bold text-dark"><?= htmlspecialchars($row['reference_number']) ?></td>
                                            <td class="fw-bold text-dark"><?= htmlspecialchars($row['event_name']) ?></td>
                                            <td class="text-dark"><?= date("M d, Y", strtotime($row['event_date'])) ?></td>
                                            <td class="text-muted"><?= htmlspecialchars($row['location']) ?></td>
                                            <td><span class="badge rounded-pill <?= $badgeClass ?>"><?= $status ?></span></td>
                                            <td>
                                                <small class="<?= $agingClass ?>">
                                                    <i class="bi bi-clock-history me-1"></i><?= $aging ?>
                                                </small>
                                            </td>
                                            <td class="text-end">
                                                <button type="button" class="btn btn-sm btn-outline-primary view-btn" 
                                                    data-bs-toggle="modal" data-bs-target="#viewStarlinkModal"
                                                    data-ref="<?= htmlspecialchars($row['reference_number']) ?>"
                                                    data-event="<?= htmlspecialchars($row['event_name']) ?>"
                                                    data-date="<?= htmlspecialchars($row['event_date']) ?>"
                                                    data-location="<?= htmlspecialchars($row['location']) ?>"
                                                    data-desc="<?= htmlspecialchars($row['description']) ?>"
                                                    data-status="<?= $status ?>">
                                                    <i class="bi bi-eye me-1"></i>View
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr>
                                        <td colspan="7" class="text-center py-5 text-muted">No Starlink requests found.</td>
                                    </tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Submit Starlink Modal -->
    <div class="modal fade" id="submitStarlinkModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-router-fill me-2"></i>Submit Starlink Request</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="alert alert-info border-info mb-4" style="background-color: white;">
                        <div class="d-flex align-items-center">
                            <i class="bi bi-info-circle-fill fs-1 text-primary me-4"></i>
                            <div>
                                <h6 class="fw-bold mb-1 text-dark">Borrowing Procedure</h6>
                                <p class="mb-0 small text-muted">Please fill out the event details below. You must generate and download your agreement form first before the system allows you to submit the event.</p>
                            </div>
                        </div>
                    </div>
                
                    <form method="POST" id="starlinkForm">
                        <input type="hidden" id="refNumber" name="ref_number" value="<?= $refNumber ?>">
                        
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

    <!-- View Starlink Modal -->
    <div class="modal fade" id="viewStarlinkModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content border-0 shadow-lg rounded-4">
                <div class="modal-header border-0">
                    <h5 class="modal-title fw-bold text-dark"><i class="bi bi-router-fill me-2"></i>Starlink Request Details</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase">Reference Number</label>
                        <div id="modalRef" class="fw-bold text-dark"></div>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase">Event Name</label>
                        <div id="modalEvent" class="fw-bold text-dark"></div>
                    </div>
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase">Event Date</label>
                            <div id="modalDate" class="text-dark"></div>
                        </div>
                        <div class="col-md-6">
                            <label class="small text-muted fw-bold text-uppercase">Location</label>
                            <div id="modalLocation" class="text-dark"></div>
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase">Description / Remarks</label>
                        <div id="modalDesc" class="p-3 bg-light border rounded text-dark" style="min-height: 80px;"></div>
                    </div>
                    <div class="mb-3">
                        <label class="small text-muted fw-bold text-uppercase">Status</label>
                        <div id="modalStatus"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // SweetAlert messages
            const swalData = <?php echo json_encode($swalData); ?>;
            if (swalData) {
                Swal.fire({
                    icon: swalData.icon,
                    title: swalData.title,
                    html: swalData.html,
                    confirmButtonColor: '#198754',
                    allowOutsideClick: false
                });
            }

            // Submit modal shown.bs.modal handler
            const submitStarlinkModal = document.getElementById('submitStarlinkModal');
            if (submitStarlinkModal) {
                let initialized = false;
                submitStarlinkModal.addEventListener('shown.bs.modal', function() {
                    if (initialized) return;
                    initialized = true;

                    const btnDownloadPdf = document.getElementById('btnDownloadPdf');
                    const downloadIcon = document.getElementById('downloadIcon');
                    const agreementCheck = document.getElementById('agreementCheck');
                    const submitBtn = document.getElementById('submitBtn');
                    const submitIcon = document.getElementById('submitIcon');

                    // Download PDF handler
                    if (btnDownloadPdf) {
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

                            if (agreementCheck) agreementCheck.disabled = false;

                            btnDownloadPdf.classList.remove('btn-primary');
                            btnDownloadPdf.classList.add('btn-outline-primary');
                            downloadIcon.classList.replace('bi-file-earmark-pdf-fill', 'bi-check-circle-fill');
                            btnDownloadPdf.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>FORM DOWNLOADED';
                        });
                    }

                    // Agreement check handler
                    if (agreementCheck) {
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
                    }
                });
            }

            // Status filter buttons
            document.querySelectorAll('.status-filter').forEach(button => {
                button.addEventListener('click', function() {
                    document.querySelectorAll('.status-filter').forEach(btn => {
                        btn.classList.remove('active', 'btn-secondary');
                        btn.classList.add('btn-outline-secondary');
                    });
                    this.classList.add('active', 'btn-secondary');
                    this.classList.remove('btn-outline-secondary');

                    const status = this.dataset.status;
                    document.querySelectorAll('.request-row').forEach(row => {
                        row.style.display = (status === 'all' || row.dataset.status === status) ? '' : 'none';
                    });
                });
            });

            // Search functionality
            const requestSearch = document.getElementById('requestSearch');
            if (requestSearch) {
                requestSearch.addEventListener('keyup', function() {
                    const searchTerm = this.value.toLowerCase();
                    document.querySelectorAll('.request-row').forEach(row => {
                        row.style.display = row.textContent.toLowerCase().includes(searchTerm) ? '' : 'none';
                    });
                });
            }

            // View button modal population
            document.querySelectorAll('.view-btn').forEach(button => {
                button.addEventListener('click', function() {
                    document.getElementById('modalRef').textContent = this.dataset.ref;
                    document.getElementById('modalEvent').textContent = this.dataset.event;
                    document.getElementById('modalDate').textContent = new Date(this.dataset.date).toLocaleDateString('en-US', { 
                        year: 'numeric', 
                        month: 'long', 
                        day: 'numeric' 
                    });
                    document.getElementById('modalLocation').textContent = this.dataset.location;
                    document.getElementById('modalDesc').textContent = this.dataset.desc || 'No description provided.';
                    
                    const status = this.dataset.status;
                    let badgeClass = 'bg-secondary';
                    if (status === 'Approved') badgeClass = 'bg-primary';
                    if (status === 'Rejected') badgeClass = 'bg-danger';
                    if (status === 'Returned') badgeClass = 'bg-success';
                    if (status === 'Pending') badgeClass = 'bg-warning text-dark';
                    document.getElementById('modalStatus').innerHTML = `<span class="badge ${badgeClass}">${status}</span>`;
                });
            });

            // Sidebar toggle
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarContainer = document.querySelector('.sidebar-container');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle && sidebarContainer && sidebarOverlay) {
                sidebarToggle.addEventListener('click', () => {
                    sidebarContainer.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });

                sidebarOverlay.addEventListener('click', () => {
                    sidebarContainer.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }

            if (window.innerWidth <= 768) {
                document.querySelectorAll('.sidebar-container .nav-link').forEach(link => {
                    link.addEventListener('click', () => {
                        sidebarContainer.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                    });
                });
            }
        });
    </script>
</body>
</html>
