<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITENAME; ?></title>
    <link rel="stylesheet" href="<?php echo URLROOT; ?>/public/css/event_styles.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header class="event-header">
        <nav class="event-nav">
            <div class="logo">
                <a href="<?php echo URLROOT; ?>">
                    <i class="fas fa-music"></i>
                    MelodyLink
                </a>
            </div>
            <div class="nav-links">
                <a href="<?php echo URLROOT; ?>/events" class="nav-link">Events</a>
                <a href="<?php echo URLROOT; ?>/marketplace" class="nav-link">Marketplace</a>
                <a href="<?php echo URLROOT; ?>/suppliers" class="nav-link">Suppliers</a>
                <a href="<?php echo URLROOT; ?>/artists" class="nav-link">Artists</a>
            </div>
            <div class="nav-auth">
                <?php if(isset($_SESSION['user_id'])) : ?>
                    <div class="user-menu">
                        <img src="<?php echo URLROOT; ?>/public/img/avatar.png" alt="User Avatar" class="avatar">
                        <div class="user-dropdown">
                            <a href="<?php echo URLROOT; ?>/users/dashboard">Dashboard</a>
                            <a href="<?php echo URLROOT; ?>/users/profile">Profile</a>
                            <a href="<?php echo URLROOT; ?>/users/logout">Logout</a>
                        </div>
                    </div>
                <?php else : ?>
                    <a href="<?php echo URLROOT; ?>/users/login" class="btn-sign-in">Sign in</a>
                <?php endif; ?>
            </div>
        </nav>