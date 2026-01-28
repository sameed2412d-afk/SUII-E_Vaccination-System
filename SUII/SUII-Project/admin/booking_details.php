<?php
require_once '../config.php';
require_once '../auth_check.php';
checkAdminAuth();

$query = "SELECT a.*, c.name as child_name, v.name as vaccine_name, 
          h.name as hospital_name, u.name as parent_name, u.phone as parent_phone
          FROM appointments a
          JOIN children c ON a.child_id = c.id
          JOIN vaccines v ON a.vaccine_id = v.id
          JOIN hospitals h ON a.hospital_id = h.id
          JOIN users u ON c.parent_id = u.id
          ORDER BY a.appointment_date DESC, a.appointment_time DESC";
$result = $conn->query($query);
?>



<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Booking Details</h1>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">All Appointments</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Child</th>
                                    <th>Vaccine</th>
                                    <th>Hospital</th>
                                    <th>Date & Time</th>
                                    <th>Parent</th>
                                    <th>Parent Phone</th>
                                    <th>Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td>#<?php echo str_pad($row['id'], 6, '0', STR_PAD_LEFT); ?></td>
                                    <td><?php echo htmlspecialchars($row['child_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['vaccine_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['hospital_name']); ?></td>
                                    <td>
                                        <?php echo date('d M Y', strtotime($row['appointment_date'])); ?><br>
                                        <small><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['parent_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['parent_phone']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            switch($row['status']) {
                                                case 'approved': echo 'success'; break;
                                                case 'pending': echo 'warning'; break;
                                                case 'rejected': echo 'danger'; break;
                                                case 'completed': echo 'info'; break;
                                                default: echo 'secondary';
                                            }
                                        ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>