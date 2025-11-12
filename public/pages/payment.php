<?php
session_start();
require_once __DIR__ . '/../backend/db.php';
$res_id = $_SESSION['reservation_id'] ?? null;

if(!$res_id) { 
    header("Location: dashboard.php");
    exit;
}

// Using prepared statement to prevent SQL injection
$stmt = $conn->prepare("SELECT r.*, v.model, v.brand, v.image, u.name as user_name 
                       FROM reservations r 
                       JOIN vehicles v ON r.vehicle_id = v.id 
                       JOIN users u ON r.user_id = u.id 
                       WHERE r.id = ?");
$stmt->bind_param("i", $res_id);
$stmt->execute();
$res = $stmt->get_result()->fetch_assoc();

if(!$res) {
    header("Location: dashboard.php");
    exit;
}

$success = false;

if(isset($_POST['pay'])){
    $method = $_POST['method'];
    $stmt = $conn->prepare("INSERT INTO payments (reservation_id, method, amount, status) VALUES (?,?,?,'completed')");
    $stmt->bind_param("ssd", $res_id, $method, $res['total_cost']);
    
    if($stmt->execute()){
        // Update reservation status
        $conn->query("UPDATE reservations SET status='confirmed' WHERE id = $res_id");
        $success = true;
        unset($_SESSION['reservation_id']);
    }
}

$vehicle_image = !empty($res['image']) && file_exists("images/{$res['image']}") 
               ? "images/{$res['image']}" 
               : "https://images.unsplash.com/photo-1549317661-bd32c8ce0db2?ixlib=rb-4.0.3&auto=format&fit=crop&w=300&h=200&q=80";
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Payment - Vehicle Pro Portal</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #667eea;
            --secondary: #764ba2;
            --success: #10b981;
            --warning: #f59e0b;
            --danger: #ef4444;
            --dark: #1f2937;
            --light: #f8fafc;
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, var(--primary) 0%, var(--secondary) 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            padding: 20px;
        }
        
        .payment-container {
            background: white;
            border-radius: 20px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.1);
            overflow: hidden;
            width: 100%;
            max-width: 800px;
            margin: 0 auto;
        }
        
        .payment-header {
            background: linear-gradient(135deg, var(--success), #34d399);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        
        .payment-header.success {
            background: linear-gradient(135deg, var(--success), #34d399);
        }
        
        .summary-card {
            background: #f8fafc;
            border-radius: 16px;
            padding: 25px;
            margin: 25px;
            border: 1px solid #e2e8f0;
        }
        
        .vehicle-image {
            width: 100px;
            height: 70px;
            object-fit: cover;
            border-radius: 10px;
        }
        
        .amount-display {
            font-size: 3rem;
            font-weight: 700;
            color: var(--success);
            text-align: center;
            margin: 20px 0;
        }
        
        .payment-method {
            border: 2px solid #e2e8f0;
            border-radius: 12px;
            padding: 20px;
            margin-bottom: 15px;
            cursor: pointer;
            transition: all 0.3s ease;
            background: white;
        }
        
        .payment-method:hover {
            border-color: var(--primary);
            background: #f8fafc;
            transform: translateY(-2px);
        }
        
        .payment-method.selected {
            border-color: var(--success);
            background: #f0fdf4;
            box-shadow: 0 5px 15px rgba(16, 185, 129, 0.1);
        }
        
        .method-icon {
            width: 50px;
            height: 50px;
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 15px;
            font-size: 1.5rem;
        }
        
        .method-mpesa { background: rgba(16, 185, 129, 0.1); color: var(--success); }
        .method-card { background: rgba(102, 126, 234, 0.1); color: var(--primary); }
        .method-cash { background: rgba(245, 158, 11, 0.1); color: var(--warning); }
        
        .btn-success {
            background: linear-gradient(135deg, var(--success), #34d399);
            border: none;
            padding: 15px 30px;
            font-weight: 600;
            border-radius: 10px;
            transition: all 0.3s ease;
        }
        
        .btn-success:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 20px rgba(16, 185, 129, 0.3);
        }
        
        .success-animation {
            animation: bounce 2s infinite;
        }
        
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
            40% { transform: translateY(-10px); }
            60% { transform: translateY(-5px); }
        }
    </style>
</head>
<body>
    <div class="payment-container">
        <?php if($success): ?>
            <!-- Success Screen -->
            <div class="payment-header success position-relative">
                <div class="success-animation">
                    <i class="fas fa-check-circle fa-4x mb-3"></i>
                </div>
                <h2 class="fw-bold">Payment Successful!</h2>
                <p class="mb-0 opacity-90">Your booking has been confirmed</p>
            </div>
            
            <div class="p-4 text-center">
                <div class="summary-card text-start">
                    <div class="row align-items-center">
                        <div class="col-md-2">
                            <img src="<?php echo $vehicle_image; ?>" 
                                 class="vehicle-image" 
                                 alt="<?php echo htmlspecialchars($res['model']); ?>">
                        </div>
                        <div class="col-md-6">
                            <h5 class="fw-bold mb-1"><?php echo htmlspecialchars($res['model']); ?></h5>
                            <p class="text-muted mb-1"><?php echo htmlspecialchars($res['brand']); ?></p>
                            <p class="mb-0 text-muted">
                                <i class="fas fa-calendar me-1"></i>
                                <?php echo date('M j, Y', strtotime($res['start_date'])); ?> - 
                                <?php echo date('M j, Y', strtotime($res['end_date'])); ?>
                            </p>
                        </div>
                        <div class="col-md-4 text-end">
                            <div class="h4 text-success fw-bold">$<?php echo number_format($res['total_cost'], 2); ?></div>
                            <span class="badge bg-success">Paid</span>
                        </div>
                    </div>
                </div>
                
                <div class="alert alert-success border-0 mb-4">
                    <h5 class="fw-bold mb-2"><i class="fas fa-check-circle me-2"></i>Booking Confirmed</h5>
                    <p class="mb-2">Thank you for your payment. Your vehicle has been reserved.</p>
                    <p class="mb-0">A confirmation email has been sent to your registered email address.</p>
                </div>
                
                <div class="d-grid gap-2 col-lg-6 mx-auto">
                    <a href="dashboard.php" class="btn btn-success btn-lg py-3">
                        <i class="fas fa-tachometer-alt me-2"></i>Back to Dashboard
                    </a>
                    <a href="index.php" class="btn btn-outline-primary btn-lg py-3">
                        <i class="fas fa-home me-2"></i>Return to Homepage
                    </a>
                </div>
            </div>
        <?php else: ?>
            <!-- Payment Form -->
            <div class="payment-header">
                <h2><i class="fas fa-credit-card me-2"></i>Complete Payment</h2>
                <p class="mb-0 opacity-90">Secure payment processing</p>
            </div>
            
            <div class="p-4">
                <!-- Booking Summary -->
                <div class="summary-card">
                    <h5 class="fw-bold mb-3"><i class="fas fa-receipt me-2"></i>Booking Summary</h5>
                    <div class="row">
                        <div class="col-md-2">
                            <img src="<?php echo $vehicle_image; ?>" 
                                 class="vehicle-image" 
                                 alt="<?php echo htmlspecialchars($res['model']); ?>">
                        </div>
                        <div class="col-md-5">
                            <h6 class="fw-bold mb-1"><?php echo htmlspecialchars($res['model']); ?></h6>
                            <p class="text-muted mb-1"><?php echo htmlspecialchars($res['brand']); ?></p>
                            <p class="mb-2 text-muted">
                                <i class="fas fa-user me-1"></i><?php echo htmlspecialchars($res['user_name']); ?>
                            </p>
                        </div>
                        <div class="col-md-5">
                            <p class="mb-1"><strong>Rental Period:</strong></p>
                            <p class="mb-1">
                                <?php echo date('M j, Y', strtotime($res['start_date'])); ?> - 
                                <?php echo date('M j, Y', strtotime($res['end_date'])); ?>
                            </p>
                            <p class="mb-0">
                                <strong>Duration:</strong> 
                                <?php 
                                    $days = (strtotime($res['end_date']) - strtotime($res['start_date'])) / 86400 + 1;
                                    echo $days . ' day' . ($days > 1 ? 's' : '');
                                ?>
                            </p>
                        </div>
                    </div>
                </div>

                <!-- Total Amount -->
                <div class="amount-display">
                    $<?php echo number_format($res['total_cost'], 2); ?>
                </div>

                <!-- Payment Method -->
                <form method="POST">
                    <div class="mb-4">
                        <h5 class="fw-bold mb-3"><i class="fas fa-wallet me-2"></i>Select Payment Method</h5>
                        
                        <div class="payment-method" onclick="selectMethod('M-Pesa')">
                            <div class="d-flex align-items-center">
                                <div class="method-icon method-mpesa">
                                    <i class="fas fa-mobile-alt"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="method" value="M-Pesa" id="mpesa" required>
                                        <label class="form-check-label fw-bold" for="mpesa">
                                            M-Pesa Mobile Money
                                        </label>
                                    </div>
                                    <p class="text-muted mb-0 mt-1">Pay instantly via M-Pesa. You'll receive a prompt on your phone.</p>
                                </div>
                            </div>
                        </div>

                        <div class="payment-method" onclick="selectMethod('Card')">
                            <div class="d-flex align-items-center">
                                <div class="method-icon method-card">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="method" value="Card" id="card">
                                        <label class="form-check-label fw-bold" for="card">
                                            Credit/Debit Card
                                        </label>
                                    </div>
                                    <p class="text-muted mb-0 mt-1">Pay securely with your Visa, MasterCard, or American Express.</p>
                                </div>
                            </div>
                        </div>

                        <div class="payment-method" onclick="selectMethod('Cash')">
                            <div class="d-flex align-items-center">
                                <div class="method-icon method-cash">
                                    <i class="fas fa-money-bill-wave"></i>
                                </div>
                                <div class="flex-grow-1">
                                    <div class="form-check">
                                        <input class="form-check-input" type="radio" name="method" value="Cash" id="cash">
                                        <label class="form-check-label fw-bold" for="cash">
                                            Cash Payment
                                        </label>
                                    </div>
                                    <p class="text-muted mb-0 mt-1">Pay in cash at any of our branch locations.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-success btn-lg py-3" name="pay">
                            <i class="fas fa-lock me-2"></i>Complete Payment - $<?php echo number_format($res['total_cost'], 2); ?>
                        </button>
                        <a href="dashboard.php" class="btn btn-outline-secondary btn-lg py-3">
                            <i class="fas fa-arrow-left me-2"></i>Cancel Booking
                        </a>
                    </div>
                </form>
            </div>
        <?php endif; ?>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    <script>
        function selectMethod(method) {
            // Remove selected class from all methods
            document.querySelectorAll('.payment-method').forEach(function(el) {
                el.classList.remove('selected');
            });
            
            // Add selected class to clicked method
            event.currentTarget.classList.add('selected');
            
            // Check the radio button
            document.querySelector('input[value="' + method + '"]').checked = true;
        }

        // Auto-select first payment method
        document.addEventListener('DOMContentLoaded', function() {
            const firstMethod = document.querySelector('.payment-method');
            if (firstMethod) {
                firstMethod.click();
            }
        });
    </script>
</body>
</html>
