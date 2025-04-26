<?php
class m_equipment {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all equipment
    public function getAllEquipment() {
        $this->db->query("SELECT * FROM h_equipment ORDER BY id DESC");
        
        return $this->db->resultSet();
    }

    // Get equipment by ID
    public function getEquipmentById($id) {
        $this->db->query("SELECT * FROM h_equipment WHERE id = :id");
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }

    // Add new equipment
    public function addEquipment($data) {
        $this->db->query("INSERT INTO h_equipment (name, description, price, category, image_url, rating, reviews, status) 
                          VALUES (:name, :description, :price, :category, :image_url, :rating, :reviews, :status)");
        
        // Bind values
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':image_url', $data['image_url']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':reviews', $data['reviews']);
        $this->db->bind(':status', $data['status']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Update equipment
    public function updateEquipment($data) {
        $this->db->query("UPDATE h_equipment 
                          SET name = :name, description = :description, price = :price, 
                              category = :category, image_url = :image_url, 
                              rating = :rating, reviews = :reviews, status = :status 
                          WHERE id = :id");
        
        // Bind values
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':name', $data['name']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':price', $data['price']);
        $this->db->bind(':category', $data['category']);
        $this->db->bind(':image_url', $data['image_url']);
        $this->db->bind(':rating', $data['rating']);
        $this->db->bind(':reviews', $data['reviews']);
        $this->db->bind(':status', $data['status']);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Delete equipment
    public function deleteEquipment($id) {
        $this->db->query("DELETE FROM h_equipment WHERE id = :id");
        $this->db->bind(':id', $id);
        
        // Execute
        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // Get equipment by category
    public function getEquipmentByCategory($category) {
        $this->db->query("SELECT * FROM h_equipment WHERE category = :category ORDER BY id DESC");
        $this->db->bind(':category', $category);
        
        return $this->db->resultSet();
    }

    // Get available equipment
    public function getAvailableEquipment() {
        $this->db->query("SELECT * FROM h_equipment WHERE status = 'available' ORDER BY id DESC");
        
        return $this->db->resultSet();
    }

    // Get booked equipment
    public function getBookedEquipment() {
        $this->db->query("SELECT * FROM h_equipment WHERE status = 'booked' ORDER BY id DESC");
        
        return $this->db->resultSet();
    }
}
