<?php
require_once '../config.php';
require_once '../auth_check.php';
checkParentAuth();

$parent_id = $_SESSION['user_id'];

// Add new child
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_child'])) {
    $child_name = $_POST['child_name'];
    $dob = $_POST['dob'];
    
    $stmt = $conn->prepare("INSERT INTO children (parent_id, name, dob) VALUES (?, ?, ?)");
    $stmt->bind_param("iss", $parent_id, $child_name, $dob);
    $stmt->execute();
    $success = "Child added successfully!";
}

// Get children list
$query = "SELECT * FROM children WHERE parent_id = ? ORDER BY created_at DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $parent_id);
$stmt->execute();
$result = $stmt->get_result();
?>



<div class="container-fluid">
    <div class="row">
        <!-- Parent Sidebar -->
        <div class="col-md-3 col-lg-2 sidebar">
            <div class="position-sticky pt-3">
                <ul class="nav flex-column">
                    <li class="nav-item">
                        <a class="nav-link active" href="child_details.php">
                            <i class="bi bi-person-badge"></i> Child Details
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vaccination_dates_parent.php">
                            <i class="bi bi-calendar-check"></i> Vaccination Dates
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="book_hospital.php">
                            <i class="bi bi-hospital"></i> Book Hospital
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="request_hospital.php">
                            <i class="bi bi-send-check"></i> Request Hospital
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="vaccination_report_parent.php">
                            <i class="bi bi-file-medical"></i> Vaccination Report
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="my_profile_parent.php">
                            <i class="bi bi-person-circle"></i> My Profile
                        </a>
                    </li>
                </ul>
            </div>
        </div>

        <!-- Main Content -->
        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Child Details</h1>
                <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addChildModal">
                    <i class="bi bi-plus-circle"></i> Add Child
                </button>
            </div>

            <?php if(isset($success)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $success; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="row">
                <?php while($row = $result->fetch_assoc()): 
                    $age = date_diff(date_create($row['dob']), date_create('today'))->y;
                ?>
                <div class="col-md-4">
                    <div class="card">
                        <div class="card-body text-center">
                            <div class="mb-3">
                                <i class="bi bi-person-circle vaccine-icon"></i>
                            </div>
                            <h5><?php echo htmlspecialchars($row['name']); ?></h5>
                            <p class="text-muted">
                                Date of Birth: <?php echo date('d M Y', strtotime($row['dob'])); ?><br>
                                Age: <?php echo $age; ?> years
                            </p>
                            <div class="btn-group">
                                <button class="btn btn-sm btn-outline-primary edit-child" data-id="<?php echo $row['id']; ?>">
                                    <i class="bi bi-pencil"></i> Edit
                                </button>
                                <button class="btn btn-sm btn-outline-danger delete-child" data-id="<?php echo $row['id']; ?>">
                                    <i class="bi bi-trash"></i> Delete
                                </button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endwhile; ?>
            </div>
        </div>
    </div>
</div>

<!-- Add Child Modal -->
<div class="modal fade" id="addChildModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add Child</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form method="POST">
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="child_name" class="form-label">Child Name *</label>
                        <input type="text" class="form-control" id="child_name" name="child_name" required>
                    </div>
                    <div class="mb-3">
                        <label for="dob" class="form-label">Date of Birth *</label>
                        <input type="date" class="form-control" id="dob" name="dob" required>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" name="add_child" class="btn btn-primary">Add Child</button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
$(document).ready(function() {
    $('.edit-child').click(function() {
        const childId = $(this).data('id');
        alert('Edit child ID: ' + childId);
    });
    
    $('.delete-child').click(function() {
        const childId = $(this).data('id');
        if(confirm('Are you sure you want to delete this child?')) {
            // In real system, you would send AJAX request to delete
            alert('Child deleted (simulated)');
        }
    });
});
</script>

<?php include '../footer.php'; ?>