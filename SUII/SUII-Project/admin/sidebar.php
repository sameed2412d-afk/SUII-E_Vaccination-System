<!-- Admin Sidebar -->
<div class="col-md-2">
    <div class="position-sticky pt-3">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'dashboard.php' ? 'active' : ''; ?>" href="dashboard.php">
                    <i class="bi bi-speedometer2"></i> Dashboard
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'all_child_details.php' ? 'active' : ''; ?>" href="all_child_details.php">
                    <i class="bi bi-people-fill"></i> All Children
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'vaccination_dates.php' ? 'active' : ''; ?>" href="vaccination_dates.php">
                    <i class="bi bi-calendar-date"></i> Vaccination Dates
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'vaccination_report.php' ? 'active' : ''; ?>" href="vaccination_report.php">
                    <i class="bi bi-file-text"></i> Vaccination Report
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'vaccine_list.php' ? 'active' : ''; ?>" href="vaccine_list.php">
                    <i class="bi bi-shield-check"></i> Vaccine List
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'parent_requests.php' ? 'active' : ''; ?>" href="parent_requests.php">
                    <i class="bi bi-person-check"></i> Parent Requests
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'add_hospital.php' ? 'active' : ''; ?>" href="add_hospital.php">
                    <i class="bi bi-hospital"></i> Add Hospital
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'list_hospitals.php' ? 'active' : ''; ?>" href="list_hospitals.php">
                    <i class="bi bi-list-ul"></i> Hospital List
                </a>
            </li>
            <li class="nav-item">
                <a class="nav-link <?php echo basename($_SERVER['PHP_SELF']) == 'booking_details.php' ? 'active' : ''; ?>" href="booking_details.php">
                    <i class="bi bi-journal-check"></i> Booking Details
                </a>
            </li>
        </ul>
    </div>
</div>