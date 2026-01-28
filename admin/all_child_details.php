<?php
require_once '../config.php';
require_once '../auth_check.php';
checkAdminAuth();

$query = "SELECT c.*, u.name as parent_name, u.email as parent_email 
          FROM children c 
          JOIN users u ON c.parent_id = u.id 
          ORDER BY c.created_at DESC";
$result = $conn->query($query);
?>

<style>
    .stat-card {
        padding: 20px;
        border-radius: 10px;
        color: #fff;
        margin-bottom: 20px;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        text-align: center;
        display: flex;
        flex-direction: column;
        justify-content: center;
        align-items: center;
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
</style>

<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">All Child Details</h1>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Registered Children</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Child Name</th>
                                    <th>Date of Birth</th>
                                    <th>Parent Name</th>
                                    <th>Parent Email</th>
                                    <th>Registered Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['dob'])); ?></td>
                                    <td><?php echo htmlspecialchars($row['parent_name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['parent_email']); ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <button class="btn btn-sm btn-info view-child" data-id="<?php echo $row['id']; ?>">
                                            <i class="bi bi-eye"></i> View
                                        </button>
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

<script>
$(document).ready(function() {
    $('.view-child').click(function() {
        const childId = $(this).data('id');
        alert('View details for child ID: ' + childId);
    });
});
</script>

<?php include '../footer.php'; ?>