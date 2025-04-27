<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="profile-container">
    <!-- Profile Header -->
    <div class="profile-header">
        <div class="profile-image-container">
            <?php if(!empty($data['profile']->profile_image)) : ?>
                <img src="<?php echo URLROOT; ?>/uploads/profiles/<?php echo $data['profile']->profile_image; ?>" alt="Profile Image" class="profile-image">
            <?php else : ?>
                <img src="<?php echo URLROOT; ?>/img/default-profile.jpg" alt="Default Profile" class="profile-image">
            <?php endif; ?>
            
            <div class="upload-overlay">
                <form action="<?php echo URLROOT; ?>/eqpsupplierprofile/uploadImage" method="POST" enctype="multipart/form-data" id="imageUploadForm">
                    <input type="file" name="profile_image" id="profile_image" class="hidden-file-input">
                    <button type="button" class="upload-btn" onclick="document.getElementById('profile_image').click()">Upload</button>
                </form>
            </div>
        </div>
        
        <div class="profile-info">
            <h1><?php echo $data['profile']->company_name; ?></h1>
            <p class="profile-subtitle">Event Equipment Supplier</p>
            <p class="profile-owner">Owner: <?php echo $data['profile']->owner_name; ?></p>
            
            <div class="profile-stats">
                <div class="stat-item">
                    <span class="stat-value">156</span>
                    <span class="stat-label">Products</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">24</span>
                    <span class="stat-label">Active Orders</span>
                </div>
                <div class="stat-item">
                    <span class="stat-value">4.8</span>
                    <span class="stat-label">Rating</span>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Profile Content -->
    <div class="profile-content">
        <!-- Display messages -->
        <?php if(isset($_SESSION['profile_success'])) : ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['profile_success']; 
                unset($_SESSION['profile_success']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['profile_error'])) : ?>
            <div class="alert alert-danger">
                <?php 
                echo $_SESSION['profile_error']; 
                unset($_SESSION['profile_error']);
                ?>
            </div>
        <?php endif; ?>
        
        <div class="profile-section">
            <h2>Contact Information</h2>
            <div class="contact-info">
                <div class="info-item">
                    <i class="fas fa-envelope"></i>
                    <span><?php echo $data['profile']->email; ?></span>
                </div>
                <div class="info-item">
                    <i class="fas fa-phone"></i>
                    <span><?php echo $data['profile']->phone; ?></span>
                </div>
                <div class="info-item">
                    <i class="fas fa-map-marker-alt"></i>
                    <span><?php echo $data['profile']->address; ?></span>
                </div>
            </div>
        </div>
        
        <div class="profile-section">
            <h2>About Us</h2>
            <p><?php echo $data['profile']->bio; ?></p>
        </div>
        
        <div class="profile-actions">
            <button class="edit-profile-btn" onclick="openEditModal()">Edit Profile</button>
        </div>
    </div>
    
    <!-- Edit Profile Modal -->
    <div id="editProfileModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeEditModal()">&times;</span>
            <h2>Edit Profile</h2>
            
            <form action="<?php echo URLROOT; ?>/eqpsupplierprofile/updateProfile" method="POST">
                <div class="form-group">
                    <label for="company_name">Company Name</label>
                    <input type="text" id="company_name" name="company_name" value="<?php echo $data['profile']->company_name; ?>" required>
                    <span class="invalid-feedback"><?php echo isset($data['company_name_err']) ? $data['company_name_err'] : ''; ?></span>
                </div>
                
                <div class="form-group">
                    <label for="owner_name">Owner Name</label>
                    <input type="text" id="owner_name" name="owner_name" value="<?php echo $data['profile']->owner_name; ?>" required>
                    <span class="invalid-feedback"><?php echo isset($data['owner_name_err']) ? $data['owner_name_err'] : ''; ?></span>
                </div>
                
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" value="<?php echo $data['profile']->email; ?>" required>
                    <span class="invalid-feedback"><?php echo isset($data['email_err']) ? $data['email_err'] : ''; ?></span>
                </div>
                
                <div class="form-group">
                    <label for="phone">Phone</label>
                    <input type="text" id="phone" name="phone" value="<?php echo $data['profile']->phone; ?>" required>
                    <span class="invalid-feedback"><?php echo isset($data['phone_err']) ? $data['phone_err'] : ''; ?></span>
                </div>
                
                <div class="form-group">
                    <label for="address">Address</label>
                    <input type="text" id="address" name="address" value="<?php echo $data['profile']->address; ?>">
                </div>
                
                <div class="form-group">
                    <label for="bio">Bio</label>
                    <textarea id="bio" name="bio" rows="4"><?php echo $data['profile']->bio; ?></textarea>
                </div>
                
                <div class="form-group">
                    <button type="submit" class="submit-btn">Update Profile</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- CSS for Profile Page -->
