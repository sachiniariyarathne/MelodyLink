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
                    
                    <li><a href="<?php echo URLROOT; ?>/users/dashboard">Dashboard</a></li>
                   
                    
                    
                    
                    <li><?php if (!isset($_SESSION['user_id'])): ?><a href="<?php echo URLROOT; ?>/users/register" class="btn-signup">Sign Up</a><?php endif; ?></li>
                </ul>
            </div>
            <div class="navbar-right">
                <button class="notif-btn" aria-label="Notifications">
                    <i class="fa fa-bell"></i>
                </button>
                    
                    <!-- Cart Button End -->
                <div class="profile-dropdown">
                    <button class="profile-btn" onclick="toggleDropdown()" aria-label="Profile">
                        <i class="fa fa-user-circle"></i>
                    </button>
                    <div class="dropdown-menu" id="dropdownMenu">
                        <a href="<?php echo URLROOT; ?>/SupplierProfile">Profile</a>
                        <a href="<?php echo URLROOT; ?>/SupplierProfile/update">Settings</a>
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