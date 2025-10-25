<?php
session_start();
require_once '../backend/db.php';
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Vehicle Pro Portal</title>
<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
  <div class="container">
    <a class="navbar-brand" href="index.php">Vehicle Pro Portal</a>
    <div class="collapse navbar-collapse">
      <ul class="navbar-nav ms-auto">
        <?php if(isset($_SESSION['user_id'])): ?>
        <li class="nav-item"><a class="nav-link" href="dashboard.php">Dashboard</a></li>
        <li class="nav-item"><a class="nav-link" href="logout.php">Logout</a></li>
        <?php else: ?>
        <li class="nav-item"><a class="nav-link" href="login.php">Login</a></li>
        <li class="nav-item"><a class="nav-link" href="register.php">Register</a></li>
        <?php endif; ?>
      </ul>
    </div>
  </div>
</nav>

<div class="container mt-5">
<h1 class="mb-4">Available Vehicles</h1>
<div class="row">
<?php
$result = $conn->query("SELECT * FROM vehicles WHERE status='available'");
while($row = $result->fetch_assoc()):
?>
<div class="col-md-4 mb-4">
  <div class="card shadow-sm">
    <img src="images/<?php echo $row['image']; ?>" class="card-img-top" style="height:200px; object-fit:cover;">
    <div class="card-body">
      <h5 class="card-title"><?php echo htmlspecialchars($row['model']); ?></h5>
      <p class="card-text">Price per day: $<?php echo number_format($row['price_per_day'],2); ?></p>
      <?php if(isset($_SESSION['user_id'])): ?>
        <a href="book.php?vehicle_id=<?php echo $row['id']; ?>" class="btn btn-primary">Book Now</a>
      <?php else: ?>
        <a href="login.php" class="btn btn-primary">Login to Book</a>
      <?php endif; ?>
    </div>
  </div>
</div>
<?php endwhile; ?>
</div>
</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.1/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
