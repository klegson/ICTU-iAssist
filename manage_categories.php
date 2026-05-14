<?php
session_start();
require 'db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Officer') {
    header("Location: index.php");
    exit;
}

$msg = "";
$msgType = "";

if (isset($_POST['toggle_express'])) {
    $categoryId = $_POST['category_id'];
    $isEligible = isset($_POST['is_express_eligible']) ? 1 : 0;

    $sql = "UPDATE category SET is_express_eligible = ? WHERE categoryId = ?";
    $stmt = $pdo->prepare($sql);
    if ($stmt->execute([$isEligible, $categoryId])) {
        $msg = "Category updated successfully.";
        $msgType = "success";
    } else {
        $msg = "Error updating category.";
        $msgType = "danger";
    }
}

$categories = $pdo->query("SELECT * FROM category ORDER BY categoryType, categoryName")->fetchAll();

$pageTitle = 'Manage Categories - DepEd Helpdesk';
include 'head.php';
?>

<body>
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <div class="d-flex flex-column flex-md-row" style="min-height: 100vh;">
        <?php include 'sidebar.php'; ?>

        <div class="flex-grow-1 main-content" style="min-height: 100vh; overflow-y: auto;">
            <?php include 'header.php'; ?>

            <div class="container-fluid py-5 px-5">

                <div class="mb-5">
                    <h2 class="fw-bold text-dark mb-1"><i class="bi bi-tags-fill text-primary me-2"></i>Manage Categories</h2>
                    <p class="text-muted">Toggle which categories are eligible for Express Lane (skip dispatcher).</p>
                </div>

                <?php if ($msg): ?>
                    <div class="alert alert-<?php echo $msgType; ?> alert-dismissible fade show" role="alert">
                        <?php echo $msg; ?>
                        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                    </div>
                <?php endif; ?>

                <div class="custom-card p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h5 class="fw-bold text-dark mb-0"><i class="bi bi-lightning-fill text-warning me-2"></i>Express Lane Eligibility</h5>
                        <span class="badge bg-express">Express = Separate Priority</span>
                    </div>

                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead style="border-bottom: 2px solid #f0f2f5;">
                                <tr>
                                    <th class="text-muted small fw-bold pb-3">CATEGORY TYPE</th>
                                    <th class="text-muted small fw-bold pb-3">CATEGORY NAME</th>
                                    <th class="text-muted small fw-bold pb-3 text-center">EXPRESS ELIGIBLE</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                $currentType = "";
                                foreach ($categories as $cat) {
                                    if ($currentType !== $cat['categoryType']) {
                                        if ($currentType !== "") echo "<tr><td colspan='3' style='padding: 10px 0;'></td></tr>";
                                        $currentType = $cat['categoryType'];
                                    }

                                    echo "<tr style='border-bottom: 1px solid #f8f9fa;'>";
                                    echo "<td class='py-3'><span class='badge bg-light text-dark border'>" . htmlspecialchars($cat['categoryType']) . "</span></td>";
                                    echo "<td class='py-3 fw-bold text-dark'>" . htmlspecialchars($cat['categoryName']) . "</td>";
                                    echo "<td class='py-3 text-center'>";
                                    echo "<form method='POST' class='d-inline'>";
                                    echo "<input type='hidden' name='category_id' value='" . $cat['categoryId'] . "'>";
                                    echo "<div class='form-check form-switch d-inline-block'>";
                                    echo "<input class='form-check-input' type='checkbox' name='is_express_eligible' value='1' " . ($cat['is_express_eligible'] ? 'checked' : '') . " onchange='this.form.submit()'>";
                                    echo "</div>";
                                    echo "<input type='hidden' name='toggle_express' value='1'>";
                                    echo "</form>";
                                    echo "</td>";
                                    echo "</tr>";
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>

                    <div class="mt-4 p-3 bg-light rounded">
                        <h6 class="fw-bold text-dark mb-2"><i class="bi bi-info-circle me-1"></i>How it works:</h6>
                        <ul class="mb-0 small text-muted">
                            <li>Categories marked as "Express Eligible" will show a lightning bolt toggle during ticket creation</li>
                            <li>Express tickets bypass Officers and notify all Technicians directly</li>
                            <li>Express is a separate priority level (distinct from High)</li>
                            <li>Technicians can "Grab" and "Release" Express tickets with a mandatory reason</li>
                        </ul>
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
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
