<?php
class m_Supplier {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Get supplier information by user ID
    public function getSupplierByUserId($userId) {
        $this->db->query('SELECT * FROM supplier WHERE user_id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        return $this->db->single();
    }
    
    // Update supplier information
    public function updateSupplier($data) {
        // Check if supplier record exists
        $this->db->query('SELECT * FROM supplier WHERE user_id = :user_id');
        $this->db->bind(':user_id', $data['user_id']);
        $supplier = $this->db->single();
        
        if ($supplier) {
            // Update existing supplier
            $this->db->query('UPDATE supplier SET 
                            company_name = :company_name
                            WHERE user_id = :user_id');
        } else {
            // Create new supplier record
            $this->db->query('INSERT INTO supplier (user_id, company_name) 
                            VALUES (:user_id, :company_name)');
        }
        
        // Bind values
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':company_name', $data['company_name']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>