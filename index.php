<?php

// If already logged in, redirect to appropriate dashboard
if(isset($_SESSION['user_id'])) {
    switch($_SESSION['role']) {
        case 'admin':
            header("Location: admin/all_child_details.php");
            break;
        case 'parent':
            header("Location: parent/child_details.php");
            break;
        case 'hospital':
            header("Location: hospital/update_vaccine_status.php");
            break;
    }
    exit();
}

require_once 'config.php';
require_once 'auth_check.php';
redirectIfLoggedIn();

?>


<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8 text-center">
            <div class="welcome-section">
                <h1 class="display-4 mb-4">
                    <i class="bi bi-shield-plus text-primary"></i> SUII Vaccination System
                </h1>
                <p class="lead mb-4">
                    A complete E-Vaccination Management System for tracking and managing 
                    infant and child vaccinations digitally.
                </p>
                <div class="row mt-5">
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="bi bi-person-circle vaccine-icon"></i>
                                <h5 class="mt-3">Parent</h5>
                                <p class="text-muted">Register your child and manage vaccinations</p>
                                <a href="parent/login.php" class="btn btn-primary">Parent Login</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="bi bi-hospital vaccine-icon"></i>
                                <h5 class="mt-3">Hospital</h5>
                                <p class="text-muted">Manage appointments and update vaccination status</p>
                                <a href="hospital/login.php" class="btn btn-primary">Hospital Login</a>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-4 mb-3">
                        <div class="card h-100">
                            <div class="card-body">
                                <i class="bi bi-shield-check vaccine-icon"></i>
                                <h5 class="mt-3">Administrator</h5>
                                <p class="text-muted">System administration and management</p>
                                <a href="admin_login.php" class="btn btn-primary">Admin Login</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="mt-5">
                <h4>System Features</h4>
                <div class="row mt-4">
                    <div class="col-md-3">
                        <div class="feature-item">
                            <i class="bi bi-calendar-check feature-icon"></i>
                            <h6>Vaccination Tracking</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="feature-item">
                            <i class="bi bi-hospital feature-icon"></i>
                            <h6>Hospital Booking</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="feature-item">
                            <i class="bi bi-file-medical feature-icon"></i>
                            <h6>Digital Reports</h6>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="feature-item">
                            <i class="bi bi-bell feature-icon"></i>
                            <h6>Reminders</h6>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.feature-item {
    padding: 20px;
    text-align: center;
}

.feature-icon {
    font-size: 2.5rem;
    color: var(--primary-color);
    margin-bottom: 10px;
}

.feature-item h6 {
    color: var(--secondary-color);
    font-weight: 600;
}
</style>

<?php include 'footer.php'; ?>