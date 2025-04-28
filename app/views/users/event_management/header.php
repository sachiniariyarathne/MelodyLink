<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?> - Event Management</title>
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <!-- Custom CSS -->
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: #1a1625;
            color: #fff;
            line-height: 1.6;
        }

        .header {
            background: rgba(255, 255, 255, 0.1);
            backdrop-filter: blur(10px);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            position: fixed;
            width: 100%;
            top: 0;
            z-index: 1000;
        }

        .header-content {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1rem 2rem;
            max-width: 1400px;
            margin: 0 auto;
        }

        .logo {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            text-decoration: none;
            color: #fff;
        }

        .logo i {
            font-size: 1.5rem;
            color: #ff4d94;
        }

        .logo span {
            font-size: 1.25rem;
            font-weight: 600;
        }

        .nav-links {
            display: flex;
            gap: 2rem;
            align-items: center;
        }

        .nav-link {
            color: rgba(255, 255, 255, 0.7);
            text-decoration: none;
            font-weight: 500;
            transition: color 0.3s;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .nav-link:hover {
            color: #fff;
        }

        .nav-link.active {
            color: #ff4d94;
        }

        .user-menu {
            position: relative;
        }

        .user-button {
            background: none;
            border: none;
            color: #fff;
            display: flex;
            align-items: center;
            gap: 0.5rem;
            cursor: pointer;
            padding: 0.5rem;
            border-radius: 0.5rem;
        }

        .user-button:hover {
            background: rgba(255, 255, 255, 0.1);
        }

        .user-avatar {
            width: 2rem;
            height: 2rem;
            border-radius: 50%;
            object-fit: cover;
        }

        .main-content {
            margin-top: 4.5rem;
            min-height: calc(100vh - 4.5rem);
        }

        @media (max-width: 768px) {
            .header-content {
                padding: 1rem;
            }

            .nav-links {
                display: none;
            }

            .mobile-menu-button {
                display: block;
            }
        }
    </style>
</head>
<body>
    <header class="header">
        <div class="header-content">
            <a href="<?php echo URLROOT; ?>/eventmanagement" class="logo">
                <i class="fas fa-ticket-alt"></i>
                <span>Event Manager</span>
            </a>
            <nav class="nav-links">
                <a href="<?php echo URLROOT; ?>/eventmanagement" class="nav-link <?php echo ($_SERVER['REQUEST_URI'] == URLROOT.'/eventmanagement') ? 'active' : ''; ?>">
                    <i class="fas fa-chart-line"></i>
                    Dashboard
                </a>
                <a href="<?php echo URLROOT; ?>/eventmanagement/events" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/eventmanagement/events') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-alt"></i>
                    Events
                </a>
                <a href="<?php echo URLROOT; ?>/eventequipmentcontroller/index" class="nav-link <?php echo (strpos($_SERVER['REQUEST_URI'], '/eventequipmentcontroller/index') !== false) ? 'active' : ''; ?>">
                    <i class="fas fa-calendar-alt"></i>
                    Event Equipment
                </a>
                <div class="user-menu">
                    <a href="<?php echo URLROOT; ?>/eventmanagement/profile" class="user-button" style="text-decoration: none; color: inherit;">
                        <img src="<?php echo URLROOT; ?>/public/img/avatars/default.png" alt="User Avatar" class="user-avatar">
                        <span style="text-decoration: none;"><?php echo $_SESSION['username']; ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </a>
                </div>
            </nav>
        </div>
    </header>
    <div class="main-content"> 