<?php
class m_VendorMerchandise {
    private $db;
    
    public function __construct() {
        $this->db = new Database;
    }
    
    // Get all merchandise for a specific supplier
    public function getMerchandiseBySupplier($supplierId) {
        // Changed column name from supplier_id to user_id
        $this->db->query('SELECT * FROM merchandise WHERE user_id = :user_id ORDER BY merch_id DESC');
        $this->db->bind(':user_id', $supplierId);
        
        return $this->db->resultSet();
    }
    
    // Get merchandise by ID
    public function getMerchandiseById($id) {
        $this->db->query('SELECT * FROM merchandise WHERE merch_id = :id');
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    // Add new merchandise
    public function addMerchandise($data) {
        // Changed supplier_id to user_id in the column list
        $this->db->query('INSERT INTO merchandise (Name, Price, Description, image, user_id) 
                        VALUES (:name, :price, :description, :image, :user_id)');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':user_id', $data['user_id']); // Changed from supplier_id to user_id
        
        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }
    
    // Update merchandise
    public function updateMerchandise($data) {
        // If image is provided, update with new image
        if(!empty($data['image'])) {
            $this->db->query('UPDATE merchandise SET 
                            Name = :name, 
                            Price = :price, 
                            Description = :description, 
                            image = :image 
                            WHERE merch_id = :id');
            
            $this->db->bind(':image', $data['image']);
        } else {
            // If no new image, don't update the image field
            $this->db->query('UPDATE merchandise SET 
                            Name = :name, 
                            Price = :price, 
                            Description = :description 
                            WHERE merch_id = :id');
        }
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':id', $data['id']);
        
        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }
    
    // Delete merchandise
    public function deleteMerchandise($id) {
        $this->db->query('DELETE FROM merchandise WHERE merch_id = :id');
        $this->db->bind(':id', $id);
        
        // Execute
        if($this->db->execute()){
            return true;
        } else {
            return false;
        }
    }
}
?>
