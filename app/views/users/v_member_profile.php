<?php require APPROOT . '/views/inc/header3.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_member.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>My Profile</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <!-- Font Awesome for icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
    <link rel="stylesheet" href="/public/css/profile.css">
</head>
<body>
    <div class="profile-container">
        <h1 class="profile-title">My Profile</h1>
        <p class="profile-desc">View your personal information and account details</p>

        <div class="profile-card">
            <div class="profile-header">
                <div class="profile-image-container">
                <img src="<?php echo URLROOT; ?>/public/uploads/img/<?php echo !empty($data['profile_pic']) ? $data['profile_pic'] : 'default-avatar.png'; ?>" alt="Profile Photo" class="profile-avatar"style="width:120px;height:120px;object-fit:cover;border-radius:50%;border:3px solid #8b5cf6;">
                </div>
                <div class="profile-header-info">
                    <h2 class="profile-name"><?php echo htmlspecialchars($data['username']); ?></h2>
                </div>
            </div>

            <div class="profile-details">
                <div class="details-section">
                    <h3>Account Information</h3>
                    
                    <div class="detail-item">
                        <div class="detail-label">Member ID</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['member_id']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Username</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['username']); ?></div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Email</div>
                        <div class="detail-value"><?php echo htmlspecialchars($data['email']); ?></div>
                    </div>
                </div>

                <div class="details-section">
                    <h3>Contact Information</h3>
                    
                    <div class="detail-item">
                        <div class="detail-label">Phone Number</div>
                        <div class="detail-value">
                            <?php echo !empty($data['phone_number']) ? htmlspecialchars($data['phone_number']) : '<span class="not-provided">Not provided</span>'; ?>
                        </div>
                    </div>
                    
                    <div class="detail-item">
                        <div class="detail-label">Address</div>
                        <div class="detail-value">
                            <?php echo !empty($data['address']) ? htmlspecialchars($data['address']) : '<span class="not-provided">Not provided</span>'; ?>
                        </div>
                    </div>
                </div>
                
                <div class="details-section account-actions">
                    <a href="<?php echo URLROOT; ?>/Member_Profile/update" class="action-btn primary-btn">
                        <i class="fa fa-edit"></i> Edit Profile
                    </a>
                    <a href="<?php echo URLROOT; ?>/Member_Profile/update#password" class="action-btn secondary-btn">
                        <i class="fa fa-key"></i> Change Password
                    </a>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
<?php require APPROOT . '/views/inc/footer.php'; ?> 