<?php
require_once '../config.php';
require_once '../auth_check.php';
checkAdminAuth();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['approve'])) {
        $user_id = $_POST['user_id'];
        // In real system, you would update user status to approved
        $message = "User request approved successfully!";
    } elseif (isset($_POST['reject'])) {
        $user_id = $_POST['user_id'];
        // In real system, you would update user status to rejected
        $message = "User request rejected!";
    }
}

// Get pending parent registrations
$query = "SELECT * FROM users WHERE role = 'parent' ORDER BY created_at DESC";
$result = $conn->query($query);
?>



<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Parent Registration Requests</h1>
            </div>

            <?php if(isset($message)): ?>
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <?php echo $message; ?>
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
            <?php endif; ?>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Pending Requests</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Name</th>
                                    <th>Email</th>
                                    <th>Phone</th>
                                    <th>Address</th>
                                    <th>Registered Date</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php while($row = $result->fetch_assoc()): ?>
                                <tr>
                                    <td><?php echo $row['id']; ?></td>
                                    <td><?php echo htmlspecialchars($row['name']); ?></td>
                                    <td><?php echo htmlspecialchars($row['email']); ?></td>
                                    <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                    <td><?php echo htmlspecialchars(substr($row['address'], 0, 50)) . '...'; ?></td>
                                    <td><?php echo date('d M Y', strtotime($row['created_at'])); ?></td>
                                    <td>
                                        <form method="POST" class="d-inline">
                                            <input type="hidden" name="user_id" value="<?php echo $row['id']; ?>">
                                            <button type="submit" name="approve" class="btn btn-success btn-sm">
                                                <i class="bi bi-check-circle"></i> Approve
                                            </button>
                                            <button type="submit" name="reject" class="btn btn-danger btn-sm">
                                                <i class="bi bi-x-circle"></i> Reject
                                            </button>
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