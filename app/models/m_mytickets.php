<?php
class m_mytickets {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }
    
    public function getUpcomingBookings($userId) {
        $this->db->query('
            SELECT eb.*, e.title, e.event_date, e.event_time, e.venue, e.image 
            FROM event_bookings eb
            JOIN events e ON eb.event_id = e.event_id
            WHERE eb.user_id = :user_id 
            AND e.event_date >= CURDATE()
            AND eb.status != "cancelled"
            ORDER BY e.event_date ASC
        ');
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
    
    public function getPastBookings($userId) {
        $this->db->query('
            SELECT eb.*, e.title, e.event_date, e.event_time, e.venue, e.image 
            FROM event_bookings eb
            JOIN events e ON eb.event_id = e.event_id
            WHERE eb.user_id = :user_id 
            AND e.event_date < CURDATE()
            ORDER BY e.event_date DESC
        ');
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
    
    public function getBookingById($id) {
        $this->db->query('
            SELECT eb.*, e.title, e.event_date, e.event_time, e.venue, e.image 
            FROM event_bookings eb
            JOIN events e ON eb.event_id = e.event_id
            WHERE eb.booking_id = :id
        ');
        
        $this->db->bind(':id', $id);
        return $this->db->single();
    }
    
    public function cancelBooking($id) {
        $this->db->query('
            UPDATE event_bookings 
            SET status = "cancelled", updated_at = NOW() 
            WHERE booking_id = :id
        ');
        
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getSavedEvents($userId) {
        // Assuming you have a saved_events table
        $this->db->query('
            SELECT e.* FROM events e
            JOIN saved_events se ON e.event_id = se.event_id
            WHERE se.user_id = :user_id
            ORDER BY e.event_date ASC
        ');
        
        $this->db->bind(':user_id', $userId);
        return $this->db->resultSet();
    }
    
    public function saveEvent($userId, $eventId) {
        // Check if already saved
        if($this->isEventSaved($userId, $eventId)) {
            return true;
        }
        
        $this->db->query('
            INSERT INTO saved_events (user_id, event_id, created_at) 
            VALUES (:user_id, :event_id, NOW())
        ');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':event_id', $eventId);
        return $this->db->execute();
    }
    
    public function unsaveEvent($userId, $eventId) {
        $this->db->query('
            DELETE FROM saved_events 
            WHERE user_id = :user_id AND event_id = :event_id
        ');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':event_id', $eventId);
        return $this->db->execute();
    }
    
    public function isEventSaved($userId, $eventId) {
        $this->db->query('
            SELECT * FROM saved_events 
            WHERE user_id = :user_id AND event_id = :event_id
        ');
        
        $this->db->bind(':user_id', $userId);
        $this->db->bind(':event_id', $eventId);
        $row = $this->db->single();
        return $row ? true : false;
    }
}
?>
