<nav class="navbar">
    <div class="navbar-container">
        <div class="navbar-left">
            <span class="logo"><i class="fa fa-music"></i> MelodyLink</span>
            <ul class="nav-links">
                <li><a href="<?php echo URLROOT; ?>/artist_home">Home</a></li>
                <li><a href="<?php echo URLROOT; ?>/Artist_Dashboard/artist_dashboard">Dashboard</a></li>
                <li><a href="<?php echo URLROOT; ?>/artist_reviews">New Releases</a></li>
                <li><a href="<?php echo URLROOT; ?>/artist_communities">Explore</a></li>
                <li><a href="<?php echo URLROOT; ?>/artist_requests">Contact Us</a></li>
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
                    <a href="<?php echo URLROOT; ?>/Artist_Profile/artist_profile">Profile</a>
                    <a href="<?php echo URLROOT; ?>/artist_settings">Settings</a>
                    <a href="<?php echo URLROOT; ?>/logout">Logout</a>
                </div>
            </div>
        </div>
    </div>
</nav>

<script>
function toggleDropdown() {
    document.getElementById("dropdownMenu").classList.toggle("show");
}
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
