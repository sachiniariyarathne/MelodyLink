<?php require APPROOT . '/views/inc/header4.php'; ?> 
<?php require APPROOT . '/views/inc/components/topnavbar_artist.php'; ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <title>Artist Profile</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
<div class="profile-container">
    <div class="profile-title">My Artist Profile</div>
    <div class="profile-desc">View and manage your artist account details</div>
    <?php flash('profile_message'); ?>
    <div class="profile-card">
        <div class="profile-header">
            <div class="profile-image-container">
                <img class="profile-image" src="<?php echo URLROOT; ?>/public/uploads/<?php echo $data['artist']->profile_pic ?? 'default-avatar.png'; ?>" alt="Artist">
            </div>
            <div class="profile-header-info">
                <div class="profile-name"><?php echo htmlspecialchars($data['artist']->Username); ?></div>
                <div class="profile-status"><?php echo htmlspecialchars($data['artist']->Genre); ?></div>
                <div class="verification-status <?php echo $data['artist']->Verification_status; ?>">
                    <?php
                        $status = $data['artist']->Verification_status;
                        if ($status === 'verified') {
                            echo '<i class="fa fa-badge-check verified"></i> Verified Artist';
                        } elseif ($status === 'pending') {
                            echo '<i class="fa fa-hourglass-half pending"></i> Verification Pending';
                        } else {
                            echo '<i class="fa fa-times-circle not_verified"></i> Not Verified';
                        }
                    ?>
                </div>
                <?php if ($status !== 'verified'): ?>
                <form class="verification-upload" action="<?php echo URLROOT; ?>/Artist_Profile/upload_verification" method="POST" enctype="multipart/form-data">
                    <label for="verification_doc"><b>Submit document for verification:</b></label><br>
                    <input type="file" name="verification_doc" id="verification_doc" accept=".pdf,.jpg,.jpeg,.png" required>
                    <button type="submit"><i class="fa fa-upload"></i> Upload</button>
                    <?php if (!empty($data['artist']->verification_document)): ?>
                        <a class="verification-doc-link" href="<?php echo URLROOT; ?>/public/uploads/verification_docs/<?php echo $data['artist']->verification_document; ?>" target="_blank">
                            View submitted document
                        </a>
                    <?php endif; ?>
                </form>
                <?php endif; ?>
            </div>
        </div>
        <div class="profile-details">
            <div class="details-section">
                <h3>Account Information</h3>
                <div class="detail-item">
                    <div class="detail-label">Artist ID</div>
                    <div class="detail-value"><?php echo $data['artist']->Artist_id; ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Username</div>
                    <div class="detail-value"><?php echo htmlspecialchars($data['artist']->Username); ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Email</div>
                    <div class="detail-value"><?php echo htmlspecialchars($data['artist']->Email); ?></div>
                </div>
            </div>
            <div class="details-section">
                <h3>Contact Information</h3>
                <div class="detail-item">
                    <div class="detail-label">Phone Number</div>
                    <div class="detail-value"><?php echo $data['artist']->Phone_number ?? '<span class="not-provided">Not provided</span>'; ?></div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Address</div>
                    <div class="detail-value"><?php echo $data['artist']->Address ?? '<span class="not-provided">Not provided</span>'; ?></div>
                </div>
            </div>
            <div class="account-actions">
                <a href="<?php echo URLROOT; ?>/Artist_Profile/update" class="action-btn primary-btn"><i class="fa fa-edit"></i> Edit Profile</a>
                <a href="<?php echo URLROOT; ?>/Artist_Profile/update" class="action-btn secondary-btn"><i class="fa fa-key"></i> Change Password</a>
            </div>
        </div>
    </div>
</div>
</body>
</html>
<?php require APPROOT . '/views/inc/footer.php'; ?> 
