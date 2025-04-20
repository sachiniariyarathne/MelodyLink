<head>
    <meta charset="UTF-8">
    <title>Member Home Page</title>
    <link rel="stylesheet" href="styles.css">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<nav class="navbar">
        <div class="navbar-container">
            <div class="navbar-left">
                <span class="logo"><i class="fa fa-music"></i> MelodyLink</span>
                <ul class="nav-links">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Dashboard</a></li>
                    <li><a href="#">Upcoming Events</a></li>
                    <li><a href="#">Store</a></li>
                    <li><a href="#">Music</a></li>
                    <li><a href="#">Contact us</a></li>
                </ul>
            </div>
            <div class="navbar-right">
                <button class="notif-btn" aria-label="Notifications">
                    <i class="fa fa-bell"></i>
                </button>
                <div class="profile-dropdown">
                    <button class="profile-btn" onclick="toggleDropdown()" aria-label="Profile">
                        <i class="fa fa-user-circle"></i>
                    </button>
                    <div class="dropdown-menu" id="dropdownMenu">
                        <a href="#">Profile</a>
                        <a href="#">Settings</a>
                        <a href="#">Logout</a>
                    </div>
                </div>
            </div>
        </div>
    </nav>