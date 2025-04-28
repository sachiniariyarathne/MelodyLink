<?php require APPROOT . '/views/users/event_management/header.php'; ?>

<style>
    .profile-container {
        max-width: 800px;
        margin: 2rem auto;
        padding: 2rem;
        background: rgba(255, 255, 255, 0.1);
        backdrop-filter: blur(10px);
        border-radius: 15px;
        box-shadow: 0 0 20px rgba(0,0,0,0.2);
        border: 1px solid rgba(255, 255, 255, 0.1);
        padding: 20px;
        margin-top: 100px;
    }

    .profile-header {
        text-align: center;
        margin-bottom: 2rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid rgba(255, 255, 255, 0.1);
    }

    .profile-header h2 {
        color: #fff;
        font-weight: 600;
        margin-bottom: 0.5rem;
    }

    .profile-avatar {
        width: 120px;
        height: 120px;
        border-radius: 50%;
        margin: 0 auto 1rem;
        object-fit: cover;
        border: 4px solid rgba(255, 255, 255, 0.1);
        box-shadow: 0 0 10px rgba(0,0,0,0.2);
    }

    .form-group {
        margin-bottom: 1.5rem;
    }

    .form-group label {
        font-weight: 500;
        color: rgba(255, 255, 255, 0.7);
        margin-bottom: 0.5rem;
    }

    .form-control {
        border-radius: 8px;
        padding: 0.75rem 1rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
        background: rgba(255, 255, 255, 0.05);
        color: #fff;
        transition: all 0.3s ease;
    }

    .form-control:focus {
        border-color: #ff4d94;
        box-shadow: 0 0 0 0.2rem rgba(255, 77, 148, 0.25);
        background: rgba(255, 255, 255, 0.1);
    }

    .form-control:disabled {
        background: rgba(255, 255, 255, 0.05);
        color: rgba(255, 255, 255, 0.5);
    }

    .btn-update {
        background: #ff4d94;
        color: white;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        border: none;
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-update:hover {
        background: #ff2d84;
        transform: translateY(-2px);
    }

    .btn-back {
        background: rgba(255, 255, 255, 0.1);
        color: #fff;
        padding: 0.75rem 2rem;
        border-radius: 8px;
        border: 1px solid rgba(255, 255, 255, 0.1);
        font-weight: 500;
        transition: all 0.3s ease;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, 0.2);
        transform: translateY(-2px);
    }

    .profile-info {
        background: rgba(255, 255, 255, 0.05);
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
        border: 1px solid rgba(255, 255, 255, 0.1);
    }

    .profile-info label {
        color: rgba(255, 255, 255, 0.7);
        font-size: 0.9rem;
    }

    .profile-info input {
        font-weight: 500;
        color: #fff;
    }

    .invalid-feedback {
        color: #ff4d94;
        font-size: 0.875rem;
        margin-top: 0.25rem;
    }

    .is-invalid {
        border-color: #ff4d94 !important;
    }

    .is-invalid:focus {
        box-shadow: 0 0 0 0.2rem rgba(255, 77, 148, 0.25) !important;
    }
</style>

<div class="container">
    <div class="profile-container">
        <div class="profile-header">
            <img src="<?php echo URLROOT; ?>/public/img/avatars/default.png" alt="Profile Avatar" class="profile-avatar">
            <h2>Organizer Profile</h2>
        </div>

        <?php flash('profile_message'); ?>
        
        <form action="<?php echo URLROOT; ?>/eventmanagement/editProfile" method="post">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" name="email" class="form-control" value="<?php echo $data['organizer']->email; ?>" disabled>
            </div>

            <div class="form-group">
                <label for="username">Username</label>
                <input type="text" name="username" class="form-control <?php echo (!empty($data['username_err'] ?? '')) ? 'is-invalid' : ''; ?>" 
                       value="<?php echo $data['organizer']->username; ?>">
                <?php if (!empty($data['username_err'] ?? '')): ?>
                    <div class="invalid-feedback"><?php echo $data['username_err']; ?></div>
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="organization">Organization</label>
                <input type="text" name="organization" class="form-control <?php echo (!empty($data['organization_err'] ?? '')) ? 'is-invalid' : ''; ?>" 
                       value="<?php echo $data['organizer']->Organization; ?>">
                <?php if (!empty($data['organization_err'] ?? '')): ?>
                    <div class="invalid-feedback"><?php echo $data['organization_err']; ?></div>
                <?php endif; ?>
            </div>

            <div class="profile-info">
                <div class="form-group">
                    <label>Account Created</label>
                    <input type="text" class="form-control" value="<?php echo date('F j, Y', strtotime($data['organizer']->created_at)); ?>" disabled>
                </div>

                <div class="form-group">
                    <label>Last Updated</label>
                    <input type="text" class="form-control" value="<?php echo date('F j, Y', strtotime($data['organizer']->updated_at)); ?>" disabled>
                </div>
            </div>

            <div class="d-flex justify-content-between mt-4">
                <button type="submit" class="btn btn-update">
                    <i class="fas fa-save"></i> Update Profile
                </button>
                <a href="<?php echo URLROOT; ?>/eventmanagement" class="btn btn-back">
                    <i class="fas fa-arrow-left"></i> Back to Dashboard
                </a>
            </div>
        </form>
    </div>
</div>

<?php require APPROOT . '/views/users/event_management/footer.php'; ?>