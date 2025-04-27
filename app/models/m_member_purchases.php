<?php
class m_member_purchases {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    public function getUserOrders($userId) {
        $this->db->query("SELECT * FROM orders WHERE user_id = :userId ORDER BY created_at DESC");
        $this->db->bind(':userId', $userId);
        return $this->db->resultSet();
    }
    
    public function getOrderById($orderId) {
        $this->db->query("SELECT * FROM orders WHERE id = :orderId");
        $this->db->bind(':orderId', $orderId);
        return $this->db->single();
    }
    
    public function getOrderItems($orderId) {
        $this->db->query("
            SELECT oi.*, m.Name, m.Price, m.image 
            FROM order_items oi
            JOIN merchandise m ON oi.merch_id = m.merch_id
            WHERE oi.order_id = :orderId
        ");
        $this->db->bind(':orderId', $orderId);
        return $this->db->resultSet();
    }
    
    public function getUserCartItems($userId) {
        $this->db->query('
            SELECT c.*, c.cart_id as cart_id, m.Name, m.Price, m.Description, m.image
            FROM cart c
            JOIN Merchandise m ON c.merch_id = m.merch_id
            WHERE c.user_id = :user_id
        ');
        $this->db->bind(':user_id', $userId); // Fixed parameter name
        return $this->db->resultSet();
    }
    
    public function getCartItemById($cartId) {
        $this->db->query("SELECT * FROM cart WHERE cart_id = :cartId");
        $this->db->bind(':cartId', $cartId); // Fixed to match SQL
        return $this->db->single();
    }
    
    public function updateCartQuantity($cartId, $quantity) {
        $this->db->query("UPDATE cart SET quantity = :quantity WHERE cart_id = :cartId");
        $this->db->bind(':quantity', $quantity);
        $this->db->bind(':cartId', $cartId); // Fixed to match SQL
        return $this->db->execute();
    }
    
    public function removeCartItem($cartId) {
        $this->db->query("DELETE FROM cart WHERE cart_id = :cartId");
        $this->db->bind(':cartId', $cartId); // Fixed to match SQL
        return $this->db->execute();
    }
    
}
