<?php require APPROOT.'/views/inc/header.php';?>

<body background>
<div class="reg-container">
    <div class="reg-illustration">
        <div class="reg-circles">
            <div></div>
            <div></div>
            <div></div>
        </div>
        <h2>Register</h2>
    </div>  

    <div class="reg-form-side">
        <form class="reg-form" action="<?php echo URLROOT; ?>/users/register" method="POST">
            <div class="reg-form-group">
                <label for="fullname">Full Name</label>
                <input type="text" id="fullname" name="name" value="<?php echo $data['name']; ?>" required>
                <span class="form-invalid"><?php echo $data['name_err']; ?></span>
            </div>

            <div class="reg-form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" value="<?php echo $data['email']; ?>" required>
                <span class="form-invalid"><?php echo $data['email_err']; ?></span>
            </div>

            <div class="reg-form-group">
                <label for="password">Password</label>
                <input type="password" name="password" id="password" required>
                <span class="form-invalid"><?php echo $data['password_err']; ?></span>
            </div>

            <div class="reg-form-group">
                <label for="confirm_password">Confirm Password</label>
                <input type="password" id="confirm_password" name="confirm_password" required>
                <span class="form-invalid"><?php echo $data['confirm_password_err']; ?></span>
            </div>

            <div class="reg-form-group">
                <label for="userType">Register as</label>
                <select id="userType" name="userType" required>
                    <option value="" <?php echo empty($data['userType']) ? 'selected' : ''; ?>>Select User Type</option>
                    <option value="member" <?php echo $data['userType'] == 'member' ? 'selected' : ''; ?>>Member</option>
                    <option value="artist" <?php echo $data['userType'] == 'artist' ? 'selected' : ''; ?>>Artist</option>
                    <option value="organizer" <?php echo $data['userType'] == 'organizer' ? 'selected' : ''; ?>>Event Organizer</option>
                    <option value="supplier" <?php echo $data['userType'] == 'supplier' ? 'selected' : ''; ?>>Event Equipment Supplier</option>
                    <option value="merchandise_vendor"><?php echo $data['userType'] == 'merchandise_vendor' ? 'selected' : ''; ?>Merchandise Vendor</option>

                </select>
                <span class="form-invalid"><?php echo $data['userType_err']; ?></span>
            </div>

            <!-- Conditional fields -->
            <div id="reg-artistFields" class="reg-form-group" style="display: <?php echo $data['userType'] == 'artist' ? 'block' : 'none'; ?>">
                <label for="specialty">Artist Genre</label>
                <input type="text" id="specialty" name="specialty" value="<?php echo $data['genre'] ?? ''; ?>">
            </div>

            <div id="reg-organizerFields" class="reg-form-group" style="display: <?php echo $data['userType'] == 'organizer' ? 'block' : 'none'; ?>">
                <label for="organization">Organization Name</label>
                <input type="text" id="organization" name="organization" value="<?php echo $data['organization'] ?? ''; ?>">
            </div>

            <div id="reg-supplierFields" class="reg-form-group" style="display: <?php echo $data['userType'] == 'supplier' ? 'block' : 'none'; ?>">
                <label for="business_type">Business Type</label>
                <input type="text" id="business_type" name="business_type" value="<?php echo $data['business_type'] ?? ''; ?>">
            </div>

            <div id="product_category_field" style="display: none;">
                <label for="product_category">Product Category</label>
                <input type="text" name="product_category" id="product_category">
            </div>

            <button type="submit">Register</button>
        </form>
    </div>
</div>

<!-- <script src="<?php echo URLROOT; ?>/js/register.js"></script> -->
</body>
<script>
    document.getElementById('userType').addEventListener('change', function() {
        // Hide all specific fields first
        document.getElementById('product_category_field').style.display = 'none';
        document.getElementById('business_type_field').style.display = 'none';
        // Show relevant field based on selection
        if (this.value === 'merchandise_vendor') {
            document.getElementById('product_category_field').style.display = 'block';
        } else if (this.value === 'supplier') {
            document.getElementById('business_type_field').style.display = 'block';
        }
    });
    </script>