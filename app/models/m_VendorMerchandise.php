<?php
class m_VendorMerchandise {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }
    
    public function addMerchandise($data) {
        $this->db->query('INSERT INTO Merchandise (Name, Price, Description, image, supplier_id) 
                          VALUES (:name, :price, :description, :image, :supplier_id)');
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':image', $data['image']);
        $this->db->bind(':supplier_id', $data['supplier_id']);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function updateMerchandise($data) {
        // If there's a new image
        if(!empty($data['image'])) {
            $this->db->query('UPDATE Merchandise SET Name = :name, Price = :price, 
                            Description = :description, image = :image 
                            WHERE merch_id = :id');
            $this->db->bind(':image', $data['image']);
        } else {
            // No new image provided
            $this->db->query('UPDATE Merchandise SET Name = :name, Price = :price, 
                            Description = :description WHERE merch_id = :id');
        }
        
        // Bind other values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':id', $data['id']);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function deleteMerchandise($id) {
        $this->db->query('DELETE FROM Merchandise WHERE merch_id = :id');
        $this->db->bind(':id', $id);
        
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    public function getMerchandiseBySupplier($supplierId) {
        $this->db->query('SELECT merch_id, Name, Price, Description, image FROM Merchandise 
                        WHERE supplier_id = :supplier_id');
        $this->db->bind(':supplier_id', $supplierId);
        return $this->db->resultSet();
    }
    
    public function getMerchandiseById($id) {
        $this->db->query('SELECT merch_id, Name, Price, Description, image, supplier_id FROM Merchandise 
                        WHERE merch_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    public function isOwner($merchId, $supplierId) {
        $this->db->query('SELECT COUNT(*) as count FROM Merchandise 
                        WHERE merch_id = :merch_id AND supplier_id = :supplier_id');
        $this->db->bind(':merch_id', $merchId);
        $this->db->bind(':supplier_id', $supplierId);
        
        $row = $this->db->single();
        return ($row->count > 0);
    }
}