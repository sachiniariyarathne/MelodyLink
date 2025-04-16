<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once APPROOT . '\libraries\PHPMailer-master\src\Exception.php';
require_once APPROOT . '\libraries\PHPMailer-master\src\PHPMailer.php';
require_once APPROOT . '\libraries\PHPMailer-master\src\SMTP.php';

class Merchandise extends Controller {
    private $merchandiseModel;
    private $cartModel;
    private $orderModel;

    public function __construct() {
        $this->merchandiseModel = $this->model('m_Merchandise');
        $this->cartModel = $this->model('m_cart');
        $this->orderModel = $this->model('m_order');
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        $this->mail = new PHPMailer(true);
        // Remove or comment out this line:
        // $this->sendOrderConfirmationEmail();
    }

    private function isAuthenticated() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    public function index() {
        $merchandise = $this->merchandiseModel->getAllMerchandise();
        
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        $data = [
            'merchandise' => $merchandise,
            'isLoggedIn' => $this->isAuthenticated(),
            'cartItems' => $_SESSION['cart'] ?? [],
            'user_id' => $_SESSION['user_id'] ?? null
        ];
    
        $this->view('v_merchandise', $data);
    }

    public function addToCart() {
        // Set proper content type for JSON responses
        header('Content-Type: application/json');
        
        try {
            if ($_SERVER['REQUEST_METHOD'] === 'POST') {
                // Get the merchandise ID from POST data
                $merchId = isset($_POST['merch_id']) ? $_POST['merch_id'] : null;
                $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
                
                // Ensure quantity is at least 1
                $quantity = max(1, $quantity);
                
                if (!$merchId) {
                    $_SESSION['cart_error'] = 'Missing merchandise ID';
                    header('Location: ' . URLROOT . '/merchandise');
                    exit;
                }
                
                // Get merchandise details
                $merchandise = $this->merchandiseModel->getMerchandiseById($merchId);
                
                if ($merchandise) {
                    if (!isset($_SESSION['cart'])) {
                        $_SESSION['cart'] = [];
                    }
                    
                    // Add item to session cart
                    if (isset($_SESSION['cart'][$merchId])) {
                        $_SESSION['cart'][$merchId]['quantity'] += $quantity;
                    } else {
                        $_SESSION['cart'][$merchId] = [
                            'id' => $merchId,
                            'name' => $merchandise->Name,
                            'price' => $merchandise->Price,
                            'quantity' => $quantity,
                            'image' => $merchandise->image
                        ];
                    }
                    
                    // Add to database if user is logged in
                    if ($this->isAuthenticated()) {
                        $userId = $_SESSION['user_id'];
                        
                        // Debug logging
                        error_log("Adding to cart for user ID: " . $userId . ", merch ID: " . $merchId);
                        
                        // Ensure user_id is an integer
                        $userId = (int)$userId;
                        $merchId = (int)$merchId;
                        
                        $result = $this->cartModel->addToCart($userId, $merchId, $quantity);
                        
                        if (!$result) {
                            error_log("Failed to add to database cart. User ID: " . $userId . ", Merch ID: " . $merchId);
                            // Continue anyway since the item is at least in the session
                        } else {
                            error_log("Successfully added to database cart");
                        }
                    }
                    
                    $totalItems = array_sum(array_column($_SESSION['cart'], 'quantity'));
                    
                    // Redirect back to merchandise page with success message
                    $_SESSION['cart_message'] = 'Item added to cart successfully';
                    header('Location: ' . URLROOT . '/merchandise');
                    exit;
                } else {
                    $_SESSION['cart_error'] = 'Merchandise not found';
                    header('Location: ' . URLROOT . '/merchandise');
                    exit;
                }
            }
        } catch (Exception $e) {
            error_log('Exception in addToCart: ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            $_SESSION['cart_error'] = 'Server error: ' . $e->getMessage();
            header('Location: ' . URLROOT . '/merchandise');
            exit;
        }
        
        // Default fallback
        $_SESSION['cart_error'] = 'Failed to add item to cart';
        header('Location: ' . URLROOT . '/merchandise');
    }

    public function removeFromCart() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $merchId = isset($_POST['merch_id']) ? $_POST['merch_id'] : null;
            
            if ($merchId && isset($_SESSION['cart'][$merchId])) {
                unset($_SESSION['cart'][$merchId]);
                
                // Remove from database if user is logged in
                if ($this->isAuthenticated()) {
                    $userId = (int)$_SESSION['user_id'];
                    $merchId = (int)$merchId;
                    $this->cartModel->removeFromCart($userId, $merchId);
                }
                
                $_SESSION['cart_message'] = 'Item removed from cart';
            } else {
                $_SESSION['cart_error'] = 'Failed to remove item from cart';
            }
        }
        
