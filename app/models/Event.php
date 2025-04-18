<?php
class Event {
    private $db;

    public function __construct() {
        $this->db = new Database;
    }

    public function getAllEvents() {
        $this->db->query('SELECT * FROM events WHERE event_date >= CURDATE() ORDER BY event_date ASC');
        return $this->db->resultSet();
    }

    public function getEventById($id) {
        $this->db->query('SELECT * FROM events WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function bookEvent($data) {
        $this->db->query('INSERT INTO event_bookings (event_id, user_id, tickets, total_price) VALUES (:event_id, :user_id, :tickets, :total_price)');
        
        // Bind values
        $this->db->bind(':event_id', $data['event_id']);
        $this->db->bind(':user_id', $data['user_id']);
        $this->db->bind(':tickets', $data['tickets']);
        $this->db->bind(':total_price', $data['total_price']);

        // Execute
        if($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
} 