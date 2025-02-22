<?php
class m_Cart {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getUserCart($userId) {
        $this->db->query('
            SELECT c.*, m.Name, m.Price, m.image 
            FROM cart c
            JOIN Merchandise m ON c.merch_id = m.merch_id
            WHERE c.user_id = :user_id
        ');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function addToCart($userId, $merchId, $quantity = 1) {
        try {
            // Start transaction
            $this->db->beginTransaction();

            // Check if item exists in cart
            $this->db->query('SELECT id FROM cart WHERE user_id = :user_id AND merch_id = :merch_id');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':merch_id', $merchId);
            $existing = $this->db->single();

            if ($existing) {
                // Update existing item
                $this->db->query('
                    UPDATE cart 
                    SET quantity = quantity + :quantity,
                        updated_at = CURRENT_TIMESTAMP 
                    WHERE user_id = :user_id 
                    AND merch_id = :merch_id
                ');
                $this->db->bind(':quantity', $quantity);
                $this->db->bind(':user_id', $userId);
                $this->db->bind(':merch_id', $merchId);
            } else {
                // Insert new item
                $this->db->query('
                    INSERT INTO cart 
                    (user_id, merch_id, quantity, created_at, updated_at) 
                    VALUES 
                    (:user_id, :merch_id, :quantity, CURRENT_TIMESTAMP, CURRENT_TIMESTAMP)
                ');
                $this->db->bind(':user_id', $userId);
                $this->db->bind(':merch_id', $merchId);
                $this->db->bind(':quantity', $quantity);
            }

            $result = $this->db->execute();
            
            if ($result) {
                $this->db->commit();
                return true;
            } else {
                $this->db->rollBack();
                return false;
            }

        } catch (Exception $e) {
            if ($this->db->inTransaction()) {
                $this->db->rollBack();
            }
            error_log('Cart Error: ' . $e->getMessage());
            return false;
        }
    }

    public function removeFromCart($userId, $merchId) {
        try {
            $this->db->query('
                DELETE FROM cart 
                WHERE user_id = :user_id 
                AND merch_id = :merch_id
            ');
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':merch_id', $merchId);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Cart Error: ' . $e->getMessage());
            return false;
        }
    }

    public function getCartCount($userId) {
        $this->db->query('
            SELECT COALESCE(SUM(quantity), 0) as total 
            FROM cart 
            WHERE user_id = :user_id
        ');
        $this->db->bind(':user_id', $userId);
        $result = $this->db->single();
        return (int)($result ? $result->total : 0);
    }

    public function updateCartQuantity($userId, $merchId, $quantity) {
        try {
            $this->db->query('
                UPDATE cart 
                SET quantity = :quantity,
                    updated_at = CURRENT_TIMESTAMP 
                WHERE user_id = :user_id 
                AND merch_id = :merch_id
            ');
            $this->db->bind(':quantity', $quantity);
            $this->db->bind(':user_id', $userId);
            $this->db->bind(':merch_id', $merchId);
            
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Cart Error: ' . $e->getMessage());
            return false;
        }
    }

    public function clearCart($userId) {
        try {
            $this->db->query('DELETE FROM cart WHERE user_id = :user_id');
            $this->db->bind(':user_id', $userId);
            return $this->db->execute();
        } catch (Exception $e) {
            error_log('Cart Error: ' . $e->getMessage());
            return false;
        }
    }
}