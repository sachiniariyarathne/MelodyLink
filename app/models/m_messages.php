<?php
class m_messages {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all pending messages with organiser info
    public function getPendingMessages() {
        $this->db->query("
            SELECT m.*, o.username, o.email, o.Organization
            FROM h_messages m
            JOIN event_organiser o ON m.sender_id = o.organiser_id
            WHERE m.status = 'pending'
            ORDER BY m.request_date DESC
        ");
        return $this->db->resultSet();
    }

    // Update message status
    public function updateMessageStatus($id, $status) {
        $this->db->query("UPDATE h_messages SET status = :status, response_date = NOW() WHERE id = :id");
        $this->db->bind(':id', $id);
        $this->db->bind(':status', $status);
        return $this->db->execute();
    }
}
