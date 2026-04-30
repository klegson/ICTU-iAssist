<?php
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");

session_start();
require 'db.php';

// Create starlink_inventory table if not exists
$pdo->exec("CREATE TABLE IF NOT EXISTS `starlink_inventory` (
  `id` int NOT NULL AUTO_INCREMENT,
  `serial_number` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `model` varchar(100) COLLATE utf8mb4_general_ci NOT NULL,
  `status` enum('Available','Borrowed','Under Maintenance') COLLATE utf8mb4_general_ci DEFAULT 'Available',
  `location` varchar(255) COLLATE utf8mb4_general_ci DEFAULT NULL,
  `notes` text COLLATE utf8mb4_general_ci,
  `created_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` timestamp NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  PRIMARY KEY (`id`),
  UNIQUE KEY `serial_number` (`serial_number`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Officer') {
    header("Location: login.php");
    exit;
}

$msg = "";
$msgType = "";

// Handle add/edit device
if (isset($_POST['save_device'])) {
    $deviceId = $_POST['deviceId'] ?? null;
    $serialNumber = trim($_POST['serial_number']);
    $model = trim($_POST['model']);
    $status = $_POST['status'];
    $location = trim($_POST['location']);
    $notes = trim($_POST['notes']);

    if ($deviceId) {
        $stmt = $pdo->prepare("UPDATE starlink_inventory SET serial_number = ?, model = ?, status = ?, location = ?, notes = ? WHERE id = ?");
        if ($stmt->execute([$serialNumber, $model, $status, $location, $notes, $deviceId])) {
            $msg = "Device updated successfully.";
            $msgType = "alert-success";
        } else {
            $msg = "Error updating device.";
            $msgType = "alert-danger";
        }
    } else {
        $stmt = $pdo->prepare("INSERT INTO starlink_inventory (serial_number, model, status, location, notes) VALUES (?, ?, ?, ?, ?)");
        if ($stmt->execute([$serialNumber, $model, $status, $location, $notes])) {
            $msg = "Device added successfully.";
            $msgType = "alert-success";
        } else {
            $msg = "Error adding device.";
            $msgType = "alert-danger";
        }
    }
}

// Handle delete
if (isset($_GET['delete']) && is_numeric($_GET['delete'])) {
    $stmt = $pdo->prepare("DELETE FROM starlink_inventory WHERE id = ?");
    if ($stmt->execute([$_GET['delete']])) {
        $msg = "Device deleted successfully.";
        $msgType = "alert-success";
    }
}

// Get statistics
$totalDevices = $pdo->query("SELECT COUNT(*) FROM starlink_inventory")->fetchColumn();
$available = $pdo->query("SELECT COUNT(*) FROM starlink_inventory WHERE status = 'Available'")->fetchColumn();
$borrowed = $pdo->query("SELECT COUNT(*) FROM starlink_inventory WHERE status = 'Borrowed'")->fetchColumn();
$maintenance = $pdo->query("SELECT COUNT(*) FROM starlink_inventory WHERE status = 'Under Maintenance'")->fetchColumn();

// Get all devices
$devices = $pdo->query("SELECT * FROM starlink_inventory ORDER BY created_at DESC")->fetchAll();

$pageTitle = 'Starlink Inventory - DepEd Helpdesk';
include 'head.php';
?>

<body class="bg-light">
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">
        <div style="width: 280px; flex-shrink: 0;"></div>
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 main-content" style="min-height: 100vh; overflow-y: auto;">
            <?php require 'header.php'; ?>

            <div class="container-fluid py-5 px-5">
                <div class="mb-4">
                    <h2 class="fw-bold text-dark mb-1"><i class="bi bi-router-fill me-2"></i>Starlink Device Inventory</h2>
                    <p class="text-muted">Manage Starlink devices and track their status.</p>
                </div>

                <?php if ($msg): ?>
                    <div class="alert <?php echo $msgType; ?> alert-dismissible fade show" role="alert">
                        <?php echo $msg; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                <?php endif; ?>

                <!-- Statistics Cards -->
                <div class="row mb-4 g-3">
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 border-top border-primary border-4 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small fw-bold mb-1">TOTAL DEVICES</div>
                                        <div class="fs-2 fw-bold text-dark"><?php echo $totalDevices; ?></div>
                                    </div>
                                    <i class="bi bi-router fs-1 text-primary opacity-25"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 border-top border-success border-4 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small fw-bold mb-1">AVAILABLE</div>
                                        <div class="fs-2 fw-bold text-success"><?php echo $available; ?></div>
                                    </div>
                                    <i class="bi bi-check-circle fs-1 text-success opacity-25"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 border-top border-warning border-4 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small fw-bold mb-1">BORROWED</div>
                                        <div class="fs-2 fw-bold text-warning"><?php echo $borrowed; ?></div>
                                    </div>
                                    <i class="bi bi-box-arrow-up-right fs-1 text-warning opacity-25"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="card shadow-sm border-0 border-top border-danger border-4 h-100">
                            <div class="card-body">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <div class="text-muted small fw-bold mb-1">MAINTENANCE</div>
                                        <div class="fs-2 fw-bold text-danger"><?php echo $maintenance; ?></div>
                                    </div>
                                    <i class="bi bi-tools fs-1 text-danger opacity-25"></i>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card shadow-sm border-0">
                    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                        <h6 class="fw-bold m-0 text-dark">Device Inventory</h6>
                        <button class="btn btn-deped-primary btn-sm" data-bs-toggle="modal" data-bs-target="#deviceModal">
                            <i class="bi bi-plus-lg me-1"></i>Add Device
                        </button>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="ps-4">Serial Number</th>
                                        <th>Model</th>
                                        <th>Status</th>
                                        <th>Location</th>
                                        <th>Notes</th>
                                        <th class="text-end pe-4">Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (count($devices) > 0): ?>
                                        <?php foreach ($devices as $device): ?>
                                            <tr>
                                                <td class="ps-4 fw-bold text-dark"><?php echo htmlspecialchars($device['serial_number']); ?></td>
                                                <td><?php echo htmlspecialchars($device['model']); ?></td>
                                                <td>
                                                    <?php
                                                    $badgeClass = 'bg-secondary';
                                                    if ($device['status'] == 'Available') $badgeClass = 'bg-success';
                                                    if ($device['status'] == 'Borrowed') $badgeClass = 'bg-warning text-dark';
                                                    if ($device['status'] == 'Under Maintenance') $badgeClass = 'bg-danger';
                                                    ?>
                                                    <span class="badge rounded-pill <?php echo $badgeClass; ?>"><?php echo $device['status']; ?></span>
                                                </td>
                                                <td class="text-muted"><?php echo htmlspecialchars($device['location']); ?></td>
                                                <td class="text-muted small"><?php echo htmlspecialchars(substr($device['notes'] ?? '', 0, 50)); ?></td>
                                                <td class="text-end pe-4">
                                                    <button class="btn btn-sm btn-outline-primary me-1" onclick="editDevice(<?php echo htmlspecialchars(json_encode($device)); ?>)">
                                                        <i class="bi bi-pencil"></i>
                                                    </button>
                                                    <a href="?delete=<?php echo $device['id']; ?>" class="btn btn-sm btn-outline-danger" onclick="return confirm('Delete this device?')">
                                                        <i class="bi bi-trash"></i>
                                                    </a>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <tr>
                                            <td colspan="6" class="text-center py-5 text-muted">No devices found. Add a device to get started.</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Add/Edit Device Modal -->
    <div class="modal fade" id="deviceModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="POST">
                    <input type="hidden" name="deviceId" id="deviceId">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalTitle">Add Device</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label class="form-label small fw-bold">SERIAL NUMBER</label>
                            <input type="text" name="serial_number" id="serialNumber" class="form-control" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">MODEL</label>
                            <input type="text" name="model" id="model" class="form-control" placeholder="e.g., Starlink Standard" required>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">STATUS</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="Available">Available</option>
                                <option value="Borrowed">Borrowed</option>
                                <option value="Under Maintenance">Under Maintenance</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">LOCATION</label>
                            <input type="text" name="location" id="location" class="form-control" placeholder="e.g., ICT Office">
                        </div>
                        <div class="mb-3">
                            <label class="form-label small fw-bold">NOTES</label>
                            <textarea name="notes" id="notes" class="form-control" rows="3"></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" name="save_device" class="btn btn-deped-primary">Save Device</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function editDevice(device) {
            document.getElementById('modalTitle').textContent = 'Edit Device';
            document.getElementById('deviceId').value = device.id;
            document.getElementById('serialNumber').value = device.serial_number;
            document.getElementById('model').value = device.model;
            document.getElementById('status').value = device.status;
            document.getElementById('location').value = device.location || '';
            document.getElementById('notes').value = device.notes || '';
            new bootstrap.Modal(document.getElementById('deviceModal')).show();
        }

        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebarContainer = document.querySelector('.sidebar-container');
            const sidebarOverlay = document.getElementById('sidebarOverlay');

            if (sidebarToggle && sidebarContainer && sidebarOverlay) {
                sidebarToggle.addEventListener('click', function() {
                    sidebarContainer.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });

                sidebarOverlay.addEventListener('click', function() {
                    sidebarContainer.classList.remove('show');
                    sidebarOverlay.classList.remove('show');
                });
            }

            if (window.innerWidth <= 768) {
                document.querySelectorAll('.sidebar-container .nav-link').forEach(function(link) {
                    link.addEventListener('click', function() {
                        sidebarContainer.classList.remove('show');
                        sidebarOverlay.classList.remove('show');
                    });
                });
            }
        });
    </script>
</body>
</html>
