<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Password Reset - MelodyLink</title>
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
    </style>
</head>
<body>
    <div class="container">
        <div class="row">
            <div class="col-md-6 mx-auto">
                <div class="reset-container">
                    <div class="text-center mb-4">
                        <h2 class="text-primary">Password Reset</h2>
                        <?php flash('reset_email'); ?>
                        <p class="text-muted">Check your email for a link to reset your password. The link will expire in 1 hour.</p>
                    </div>
                    <div class="d-grid gap-2">
                        <a href="<?php echo URLROOT; ?>/users/login" class="btn btn-custom text-white">
                            Back to Login
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>