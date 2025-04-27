<?php require APPROOT . '/views/inc/header4.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_artist.php'; ?>

<head>
    <title>Manage Profile - Artist</title>
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<div class="artist-manage-container">
    <h1 class="artist-manage-title">Manage Profile</h1>
    <div class="artist-manage-desc">Update your personal information and manage your account</div>
     
    <div class="artist-success-msg"><?php flash('profile_message'); ?></div>


    <!-- Profile Photo Upload Card -->
    <form class="artist-profile-card artist-photo-card" action="<?php echo URLROOT; ?>/Artist_Profile/update" method="POST" enctype="multipart/form-data">
        <div class="artist-photo-upload-section">
            <div class="artist-photo-avatar">
                <img class="artist-profile-avatar" src="<?php echo URLROOT; ?>/public/uploads/artists/<?php echo $data['profile_pic'] ?? 'default-avatar.png'; ?>" alt="Profile Photo">
                <div class="artist-photo-label">Profile Photo</div>
            </div>
            <div class="artist-photo-upload-actions">
                <label class="artist-upload-btn">
                    <i class="fa fa-upload"></i> Upload New Photo
                    <input type="file" name="profile_pic" style="display:none;">
                </label>
                <div class="artist-profile-photo-hint">Maximum file size: 2MB</div>
                <div class="artist-error-msg"><?php echo $data['profile_pic_err'] ?? ''; ?></div>
            </div>
        </div>
    </form>

    <!-- Personal Details Card -->
    <form class="artist-profile-card" action="<?php echo URLROOT; ?>/Artist_Profile/update" method="POST" enctype="multipart/form-data">
        <h2 class="artist-section-title">Personal Details</h2>
        <div class="artist-profile-grid">
            <div>
                <label>Member ID</label>
                <input type="text" value="<?php echo htmlspecialchars($data['artist_id']); ?>" disabled>
            </div>
            <div>
                <label>Username</label>
                <input type="text" name="username" value="<?php echo htmlspecialchars($data['username']); ?>">
                <div class="artist-error-msg"><?php echo $data['username_err'] ?? ''; ?></div>
            </div>
            <div>
                <label>Email</label>
                <input type="email" name="email" value="<?php echo htmlspecialchars($data['email']); ?>">
                <div class="artist-error-msg"><?php echo $data['email_err'] ?? ''; ?></div>
            </div>
            <div>
                <label>Phone Number</label>
                <input type="text" name="phone_number" value="<?php echo htmlspecialchars($data['phone_number']); ?>">
                <div class="artist-error-msg"><?php echo $data['phone_err'] ?? ''; ?></div>
            </div>
            <div>
                <label>Address</label>
                <input type="text" name="address" value="<?php echo htmlspecialchars($data['address']); ?>">
                <div class="artist-error-msg"><?php echo $data['address_err'] ?? ''; ?></div>
            </div>
        </div>
        <button type="submit" class="artist-save-btn">Save Changes</button>
    </form>

    <!-- Password Change Card -->
    <form class="artist-profile-card" method="POST" action="<?php echo URLROOT; ?>/Artist_Profile/change_password">
        <h2 class="artist-section-title">Change Password</h2>
        <div class="artist-profile-grid">
            <div>
                <label>New Password</label>
                <input type="password" name="new_password" placeholder="New Password" required>
                <div class="artist-error-msg"><?php echo $data['new_password_err'] ?? ''; ?></div>
            </div>
            <div>
                <label>Confirm New Password</label>
                <input type="password" name="confirm_password" placeholder="Confirm New Password" required>
                <div class="artist-error-msg"><?php echo $data['confirm_password_err'] ?? ''; ?></div>
            </div>
        </div>
        <button type="submit" class="artist-save-btn">Update Password</button>
        <?php if (!empty($data['success_msg'])): ?>
            <div class="artist-success-msg"><?php echo $data['success_msg']; ?></div>
        <?php endif; ?>
    </form>
</div>
</body>


<?php require APPROOT . '/views/inc/footer.php'; ?> 