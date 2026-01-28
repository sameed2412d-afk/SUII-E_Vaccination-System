<!-- Parent Sidebar -->
<div class="col-md-3 col-lg-2 sidebar">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'child_details.php' ? 'active' : ''; ?>" href="child_details.php">
                    <i class="bi bi-person-badge"></i> Child Details
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'vaccination_dates_parent.php' ? 'active' : ''; ?>" href="vaccination_dates_parent.php">
                    <i class="bi bi-calendar-check"></i> Vaccination Dates
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'book_hospital.php' ? 'active' : ''; ?>" href="book_hospital.php">
                    <i class="bi bi-hospital"></i> Book Hospital
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'request_hospital.php' ? 'active' : ''; ?>" href="request_hospital.php">
                    <i class="bi bi-send-check"></i> Request Hospital
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'vaccination_report_parent.php' ? 'active' : ''; ?>" href="vaccination_report_parent.php">
                    <i class="bi bi-file-medical"></i> Vaccination Report
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'my_profile_parent.php' ? 'active' : ''; ?>" href="my_profile_parent.php">
                    <i class="bi bi-person-circle"></i> My Profile
                </a>
            </li>
        </ul>
    </div>
</div>