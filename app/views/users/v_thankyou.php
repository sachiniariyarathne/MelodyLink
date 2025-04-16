<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container py-5">
    <div class="row">
        <div class="col-md-8 mx-auto">
            <div class="card shadow-sm">
                <div class="card-body text-center py-5">
                    <div class="mb-4">
                        <i class="fa fa-check-circle text-success" style="font-size: 80px;"></i>
                    </div>
                    <h1 class="card-title mb-4">Thank You for Your Purchase!</h1>
                    
                    <p class="lead">Your order has been successfully placed.</p>
                    
                    <div class="order-details text-left my-4 p-4 bg-light rounded">
                        <h4>Order Details</h4>
                        <hr>
                        <p><strong>Order ID:</strong> <?php echo $data['order_details']['order_id']; ?></p>
                        <p><strong>Name:</strong> <?php echo $data['order_details']['name']; ?></p>
                        <p><strong>Email:</strong> <?php echo $data['order_details']['email']; ?></p>
                        
                        <h5 class="mt-4">Items Purchased:</h5>
                        <div class="table-responsive">
                            <table class="table table-sm">
                                <thead>
                                    <tr>
                                        <th>Item</th>
                                        <th>Quantity</th>
                                        <th class="text-right">Price</th>
                                        <th class="text-right">Subtotal</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php 
                                    $total = 0;
                                    foreach ($data['order_details']['items'] as $item): 
                                        $itemTotal = $item['price'] * $item['quantity'];
                                        $total += $itemTotal;
                                    ?>
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                <?php if (!empty($item['image'])): ?>
                                                <img src="<?php echo URLROOT; ?>/public/images/<?php echo $item['image']; ?>" 
                                                     alt="<?php echo $item['name']; ?>" 
                                                     class="img-thumbnail mr-2" 
                                                     style="width: 50px; height: 50px; object-fit: cover;">
                                                <?php endif; ?>
                                                <?php echo $item['name']; ?>
                                            </div>
                                        </td>
                                        <td><?php echo $item['quantity']; ?></td>
                                        <td class="text-right">$<?php echo number_format($item['price'], 2); ?></td>
                                        <td class="text-right">$<?php echo number_format($itemTotal, 2); ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <th colspan="3" class="text-right">Total:</th>
                                        <th class="text-right">$<?php echo number_format($data['order_details']['total'], 2); ?></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <p>A confirmation email has been sent to <strong><?php echo $data['order_details']['email']; ?></strong></p>
                    
                    <div class="mt-4">
                        <p>If you have any questions about your order, please don't hesitate to contact us.</p>
                        <a href="<?php echo URLROOT; ?>/merchandise" class="btn btn-primary">Continue Shopping</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<?php require APPROOT . '/views/inc/footer.php'; ?>