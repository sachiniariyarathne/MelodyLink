<?php require APPROOT . '/views/inc/headerind.php'; ?>

<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card">
                <div class="card-header bg-primary text-white">
                    <h2 class="card-title">Edit Your Profile</h2>
                </div>
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/supplierProfile/edit" method="post">
                        <div class="form-group mb-3">
                            <label for="username">Username: <sup>*</sup></label>
                            <input type="text" name="username" class="form-control <?php echo (!empty($data['username_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['username']; ?>">
                            <span class="invalid-feedback"><?php echo $data['username_err']; ?></span>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="email">Email: <sup>*</sup></label>
                            <input type="email" name="email" class="form-control <?php echo (!empty($data['email_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['email']; ?>">
                            <span class="invalid-feedback"><?php echo $data['email_err']; ?></span>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="phone_number">Phone Number: <sup>*</sup></label>
                            <input type="text" name="phone_number" class="form-control <?php echo (!empty($data['phone_number_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['phone_number']; ?>">
                            <span class="invalid-feedback"><?php echo $data['phone_number_err']; ?></span>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="address">Address: <sup>*</sup></label>
                            <textarea name="address" class="form-control <?php echo (!empty($data['address_err'])) ? 'is-invalid' : ''; ?>"><?php echo $data['address']; ?></textarea>
                            <span class="invalid-feedback"><?php echo $data['address_err']; ?></span>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="company_name">Company/Shop Name: <sup>*</sup></label>
                            <input type="text" name="company_name" class="form-control <?php echo (!empty($data['company_name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['company_name']; ?>">
                            <span class="invalid-feedback"><?php echo $data['company_name_err']; ?></span>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="business_type">Business Type: <sup>*</sup></label>
                            <input type="text" name="business_type" class="form-control <?php echo (!empty($data['business_type_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['business_type']; ?>">
                            <span class="invalid-feedback"><?php echo $data['business_type_err']; ?></span>
                        </div>
                        
                        <hr>
                        <h4>Change Password (Optional)</h4>
                        
                        <div class="form-group mb-3">
                            <label for="current_password">Current Password:</label>
                            <input type="password" name="current_password" class="form-control <?php echo (!empty($data['current_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['current_password']; ?>">
                            <span class="invalid-feedback"><?php echo $data['current_password_err']; ?></span>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="new_password">New Password:</label>
                            <input type="password" name="new_password" class="form-control <?php echo (!empty($data['new_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['new_password']; ?>">
                            <span class="invalid-feedback"><?php echo $data['new_password_err']; ?></span>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="confirm_password">Confirm New Password:</label>
                            <input type="password" name="confirm_password" class="form-control <?php echo (!empty($data['confirm_password_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['confirm_password']; ?>">
                            <span class="invalid-feedback"><?php echo $data['confirm_password_err']; ?></span>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col">
                                <input type="submit" value="Update Profile" class="btn btn-success">
                                <a href="<?php echo URLROOT; ?>/supplierProfile" class="btn btn-light">Cancel</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>