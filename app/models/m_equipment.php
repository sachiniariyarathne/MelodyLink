<?php
class EquipmentModel {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    // Get all equipment from database
    public function getAllEquipment() {
        $result = $this->conn->query("SELECT * FROM equipment");
        return $result->fetch_all(MYSQLI_ASSOC);
    }

    // Add new equipment to database
    public function addEquipment($name, $description, $price, $category, $image_url, $rating, $reviews, $status) {
        $stmt = $this->conn->prepare("INSERT INTO equipment (name, description, price, category, image_url, rating, reviews, status) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
        $stmt->bind_param("ssdssdis", $name, $description, $price, $category, $image_url, $rating, $reviews, $status);
        return $stmt->execute();
    }

    // Delete equipment from database
    public function deleteEquipment($id) {
        $stmt = $this->conn->prepare("DELETE FROM equipment WHERE id = ?");
        $stmt->bind_param("i", $id);
        return $stmt->execute();
    }
}
?>