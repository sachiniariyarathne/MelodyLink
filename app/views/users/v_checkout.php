<?php require APPROOT . '/views/inc/header.php'; ?>

<div class="container py-4">
    <h1 class="mb-4">Checkout</h1>
    
    <?php if(isset($_SESSION['checkout_message'])): ?>
        <div class="alert alert-success">
            <?php echo $_SESSION['checkout_message']; ?>
            <?php unset($_SESSION['checkout_message']); ?>
        </div>
    <?php endif; ?>
    
    <?php if(isset($_SESSION['checkout_error'])): ?>
        <div class="alert alert-danger">
            <?php echo $_SESSION['checkout_error']; ?>
            <?php unset($_SESSION['checkout_error']); ?>
        </div>
    <?php endif; ?>
    
    <div class="row">
        <!-- Order Summary -->
        <div class="col-md-5 order-md-2 mb-4">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Order Summary</h4>
                </div>
                <div class="card-body">
                    <ul class="list-group mb-3">
                        <?php foreach($data['cartItems'] as $item): ?>
                            <li class="list-group-item d-flex justify-content-between lh-condensed">
                                <div>
                                    <h6 class="my-0"><?php echo $item['name']; ?></h6>
                                    <small class="text-muted">Quantity: <?php echo $item['quantity']; ?></small>
                                </div>
                                <span class="text-muted">$<?php echo number_format($item['price'] * $item['quantity'], 2); ?></span>
                            </li>
                        <?php endforeach; ?>
                        
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Subtotal</span>
                            <strong>$<?php echo number_format($data['subtotal'], 2); ?></strong>
                        </li>
                        
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Tax (8%)</span>
                            <strong>$<?php echo number_format($data['tax'], 2); ?></strong>
                        </li>
                        
                        <li class="list-group-item d-flex justify-content-between">
                            <span>Shipping</span>
                            <strong>$<?php echo number_format($data['shipping'], 2); ?></strong>
                        </li>
                        
                        <li class="list-group-item d-flex justify-content-between bg-light">
                            <span class="text-success">Total (USD)</span>
                            <strong class="text-success">$<?php echo number_format($data['total'], 2); ?></strong>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <!-- Checkout Form -->
        <div class="col-md-7 order-md-1">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">Billing Address</h4>
                </div>
                <div class="card-body">
                    <form action="<?php echo URLROOT; ?>/merchandise/process" method="POST">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="firstName">First name</label>
                                <input type="text" class="form-control" id="firstName" name="firstName" value="<?php echo isset($data['user']->firstName) ? $data['user']->firstName : ''; ?>" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="lastName">Last name</label>
                                <input type="text" class="form-control" id="lastName" name="lastName" value="<?php echo isset($data['user']->lastName) ? $data['user']->lastName : ''; ?>" required>
                            </div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="email">Email</label>
                            <input type="email" class="form-control" id="email" name="email" placeholder="you@example.com" value="<?php echo isset($data['user']->email) ? $data['user']->email : ''; ?>" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address">Address</label>
                            <input type="text" class="form-control" id="address" name="address" placeholder="1234 Main St" required>
                        </div>
                        
                        <div class="mb-3">
                            <label for="address2">Address 2 <span class="text-muted">(Optional)</span></label>
                            <input type="text" class="form-control" id="address2" name="address2" placeholder="Apartment or suite">
                        </div>
                        
                        <div class="row">
                            <div class="col-md-5 mb-3">
                                <label for="country">Country</label>
                                <select class="custom-select d-block w-100" id="country" name="country" required>
                                    <option value="">Choose...</option>
                                    <option value="US">United States</option>
                                    <option value="CA">Canada</option>
                                </select>
                            </div>
                            <div class="col-md-4 mb-3">
                                <label for="state">State</label>
                                <select class="custom-select d-block w-100" id="state" name="state" required>
                                    <option value="">Choose...</option>
                                    <option value="CA">California</option>
                                    <option value="NY">New York</option>
                                    <option value="TX">Texas</option>
                                    <!-- Add more states as needed -->
                                </select>
                            </div>
                            <div class="col-md-3 mb-3">
                                <label for="zip">Zip</label>
                                <input type="text" class="form-control" id="zip" name="zip" required>
                            </div>
                        </div>
                        
                        <hr class="mb-4">
                        
                        <h4 class="mb-3">Payment</h4>
                        
                        <div class="d-block my-3">
                            <div class="custom-control custom-radio">
                                <input id="credit" name="paymentMethod" type="radio" class="custom-control-input" value="credit" checked required>
                                <label class="custom-control-label" for="credit">Credit card</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input id="debit" name="paymentMethod" type="radio" class="custom-control-input" value="debit" required>
                                <label class="custom-control-label" for="debit">Debit card</label>
                            </div>
                            <div class="custom-control custom-radio">
                                <input id="paypal" name="paymentMethod" type="radio" class="custom-control-input" value="paypal" required>
                                <label class="custom-control-label" for="paypal">PayPal</label>
                            </div>
                        </div>
                        
                        <!-- Credit card information (shown conditionally based on payment method) -->
                        <div id="creditCardInfo">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label for="cc-name">Name on card</label>
                                    <input type="text" class="form-control" id="cc-name" name="cc-name">
                                    <small class="text-muted">Full name as displayed on card</small>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label for="cc-number">Credit card number</label>
                                    <input type="text" class="form-control" id="cc-number" name="cc-number">
                                </div>
                            </div>
                            
                            <div class="row">
                                <div class="col-md-3 mb-3">
                                    <label for="cc-expiration">Expiration</label>
                                    <input type="text" class="form-control" id="cc-expiration" name="cc-expiration" placeholder="MM/YY">
                                </div>
                                <div class="col-md-3 mb-3">
                                    <label for="cc-cvv">CVV</label>
                                    <input type="text" class="form-control" id="cc-cvv" name="cc-cvv">
                                </div>
                            </div>
                        </div>
                        
                        <hr class="mb-4">
                        
                        <button class="btn btn-primary btn-lg btn-block" type="submit">
                            <i class="fa fa-credit-card"></i> Place Order
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Simple script to toggle credit card fields based on payment method
    document.addEventListener('DOMContentLoaded', function() {
        const paymentMethods = document.querySelectorAll('input[name="paymentMethod"]');
        const creditCardInfo = document.getElementById('creditCardInfo');
        
        function toggleCreditCardFields() {
            const selectedMethod = document.querySelector('input[name="paymentMethod"]:checked').value;
            
            if (selectedMethod === 'credit' || selectedMethod === 'debit') {
                creditCardInfo.style.display = 'block';
                document.getElementById('cc-name').required = true;
                document.getElementById('cc-number').required = true;
                document.getElementById('cc-expiration').required = true;
                document.getElementById('cc-cvv').required = true;
            } else {
                creditCardInfo.style.display = 'none';
                document.getElementById('cc-name').required = false;
                document.getElementById('cc-number').required = false;
                document.getElementById('cc-expiration').required = false;
                document.getElementById('cc-cvv').required = false;
            }
        }
        
        // Initialize
        toggleCreditCardFields();
        
        // Add event listeners
        paymentMethods.forEach(method => {
            method.addEventListener('change', toggleCreditCardFields);
        });
    });
</script>

