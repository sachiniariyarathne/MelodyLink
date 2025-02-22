<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Reset Password - MelodyLink</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: linear-gradient(135deg, #6a11cb 0%, #2575fc 100%);
            height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        .reset-container {
            background: white;
            border-radius: 15px;
            box-shadow: 0 15px 35px rgba(0,0,0,0.1);
            padding: 40px;
            animation: slideIn 0.5s ease-in-out;
        }
        .btn-custom {
            background: linear-gradient(to right, #6a11cb 0%, #2575fc 100%);
            border: none;
            transition: transform 0.3s ease;
        }
        .btn-custom:hover {
            transform: scale(1.05);
        }
        @keyframes slideIn {
            from { opacity: 0; transform: translateY(30px); }
            to { opacity: 1; transform: translateY(0); }
        }
        .form-control:focus {
            border-color: #6a11cb;
            box-shadow: 0 0 0 0.2rem rgba(106, 17, 203, 0.25);
        }
        .password-strength {
            height: 4px;
            background: #e0e0e0;
            margin-top: 5px;
        }
        .password-strength-bar {
            height: 100%;
            width: 0;
            background: linear-gradient(to right, red, orange, green);
            transition: width 0.5s ease;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="reset-container">
                    <div class="text-center mb-4">
                        <h2 class="text-primary">Reset Password</h2>
                        <p class="text-muted">Create a strong new password</p>
                    </div>
                    <form action="<?php echo URLROOT; ?>/passwordreset/reset/<?php echo $data['token']; ?>" method="post">
                        <div class="form-group mb-3">
                            <label for="password" class="form-label">New Password</label>
                            <input type="password" name="password" id="password" class="form-control 
                                <?php echo (!empty($data['password_err'])) ? 'is-invalid' : ''; ?>" 
                                value="<?php echo $data['password']; ?>" 
                                placeholder="Enter new password">
                            <div class="password-strength">
                                <div class="password-strength-bar" id="password-strength-bar"></div>
                            </div>
                            <span class="invalid-feedback"><?php echo $data['password_err']; ?></span>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="confirm_password" class="form-label">Confirm Password</label>
                            <input type="password" name="confirm_password" class="form-control 
                                <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" 
                                value="<?php echo $data['confirm_password']; ?>" 
                                placeholder="Confirm new password">
                            <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                        </div>
                        
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-custom text-white">
                                Reset Password
                            </button>
                            <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-outline-secondary">
                                Cancel
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.getElementById('password').addEventListener('input', function() {
            const password = this.value;
            const strengthBar = document.getElementById('password-strength-bar');
            
            let strength = 0;
            if (password.length > 7) strength++;
            if (password.match(/[a-z]+/)) strength++;
            if (password.match(/[A-Z]+/)) strength++;
            if (password.match(/[0-9]+/)) strength++;
            if (password.match(/[$@#&!]+/)) strength++;

            strengthBar.style.width = `${strength * 20}%`;
        });
    </script>
</body>
</html>