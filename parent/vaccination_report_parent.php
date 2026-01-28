<?php
require_once '../config.php';
require_once '../auth_check.php';
checkParentAuth();

$parent_id = $_SESSION['user_id'];

$query = "SELECT vr.*, c.name as child_name, v.name as vaccine_name, 
          h.name as hospital_name
          FROM vaccination_records vr
          JOIN children c ON vr.child_id = c.id
          JOIN vaccines v ON vr.vaccine_id = v.id
          JOIN hospitals h ON vr.hospital_id = h.id
          WHERE c.parent_id = ?
          ORDER BY vr.vaccination_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("i", $parent_id);
$stmt->execute();
$result = $stmt->get_result();
?>



<div class="container-fluid">
    <div class="row">
        <?php include 'parent_sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Vaccination History</h1>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Vaccination Records</h5>
                </div>
                <div class="card-body">
                    <?php if($result->num_rows === 0): ?>
                    <div class="alert alert-info">
                        No vaccination records found.
                    </div>
                    <?php else: ?>
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Child</th>
                                    <th>Vaccine</th>
                                    <th>Hospital</th>
                                    <th>Status</th>
                                    <th>Notes</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($row['vaccination_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['child_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['vaccine_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['hospital_name']); ?></td>
                                    <td>
                                        <span class="badge bg-<?php 
                                            switch($row['status']) {
                                                case 'given': echo 'success'; break;
                                                case 'missed': echo 'danger'; break;
                                                default: echo 'warning';
                                            }
                                        ?>">
                                            <?php echo ucfirst($row['status']); ?>
                                        </span>
                                    </td>
                                    <td><?php echo htmlspecialchars($row['notes'] ?? 'N/A'); ?></td>
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

<?php include '../footer.php'; ?>