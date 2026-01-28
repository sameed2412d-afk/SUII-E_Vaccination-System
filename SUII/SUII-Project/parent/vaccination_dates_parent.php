<?php
require_once '../config.php';
require_once '../auth_check.php';
checkParentAuth();

$parent_id = $_SESSION['user_id'];

$query = "SELECT a.*, c.name as child_name, v.name as vaccine_name, 
          h.name as hospital_name
          FROM appointments a
          JOIN children c ON a.child_id = c.id
          JOIN vaccines v ON a.vaccine_id = v.id
          JOIN hospitals h ON a.hospital_id = h.id
          WHERE c.parent_id = ? AND a.appointment_date >= CURDATE()
          ORDER BY a.appointment_date ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $parent_id);
$stmt->execute();
$result = $stmt->get_result();
?>


<div class="container-fluid">
    <div class="row">
        <?php include 'parent_sidebar.php'; // Create similar sidebar ?>

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Upcoming Vaccination Dates</h1>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Your Child's Vaccination Schedule</h5>
                </div>
                <div class="card-body">
                    <?php if($result->num_rows === 0): ?>
                    <div class="alert alert-info">
                        No upcoming vaccination appointments found.
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Child</th>
                                    <th>Vaccine</th>
                                    <th>Hospital</th>
                                    <th>Status</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($row['appointment_date'])); ?></td>
                                    <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['child_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['vaccine_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['hospital_name']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            switch($row['status']) {
                                                case 'approved': echo 'success'; break;
                                                case 'pending': echo 'warning'; break;
                                                default: echo 'secondary';
                                            }
                                        ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <?php if($row['status'] == 'pending'): ?>
                                        <button class="btn btn-sm btn-warning cancel-appointment" data-id="<?php echo $row['id']; ?>">
                                            Cancel
                                        </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                                <?php endwhile; ?>
                            </tbody>
                        </table>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.cancel-appointment').click(function() {
        const appointmentId = $(this).data('id');
        if(confirm('Are you sure you want to cancel this appointment?')) {
            alert('Appointment cancelled (simulated)');
        }
    });
});
</script>

<?php include '../footer.php'; ?>