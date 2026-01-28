<?php
require_once '../config.php';
require_once '../auth_check.php';
checkAdminAuth();

if (isset($_GET['delete_id'])) {
    $id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM hospitals WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    header("Location: list_hospitals.php");
    exit();
}

$query = "SELECT h.*, u.email FROM hospitals h JOIN users u ON h.user_id = u.id ORDER BY h.name";
$result = $conn->query($query);
?>


<div class="container-fluid">
    <div class="row">
        <?php include 'sidebar.php'; ?>

        <div class="col-md-9 ms-sm-auto col-lg-10 px-md-4">
            <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center pt-3 pb-2 mb-3">
                <h1 class="h2">Hospital List</h1>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Registered Hospitals</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Hospital Name</th>
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
                                        <button class="btn btn-warning btn-sm edit-btn" data-id="<?php echo $row['id']; ?>">
                                            <i class="bi bi-pencil"></i> Edit
                                        </button>
                                        <a href="?delete_id=<?php echo $row['id']; ?>" class="btn btn-danger btn-sm delete-btn">
                                            <i class="bi bi-trash"></i> Delete
                                        </a>
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
    $('.edit-btn').click(function() {
        const hospitalId = $(this).data('id');
        alert('Edit functionality for hospital ID: ' + hospitalId);
        // In a real system, you would show a modal with edit form
    });
});
</script>

<?php include '../footer.php'; ?>