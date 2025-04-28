<?php
class Cart extends Controller {
    private $cartModel;
    private $merchandiseModel;
    private $orderModel;

    public function __construct() {
        // Initialize models
        $this->cartModel = $this->model('m_cart');
        $this->merchandiseModel = $this->model('m_Merchandise');
        
        // Load order model if available (for checkout process)
        if (file_exists('../app/models/m_order.php')) {
            $this->orderModel = $this->model('m_order');
        }
        
        // Ensure session is started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
    }

    private function isAuthenticated() {
        return isset($_SESSION['user_id']) && !empty($_SESSION['user_id']);
    }

    // Main cart page
    public function index() {
        // Initialize cart if not exists
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
    
    // Add item to cart
    public function add() {
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

    // Remove item from cart
    public function remove() {
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
    
    // Update item quantity in cart
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
        
        header('Location: ' . URLROOT . '/cart');
        exit;
    }
    
    // Clear all items from cart
    public function clear() {
        // Clear session cart
        $_SESSION['cart'] = [];
        
        // Clear database cart if user is logged in
        if ($this->isAuthenticated()) {
            $userId = (int)$_SESSION['user_id'];
            $this->cartModel->clearCart($userId);
        }
        
        $_SESSION['cart_message'] = 'Cart cleared successfully';
        header('Location: ' . URLROOT . '/cart');
        exit;
    }
    
    // Get cart count for AJAX requests
    public function count() {
        header('Content-Type: application/json');
        $totalItems = 0;
        
        if (isset($_SESSION['cart'])) {
            $totalItems = array_sum(array_column($_SESSION['cart'], 'quantity'));
        }
        
        echo json_encode(['cartCount' => $totalItems]);
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
            header('Location: ' . URLROOT . '/cart');
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
}