<?php
session_start();
require_once __DIR__ . '/../backend/db.php';
if(!isset($_SESSION['user_id'])) header("Location: login.php");

$vehicle_id = $_GET['vehicle_id'] ?? null;

// Using prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT * FROM vehicles WHERE id = ?");
$stmt->bind_param("i", $vehicle_id);
$stmt->execute();
$vehicle = $stmt->get_result()->fetch_assoc();

$errors=[]; 

if(isset($_POST['book'])){
    $start = $_POST['start_date'];
    $end = $_POST['end_date'];
    $days = (strtotime($end) - strtotime($start)) / 86400 + 1; 
    $total = $days * $vehicle['price_per_day'];

    // Insert reservation
    $stmt = $conn->prepare("INSERT INTO reservations (user_id, vehicle_id, start_date, end_date, total_cost) VALUES (?,?,?,?,?)");
    $stmt->bind_param("iissd", $_SESSION['user_id'], $vehicle_id, $start, $end, $total);
    $stmt->execute();

    // Update vehicle status
    $conn->query("UPDATE vehicles SET status='booked' WHERE id=$vehicle_id");

    $_SESSION['reservation_id'] = $conn->insert_id;
    header("Location: payment.php"); 
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Book <?php echo htmlspecialchars($vehicle['model']); ?> - Vehicle Pro Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        .booking-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 10px 30px rgba(0,0,0,0.2);
            overflow: hidden;
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
        }
        .booking-header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 25px;
            text-align: center;
        }
        .vehicle-info {
            background: #f8f9fa;
            border-radius: 10px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .price-display {
            font-size: 1.5rem;
            font-weight: bold;
            color: #28a745;
        }
        .btn-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            border: none;
            padding: 12px 30px;
            font-weight: 600;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="booking-container">
        <div class="booking-header">
            <h2><i class="fas fa-calendar-check me-2"></i>Book Vehicle</h2>
            <p class="mb-0">Complete your reservation</p>
        </div>
        
        <div class="p-4">
            <!-- Vehicle Information -->
            <div class="vehicle-info">
                <div class="row align-items-center">
                    <div class="col-md-8">
                        <h4 class="mb-2"><?php echo htmlspecialchars($vehicle['model']); ?></h4>
                        <p class="mb-1 text-muted">
                            <i class="fas fa-tag me-2"></i><?php echo htmlspecialchars($vehicle['brand']); ?>
                        </p>
                        <p class="mb-1 text-muted">
                            <i class="fas fa-calendar me-2"></i>Year: <?php echo htmlspecialchars($vehicle['year']); ?>
                        </p>
                        <p class="mb-0 text-muted">
                            <i class="fas fa-dollar-sign me-2"></i>Price per day: $<?php echo number_format($vehicle['price_per_day'], 2); ?>
                        </p>
                    </div>
                    <div class="col-md-4 text-center">
                        <div class="price-display">$<?php echo number_format($vehicle['price_per_day'], 2); ?>/day</div>
                    </div>
                </div>
            </div>

            <!-- Booking Form -->
            <form method="POST">
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">Start Date</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" name="start_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label class="form-label fw-bold">End Date</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                            <input type="date" name="end_date" class="form-control" required min="<?php echo date('Y-m-d'); ?>">
                        </div>
                    </div>
                </div>

                <!-- Price Calculation Preview -->
                <div id="pricePreview" class="alert alert-info" style="display: none;">
                    <div class="d-flex justify-content-between align-items-center">
                        <span>Estimated Total:</span>
                        <strong id="totalPrice">$0.00</strong>
                    </div>
                    <small class="text-muted" id="daysCount">0 days</small>
                </div>

                <div class="d-grid gap-2">
                    <button type="submit" class="btn btn-primary btn-lg py-3" name="book">
                        <i class="fas fa-credit-card me-2"></i>Proceed to Payment
                    </button>
                    <a href="dashboard.php" class="btn btn-outline-secondary">
                        <i class="fas fa-arrow-left me-2"></i>Back to Dashboard
                    </a>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        // Price calculation preview
        const startDate = document.querySelector('input[name="start_date"]');
        const endDate = document.querySelector('input[name="end_date"]');
        const pricePerDay = <?php echo $vehicle['price_per_day']; ?>;
        const pricePreview = document.getElementById('pricePreview');
        const totalPrice = document.getElementById('totalPrice');
        const daysCount = document.getElementById('daysCount');

        function calculatePrice() {
            if (startDate.value && endDate.value) {
                const start = new Date(startDate.value);
                const end = new Date(endDate.value);
                const days = Math.max(1, Math.ceil((end - start) / (1000 * 60 * 60 * 24)) + 1);
                const total = days * pricePerDay;
                
                totalPrice.textContent = '$' + total.toFixed(2);
                daysCount.textContent = days + ' day' + (days !== 1 ? 's' : '');
                pricePreview.style.display = 'block';
            } else {
                pricePreview.style.display = 'none';
            }
        }

        startDate.addEventListener('change', calculatePrice);
        endDate.addEventListener('change', calculatePrice);
    </script>
</body>
</html>