        header('Location: ' . URLROOT . '/cart');
        exit;
    }
    
    public function getCartCount() {
        header('Content-Type: application/json');
        $totalItems = 0;
        
        if (isset($_SESSION['cart'])) {
            $totalItems = array_sum(array_column($_SESSION['cart'], 'quantity'));
        }
        
        echo json_encode(['cartCount' => $totalItems]);
    }
    
    // Method to debug the current user session
    public function debugSession() {
        if (!$this->isAuthenticated()) {
            echo "Not logged in";
            return;
        }
        
        echo "User ID: " . $_SESSION['user_id'] . " (Type: " . gettype($_SESSION['user_id']) . ")<br>";
        echo "Session cart items: " . count($_SESSION['cart'] ?? []);
    }

    public function cart() {
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // If user is logged in, sync with database cart
        if ($this->isAuthenticated()) {
            $this->syncCart();
        }
        
        $data = [
            'isLoggedIn' => $this->isAuthenticated(),
            'cartItems' => $_SESSION['cart'] ?? [],
            'user_id' => $_SESSION['user_id'] ?? null
        ];
    
        $this->view('users/v_cart', $data);
    }
    
    public function updateQuantity() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $merchId = isset($_POST['merch_id']) ? $_POST['merch_id'] : null;
            $quantity = isset($_POST['quantity']) ? (int)$_POST['quantity'] : 1;
            
            // Ensure quantity is at least 1
            $quantity = max(1, $quantity);
            
            if ($merchId && isset($_SESSION['cart'][$merchId])) {
                // Update session cart quantity
                $_SESSION['cart'][$merchId]['quantity'] = $quantity;
                
                // Update database if user is logged in
                if ($this->isAuthenticated()) {
                    $userId = (int)$_SESSION['user_id'];
                    $merchId = (int)$merchId;
                    $this->cartModel->updateCartItemQuantity($userId, $merchId, $quantity);
                }
                
                $_SESSION['cart_message'] = 'Cart updated successfully';
            } else {
                $_SESSION['cart_error'] = 'Failed to update cart';
            }
        }
        
        header('Location: ' . URLROOT . '/merchandise/cart');
        exit;
    }
    
    // Clear all items from cart
    public function clearCart() {
        // Clear session cart
        $_SESSION['cart'] = [];
        
        // Clear database cart if user is logged in
        if ($this->isAuthenticated()) {
            $userId = (int)$_SESSION['user_id'];
            $this->cartModel->clearCart($userId);
        }
        
        $_SESSION['cart_message'] = 'Cart cleared successfully';
        header('Location: ' . URLROOT . '/merchandise/cart');
        exit;
    }
    
    // Sync session cart with database cart
    private function syncCart() {
        if (!$this->isAuthenticated()) {
            return;
        }
        
        $userId = (int)$_SESSION['user_id'];
        
        // Get database cart
        $dbCartItems = $this->cartModel->getCartItems($userId);
        
        // Initialize session cart if not exists
        if (!isset($_SESSION['cart'])) {
            $_SESSION['cart'] = [];
        }
        
        // Create a map of session cart items
        $sessionCart = $_SESSION['cart'];
        
        // Merge database cart into session cart
        foreach ($dbCartItems as $item) {
            $merchId = $item->merch_id;
            
            // If item exists in both, use the higher quantity
            if (isset($sessionCart[$merchId])) {
                $sessionCart[$merchId]['quantity'] = max($sessionCart[$merchId]['quantity'], $item->quantity);
                
                // Update database to match if needed
                if ($sessionCart[$merchId]['quantity'] != $item->quantity) {
                    $this->cartModel->updateCartItemQuantity($userId, $merchId, $sessionCart[$merchId]['quantity']);
                }
            } else {
                // Add database item to session cart
                $sessionCart[$merchId] = [
                    'id' => $merchId,
                    'name' => $item->Name,
                    'price' => $item->Price,
                    'quantity' => $item->quantity,
                    'image' => $item->image
                ];
            }
        }
        
        // Add any session-only items to database
        foreach ($sessionCart as $merchId => $item) {
            $found = false;
            foreach ($dbCartItems as $dbItem) {
                if ($dbItem->merch_id == $merchId) {
                    $found = true;
                    break;
                }
            }
            
            if (!$found) {
                $this->cartModel->addToCart($userId, $merchId, $item['quantity']);
            }
        }
        
        // Update session cart
        $_SESSION['cart'] = $sessionCart;
    }
    
    // Proceed to checkout page
    public function checkout() {
        // Verify cart is not empty
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            $_SESSION['cart_error'] = 'Your cart is empty';
            header('Location: ' . URLROOT . '/merchandise/cart');
            exit;
        }
        
        // Check if user is logged in
        if (!$this->isAuthenticated()) {
            $_SESSION['login_message'] = 'Please log in to proceed with checkout';
            header('Location: ' . URLROOT . '/users/login');
            exit;
        }
        
        // Calculate order summary
        $cartItems = $_SESSION['cart'];
        $subtotal = 0;
        $totalItems = 0;
        
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
            $totalItems += $item['quantity'];
        }
        
        // Add tax (adjust rate as needed)
        $taxRate = 0.08; // 8%
        $tax = $subtotal * $taxRate;
        
        // Add shipping (adjust as needed)
        $shipping = 5.99;
        
        // Calculate total
        $total = $subtotal + $tax + $shipping;
        
        $data = [
            'isLoggedIn' => true,
            'cartItems' => $cartItems,
            'user_id' => $_SESSION['user_id'],
            'subtotal' => $subtotal,
            'tax' => $tax,
            'shipping' => $shipping,
            'total' => $total,
            'totalItems' => $totalItems
        ];
        
        $this->view('users/v_checkout', $data);
    }

    // Process order and send confirmation email
    public function processOrder() {
        // Check if user is logged in
        if (!$this->isAuthenticated()) {
            redirect('users/login');
            return;
        }
        
        // Verify form submission
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            redirect('merchandise/checkout');
            return;
        }
        
        // Validate cart is not empty
        if (!isset($_SESSION['cart']) || empty($_SESSION['cart'])) {
            $_SESSION['checkout_error'] = 'Your cart is empty';
            redirect('merchandise/checkout');
            return;
        }
        
        // Get user information
        $userId = $_SESSION['user_id'];
        $firstName = trim($_POST['firstName']);
        $lastName = trim($_POST['lastName']);
        $email = trim($_POST['email']);
        $address = trim($_POST['address']);
        $address2 = trim($_POST['address2'] ?? '');
        $country = trim($_POST['country']);
        $state = trim($_POST['state']);
        $zip = trim($_POST['zip']);
        $paymentMethod = trim($_POST['paymentMethod']);
        
        // Get cart information
        $cartItems = $_SESSION['cart'];
        $subtotal = 0;
        
        foreach ($cartItems as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }
        
        // Calculate tax and shipping
        $taxRate = 0.08;
        $tax = $subtotal * $taxRate;
        $shipping = 5.99;
        $total = $subtotal + $tax + $shipping;
        
        // Generate order ID
        $orderId = 'ORD-' . time() . '-' . $userId;
        
        // Prepare address information for order model
        $addressInfo = [
            'firstName' => $firstName,
            'lastName' => $lastName,
            'address' => $address,
            'address2' => $address2,
            'country' => $country,
            'state' => $state,
            'zip' => $zip,
            'paymentMethod' => $paymentMethod
        ];
        
        // Save the order to database using order model
        $orderCreated = $this->orderModel->createOrder($userId, $orderId, $cartItems, $total, $addressInfo);
        
        if (!$orderCreated) {
            // Log the error
            error_log('Failed to create order in database');
            $_SESSION['checkout_error'] = 'Failed to process your order. Please try again.';
            redirect('merchandise/checkout');
            return;
        }
        
        // Send confirmation email
        $emailSent = $this->sendOrderConfirmationEmail($email, $orderId, $firstName, $lastName, $cartItems, $subtotal, $tax, $shipping, $total);
        
        if (!$emailSent) {
            // Log the error but continue with order processing
            error_log('Failed to send order confirmation email to ' . $email);
        }
        
        // Clear the cart after successful order
        $this->clearCart();
        
        // Store order data in session for the thank you page
        $_SESSION['order_details'] = [
            'order_id' => $orderId,
            'name' => $firstName . ' ' . $lastName,
            'email' => $email,
            'total' => $total,
            'items' => $cartItems
        ];
        
        // Redirect to thank you page
        redirect('merchandise/thankyou');
    }

    // Send order confirmation email
    // Send order confirmation email
