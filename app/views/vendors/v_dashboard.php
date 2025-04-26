<?php require APPROOT . '/views/inc/header3.php'; ?>
<?php require APPROOT . '/views/inc/components/topnavbar_member.php'; ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-12">
            <h2 class="text-center" style="color: #6610f2;">Merchandise Management</h2>
            <?php flash('merchandise_message'); ?>
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h4>Your Products</h4>
                <a href="<?php echo URLROOT; ?>/vendorMerchandise/add" class="btn" style="background-color: #6610f2; color: white;">Add New Product</a>
            </div>
            
            <?php if(empty($data['merchandise'])) : ?>
                <div class="alert alert-info">You haven't added any products yet.</div>
            <?php else : ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead style="background-color: #6610f2; color: white;">
                            <tr>
                                <th>Image</th>
                                <th>Name</th>
                                <th>Price</th>
                                <th>Description</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach($data['merchandise'] as $item) : ?>
                                <tr>
                                    <td><img src="<?php echo URLROOT; ?>/public/images/<?php echo $item->image; ?>" alt="<?php echo $item->Name; ?>" style="width: 50px; height: 50px;"></td>
                                    <td><?php echo $item->Name; ?></td>
                                    <td>$<?php echo number_format($item->Price, 2); ?></td>
                                    <td><?php echo substr($item->Description, 0, 50) . (strlen($item->Description) > 50 ? '...' : ''); ?></td>
                                    <td>
                                        <a href="<?php echo URLROOT; ?>/vendorMerchandise/edit/<?php echo $item->merch_id; ?>" class="btn btn-sm" style="background-color: #6610f2; color: white;">Edit</a>
                                        
                                        <form class="d-inline" action="<?php echo URLROOT; ?>/vendorMerchandise/delete/<?php echo $item->merch_id; ?>" method="post" onsubmit="return confirm('Are you sure you want to delete this product?');">
                                            <button type="submit" class="btn btn-sm btn-danger">Delete</button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>