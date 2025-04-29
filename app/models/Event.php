<?php
class Event {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    public function getTotalBookings($organizerId) {
        $this->db->query('
            SELECT COUNT(*) as total 
            FROM event_bookings eb
            JOIN events e ON eb.event_id = e.event_id
            WHERE e.organiser_id = :organizer_id AND eb.payment_status = "completed"
        ');
        $this->db->bind(':organizer_id', $organizerId);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getTotalRevenue($organizerId) {
        $this->db->query('
            SELECT SUM(eb.total_price) as total 
            FROM event_bookings eb
            JOIN events e ON eb.event_id = e.event_id
            WHERE e.organiser_id = :organizer_id AND eb.payment_status = "completed"
        ');
        $this->db->bind(':organizer_id', $organizerId);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getActiveEventsCount($organizerId) {
        $this->db->query('
            SELECT COUNT(*) as total 
            FROM events 
            WHERE organiser_id = :organizer_id AND event_date >= CURDATE()
        ');
        $this->db->bind(':organizer_id', $organizerId);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getEndingSoonCount($organizerId) {
        $this->db->query('
            SELECT COUNT(*) as total 
            FROM events 
            WHERE organiser_id = :organizer_id 
            AND event_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 7 DAY)
        ');
        $this->db->bind(':organizer_id', $organizerId);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getTotalCustomers($organizerId) {
        $this->db->query('
            SELECT COUNT(DISTINCT eb.user_id) as total 
            FROM event_bookings eb
            JOIN events e ON eb.event_id = e.event_id
            WHERE e.organiser_id = :organizer_id
        ');
        $this->db->bind(':organizer_id', $organizerId);
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    public function getRecentBookings($organizerId, $limit = 10) {
        $this->db->query('
            SELECT eb.*, e.title as event_title, m.Username as customer_name, m.profile_pic as user_avatar 
            FROM event_bookings eb
            JOIN events e ON eb.event_id = e.event_id
            JOIN member m ON eb.user_id = m.member_id
            WHERE e.organiser_id = :organizer_id
            ORDER BY eb.created_at DESC
            LIMIT :limit
        ');
        $this->db->bind(':organizer_id', $organizerId);
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
                   SUM(CASE WHEN eb.payment_status = "completed" THEN eb.total_price ELSE 0 END) as total_income,
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
                   COALESCE(SUM(CASE WHEN eb.payment_status = "completed" THEN eb.total_price ELSE 0 END), 0) as total_income,
                   CASE 
                       WHEN e.event_date < CURDATE() THEN "ended"
                       ELSE "active"
                   END as status,
                   COALESCE((
                       SELECT SUM(CAST(JSON_UNQUOTE(JSON_EXTRACT(eb2.tickets, CONCAT("$.", t.ticket_type_id))) AS UNSIGNED))
                       FROM event_bookings eb2
                       JOIN ticket_types t ON t.event_id = eb2.event_id
                       WHERE eb2.event_id = e.event_id AND eb2.payment_status = "completed"
                   ), 0) as quantity_sold
            FROM events e
            LEFT JOIN event_bookings eb ON e.event_id = eb.event_id
            WHERE e.event_id = :id
            GROUP BY e.event_id
        ');
        $this->db->bind(':id', $id);
        $result = $this->db->single();
    
        if (!$result) {
            error_log("No event found for event_id: $id");
            return false;
        }
    
        error_log("Event ID: $id, Quantity Sold: {$result->quantity_sold}, Total Bookings: {$result->total_bookings}");
        return $result;
    }

    public function createEvent($data) {
        $this->db->beginTransaction();

        try {
            $this->db->query('INSERT INTO events (organiser_id, title, eventType, description, event_date, event_time, venue, image) 
                            VALUES (:organiser_id, :title, :eventType, :description, :event_date, :event_time, :venue, :image)');
            
            $this->db->bind(':organiser_id', $data['organiser_id']);
            $this->db->bind(':title', $data['title']);
            $this->db->bind(':eventType', $data['eventType']);
            $this->db->bind(':description', $data['description']);
            $this->db->bind(':event_date', $data['event_date']);
            $this->db->bind(':event_time', $data['event_time']);
            $this->db->bind(':venue', $data['venue']);
            $this->db->bind(':image', $data['image']);

            $this->db->execute();
            $eventId = $this->db->lastInsertId();

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
            if (empty($data['event_id']) || empty($data['title']) || empty($data['description']) || 
                empty($data['event_date']) || empty($data['event_time']) || empty($data['venue'])) {
                throw new Exception('Missing required fields');
            }
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

            if (!$this->db->execute()) {
                throw new Exception('Failed to update event');
            }

            $this->db->query('DELETE FROM ticket_types WHERE event_id = :event_id');
            $this->db->bind(':event_id', $data['event_id']);
            
            if (!$this->db->execute()) {
                throw new Exception('Failed to delete existing ticket types');
            }

            if (!empty($data['ticket_types'])) {
                foreach ($data['ticket_types'] as $ticket) {
                    if (empty($ticket['name']) || !isset($ticket['price']) || !isset($ticket['quantity'])) {
                        throw new Exception('Invalid ticket type data');
                    }

                    $this->db->query('INSERT INTO ticket_types (event_id, name, price, quantity_available) 
                                    VALUES (:event_id, :name, :price, :quantity)');
                    
                    $this->db->bind(':event_id', $data['event_id']);
                    $this->db->bind(':name', $ticket['name']);
                    $this->db->bind(':price', $ticket['price']);
                    $this->db->bind(':quantity', $ticket['quantity']);

                    if (!$this->db->execute()) {
                        throw new Exception('Failed to insert ticket type');
                    }
                }
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
        $this->db->query('DELETE FROM events WHERE event_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function getEventTicketTypes($eventId) {
        $this->db->query('
            SELECT t.*, 
                   t.name as ticket_type,
                   t.quantity_available as original_quantity,
                   COALESCE((
                       SELECT SUM(CAST(JSON_UNQUOTE(JSON_EXTRACT(b.tickets, CONCAT("$.", t.ticket_type_id))) AS UNSIGNED))
                       FROM event_bookings b
                       WHERE b.event_id = t.event_id AND b.payment_status = "completed"
                   ), 0) as quantity_sold
            FROM ticket_types t
            WHERE t.event_id = :event_id
        ');
        $this->db->bind(':event_id', $eventId);
        $results = $this->db->resultSet();
    
        if (empty($results)) {
            error_log("No ticket types found for event_id: $eventId");
        }
    
        foreach ($results as $ticket) {
            $ticket->available_quantity = $ticket->original_quantity - $ticket->quantity_sold;
            error_log("Event ID: $eventId, Ticket Type ID: {$ticket->ticket_type_id}, Quantity Sold: {$ticket->quantity_sold}, Available: {$ticket->available_quantity}");
        }
    
        return $results;
    }

    public function getEventBookings($eventId) {
        $this->db->query('
            SELECT eb.*, 
                   m.Username as customer_name, 
                   m.email, 
                   m.Phone_number,
                   t.name as ticket_type,
                   JSON_EXTRACT(eb.tickets, CONCAT("$.", t.ticket_type_id)) as ticket_quantity
            FROM event_bookings eb
            JOIN member m ON eb.user_id = m.member_id
            LEFT JOIN ticket_types t ON t.event_id = eb.event_id
            WHERE eb.event_id = :event_id
            ORDER BY eb.created_at DESC
        ');
        $this->db->bind(':event_id', $eventId);
        $results = $this->db->resultSet();
    
        $bookings = [];
        foreach ($results as $row) {
            $bookingId = $row->booking_id;
            if (!isset($bookings[$bookingId])) {
                $bookings[$bookingId] = (object)[
                    'booking_id' => $row->booking_id,
                    'event_id' => $row->event_id,
                    'user_id' => $row->user_id,
                    'customer_name' => $row->customer_name,
                    'email' => $row->email,
                    'secret_code' => $row->secret_code,
                    'total_price' => $row->total_price,
                    'payment_status' => $row->payment_status,
                    'created_at' => $row->created_at,
                    'status' => $row->status,
                    'tickets' => []
                ];
            }
            if ($row->ticket_type && $row->ticket_quantity) {
                $bookings[$bookingId]->tickets[] = (object)[
                    'ticket_type' => $row->ticket_type,
                    'quantity' => $row->ticket_quantity
                ];
            }
        }
        return array_values($bookings);
    }

    public function getTicketTypeById($id) {
        $this->db->query('SELECT * FROM ticket_types WHERE ticket_type_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getUserById($userId) {
        $this->db->query('SELECT * FROM member WHERE member_id = :user_id');
        $this->db->bind(':user_id', $userId);
        
        $row = $this->db->single();
        return $row;
    }

    public function createBooking($data) {
        $this->db->beginTransaction();

        try {
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

            $bookingId = $this->db->lastInsertId();

            $this->db->query('UPDATE ticket_types SET quantity_available = quantity_available - :quantity 
                            WHERE ticket_type_id = :ticket_type_id');
            
            $this->db->bind(':quantity', $data['quantity']);
            $this->db->bind(':ticket_type_id', $data['ticket_type_id']);
            
            $this->db->execute();

            $this->db->commit();
            return $bookingId; 
        } catch (Exception $e) {
            $this->db->rollBack();
            error_log('Error creating booking: ' . $e->getMessage());
            return false;
        }
    }

    public function getBookingById($id) {
        $this->db->query('SELECT * FROM event_bookings WHERE booking_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function lastInsertId() {
        return $this->db->lastInsertId();
    }

    public function getOrganizerById($id) {
        $this->db->query('SELECT * FROM event_organiser WHERE organiser_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function updateOrganizerProfile($data) {
        $this->db->query('UPDATE event_organiser SET username = :username, Organization = :organization, updated_at = CURRENT_TIMESTAMP WHERE organiser_id = :id');
        
        $this->db->bind(':id', $data['organizer_id']);
        $this->db->bind(':username', $data['username']);
        $this->db->bind(':organization', $data['organization']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }
} 