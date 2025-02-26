<?php require APPROOT.'/views/inc/header.php';?> 
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
</head>
<body>
    <div class="login-container">
        <div class="illustration-side">
            <div class="circles">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="illustration-text">
                <h2>Welcome Back</h2>
                <p>We're glad to see you again!</p>
            </div>
        </div>
        
        <div class="form-side">
            <form class="login-form" action="<?php echo URLROOT; ?>/users/login" method="POST">
                <h2>Login to Your Account</h2>
                
                <div class="form-group">
                    <label for="email">Email Address</label>
                    <input type="email" id="email" name="email" required>
                </div>
                
                <div class="form-group">
                    <label for="password">Password</label>
                    <input type="password" id="password" name="password" required>
                </div>
                
                <div class="form-links">
                    <div class="remember-me">
                        <input type="checkbox" id="remember">
                        <label for="remember">Remember me</label>
                    </div>
                    <a href="<?php echo URLROOT; ?>/users/forgotpassword" class="forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit">Login</button>
                
                <div class="register-link">
                    Don't have an account? 
                    <a href="<?php echo URLROOT; ?>/users/register">Sign up</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>