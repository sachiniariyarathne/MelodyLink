<?php require APPROOT . '/views/inc/header3.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar_member.php'; ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card" style="border-color: #6610f2;">
                <div class="card-header text-white" style="background-color: #6610f2;">
                    <h2 class="mb-0">Add New Merchandise</h2>
                </div>
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/vendorMerchandise/add" method="post" enctype="multipart/form-data">
                        <div class="form-group mb-3">
                            <label for="name">Product Name <span class="text-danger">*</span></label>
                            <input type="text" name="name" class="form-control <?php echo (!empty($data['name_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['name']; ?>">
                            <span class="invalid-feedback"><?php echo $data['name_err']; ?></span>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="price">Price ($) <span class="text-danger">*</span></label>
                            <input type="number" step="0.01" min="0" name="price" class="form-control <?php echo (!empty($data['price_err'])) ? 'is-invalid' : ''; ?>" value="<?php echo $data['price']; ?>">
                            <span class="invalid-feedback"><?php echo $data['price_err']; ?></span>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="description">Description <span class="text-danger">*</span></label>
                            <textarea name="description" class="form-control <?php echo (!empty($data['description_err'])) ? 'is-invalid' : ''; ?>" rows="4"><?php echo $data['description']; ?></textarea>
                            <span class="invalid-feedback"><?php echo $data['description_err']; ?></span>
                        </div>
                        
                        <div class="form-group mb-3">
                            <label for="image">Product Image <span class="text-danger">*</span></label>
                            <input type="file" name="image" class="form-control <?php echo (!empty($data['image_err'])) ? 'is-invalid' : ''; ?>">
                            <small class="text-muted">Accepted formats: JPG, JPEG, PNG, GIF (Max size: 5MB)</small>
                            <span class="invalid-feedback"><?php echo $data['image_err']; ?></span>
                        </div>
                        
                        <div class="row mt-4">
                            <div class="col">
                                <a href="<?php echo URLROOT; ?>/vendorMerchandise" class="btn btn-secondary">Cancel</a>
                            </div>
                            <div class="col text-end">
                                <button type="submit" class="btn text-white" style="background-color: #6610f2;">Add Product</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>