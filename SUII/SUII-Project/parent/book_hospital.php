<?php
require_once '../config.php';
require_once '../auth_check.php';
checkParentAuth();

$parent_id = $_SESSION['user_id'];

// Get children for dropdown
$children = $conn->query("SELECT * FROM children WHERE parent_id = $parent_id");

// Get hospitals
$hospitals = $conn->query("SELECT * FROM hospitals ORDER BY name");

// Get vaccines
$vaccines = $conn->query("SELECT * FROM vaccines WHERE status = 'available'");

// Book appointment
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['book_appointment'])) {
    $child_id = $_POST['child_id'];
    $hospital_id = $_POST['hospital_id'];
    $vaccine_id = $_POST['vaccine_id'];
    $appointment_date = $_POST['appointment_date'];
    $appointment_time = $_POST['appointment_time'];
    
    $stmt = $conn->prepare("INSERT INTO appointments (child_id, hospital_id, vaccine_id, appointment_date, appointment_time) VALUES (?, ?, ?, ?, ?)");
    $stmt->bind_param("iiiss", $child_id, $hospital_id, $vaccine_id, $appointment_date, $appointment_time);
    
    if ($stmt->execute()) {
        $success = "Appointment booked successfully! Waiting for hospital approval.";
    } else {
        $error = "Failed to book appointment.";
    }
}
?>



<div class="container-fluid">
    <div class="row">
        <?php include 'parent_sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Book Hospital Appointment</h1>
            </div>

            <?php if(isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>
            
            <?php if(isset($error)): ?>
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <?php echo $error; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Book Vaccination Appointment</h5>
                </div>
                <div class="card-body">
                    <form method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="child_id" class="form-label">Select Child *</label>
                                <select class="form-select" id="child_id" name="child_id" required>
                                    <option value="">Select Child</option>
                                    <?php while($child = $children->fetch_assoc()): ?>
                                    <option value="<?php echo $child['id']; ?>">
                                        <?php echo htmlspecialchars($child['name']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label for="vaccine_id" class="form-label">Select Vaccine *</label>
                                <select class="form-select" id="vaccine_id" name="vaccine_id" required>
                                    <option value="">Select Vaccine</option>
                                    <?php while($vaccine = $vaccines->fetch_assoc()): ?>
                                    <option value="<?php echo $vaccine['id']; ?>">
                                        <?php echo htmlspecialchars($vaccine['name']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="hospital_id" class="form-label">Select Hospital *</label>
                                <select class="form-select" id="hospital_id" name="hospital_id" required>
                                    <option value="">Select Hospital</option>
                                    <?php while($hospital = $hospitals->fetch_assoc()): ?>
                                    <option value="<?php echo $hospital['id']; ?>">
                                        <?php echo htmlspecialchars($hospital['name']); ?> - 
                                        <?php echo htmlspecialchars($hospital['address']); ?>
                                    </option>
                                    <?php endwhile; ?>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label for="appointment_date" class="form-label">Appointment Date *</label>
                                <input type="date" class="form-control" id="appointment_date" name="appointment_date" min="<?php echo date('Y-m-d'); ?>" required>
                            </div>
                            <div class="col-md-3">
                                <label for="appointment_time" class="form-label">Appointment Time *</label>
                                <select class="form-select" id="appointment_time" name="appointment_time" required>
                                    <option value="">Select Time</option>
                                    <option value="09:00:00">09:00 AM</option>
                                    <option value="10:00:00">10:00 AM</option>
                                    <option value="11:00:00">11:00 AM</option>
                                    <option value="14:00:00">02:00 PM</option>
                                    <option value="15:00:00">03:00 PM</option>
                                    <option value="16:00:00">04:00 PM</option>
                                </select>
                            </div>
                        </div>
                        
                        <div class="d-grid">
                            <button type="submit" name="book_appointment" class="btn btn-primary btn-lg">
                                <i class="bi bi-calendar-plus"></i> Book Appointment
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Available Hospitals -->
            <div class="card mt-4">
                <div class="card-header">
                    <h5 class="mb-0">Available Hospitals</h5>
                </div>
                <div class="card-body">
                    <div class="row">
                        <?php 
                        $hospitals->data_seek(0); // Reset pointer
                        while($hospital = $hospitals->fetch_assoc()): 
                        ?>
                        <div class="col-md-6 mb-3">
                            <div class="card h-100">
                                <div class="card-body">
                                    <h6 class="card-title"><?php echo htmlspecialchars($hospital['name']); ?></h6>
                                    <p class="card-text">
                                        <i class="bi bi-geo-alt"></i> <?php echo htmlspecialchars($hospital['address']); ?><br>
                                        <i class="bi bi-telephone"></i> <?php echo htmlspecialchars($hospital['phone']); ?>
                                    </p>
                                </div>
                            </div>
                        </div>
                        <?php endwhile; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>