<style>
    .profile-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 2rem;
        color: #f3f4f6;
    }
    
    .profile-header {
        display: flex;
        align-items: center;
        gap: 2rem;
        background: #1e1e3f;
        border-radius: 12px;
        padding: 2rem;
        margin-bottom: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .profile-image-container {
        position: relative;
        width: 150px;
        height: 150px;
        border-radius: 50%;
        overflow: hidden;
        border: 4px solid #2d2b5f;
    }
    
    .profile-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
    }
    
    .upload-overlay {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        background: rgba(0, 0, 0, 0.7);
        padding: 0.5rem;
        text-align: center;
        transform: translateY(100%);
        transition: transform 0.3s ease;
    }
    
    .profile-image-container:hover .upload-overlay {
        transform: translateY(0);
    }
    
    .hidden-file-input {
        display: none;
    }
    
    .upload-btn {
        background: #a78bfa;
        color: white;
        border: none;
        padding: 0.5rem 1rem;
        border-radius: 4px;
        cursor: pointer;
        font-size: 0.8rem;
    }
    
    .profile-info {
        flex: 1;
    }
    
    .profile-info h1 {
        margin: 0;
        font-size: 2rem;
        font-weight: 700;
    }
    
    .profile-subtitle {
        color: #a78bfa;
        margin: 0.5rem 0;
        font-size: 1.1rem;
    }
    
    .profile-owner {
        margin: 0.5rem 0 1.5rem;
        font-size: 1rem;
        color: #9ca3af;
    }
    
    .profile-stats {
        display: flex;
        gap: 2rem;
    }
    
    .stat-item {
        display: flex;
        flex-direction: column;
        align-items: center;
    }
    
    .stat-value {
        font-size: 1.5rem;
        font-weight: 700;
    }
    
    .stat-label {
        font-size: 0.9rem;
        color: #9ca3af;
    }
    
    .profile-content {
        background: #1e1e3f;
        border-radius: 12px;
        padding: 2rem;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
    }
    
    .profile-section {
        margin-bottom: 2rem;
    }
    
    .profile-section h2 {
        font-size: 1.25rem;
        margin-bottom: 1rem;
        color: #a78bfa;
        border-bottom: 1px solid #2d2b5f;
        padding-bottom: 0.5rem;
    }
    
    .contact-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1rem;
    }
    
    .info-item {
        display: flex;
        align-items: center;
        gap: 0.75rem;
    }
    
    .info-item i {
        color: #a78bfa;
        font-size: 1.2rem;
        width: 24px;
        text-align: center;
    }
    
    .profile-actions {
        display: flex;
        justify-content: flex-end;
        margin-top: 2rem;
    }
    
    .edit-profile-btn {
        background: #a78bfa;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 500;
        transition: background 0.2s ease;
    }
    
    .edit-profile-btn:hover {
        background: #8b5cf6;
    }
    
    /* Modal Styles */
    .modal {
        display: none;
        position: fixed;
        z-index: 1000;
        left: 0;
        top: 0;
        width: 100%;
        height: 100%;
        overflow: auto;
        background-color: rgba(0, 0, 0, 0.7);
    }
    
    .modal-content {
        background: #1e1e3f;
        margin: 5% auto;
        padding: 2rem;
        border-radius: 12px;
        width: 80%;
        max-width: 700px;
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
        animation: modalFadeIn 0.3s;
    }
    
    @keyframes modalFadeIn {
        from {opacity: 0; transform: translateY(-50px);}
        to {opacity: 1; transform: translateY(0);}
    }
    
    .close {
        color: #9ca3af;
        float: right;
        font-size: 28px;
        font-weight: bold;
        cursor: pointer;
    }
    
    .close:hover {
        color: #f3f4f6;
    }
    
    .form-group {
        margin-bottom: 1.5rem;
    }
    
    .form-group label {
        display: block;
        margin-bottom: 0.5rem;
        font-weight: 500;
    }
    
    .form-group input, .form-group textarea {
        width: 100%;
        padding: 0.75rem;
        border-radius: 6px;
        border: 1px solid #2d2b5f;
        background: #252550;
        color: #f3f4f6;
        font-family: inherit;
    }
    
    .form-group input:focus, .form-group textarea:focus {
        outline: none;
        border-color: #a78bfa;
    }
    
    .invalid-feedback {
        color: #ef4444;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        display: block;
    }
    
    .submit-btn {
        background: #a78bfa;
        color: white;
        border: none;
        padding: 0.75rem 1.5rem;
        border-radius: 8px;
        cursor: pointer;
        font-size: 1rem;
        font-weight: 500;
        transition: background 0.2s ease;
        width: 100%;
    }
    
    .submit-btn:hover {
        background: #8b5cf6;
    }
    
    .alert {
        padding: 1rem;
        margin-bottom: 1.5rem;
        border-radius: 8px;
    }
    
    .alert-success {
        background: rgba(16, 185, 129, 0.2);
        color: #10b981;
        border: 1px solid rgba(16, 185, 129, 0.3);
    }
    
    .alert-danger {
        background: rgba(239, 68, 68, 0.2);
        color: #ef4444;
        border: 1px solid rgba(239, 68, 68, 0.3);
    }
    
    /* Responsive Styles */
    @media (max-width: 768px) {
        .profile-header {
            flex-direction: column;
            text-align: center;
        }
        
        .profile-stats {
            justify-content: center;
        }
        
        .profile-image-container {
            margin: 0 auto;
        }
    }
</style>

<!-- JavaScript for Modal and Image Upload -->
<script>
    // Auto-submit form when file is selected
    document.getElementById('profile_image').addEventListener('change', function() {
        document.getElementById('imageUploadForm').submit();
    });
    
    // Modal functions
    function openEditModal() {
        document.getElementById('editProfileModal').style.display = 'block';
    }
    
    function closeEditModal() {
        document.getElementById('editProfileModal').style.display = 'none';
    }
    
    // Close modal when clicking outside of it
    window.onclick = function(event) {
        let modal = document.getElementById('editProfileModal');
        if (event.target == modal) {
            modal.style.display = 'none';
        }
    }
</script>

