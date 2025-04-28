<?php
class EventEquipment {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getAllEquipment() {
        $this->db->query('
            SELECT e.*, 
                   COUNT(DISTINCT m.id) as total_requests,
                   COUNT(DISTINCT CASE WHEN m.status = "accepted" THEN m.id END) as accepted_requests
            FROM h_equipment e
            LEFT JOIN h_messages m ON e.id = m.equipment_id
            GROUP BY e.id
            ORDER BY e.created_at DESC
        ');
        return $this->db->resultSet();
    }

    public function getEquipmentByCategory($category) {
        $this->db->query('
            SELECT e.*, 
                   COUNT(DISTINCT m.id) as total_requests,
                   COUNT(DISTINCT CASE WHEN m.status = "accepted" THEN m.id END) as accepted_requests
            FROM h_equipment e
            LEFT JOIN h_messages m ON e.id = m.equipment_id
            WHERE e.category = :category
            GROUP BY e.id
            ORDER BY e.created_at DESC
        ');
        $this->db->bind(':category', $category);
        return $this->db->resultSet();
    }

    public function getEquipmentById($id) {
        $this->db->query('
            SELECT e.*, 
                   COUNT(DISTINCT m.id) as total_requests,
                   COUNT(DISTINCT CASE WHEN m.status = "accepted" THEN m.id END) as accepted_requests
            FROM h_equipment e
            LEFT JOIN h_messages m ON e.id = m.equipment_id
            WHERE e.id = :id
            GROUP BY e.id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function createRentalRequest($data) {
        $this->db->query('
            INSERT INTO h_messages (
                equipment_id, sender_id, message, request_date, status
            ) VALUES (
                :equipment_id, :sender_id, :message, :request_date, "pending"
            )
        ');
        
        $this->db->bind(':equipment_id', $data['equipment_id']);
        $this->db->bind(':sender_id', $data['sender_id']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':request_date', $data['request_date']);

        return $this->db->execute();
    }

    public function getUserRequests($userId) {
        $this->db->query('
            SELECT m.*, e.name as equipment_name, e.image_url, e.price
            FROM h_messages m
            JOIN h_equipment e ON m.equipment_id = e.id
            WHERE m.sender_id = :user_id
            ORDER BY m.created_at DESC
        ');
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }

    public function getRequestById($id) {
        $this->db->query('
            SELECT m.*, e.name as equipment_name, e.image_url, e.price
            FROM h_messages m
            JOIN h_equipment e ON m.equipment_id = e.id
            WHERE m.id = :id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateRequest($data) {
        $this->db->query('
            UPDATE h_messages 
            SET message = :message,
                request_date = :request_date
            WHERE id = :id AND sender_id = :sender_id
        ');
        
        $this->db->bind(':id', $data['id']);
        $this->db->bind(':sender_id', $data['sender_id']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':request_date', $data['request_date']);

        return $this->db->execute();
    }

    public function deleteRequest($id, $userId) {
        $this->db->query('
            DELETE FROM h_messages 
            WHERE id = :id AND sender_id = :sender_id AND status = "pending"
        ');
        
        $this->db->bind(':id', $id);
        $this->db->bind(':sender_id', $userId);

        return $this->db->execute();
    }
} 