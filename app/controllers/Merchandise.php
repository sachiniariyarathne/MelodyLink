<?php
class Merchandise extends Controller {
    private $merchandiseModel;
    private $cartModel;

    public function __construct() {
        $this->merchandiseModel = $this->model('m_Merchandise');
        $this->cartModel = $this->model('m_cart');
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
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
                        $_SESSION['cart'][$merchId]['quantity']++;
                    } else {
                        $_SESSION['cart'][$merchId] = [
                            'id' => $merchId,
                            'name' => $merchandise->Name,
                            'price' => $merchandise->Price,
                            'quantity' => 1,
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
                        
                        $result = $this->cartModel->addToCart($userId, $merchId, 1);
                        
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
}