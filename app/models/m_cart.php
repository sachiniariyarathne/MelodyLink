<?php
class m_cart {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    /**
     * Add item to cart in database
     * 
     * @param int $user_id The member_id from the member table
     * @param int $merch_id The merchandise ID
     * @param int $quantity The quantity to add
     * @return bool True if successful, false otherwise
     */
    public function addToCart($user_id, $merch_id, $quantity = 1) {
        try {
            // Validate that the user exists before adding to cart
            $this->db->query('SELECT member_id FROM member WHERE member_id = :user_id');
            $this->db->bind(':user_id', $user_id);
            $member = $this->db->single();
            
            if (!$member) {
                error_log('Failed to add to cart: Member not found with ID ' . $user_id);
                return false;
            }
            
            // Check if the item already exists in the cart for this user
            $this->db->query('SELECT * FROM cart WHERE user_id = :user_id AND merch_id = :merch_id');
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':merch_id', $merch_id);
            $existingItem = $this->db->single();
            
            if ($existingItem) {
                // Update quantity if item already exists
                $this->db->query('UPDATE cart SET quantity = quantity + :quantity, updated_at = current_timestamp() WHERE user_id = :user_id AND merch_id = :merch_id');
                $this->db->bind(':quantity', $quantity);
                $this->db->bind(':user_id', $user_id);
                $this->db->bind(':merch_id', $merch_id);
                
                $result = $this->db->execute();
                error_log('Updated cart item: user_id=' . $user_id . ', merch_id=' . $merch_id . ', result=' . ($result ? 'success' : 'failure'));
                return $result;
            } else {
                // Insert new item if it doesn't exist
                $this->db->query('INSERT INTO cart (user_id, merch_id, quantity, created_at, updated_at) 
                                  VALUES (:user_id, :merch_id, :quantity, current_timestamp(), current_timestamp())');
                $this->db->bind(':user_id', $user_id);
                $this->db->bind(':merch_id', $merch_id);
                $this->db->bind(':quantity', $quantity);
                
                $result = $this->db->execute();
                error_log('Inserted new cart item: user_id=' . $user_id . ', merch_id=' . $merch_id . ', result=' . ($result ? 'success' : 'failure'));
                return $result;
            }
        } catch (Exception $e) {
            error_log('Error adding to cart: ' . $e->getMessage() . ' ' . $e->getTraceAsString());
            return false;
        }
    }
    
    /**
     * Remove item from cart in database
     * 
     * @param int $user_id The member_id from the member table
     * @param int $merch_id The merchandise ID
     * @return bool True if successful, false otherwise
     */
    public function removeFromCart($user_id, $merch_id) {
        try {
            $this->db->query('DELETE FROM cart WHERE user_id = :user_id AND merch_id = :merch_id');
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':merch_id', $merch_id);
            
            $result = $this->db->execute();
            error_log('Removed cart item: user_id=' . $user_id . ', merch_id=' . $merch_id . ', result=' . ($result ? 'success' : 'failure'));
            return $result;
        } catch (Exception $e) {
            error_log('Error removing from cart: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get all cart items for a specific user
     * 
     * @param int $user_id The member_id from the member table
     * @return array Array of cart items
     */
    public function getCartItems($user_id) {
        $this->db->query('
            SELECT c.*, m.Name, m.Price, m.image 
            FROM cart c
            JOIN Merchandise m ON c.merch_id = m.merch_id
            WHERE c.user_id = :user_id
        ');
        $this->db->bind(':user_id', $user_id);
        
        return $this->db->resultSet();
    }
    
    /**
     * Update cart item quantity
     * 
     * @param int $user_id The member_id from the member table
     * @param int $merch_id The merchandise ID
     * @param int $quantity The new quantity
     * @return bool True if successful, false otherwise
     */
    public function updateCartItemQuantity($user_id, $merch_id, $quantity) {
        try {
            $this->db->query('UPDATE cart SET quantity = :quantity, updated_at = current_timestamp() WHERE user_id = :user_id AND merch_id = :merch_id');
            $this->db->bind(':quantity', $quantity);
            $this->db->bind(':user_id', $user_id);
            $this->db->bind(':merch_id', $merch_id);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error updating cart item: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Clear the entire cart for a user
     * 
     * @param int $user_id The member_id from the member table
     * @return bool True if successful, false otherwise
     */
    public function clearCart($user_id) {
        try {
            $this->db->query('DELETE FROM cart WHERE user_id = :user_id');
            $this->db->bind(':user_id', $user_id);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Error clearing cart: ' . $e->getMessage());
            return false;
        }
    }
    
    /**
     * Get the total number of items in a user's cart
     * 
     * @param int $user_id The member_id from the member table
     * @return int Total number of items
     */
    public function getCartItemCount($user_id) {
        $this->db->query('SELECT SUM(quantity) as total FROM cart WHERE user_id = :user_id');
        $this->db->bind(':user_id', $user_id);
        
        $result = $this->db->single();
        return $result->total ?? 0;
    }
    
    /**
     * Sync session cart with database for a logged-in user
     * 
     * @param int $user_id The member_id from the member table
     * @param array $sessionCart The cart items from session
     * @return bool True if successful, false otherwise
     */
    public function syncSessionCartWithDatabase($user_id, $sessionCart) {
        if (empty($sessionCart)) {
            return true;
        }
        
        try {
            foreach ($sessionCart as $merchId => $item) {
                $this->addToCart($user_id, $merchId, $item['quantity']);
            }
            return true;
        } catch (Exception $e) {
            error_log('Error syncing session cart: ' . $e->getMessage());
            return false;
        }
    }
}