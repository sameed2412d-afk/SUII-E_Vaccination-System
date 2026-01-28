<?php
$current_role = isset($_SESSION['role']) ? $_SESSION['role'] : 'guest';
$user_name = isset($_SESSION['name']) ? $_SESSION['name'] : 'Guest';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SUII - E-Vaccination System</title>
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.8.1/font/bootstrap-icons.css">
    <!-- Custom CSS -->
    <style>
        :root {
            --primary-color: #0d6efd;
            --secondary-color: #198754;
            --light-blue: #e3f2fd;
            --light-green: #d1e7dd;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar-brand {
            font-weight: bold;
            color: var(--primary-color) !important;
        }
        
        .sidebar {
            background: linear-gradient(180deg, var(--primary-color) 0%, #0b5ed7 100%);
            min-height: calc(100vh - 56px);
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        
        .sidebar a {
            color: white !important;
            padding: 12px 15px;
            text-decoration: none;
            display: block;
            transition: all 0.3s;
            border-left: 3px solid transparent;
        }
        
        .sidebar a:hover, .sidebar a.active {
            background-color: rgba(255,255,255,0.1);
            border-left: 3px solid white;
        }
        
        .sidebar i {
            width: 25px;
        }
        
        .card {
            border-radius: 10px;
            border: none;
            box-shadow: 0 0 15px rgba(0,0,0,0.05);
            margin-bottom: 20px;
        }
        
        .card-header {
            background-color: var(--primary-color);
            color: white;
            border-radius: 10px 10px 0 0 !important;
            padding: 15px 20px;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-success {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        .stat-card {
            border-radius: 10px;
            padding: 20px;
            color: white;
            margin-bottom: 20px;
        }
        
        .bg-vaccine-blue {
            background: linear-gradient(135deg, var(--primary-color), #0b5ed7);
        }
        
        .bg-vaccine-green {
            background: linear-gradient(135deg, var(--secondary-color), #157347);
        }
        
        .table th {
            background-color: var(--light-blue);
            border-top: none;
        }
        
        .badge-pending {
            background-color: #ffc107;
            color: #000;
        }
        
        .badge-approved {
            background-color: var(--secondary-color);
        }
        
        .badge-rejected {
            background-color: #dc3545;
        }
        
        .badge-completed {
            background-color: #6c757d;
        }
        
        .vaccine-icon {
            font-size: 2rem;
            color: var(--primary-color);
        }
        
        .welcome-section {
            background: linear-gradient(135deg, var(--light-blue), white);
            border-radius: 15px;
            padding: 30px;
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <!-- Navigation Bar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">
                <i class="bi bi-shield-plus me-2"></i>
                SUII Vaccination System
            </a>
            <div class="d-flex align-items-center">
                <?php if(isset($_SESSION['user_id'])): ?>
                    <span class="me-3">Welcome, <?php echo htmlspecialchars($user_name); ?></span>
                    <a href="../logout.php" class="btn btn-outline-danger btn-sm">
                        <i class="bi bi-box-arrow-right"></i> Logout
                    </a>
                <?php endif; ?>
            </div>
        </div>
    </nav>