<?php
class m_eqpsupplier_profile {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }
    
    // Get supplier profile
    public function getSupplierProfile($supplierId) {
        $this->db->query("SELECT * FROM h_eqp_suppliers WHERE id = :id");
        $this->db->bind(':id', $supplierId);
        
        return $this->db->single();
    }
    
    // Update profile image
    public function updateProfileImage($supplierId, $imageName) {
        $this->db->query("UPDATE h_eqp_suppliers SET profile_image = :profile_image WHERE id = :id");
        $this->db->bind(':profile_image', $imageName);
        $this->db->bind(':id', $supplierId);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Update profile
    public function updateProfile($supplierId, $data) {
        $this->db->query("UPDATE h_eqp_suppliers SET 
                          company_name = :company_name, 
                          owner_name = :owner_name, 
                          email = :email, 
                          phone = :phone, 
                          address = :address, 
                          bio = :bio 
                          WHERE id = :id");
        
        // Bind values
        $this->db->bind(':company_name', $data['company_name']);
        $this->db->bind(':owner_name', $data['owner_name']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':address', $data['address']);
        $this->db->bind(':bio', $data['bio']);
        $this->db->bind(':id', $supplierId);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
}
