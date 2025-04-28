<head>
    <meta charset="UTF-8">
    <title>Member Home Page</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<div>
<nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-left">
                <span class="logo"><i class="fa fa-music"></i> MelodyLink</span>
                <ul class="nav-links">
                    <li><a href="<?php echo URLROOT; ?>/Member_Homepage/Homepage">Home</a></li>
                    <li><a href="<?php echo URLROOT; ?>/users/dashboard">Dashboard</a></li>
                    <li><a href="<?php echo URLROOT; ?>/events">Upcoming Events</a></li>
                    <li><a href="<?php echo URLROOT; ?>/Merchandise">Store</a></li>
                    <li><a href="<?php echo URLROOT; ?>/Member_Music">Music</a></li>
                    <li><a href="<?php echo URLROOT; ?>/contact_us/contact">Contact Us</a></li>
                    <li><?php if (!isset($_SESSION['user_id'])): ?><a href="<?php echo URLROOT; ?>/users/register" class="btn-signup">Sign Up</a><?php endif; ?></li>
                </ul>
            </div>
            <div class="navbar-right">
                <button class="notif-btn" aria-label="Notifications">
                    <i class="fa fa-bell"></i>
                </button>
                    <!-- Cart Button Start -->
                    <a href="<?php echo URLROOT; ?>/cart" class="cart-btn" style="position: relative;">
                        <svg width="24" height="24" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <circle cx="9" cy="21" r="1"/>
                            <circle cx="20" cy="21" r="1"/>
                            <path d="M1 1h4l2.68 13.39a2 2 0 0 0 2 1.61h9.72a2 2 0 0 0 2-1.61L23 6H6"/>
                        </svg>
                        <span class="cart-count" id="cartCount">
                            <?php echo isset($data['cartItems']) ? array_sum(array_column($data['cartItems'], 'quantity')) : 0; ?>
                        </span>
                        <span class="cart-tooltip"></span>
                    </a>
                    <!-- Cart Button End -->
                <div class="profile-dropdown">
                    <button class="profile-btn" onclick="toggleDropdown()" aria-label="Profile">
                        <i class="fa fa-user-circle"></i>
                    </button>
                    <div class="dropdown-menu" id="dropdownMenu">
                        <a href="<?php echo URLROOT; ?>/Member_Profile/profile">Profile</a>
                        <a href="<?php echo URLROOT; ?>/Member_Profile/update">Settings</a>
                        <a href="<?php echo URLROOT; ?>/users/login">Logout</a>
                    </div>
                </div>
            </div>
            <script>
                function toggleDropdown() {
                    document.getElementById("dropdownMenu").classList.toggle("show");
                }

                // Close the dropdown if clicked outside
                window.onclick = function(event) {
                    if (!event.target.closest('.profile-dropdown')) {
                        var dropdowns = document.getElementsByClassName("dropdown-menu");
                        for (var i = 0; i < dropdowns.length; i++) {
                            var openDropdown = dropdowns[i];
                            if (openDropdown.classList.contains('show')) {
                                openDropdown.classList.remove('show');
                            }
                        }
                    }
                }
            </script>

        </div>
    </nav>
</div>