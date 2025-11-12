<?php
session_start();
error_reporting(0);
ini_set('display_errors', 0);

require_once '../backend/config.php';
require_once '../backend/db.php';

// Get available vehicles
$vehicles = [];
try {
    $sql = "SELECT * FROM vehicles WHERE status = 'available' ORDER BY created_at DESC";
    $stmt = $pdo->prepare($sql);
    $stmt->execute();
    $vehicles = $stmt->fetchAll();
} catch (PDOException $e) {
    error_log("Vehicles query failed: " . $e->getMessage());
    $vehicles = [];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Vehicle Pro Portal - Home</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <link rel="stylesheet" href="assets/css/custom.css">
</head>
<body class="bg-light">
    <!-- Navigation -->
    <nav class="navbar navbar-expand-lg navbar-dark bg-dark">
        <div class="container">
            <a class="navbar-brand" href="index.php">
                <i class="fas fa-car"></i> Vehicle Pro Portal
            </a>
            <div class="navbar-nav ms-auto">
                <?php if (isset($_SESSION['user_id'])): ?>
                    <a href="dashboard.php" class="nav-link">Dashboard</a>
                    <a href="logout.php" class="nav-link">Logout</a>
                <?php else: ?>
                    <a href="login.php" class="nav-link">Login</a>
                    <a href="register.php" class="nav-link">Register</a>
                <?php endif; ?>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <section class="hero-section bg-primary text-white py-5">
        <div class="container">
            <div class="row align-items-center">
                <div class="col-lg-6">
                    <h1 class="display-4 fw-bold">Find Your Perfect Ride</h1>
                    <p class="lead">Discover our premium collection of vehicles for your next adventure</p>
                    <?php if (!isset($_SESSION['user_id'])): ?>
                        <div class="mt-4">
                            <a href="register.php" class="btn btn-light btn-lg me-3">Get Started</a>
                            <a href="login.php" class="btn btn-outline-light btn-lg">Login</a>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="col-lg-6 text-center">
                    <i class="fas fa-car fa-10x text-white-50"></i>
                </div>
            </div>
        </div>
    </section>

    <!-- Vehicles Section -->
    <section class="py-5">
        <div class="container">
            <h2 class="text-center mb-5">Available Vehicles</h2>
            
            <?php if (empty($vehicles)): ?>
                <div class="text-center py-5">
                    <i class="fas fa-car fa-4x text-muted mb-3"></i>
                    <h4 class="text-muted">No vehicles available at the moment</h4>
                </div>
            <?php else: ?>
                <div class="row">
                    <?php foreach ($vehicles as $vehicle): ?>
                        <div class="col-lg-4 col-md-6 mb-4">
                            <div class="card h-100 shadow-sm">
                                <img src="<?= htmlspecialchars($vehicle['image_path'] ?? 'assets/images/default-vehicle.jpg') ?>" 
                                     class="card-img-top vehicle-image" 
                                     alt="<?= htmlspecialchars($vehicle['brand'] ?? '') ?> <?= htmlspecialchars($vehicle['model'] ?? '') ?>"
                                     onerror="this.src='assets/images/default-vehicle.jpg'">
                                <div class="card-body">
                                    <h5 class="card-title">
                                        <?= htmlspecialchars($vehicle['brand'] ?? '') ?> <?= htmlspecialchars($vehicle['model'] ?? '') ?>
                                    </h5>
                                    <p class="card-text">
                                        <small class="text-muted">
                                            <i class="fas fa-calendar-alt me-1"></i>
                                            Year: <?= htmlspecialchars($vehicle['year'] ?? 'N/A') ?>
                                        </small>
                                    </p>
                                    <div class="d-flex justify-content-between align-items-center">
                                        <span class="h5 text-success mb-0">
                                            $<?= number_format($vehicle['price_per_day'] ?? 0, 2) ?>/day
                                        </span>
                                        <a href="book.php?id=<?= $vehicle['id'] ?>" class="btn btn-primary">
                                            <i class="fas fa-calendar-check me-1"></i> Book Now
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
