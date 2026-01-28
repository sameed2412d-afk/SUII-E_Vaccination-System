<?php
require_once '../config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);
    $phone = $_POST['phone'];
    $address = $_POST['address'];
    
    $stmt = $conn->prepare("INSERT INTO users (name, email, password, role, phone, address) VALUES (?, ?, ?, 'parent', ?, ?)");
    $stmt->bind_param("sssss", $name, $email, $password, $phone, $address);
    
    if ($stmt->execute()) {
        header("Location: login.php?registered=1");
        exit();
    } else {
        $error = "Registration failed. Email might already exist.";
    }
}
?>

<?php include '../header.php'; ?>

<div class="container">
    <div class="row justify-content-center mt-5">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">Parent Registration</h4>
                </div>
                <div class="card-body">
                    <?php if(isset($error)): ?>
                    <div class="alert alert-danger"><?php echo $error; ?></div>
                    <?php endif; ?>
                    
                    <form method="POST">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="name" class="form-label">Full Name *</label>
                                <input type="text" class="form-control" id="name" name="name" required>
                            </div>
                            <div class="col-md-6">
                                <label for="email" class="form-label">Email Address *</label>
                                <input type="email" class="form-control" id="email" name="email" required>
                            </div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="password" class="form-label">Password *</label>
                                <input type="password" class="form-control" id="password" name="password" required>
                            </div>
                            <div class="col-md-6">
                                <label for="phone" class="form-label">Phone Number *</label>
                                <input type="tel" class="form-control" id="phone" name="phone" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address" class="form-label">Address *</label>
                            <textarea class="form-control" id="address" name="address" rows="3" required></textarea>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary">Register</button>
                            <a href="login.php" class="btn btn-link">Already have an account? Login here</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php include '../footer.php'; ?>