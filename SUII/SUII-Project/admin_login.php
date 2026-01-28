<?php
require_once 'config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'];
    $password = $_POST['password'];

    $stmt = $conn->prepare("SELECT * FROM users WHERE email = ? AND role = 'admin'");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();

    // Plain text password check (NOT secure)
    if ($user && $password === $user['password']) {
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['name'] = $user['name'];
        $_SESSION['email'] = $user['email'];
        $_SESSION['role'] = $user['role'];

        header("Location: admin/all_child_details.php");
        exit();
    } else {
        $error = "Invalid email or password";
    }
}

?>

<?php include 'header.php'; ?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Admin Login</h4>
                </div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" class="form-control" id="email" name="email" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Login as Admin</button>
                            <div class="text-center mt-3">
                                <a href="parent/login.php" class="btn btn-link">Parent Login</a> | 
                                <a href="hospital/login.php" class="btn btn-link">Hospital Login</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Demo Credentials Card -->
            <div class="card mt-3">
                <div class="card-body">
                    <h6>Demo Credentials:</h6>
                    <p class="mb-1">Email: admin@suii.com</p>
                    <p class="mb-0">Password: admin123</p>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include 'footer.php'; ?>