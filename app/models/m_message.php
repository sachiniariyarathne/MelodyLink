<?php
class m_message {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all messages with sender details and equipment details
    public function getAllMessages() {
        $this->db->query("SELECT m.*, u.name as sender_name, u.role as sender_role, 
                          e.name as equipment_name, e.price as equipment_price, 
                          e.category as equipment_category, e.image_url as equipment_image
                          FROM h_messages m
                          JOIN h_users u ON m.sender_id = u.id
                          JOIN h_equipment e ON m.equipment_id = e.id
                          ORDER BY m.request_date DESC");
        
        return $this->db->resultSet();
    }
    
    // Get message by ID
    public function getMessageById($id) {
        $this->db->query("SELECT * FROM h_messages WHERE id = :id");
        $this->db->bind(':id', $id);
        
        return $this->db->single();
    }
    
    // Add new message
    public function addMessage($data) {
        $this->db->query("INSERT INTO h_messages (equipment_id, message, sender_id, request_date, status) 
                          VALUES (:equipment_id, :message, :sender_id, :request_date, :status)");
        
        // Bind values
        $this->db->bind(':equipment_id', $data['equipment_id']);
        $this->db->bind(':message', $data['message']);
        $this->db->bind(':sender_id', $data['sender_id']);
        $this->db->bind(':request_date', $data['request_date']);
        $this->db->bind(':status', $data['status']);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Update message status
    public function updateMessageStatus($id, $status) {
        $this->db->query("UPDATE h_messages SET status = :status, response_date = NOW() WHERE id = :id");
        
        // Bind values
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        
        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
    
    // Get messages by user ID
    public function getMessagesByUserId($userId) {
        $this->db->query("SELECT m.*, e.name as equipment_name, e.price as equipment_price, 
                          e.category as equipment_category, e.image_url as equipment_image
                          FROM h_messages m
                          JOIN h_equipment e ON m.equipment_id = e.id
                          WHERE m.sender_id = :sender_id
                          ORDER BY m.request_date DESC");
        
        $this->db->bind(':sender_id', $userId);
        
        return $this->db->resultSet();
    }
}
