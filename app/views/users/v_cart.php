<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Shopping Cart</title>
    <!-- <link rel="stylesheet" href="<?php echo URLROOT; ?>/css/style.css"> -->
   
</head>
<body>
    <?php require APPROOT . '/views/inc/header2.php'; ?>
    
    <div class="container">
        <h1>Your Shopping Cart</h1>
        
        <?php if(isset($_SESSION['cart_message'])): ?>
            <div class="alert alert-success">
                <?php 
                    echo $_SESSION['cart_message']; 
                    unset($_SESSION['cart_message']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if(isset($_SESSION['cart_error'])): ?>
            <div class="alert alert-danger">
                <?php 
                    echo $_SESSION['cart_error']; 
                    unset($_SESSION['cart_error']);
                ?>
            </div>
        <?php endif; ?>
        
        <?php if(empty($data['cartItems'])): ?>
            <div class="empty-cart">
                <p>Your cart is empty</p>
                <a href="<?php echo URLROOT; ?>/merchandise" class="btn btn-primary">Continue Shopping</a>
            </div>
        <?php else: ?>
            <div class="cart-items">
                <table class="cart-table">
                    <thead>
                        <tr>
                            <th>Product</th>
                            <th>Price</th>
                            <th>Quantity</th>
                            <th>Total</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php 
                        $totalAmount = 0;
                        foreach($data['cartItems'] as $item): 
                            $itemTotal = $item['price'] * $item['quantity'];
                            $totalAmount += $itemTotal;
                        ?>
                            <tr>
                                <td>
                                    <div class="cart-product">
                                        <?php if(!empty($item['image'])): ?>
                                            <img src="<?php echo URLROOT; ?>/img/<?php echo $item['image']; ?>" alt="<?php echo $item['name']; ?>" class="cart-product-image">
                                        <?php endif; ?>
                                        <span class="cart-product-name"><?php echo $item['name']; ?></span>
                                    </div>
                                </td>
                                <td>Rs<?php echo number_format($item['price'], 2); ?></td>
                                <td>
                                    <form action="<?php echo URLROOT; ?>/cart/updateQuantity" method="post" class="quantity-form">
                                        <input type="hidden" name="merch_id" value="<?php echo $item['id']; ?>">
                                        <input type="number" name="quantity" value="<?php echo $item['quantity']; ?>" min="1" class="quantity-input">
                                        <button type="submit" class="btn btn-sm btn-update">Update</button>
                                    </form>
                                </td>
                                <td>Rs<?php echo number_format($itemTotal, 2); ?></td>
                                <td>
                                    <form action="<?php echo URLROOT; ?>/merchandise/removeFromCart" method="post">
                                        <input type="hidden" name="merch_id" value="<?php echo $item['id']; ?>">
                                        <button type="submit" class="btn btn-sm btn-danger">Remove</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="3" class="text-right"><strong>Total:</strong></td>
                            <td><strong>$<?php echo number_format($totalAmount, 2); ?></strong></td>
                            <td></td>
                        </tr>
                    </tfoot>
                </table>
                
                <div class="cart-actions">
                    <a href="<?php echo URLROOT; ?>/merchandise" class="btn btn-secondary">Continue Shopping</a>
                    <?php if($data['isLoggedIn']): ?>
                        <a href="<?php echo URLROOT; ?>merchandise/checkout" class="btn btn-primary">Proceed to Checkout</a>
                    <?php else: ?>
                        <div class="login-prompt">
                            <p>Please <a href="<?php echo URLROOT; ?>/users/login">login</a> to checkout</p>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        <?php endif; ?>
    </div>
    
    <!-- php require APPROOT . '/views/inc/footer.php' -->

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // You can add JavaScript for quantity adjustments, etc.
        const quantityForms = document.querySelectorAll('.quantity-form');
        quantityForms.forEach(form => {
            const quantityInput = form.querySelector('.quantity-input');
            quantityInput.addEventListener('change', function() {
                form.submit();
            });
        });
    });
    </script>
</body>
</html>