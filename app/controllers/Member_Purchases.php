<?php
class Member_Purchases extends Controller {
    private $purchaseModel;
    
    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'member') {
            redirect('users/login');
        }
        
                // Load models
                $this->userModel = $this->model('m_users');
                $this->purchaseModel = $this->model('m_member_purchases');
    }
    
    public function index() {
        $userId = $_SESSION['user_id'];
        
        // Get user orders
        $orders = $this->purchaseModel->getUserOrders($userId);
        
        // Get order items for each order
        foreach ($orders as $order) {
            $order->items = $this->purchaseModel->getOrderItems($order->id);
        }
        
        // Get user cart items
        $cartItems = $this->purchaseModel->getUserCartItems($userId);
        
        // Calculate cart total
        $cartTotal = 0;
        foreach ($cartItems as $item) {
            $cartTotal += $item->Price * $item->quantity;
        }
        
        // For demo purposes, set shipping cost
        $shipping = !empty($cartItems) ? 10.00 : 0;
        
        // Get user data
        $memberInfo = $this->userModel->getUserData($userId);
        
        $data = [
            'orders' => $orders,
            'cart_items' => $cartItems,
            'cart_total' => $cartTotal,
            'shipping' => $shipping,
            // 'profile_pic' => $profile->profile_pic ?? 'default-avatar.png'
            'member_info' => [
                'username' => $memberInfo->Username,
                'email' => $memberInfo->email,
                'profile_pic' => $memberInfo->profile_pic
            ],
        ];

        
        $this->view('users/v_member_purchases', $data);
    }
    
    public function order_details($orderId) {
        $userId = $_SESSION['user_id'];
        
        // Verify order belongs to user
        $order = $this->purchaseModel->getOrderById($orderId);
        if (!$order || $order->user_id != $userId) {
            redirect('Member_Purchases');
        }
        
        // Get order items
        $orderItems = $this->purchaseModel->getOrderItems($orderId);
        
        // Get shipping/billing info
        $order->items = $orderItems;
        
        // Get user profile info
        $profile = $this->purchaseModel->getUserProfile($userId);
        
        $data = [
            'order' => $order,
            'profile_pic' => $profile->profile_pic ?? 'default-avatar.png'
        ];
        
        $this->view('users/v_member_order_details', $data);
    }
    
    public function update_cart() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $cartId = $_POST['cart_id'];
            $action = $_POST['action'];
            
            // Verify cart item belongs to user
            $cartItem = $this->purchaseModel->getCartItemById($cartId);
            if (!$cartItem || $cartItem->user_id != $userId) {
                redirect('Member_Purchases');
            }
            
            $quantity = $cartItem->quantity;
            
            if ($action == 'increase') {
                $quantity++;
            } elseif ($action == 'decrease') {
                $quantity--;
            }
            
            // Remove item if quantity is 0
            if ($quantity <= 0) {
                $this->purchaseModel->removeCartItem($cartId);
            } else {
                $this->purchaseModel->updateCartQuantity($cartId, $quantity);
            }
        }
        
        redirect('Member_Purchases');
    }
    
    public function remove_from_cart() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $userId = $_SESSION['user_id'];
            $cartId = $_POST['cart_id'];
            
            // Verify cart item belongs to user
            $cartItem = $this->purchaseModel->getCartItemById($cartId);
            if (!$cartItem || $cartItem->user_id != $userId) {
                redirect('Member_Purchases');
            }
            
            $this->purchaseModel->removeCartItem($cartId);
        }
        
        redirect('Member_Purchases');
    }
    
    public function checkout() {
        $userId = $_SESSION['user_id'];
        
        // Get user cart items
        $cartItems = $this->purchaseModel->getUserCartItems($userId);
        
        if (empty($cartItems)) {
            redirect('Member_Purchases');
        }
        
        // Calculate cart total
        $cartTotal = 0;
        foreach ($cartItems as $item) {
            $cartTotal += $item->Price * $item->quantity;
        }
        
        // Shipping cost for demo
        $shipping = 10.00;
        $total = $cartTotal + $shipping;
        
        // Get user profile info for shipping/billing
        $profile = $this->purchaseModel->getUserProfile($userId);
        
        $data = [
            'cart_items' => $cartItems,
            'cart_total' => $cartTotal,
            'shipping' => $shipping,
            'total' => $total,
            'profile' => $profile,
            'profile_pic' => $profile->profile_pic ?? 'default-avatar.png'
        ];
        
        $this->view('users/v_member_checkout', $data);
    }
    
    public function mytickets() {
        $this->index();
    }
}
