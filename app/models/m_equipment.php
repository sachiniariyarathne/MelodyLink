<?php
class M_Equipment {
    private $db;

    public function __construct($db) {
        $this->db = $db;
    }

    // Get all equipment items
    public function getAllEquipment() {
        $result = $this->db->query("SELECT * FROM h_equipment");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Get equipment by ID
    public function getEquipmentById($id) {
        $stmt = $this->db->prepare("SELECT * FROM h_equipment WHERE id = ?");
        $stmt->bind_param("i", $id);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_assoc();
    }

    // Get equipment by category
    public function getEquipmentByCategory($category) {
        $stmt = $this->db->prepare("SELECT * FROM h_equipment WHERE category = ?");
        $stmt->bind_param("s", $category);
        $stmt->execute();
        $result = $stmt->get_result();
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add new equipment
    public function addEquipment($data) {
        $stmt = $this->db->prepare("INSERT INTO h_equipment (name, description, price, category, image_url, rating, reviews, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdssdis", 
            $data['name'], 
            $data['description'], 
            $data['price'], 
            $data['category'], 
            $data['image_url'], 
            $data['rating'], 
            $data['reviews'], 
            $data['status']
        );
        return $stmt->execute();
    }

    // Update equipment
    public function updateEquipment($id, $data) {
        $stmt = $this->db->prepare("UPDATE h_equipment SET name = ?, description = ?, price = ?, category = ?, image_url = ?, rating = ?, reviews = ?, status = ? WHERE id = ?");
        $stmt->bind_param("ssdssdisd", 
            $data['name'], 
            $data['description'], 
            $data['price'], 
            $data['category'], 
            $data['image_url'], 
            $data['rating'], 
            $data['reviews'], 
            $data['status'],
            $id
        );
        return $stmt->execute();
    }

    // Delete equipment
    public function deleteEquipment($id) {
        $stmt = $this->db->prepare("DELETE FROM h_equipment WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
    
    // Update equipment status
    public function updateStatus($id, $status) {
        $stmt = $this->db->prepare("UPDATE h_equipment SET status = ? WHERE id = ?");
        $stmt->bind_param("si", $status, $id);
        return $stmt->execute();
    }
}
?>
