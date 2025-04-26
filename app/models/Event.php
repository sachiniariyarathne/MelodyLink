<?php
class Event {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Dashboard Methods
    public function getTotalBookings() {
        $this->db->query('SELECT COUNT(*) as total FROM event_bookings WHERE status = "confirmed"');
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getTotalRevenue() {
        $this->db->query('SELECT SUM(total_price) as total FROM event_bookings WHERE status = "confirmed"');
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getActiveEventsCount() {
        $this->db->query('SELECT COUNT(*) as total FROM events WHERE event_date >= CURDATE()');
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getEndingSoonCount() {
        $this->db->query('SELECT COUNT(*) as total FROM events WHERE event_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)');
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getTotalCustomers() {
        $this->db->query('SELECT COUNT(DISTINCT user_id) as total FROM event_bookings');
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getRecentBookings($limit = 10) {
        $this->db->query('
            SELECT eb.*, e.title as event_title, m.Username as customer_name, m.profile_pic as user_avatar 
            FROM event_bookings eb
            JOIN events e ON eb.event_id = e.event_id
            JOIN member m ON eb.user_id = m.member_id
            ORDER BY eb.created_at DESC
            LIMIT :limit
        ');
        $this->db->bind(':limit', $limit);
        return $this->db->resultSet();
    }

    // Event Methods
    public function getAllEvents() {
        $this->db->query('SELECT * FROM events ORDER BY event_date DESC');
        return $this->db->resultSet();
    }

    public function getOrganizerEvents($organizerId) {
        $this->db->query('
            SELECT e.*, 
                   COUNT(DISTINCT eb.booking_id) as total_bookings,
                   SUM(CASE WHEN eb.status = "confirmed" THEN eb.total_price ELSE 0 END) as total_income,
                   CASE 
                       WHEN e.event_date < CURDATE() THEN "ended"
                       ELSE "active"
                   END as status
            FROM events e
            LEFT JOIN event_bookings eb ON e.event_id = eb.event_id
            WHERE e.organiser_id = :organiser_id
            GROUP BY e.event_id
            ORDER BY e.event_date DESC
        ');
        $this->db->bind(':organiser_id', $organizerId);
        return $this->db->resultSet();
    }

    public function getEventById($id) {
        $this->db->query('
            SELECT e.*, 
                   COUNT(DISTINCT eb.booking_id) as total_bookings,
                   SUM(CASE WHEN eb.status = "confirmed" THEN eb.total_price ELSE 0 END) as total_income
            FROM events e
            LEFT JOIN event_bookings eb ON e.event_id = eb.event_id
            WHERE e.event_id = :id
            GROUP BY e.event_id
        ');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function createEvent($data) {
        $this->db->beginTransaction();

        try {
            // Insert event
            $this->db->query('INSERT INTO events (organiser_id, title, description, event_date, event_time, venue, image) 
                            VALUES (:organiser_id, :title, :description, :event_date, :event_time, :venue, :image)');
            
            $this->db->bind(':organiser_id', $data['organiser_id']);
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':description', $data['description']);
            $this->db->bind(':event_date', $data['event_date']);
            $this->db->bind(':event_time', $data['event_time']);
            $this->db->bind(':venue', $data['venue']);
            $this->db->bind(':image', $data['image']);

            $this->db->execute();
            $eventId = $this->db->lastInsertId();

            // Insert ticket types
            foreach ($data['ticket_types'] as $ticket) {
                $this->db->query('INSERT INTO ticket_types (event_id, name, price, quantity_available) 
                                VALUES (:event_id, :name, :price, :quantity)');
                
                $this->db->bind(':event_id', $eventId);
                $this->db->bind(':name', $ticket['name']);
                $this->db->bind(':price', $ticket['price']);
                $this->db->bind(':quantity', $ticket['quantity']);

                $this->db->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error creating event: ' . $e->getMessage());
            return false;
        }
    }

    public function updateEvent($data) {
        $this->db->beginTransaction();

        try {
            // Update event
            $this->db->query('UPDATE events SET title = :title, description = :description, 
                            event_date = :event_date, event_time = :event_time, 
                            venue = :venue, image = :image 
                            WHERE event_id = :event_id');
            
            $this->db->bind(':event_id', $data['event_id']);
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':description', $data['description']);
            $this->db->bind(':event_date', $data['event_date']);
            $this->db->bind(':event_time', $data['event_time']);
            $this->db->bind(':venue', $data['venue']);
            $this->db->bind(':image', $data['image']);

            $this->db->execute();

            // Delete existing ticket types
            $this->db->query('DELETE FROM ticket_types WHERE event_id = :event_id');
            $this->db->bind(':event_id', $data['event_id']);
            $this->db->execute();

            // Insert new ticket types
            foreach ($data['ticket_types'] as $ticket) {
                $this->db->query('INSERT INTO ticket_types (event_id, name, price, quantity_available) 
                                VALUES (:event_id, :name, :price, :quantity)');
                
                $this->db->bind(':event_id', $data['event_id']);
                $this->db->bind(':name', $ticket['name']);
                $this->db->bind(':price', $ticket['price']);
                $this->db->bind(':quantity', $ticket['quantity']);

                $this->db->execute();
            }

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error updating event: ' . $e->getMessage());
            return false;
        }
    }

    public function deleteEvent($id) {
        // Delete event (ticket types will be deleted by foreign key cascade)
        $this->db->query('DELETE FROM events WHERE event_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getEventTicketTypes($eventId) {
        $this->db->query('
            SELECT t.*, 
                   (t.quantity_available - COALESCE(SUM(CASE WHEN b.status = "confirmed" THEN JSON_EXTRACT(b.tickets, CONCAT("$.", t.ticket_type_id)) ELSE 0 END), 0)) as available_quantity
            FROM ticket_types t
            LEFT JOIN event_bookings b ON t.event_id = b.event_id
            WHERE t.event_id = :event_id
            GROUP BY t.ticket_type_id
        ');
        $this->db->bind(':event_id', $eventId);
        return $this->db->resultSet();
    }

    public function getEventBookings($eventId) {
        $this->db->query('
            SELECT eb.*, m.Username as customer_name, m.email, m.Phone_number
            FROM event_bookings eb
            JOIN member m ON eb.user_id = m.member_id
            WHERE eb.event_id = :event_id
            ORDER BY eb.created_at DESC
        ');
        $this->db->bind(':event_id', $eventId);
        return $this->db->resultSet();
    }

    public function getTicketTypeById($id) {
        $this->db->query('SELECT * FROM ticket_types WHERE ticket_type_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getUserById($id) {
        $this->db->query('SELECT * FROM member WHERE member_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function createBooking($data) {
        $this->db->beginTransaction();

        try {
            // Insert booking
            $this->db->query('INSERT INTO event_bookings (event_id, user_id, tickets, total_price, payment_status, payment_method, card_last_four, secret_code) 
                            VALUES (:event_id, :user_id, :tickets, :total_price, :payment_status, :payment_method, :card_last_four, :secret_code)');
            
            $tickets = json_encode([$data['ticket_type_id'] => $data['quantity']]);
            
            $this->db->bind(':event_id', $data['event_id']);
            $this->db->bind(':user_id', $data['user_id']);
            $this->db->bind(':tickets', $tickets);
            $this->db->bind(':total_price', $data['total_price']);
            $this->db->bind(':payment_status', $data['payment_status']);
            $this->db->bind(':payment_method', $data['payment_method']);
            $this->db->bind(':card_last_four', $data['card_last_four']);
            $this->db->bind(':secret_code', $data['secret_code']);

            $this->db->execute();

            // Update available quantity
            $this->db->query('UPDATE ticket_types SET quantity_available = quantity_available - :quantity 
                            WHERE ticket_type_id = :ticket_type_id');
            
            $this->db->bind(':quantity', $data['quantity']);
            $this->db->bind(':ticket_type_id', $data['ticket_type_id']);
            
            $this->db->execute();

            $this->db->commit();
            return true;
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error creating booking: ' . $e->getMessage());
            return false;
        }
    }
} 