<?php
class m_supplier_profile {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Get user by ID
    public function getUserById($id) {
        $this->db->query('SELECT * FROM supplier WHERE user_id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    // Update user
    public function updateUser($data) {
        // Check if password is being updated
        if (isset($data['password'])) {
            $this->db->query('UPDATE supplier SET 
                            Username = :username, 
                            email = :email, 
                            Phone_number = :phone_number, 
                            Address = :address,
                            Password = :password,
                            BusinessType = :business_type
                            WHERE user_id = :id');
            
            $this->db->bind(':password', $data['password']);
        } else {
            // No password update
            $this->db->query('UPDATE supplier SET 
                            Username = :username, 
                            email = :email, 
                            Phone_number = :phone_number, 
                            Address = :address,
                            BusinessType = :business_type
                            WHERE user_id = :id');
        }
        
        // Bind values
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone_number', $data['phone_number']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':business_type', $data['business_type'] ?? '');
        $this->db->bind(':id', $data['id']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
?>