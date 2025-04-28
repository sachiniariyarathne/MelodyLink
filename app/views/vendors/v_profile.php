<?php require APPROOT . '/views/inc/header3.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title">Your Supplier Profile</h2>
                </div>
                <div class="card-body">
                    <?php flash('profile_message'); ?>
                    
                    <div class="profile-info">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Username:</div>
                            <div class="col-md-8"><?php echo $data['userInfo']->Username; ?></div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Email:</div>
                            <div class="col-md-8"><?php echo $data['userInfo']->email; ?></div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Phone Number:</div>
                            <div class="col-md-8"><?php echo $data['userInfo']->Phone_number; ?></div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Address:</div>
                            <div class="col-md-8"><?php echo $data['userInfo']->Address; ?></div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Company/Shop Name:</div>
                            <div class="col-md-8"><?php echo $data['supplierInfo']->company_name ?? 'Not set'; ?></div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Business Type:</div>
                            <div class="col-md-8"><?php echo $data['userInfo']->BusinessType; ?></div>
                        </div>
                        
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Account Status:</div>
                            <div class="col-md-8">
                                <?php if($data['userInfo']->is_active == 1): ?>
                                    <span class="badge bg-success">Active</span>
                                <?php else: ?>
                                    <span class="badge bg-danger">Inactive</span>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="<?php echo URLROOT; ?>/supplierProfile/edit" class="btn btn-primary">Edit Profile</a>
                        <a href="<?php echo URLROOT; ?>/vendorMerchandise" class="btn btn-secondary">Manage Products</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>