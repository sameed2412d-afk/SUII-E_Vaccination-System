<?php
require_once '../config.php';
require_once '../auth_check.php';
checkAdminAuth();

// Get statistics
$total_children = $conn->query("SELECT COUNT(*) as count FROM children")->fetch_assoc()['count'];
$total_parents = $conn->query("SELECT COUNT(*) as count FROM users WHERE role = 'parent'")->fetch_assoc()['count'];
$total_hospitals = $conn->query("SELECT COUNT(*) as count FROM hospitals")->fetch_assoc()['count'];
$pending_requests = $conn->query("SELECT COUNT(*) as count FROM appointments WHERE status = 'pending'")->fetch_assoc()['count'];
?>


<style>
    .stat-card {
        padding: 20px;
        border-radius: 10px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center; /* Center align content */
        display: flex; /* Use flexbox for alignment */
        flex-direction: column; /* Stack items vertically */
        justify-content: center; /* Center items vertically */
        align-items: center; /* Center items horizontally */
    }

    .stat-card h6 {
        margin-bottom: 10px; 
    }

    .stat-card i {
        margin-top: 10px; 
        font-size: 2.5rem; 
    }

    .bg-vaccine-blue {
        background-color: #007bff;
    }

    .bg-vaccine-green {
        background-color: #28a745;
    }

    .bg-primary {
        background-color: #007bff;
    }

    .bg-warning {
        background-color: #ffc107;
    }

    .card {
        border: none;
        border-radius: 10px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }

    .card-header {
        background-color: #f8f9fa;
        border-bottom: 1px solid #e9ecef;
        border-radius: 10px 10px 0 0;
    }

    .card-body {
        padding: 20px;
    }

    .btn {
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 10px;
    }

    .list-group-item {
        border: none;
        border-bottom: 1px solid #e9ecef;
    }

    .list-group-item:last-child {
        border-bottom: none;
    }

    .stat-card-container {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-wrap: wrap;
        gap: 20px;
        height: calc(100vh - 100px); /* Adjust height to center content vertically */
        margin-top: 0; /* Remove extra margin */
    }

    .col-md-9 {
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
    }
</style>

<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Admin Dashboard</h1>
            </div>

            <!-- Statistics Cards -->
            <div class="stat-card-container">
                <div class="stat-card bg-vaccine-blue">
                    <h6>Total Children</h6>
                    <h2><?php echo $total_children; ?></h2>
                    <i class="bi bi-people-fill"></i>
                </div>
                <div class="stat-card bg-vaccine-green">
                    <h6>Registered Parents</h6>
                    <h2><?php echo $total_parents; ?></h2>
                    <i class="bi bi-person-badge"></i>
                </div>
                <div class="stat-card bg-primary">
                    <h6>Hospitals</h6>
                    <h2><?php echo $total_hospitals; ?></h2>
                    <i class="bi bi-hospital"></i>
                </div>
                <div class="stat-card bg-warning">
                    <h6>Pending Requests</h6>
                    <h2><?php echo $pending_requests; ?></h2>
                    <i class="bi bi-clock-history"></i>
                </div>
            </div>

            <!-- Quick Actions and Recent Activity -->
            <div class="row mt-4">
                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Quick Actions</h5>
                        </div>
                        <div class="card-body">
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <a href="parent_requests.php" class="btn btn-primary w-100">
                                        <i class="bi bi-person-check"></i> Approve Parents
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="add_hospital.php" class="btn btn-success w-100">
                                        <i class="bi bi-hospital"></i> Add Hospital
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="vaccine_list.php" class="btn btn-info w-100">
                                        <i class="bi bi-shield-check"></i> Manage Vaccines
                                    </a>
                                </div>
                                <div class="col-md-6">
                                    <a href="booking_details.php" class="btn btn-warning w-100">
                                        <i class="bi bi-journal-check"></i> View Bookings
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-md-6">
                    <div class="card">
                        <div class="card-header">
                            <h5 class="mb-0">Recent Activity</h5>
                        </div>
                        <div class="card-body">
                            <div class="list-group">
                                <?php
                                $recent_query = "SELECT 'appointment' as type, a.created_at, c.name as child_name 
                                                FROM appointments a 
                                                JOIN children c ON a.child_id = c.id 
                                                UNION ALL
                                                SELECT 'registration' as type, created_at, name as child_name 
                                                FROM children 
                                                ORDER BY created_at DESC LIMIT 5";
                                $recent_result = $conn->query($recent_query);
                                
                                while($activity = $recent_result->fetch_assoc()):
                                ?>
                                <div class="list-group-item list-group-item-action">
                                    <div class="d-flex w-100 justify-content-between">
                                        <h6 class="mb-1">
                                            <?php if($activity['type'] == 'appointment'): ?>
                                                <i class="bi bi-calendar-check text-primary"></i> New Appointment
                                            <?php else: ?>
                                                <i class="bi bi-person-plus text-success"></i> Child Registered
                                            <?php endif; ?>
                                        </h6>
                                        <small><?php echo date('H:i', strtotime($activity['created_at'])); ?></small>
                                    </div>
                                    <p class="mb-1"><?php echo htmlspecialchars($activity['child_name']); ?></p>
                                    <small class="text-muted"><?php echo date('d M Y', strtotime($activity['created_at'])); ?></small>
                                </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>