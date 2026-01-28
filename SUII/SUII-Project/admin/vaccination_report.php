<?php
require_once '../config.php';
require_once '../auth_check.php';
checkAdminAuth();

$date_from = $_GET['date_from'] ?? date('Y-m-01');
$date_to = $_GET['date_to'] ?? date('Y-m-d');

$query = "SELECT DATE(vr.vaccination_date) as vaccination_date, 
          COUNT(*) as total_vaccinations,
          SUM(CASE WHEN vr.status = 'given' THEN 1 ELSE 0 END) as given_count,
          SUM(CASE WHEN vr.status = 'missed' THEN 1 ELSE 0 END) as missed_count,
          v.name as vaccine_name
          FROM vaccination_records vr
          JOIN vaccines v ON vr.vaccine_id = v.id
          WHERE vr.vaccination_date BETWEEN ? AND ?
          GROUP BY DATE(vr.vaccination_date), vr.vaccine_id
          ORDER BY vr.vaccination_date DESC";
$stmt = $conn->prepare($query);
$stmt->bind_param("ss", $date_from, $date_to);
$stmt->execute();
$result = $stmt->get_result();
?>



<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Vaccination Reports</h1>
            </div>

            <!-- Filter Form -->
            <div class="card mb-4">
                <div class="card-body">
                    <form method="GET" class="row g-3">
                        <div class="col-md-4">
                            <label for="date_from" class="form-label">From Date</label>
                            <input type="date" class="form-control" id="date_from" name="date_from" value="<?php echo $date_from; ?>">
                        </div>
                        <div class="col-md-4">
                            <label for="date_to" class="form-label">To Date</label>
                            <input type="date" class="form-control" id="date_to" name="date_to" value="<?php echo $date_to; ?>">
                        </div>
                        <div class="col-md-4 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary">Generate Report</button>
                        </div>
                    </form>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Date-wise Vaccination Report</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>Date</th>
                                    <th>Vaccine</th>
                                    <th>Total Vaccinations</th>
                                    <th>Given</th>
                                    <th>Missed</th>
                                    <th>Completion Rate</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): 
                                    $completion_rate = $row['total_vaccinations'] > 0 ? 
                                        round(($row['given_count'] / $row['total_vaccinations']) * 100, 2) : 0;
                                ?>
                                <tr>
                                    <td><?php echo date('d M Y', strtotime($row['vaccination_date'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['vaccine_name']); ?></td>
                                    <td><?php echo $row['total_vaccinations']; ?></td>
                                    <td><span class="badge bg-success"><?php echo $row['given_count']; ?></span></td>
                                    <td><span class="badge bg-danger"><?php echo $row['missed_count']; ?></span></td>
                                    <td>
                                        <div class="progress" style="height: 20px;">
                                            <div class="progress-bar bg-success" role="progressbar" 
                                                 style="width: <?php echo $completion_rate; ?>%">
                                                <?php echo $completion_rate; ?>%
                                            </div>
                                        </div>
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