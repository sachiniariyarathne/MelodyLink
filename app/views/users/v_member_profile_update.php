<?php require APPROOT . '/views/inc/header3.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_member.php'; ?>

<head>
    <meta charset="UTF-8">
    <title>Manage Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<div class="profile-container">
    <h1 class="profile-title">Manage Profile</h1>
    <p class="profile-desc">Update your personal information and manage your account</p>

    <form class="profile-form" action="" method="POST" enctype="multipart/form-data">
        <!-- Profile Photo -->
        <div class="profile-card">
            <div class="profile-photo-section">
                <img class="profile-avatar"
                     src="<?php echo !empty($data['profile_pic']) ? '/public/uploads/' . $data['profile_pic'] : '/images/default-avatar.png'; ?>"
                     alt="Profile Photo">
                <div>
                    <label for="profile_pic" class="upload-btn">
                        <i class="fa fa-upload"></i> Upload New Photo
                        <input type="file" name="profile_pic" id="profile_pic" accept="image/*" style="display:none;">
                    </label>
                    <div class="profile-photo-hint">Maximum file size: 2MB</div>
                    <?php if (!empty($data['profile_pic_err'])): ?>
                        <div class="error-msg"><?php echo $data['profile_pic_err']; ?></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Personal Details -->
        <div class="profile-card">
            <h2 class="card-title">Personal Details</h2>
            <div class="profile-fields">
                <div class="field-group">
                    <label for="member_id">Member ID</label>
                    <input type="text" id="member_id" value="<?php echo htmlspecialchars($data['member_id'] ?? ($_SESSION['user_id'] ?? '')); ?>" readonly>
                </div>
                <div class="field-group">
                    <label for="username">Username</label>
                    <input type="text" name="username" id="username"
                           value="<?php echo htmlspecialchars($data['username'] ?? ''); ?>" required>
                    <?php if (!empty($data['username_err'])): ?>
                        <span class="error-msg"><?php echo $data['username_err']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="profile-fields">
                <div class="field-group">
                    <label for="email">Email</label>
                    <input type="email" name="email" id="email"
                           value="<?php echo htmlspecialchars($data['email'] ?? ''); ?>" required>
                    <?php if (!empty($data['email_err'])): ?>
                        <span class="error-msg"><?php echo $data['email_err']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="field-group">
                    <label for="phone">Phone Number</label>
                    <input type="text" name="phone" id="phone"
                           value="<?php echo htmlspecialchars($data['phone'] ?? ''); ?>">
                    <?php if (!empty($data['phone_err'])): ?>
                        <span class="error-msg"><?php echo $data['phone_err']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <div class="profile-fields">
                <div class="field-group" style="flex:2;">
                    <label for="address">Address</label>
                    <input type="text" name="address" id="address"
                           value="<?php echo htmlspecialchars($data['address'] ?? ''); ?>">
                    <?php if (!empty($data['address_err'])): ?>
                        <span class="error-msg"><?php echo $data['address_err']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <button class="save-btn" type="submit" name="save_profile">Save Changes</button>
        </div>

        <!-- Change Password -->
        <div class="profile-card">
            <h2 class="card-title">Change Password</h2>
            <div class="profile-fields">
                <div class="field-group">
                    <label for="password">New Password</label>
                    <input type="password" name="password" id="password" placeholder="New Password">
                    <?php if (!empty($data['password_err'])): ?>
                        <span class="error-msg"><?php echo $data['password_err']; ?></span>
                    <?php endif; ?>
                </div>
                <div class="field-group">
                    <label for="confirm_password">Confirm New Password</label>
                    <input type="password" name="confirm_password" id="confirm_password" placeholder="Confirm New Password">
                    <?php if (!empty($data['confirm_password_err'])): ?>
                        <span class="error-msg"><?php echo $data['confirm_password_err']; ?></span>
                    <?php endif; ?>
                </div>
            </div>
            <button class="save-btn" type="submit" name="save_password">Update Password</button>
        </div>
    </form>
</div>
</body>
<?php require APPROOT . '/views/inc/footer.php'; ?> 

