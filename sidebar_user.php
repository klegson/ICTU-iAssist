<div class="card shadow-sm border-0 h-100">
    <div class="card-body p-3">
        <h6 class="text-uppercase text-secondary small fw-bold mb-3 ls-1">User Menu</h6>

        <div class="list-group list-group-flush">
            <a href="db_user.php" class="list-group-item list-group-item-action border-0 rounded mb-1 <?php echo basename($_SERVER['PHP_SELF']) == 'db_user.php' ? 'active' : ''; ?>">
                <i class="bi bi-speedometer2 me-2"></i> Dashboard
            </a>

            <a href="db_user.php" class="list-group-item list-group-item-action border-0 rounded mb-1">
                <i class="bi bi-plus-circle me-2"></i> Submit Ticket
            </a>

            <a href="#" class="list-group-item list-group-item-action border-0 rounded mb-1 text-muted">
                <i class="bi bi-person-circle me-2"></i> My Profile
            </a>

            <a href="logout.php" class="list-group-item list-group-item-action border-0 rounded mb-1 text-danger mt-3">
                <i class="bi bi-box-arrow-left me-2"></i> Logout
            </a>
        </div>
    </div>
</div>