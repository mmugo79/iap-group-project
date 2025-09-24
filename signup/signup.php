<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <div class="form-content" id="signup-form">
                <form action="mail.php" method="POST">
                    <div class="form-group">
                        <label for="signup-username">Username</label>
                        <input type="text" id="signup-username" placeholder="Enter your username" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="signup-email">Email Address</label>
                        <input type="email" id="signup-email" placeholder="Enter your email" required>
                    </div>
                    
                    <div class="form-group">
                        <label for="signup-password">Password</label>
                        <input type="password" id="signup-password" placeholder="Create a password" required>
                    </div>
                    
                    <div class="checkbox-group">
                        <input type="checkbox" id="terms" required>
                        <label for="terms">I have read and agree to the conditions</label>
                    </div>
                    
                    <button type="submit" class="submit-btn">REGISTER</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>