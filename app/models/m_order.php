<?php
class m_order {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }
    
    /**
     * Create a new order (modified to work without transactions)
     * 
     * @param int $userId The user ID
     * @param string $orderId The order ID
     * @param array $items The order items
     * @param float $total The order total
     * @param array $addressInfo The address information
     * @return bool True if successful, false otherwise
     */
    public function createOrder($userId, $orderId, $items, $total, $addressInfo) {
        try {
            // Insert order into orders table without transaction
            $this->db->query('INSERT INTO orders (order_id, user_id, total_amount, shipping_address, 
                             billing_address, payment_method, order_status, created_at) 
                             VALUES (:order_id, :user_id, :total_amount, :shipping_address, 
                             :billing_address, :payment_method, :order_status, current_timestamp())');
            
            // Prepare address string
            $addressStr = json_encode([
                'firstName' => $addressInfo['firstName'],
                'lastName' => $addressInfo['lastName'],
                'address' => $addressInfo['address'],
                'address2' => $addressInfo['address2'],
                'country' => $addressInfo['country'],
                'state' => $addressInfo['state'],
                'zip' => $addressInfo['zip']
            ]);
            
            $this->db->bind(':order_id', $orderId);
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':total_amount', $total);
            $this->db->bind(':shipping_address', $addressStr);
            $this->db->bind(':billing_address', $addressStr); // Using same address for billing and shipping
            $this->db->bind(':payment_method', $addressInfo['paymentMethod']);
            $this->db->bind(':order_status', 'pending'); // Initial order status
            
            $result = $this->db->execute();
            
            if (!$result) {
                return false;
            }
            
            // Insert order items
            $allItemsInserted = true;
            foreach ($items as $item) {
                $this->db->query('INSERT INTO order_items (order_id, merch_id, quantity, price, subtotal) 
                                 VALUES (:order_id, :merch_id, :quantity, :price, :subtotal)');
                
                $this->db->bind(':order_id', $orderId);
                $this->db->bind(':merch_id', $item['id']);
                $this->db->bind(':quantity', $item['quantity']);
                $this->db->bind(':price', $item['price']);
                $this->db->bind(':subtotal', $item['price'] * $item['quantity']);
                
                $itemResult = $this->db->execute();
                
                if (!$itemResult) {
                    $allItemsInserted = false;
                    break;
                }
            }
            
            return $allItemsInserted;
        } catch (Exception $e) {
            error_log('Error creating order: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get order by ID
     * 
     * @param string $orderId The order ID
     * @return object|bool Order data or false if not found
     */
    public function getOrderById($orderId) {
        $this->db->query('SELECT * FROM orders WHERE order_id = :order_id');
        $this->db->bind(':order_id', $orderId);
        
        return $this->db->single();
    }
    
    /**
     * Get order items by order ID
     * 
     * @param string $orderId The order ID
     * @return array Order items
     */
    public function getOrderItems($orderId) {
        $this->db->query('
            SELECT oi.*, m.Name, m.image 
            FROM order_items oi
            JOIN Merchandise m ON oi.merch_id = m.merch_id
            WHERE oi.order_id = :order_id
        ');
        $this->db->bind(':order_id', $orderId);
        
        return $this->db->resultSet();
    }
    
    /**
     * Get orders by user ID
     * 
     * @param int $userId The user ID
     * @return array User's orders
     */
    public function getOrdersByUserId($userId) {
        $this->db->query('SELECT * FROM orders WHERE user_id = :user_id ORDER BY created_at DESC');
        $this->db->bind(':user_id', $userId);
        
        return $this->db->resultSet();
    }
    
    /**
     * Update order status
     * 
     * @param string $orderId The order ID
     * @param string $status The new status
     * @return bool True if successful, false otherwise
     */
    public function updateOrderStatus($orderId, $status) {
        $this->db->query('UPDATE orders SET order_status = :order_status WHERE order_id = :order_id');
        $this->db->bind(':order_status', $status);
        $this->db->bind(':order_id', $orderId);
        
        return $this->db->execute();
    }
}