private function sendOrderConfirmationEmail($email, $orderId, $firstName, $lastName, $cartItems, $subtotal, $tax, $shipping, $total) {
    // Create a new PHPMailer instance within this method
    $mail = new PHPMailer(true);
    
    try {
        // Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'IndipaPerera5@gmail.com';
        $mail->Password = 'mxpt ybvk rgcb sbtv'; // Your app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('MelodyLink.noreply@gmail.com', 'MelodyLink');
        
        // Recipients
        $mail->addAddress($email, $firstName . ' ' . $lastName);
        
        // Content
        $mail->isHTML(true);
        $mail->Subject = 'Your MelodyLink Order #' . $orderId;
        
        // Create HTML email body
        $emailBody = "
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                .order-container { max-width: 600px; margin: 0 auto; }
                .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
                .content { padding: 20px; }
                .item { display: flex; margin-bottom: 15px; border-bottom: 1px solid #eee; padding-bottom: 15px; }
                .item-image { width: 80px; margin-right: 15px; }
                .item-details { flex-grow: 1; }
                .total-section { background-color: #f8f9fa; padding: 15px; margin-top: 20px; }
                .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; }
            </style>
        </head>
        <body>
            <div class='order-container'>
                <div class='header'>
                    <h2>Thank You for Your Purchase!</h2>
                    <p>Order #$orderId</p>
                </div>
                <div class='content'>
                    <p>Dear $firstName $lastName,</p>
                    <p>Thank you for your purchase from MelodyLink. We're excited to confirm your order.</p>
                    
                    <h3>Order Summary:</h3>";
        
        // Add each item to the email
        foreach ($cartItems as $item) {
            $itemTotal = $item['price'] * $item['quantity'];
            $imagePath = URLROOT . '/public/images/' . $item['image']; // Adjust path based on your structure
            
            $emailBody .= "
                    <div class='item'>
                        <div class='item-image'>
                            <img src='$imagePath' alt='{$item['name']}' style='max-width: 80px;'>
                        </div>
                        <div class='item-details'>
                            <strong>{$item['name']}</strong><br>
                            Quantity: {$item['quantity']}<br>
                            Price: $" . number_format($item['price'], 2) . "<br>
                            Subtotal: $" . number_format($itemTotal, 2) . "
                        </div>
                    </div>";
        }
        
        // Add total section
        $emailBody .= "
                    <div class='total-section'>
                        <p><strong>Subtotal:</strong> $" . number_format($subtotal, 2) . "</p>
                        <p><strong>Tax:</strong> $" . number_format($tax, 2) . "</p>
                        <p><strong>Shipping:</strong> $" . number_format($shipping, 2) . "</p>
                        <p><strong>Total:</strong> $" . number_format($total, 2) . "</p>
                    </div>
                    
                    <p>Your items will be shipped soon. We'll send you another email with tracking information when your order ships.</p>
                    
                    <p>If you have any questions about your order, please contact our customer service team.</p>
                    
                    <p>Thank you for shopping with MelodyLink!</p>
                </div>
                <div class='footer'>
                    <p>© " . date('Y') . " MelodyLink. All rights reserved.</p>
                </div>
            </div>
        </body>
        </html>";
        
        $mail->Body = $emailBody;
        $mail->AltBody = "Thank you for your order #$orderId. Your total is $" . number_format($total, 2); // Plain text version
        
        $mail->send();
        return true;
    } catch (Exception $e) {
        error_log("Error sending order confirmation email: " . $e->getMessage());
        return false;
    }
}

    // Thank you page after successful order
    public function thankyou() {
        // Check if order details exist in session
        if (!isset($_SESSION['order_details'])) {
            redirect('merchandise');
            return;
        }
        
        $data = [
            'order_details' => $_SESSION['order_details'],
            'isLoggedIn' => $this->isAuthenticated()
        ];
        
        // Clear order details from session after displaying the page
        // You might want to comment this out during testing
        // unset($_SESSION['order_details']);
        
        $this->view('users/v_thankyou', $data);
    }
    
    // User order history page
    public function orders() {
        // Check if user is logged in
        if (!$this->isAuthenticated()) {
            redirect('users/login');
            return;
        }
        
        $userId = (int)$_SESSION['user_id'];
        $orders = $this->orderModel->getOrdersByUserId($userId);
        
        $orderDetails = [];
        
        // Get items for each order
        foreach ($orders as $order) {
            $items = $this->orderModel->getOrderItems($order->order_id);
            $orderDetails[] = [
                'order' => $order,
                'items' => $items
            ];
        }
        
        $data = [
            'isLoggedIn' => true,
            'orderDetails' => $orderDetails
        ];
        
        $this->view('users/v_order_history', $data);
    }
    
    // View specific order details
    public function viewOrder($orderId = null) {
        // Check if user is logged in
        if (!$this->isAuthenticated()) {
            redirect('users/login');
            return;
        }
        
        // Check if order ID is provided
        if (!$orderId) {
            redirect('merchandise/orders');
            return;
        }
        
        // Get order details
        $order = $this->orderModel->getOrderById($orderId);
        
        // Verify order belongs to the current user
        if (!$order || $order->user_id != $_SESSION['user_id']) {
            $_SESSION['order_error'] = 'Order not found or access denied';
            redirect('merchandise/orders');
            return;
        }
        
        // Get order items
        $items = $this->orderModel->getOrderItems($orderId);
        
        $data = [
            'isLoggedIn' => true,
            'order' => $order,
            'items' => $items
        ];
        
        $this->view('users/v_order_details', $data);
    }

    // Handle order processing route
    public function process() {
        $this->processOrder();
    }
}