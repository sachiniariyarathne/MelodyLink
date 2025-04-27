<?php require APPROOT.'/views/inc/header.php';?> 
<!DOCTYPE html>
<html>
<head>
    <title>Login</title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/login_styles.css">
</head>
<body>
    <div class="login-container">
        <div class="login-illustration-side">
            <div class="login-circles">
                <div></div>
                <div></div>
                <div></div>
            </div>
            <div class="login-illustration-text">
                <h2>Welcome Back</h2>
                <p>We're glad to see you again!</p>
            </div>
        </div>
        
        <div class="login-form-side">
            <form class="login-form" action="<?php echo URLROOT; ?>/users/login" method="POST">
                <h2>Login to Your Account</h2>
                
                <div class="login-form-group">
                    <label for="login-email">Email Address</label>
                    <input type="email" name="email" ... value="<?php echo $data['email']; ?>">
                    <?php if(!empty($data['email_err'])): ?>
                        <div class="error-message"><?php echo $data['email_err']; ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="login-form-group">
                    <label for="login-password">Password</label>
                    <input type="password" name="password" ...>
                    <?php if(!empty($data['password_err'])): ?>
                        <div class="error-message"><?php echo $data['password_err']; ?></div>
                    <?php endif; ?>
                </div>
                
                <div class="login-form-links">
                    <div class="login-remember-me">
                        <input type="checkbox" id="login-remember">
                        <label for="login-remember">Remember me</label>
                    </div>
                    <a href="<?php echo URLROOT; ?>/passwordReset/forgot" class="login-forgot-password">Forgot Password?</a>
                </div>
                
                <button type="submit" class="login-btn">Login</button>
                
                <div class="login-register-link">
                    Don't have an account? 
                    <a href="<?php echo URLROOT; ?>/users/register">Sign up</a>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
