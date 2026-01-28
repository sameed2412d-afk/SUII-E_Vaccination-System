<?php
require_once '../config.php';
require_once '../auth_check.php';
checkAdminAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_status'])) {
    $vaccine_id = $_POST['vaccine_id'];
    $status = $_POST['status'];
    
    $stmt = $conn->prepare("UPDATE vaccines SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $vaccine_id);
    $stmt->execute();
}

$query = "SELECT * FROM vaccines ORDER BY name";
$result = $conn->query($query);
?>



<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Vaccine Availability</h1>
            </div>

            <div class="row mb-4">
                <div class="col-md-3">
                    <div class="stat-card bg-vaccine-blue">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Available Vaccines</h6>
                                <h2 class="mb-0">
                                    <?php 
                                    $available = $conn->query("SELECT COUNT(*) as count FROM vaccines WHERE status = 'available'")->fetch_assoc()['count'];
                                    echo $available;
                                    ?>
                                </h2>
                            </div>
                            <i class="bi bi-shield-check" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="stat-card bg-vaccine-green">
                        <div class="d-flex justify-content-between align-items-center">
                            <div>
                                <h6 class="mb-0">Total Vaccines</h6>
                                <h2 class="mb-0">
                                    <?php 
                                    $total = $conn->query("SELECT COUNT(*) as count FROM vaccines")->fetch_assoc()['count'];
                                    echo $total;
                                    ?>
                                </h2>
                            </div>
                            <i class="bi bi-shield" style="font-size: 2.5rem;"></i>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Vaccine Status</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Vaccine Name</th>
                                    <th>Status</th>
                                    <th>Created Date</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php echo $row['status'] == 'available' ? 'success' : 'danger'; ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="vaccine_id" value="<?php echo $row['id']; ?>">
                                            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()" style="width: auto; display: inline-block;">
                                                <option value="available" <?php echo $row['status'] == 'available' ? 'selected' : ''; ?>>Available</option>
                                                <option value="unavailable" <?php echo $row['status'] == 'unavailable' ? 'selected' : ''; ?>>Unavailable</option>
                                            </select>
                                            <input type="hidden" name="update_status" value="1">
                                        </form>
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