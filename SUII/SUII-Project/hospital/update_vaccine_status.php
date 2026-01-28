<?php
require_once '../config.php';
require_once '../auth_check.php';
checkHospitalAuth();

$hospital_id = $_SESSION['user_id'];

// Get hospital appointments
$query = "SELECT a.*, c.name as child_name, v.name as vaccine_name, 
          u.name as parent_name, u.phone as parent_phone
          FROM appointments a
          JOIN children c ON a.child_id = c.id
          JOIN vaccines v ON a.vaccine_id = v.id
          JOIN users u ON c.parent_id = u.id
          JOIN hospitals h ON a.hospital_id = h.id
          WHERE h.user_id = ? AND a.status IN ('approved', 'pending')
          ORDER BY a.appointment_date ASC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $hospital_id);
$stmt->execute();
$result = $stmt->get_result();

// Update status
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $appointment_id = $_POST['appointment_id'];
    $status = $_POST['status'];
    $notes = $_POST['notes'] ?? '';
    
    $stmt = $conn->prepare("UPDATE appointments SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $appointment_id);
    $stmt->execute();
    
    // If completed, add to vaccination records
    if ($status === 'completed') {
        // Get appointment details
        $appointment_query = "SELECT * FROM appointments WHERE id = ?";
        $appointment_stmt = $conn->prepare($appointment_query);
        $appointment_stmt->bind_param("i", $appointment_id);
        $appointment_stmt->execute();
        $appointment = $appointment_stmt->get_result()->fetch_assoc();
        
        // Insert into vaccination records
        $record_stmt = $conn->prepare("INSERT INTO vaccination_records (child_id, vaccine_id, hospital_id, vaccination_date, status, notes) VALUES (?, ?, ?, CURDATE(), 'given', ?)");
        $record_stmt->bind_param("iiis", $appointment['child_id'], $appointment['vaccine_id'], $appointment['hospital_id'], $notes);
        $record_stmt->execute();
    }
    
    $success = "Status updated successfully!";
}
?>



<div class="container-fluid">
    <div class="row">
        <!-- Hospital Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="update_vaccine_status.php">
                            <i class="bi bi-clipboard-check"></i> Update Status
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Update Vaccination Status</h1>
            </div>

            <?php if(isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Today's Appointments</h5>
                </div>
                <div class="card-body">
                    <?php if($result->num_rows === 0): ?>
                    <div class="alert alert-info">
                        No appointments scheduled for today.
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Time</th>
                                    <th>Child</th>
                                    <th>Parent</th>
                                    <th>Vaccine</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('h:i A', strtotime($row['appointment_time'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['child_name']); ?></td>
                                    <td>
                                        <?php echo htmlspecialchars($row['parent_name']); ?><br>
                                        <small><?php echo htmlspecialchars($row['parent_phone']); ?></small>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['vaccine_name']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            echo $row['status'] == 'approved' ? 'success' : 'warning';
                                        ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <button class="btn btn-sm btn-primary update-status" 
                                                data-id="<?php echo $row['id']; ?>"
                                                data-child="<?php echo htmlspecialchars($row['child_name']); ?>"
                                                data-vaccine="<?php echo htmlspecialchars($row['vaccine_name']); ?>">
                                            Update Status
                                        </button>
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

<!-- Update Status Modal -->
<div class="modal fade" id="updateStatusModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Update Vaccination Status</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Child</label>
                        <input type="text" class="form-control" id="modal_child" readonly>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Vaccine</label>
                        <input type="text" class="form-control" id="modal_vaccine" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">Status *</label>
                        <select class="form-select" id="status" name="status" required>
                            <option value="approved">Approved</option>
                            <option value="completed">Completed</option>
                            <option value="rejected">Rejected</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="notes" class="form-label">Notes (Optional)</label>
                        <textarea class="form-control" id="notes" name="notes" rows="3"></textarea>
                    </div>
                    <input type="hidden" id="appointment_id" name="appointment_id">
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-primary">Update Status</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.update-status').click(function() {
        const appointmentId = $(this).data('id');
        const childName = $(this).data('child');
        const vaccineName = $(this).data('vaccine');
        
        $('#modal_child').val(childName);
        $('#modal_vaccine').val(vaccineName);
        $('#appointment_id').val(appointmentId);
        
        $('#updateStatusModal').modal('show');
    });
});
</script>

<?php include '../footer.php'; ?>