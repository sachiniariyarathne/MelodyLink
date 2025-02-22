<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Forgot Password - MelodyLink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .forgot-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 40px;
            animation: fadeIn 0.5s ease-in-out;
        }
        .btn-custom {
            background: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);
            border: none;
            transition: transform 0.3s ease;
        }
        .btn-custom:hover {
            transform: scale(1.05);
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 0 0.2rem rgba(106, 17, 203, 0.25);
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="forgot-container">
                    <div class="text-center mb-4">
                        <h2 class="text-primary">Forgot Password</h2>
                        <p class="text-muted">Enter your email to reset your password</p>
                    </div>
                    <form action="<?php echo URLROOT; ?>/passwordreset/forgot" method="post">
                        <div class="form-group mb-3">
                            <label for="email" class="form-label">Email Address</label>
                            <input type="email" name="email" class="form-control 
                                <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" 
                                value="<?php echo $data['email']; ?>" 
                                placeholder="Enter your email">
                            <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-custom text-white">
                                Send Reset Link
                            </button>
                            <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-outline-secondary">
                                Back to Login
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</body>
</html>