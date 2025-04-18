<?php
class Event {
    private $db;

    public function __construct() {
        $this->db = new Database();
    }

    // Get all events
    public function getAllEvents() {
        $this->db->query('SELECT * FROM events ORDER BY event_date DESC');
        return $this->db->resultSet();
    }

    // Get all events for a specific organizer
    public function getOrganizerEvents($organizerId) {
        $this->db->query('SELECT * FROM events WHERE organiser_id = :organiser_id ORDER BY event_date DESC');
        $this->db->bind(':organiser_id', $organizerId);
        
        $results = $this->db->resultSet();
        
        // Calculate total bookings and income for each event
        foreach($results as $event) {
            $event->total_bookings = $this->getTotalBookings($event->event_id);
            $event->total_income = $this->getTotalIncome($event->event_id);
        }
        
        return $results;
    }

    // Get total bookings for an event
    private function getTotalBookings($eventId) {
        $this->db->query('SELECT COUNT(*) as total FROM bookings WHERE event_id = :event_id AND status = "confirmed"');
        $this->db->bind(':event_id', $eventId);
        
        $result = $this->db->single();
        return $result->total;
    }

    // Get total income for an event
    private function getTotalIncome($eventId) {
        $this->db->query('SELECT SUM(total_price) as total FROM bookings WHERE event_id = :event_id AND status = "confirmed"');
        $this->db->bind(':event_id', $eventId);
        
        $result = $this->db->single();
        return $result->total ?? 0;
    }

    // Get event by ID
    public function getEventById($id) {
        $this->db->query('SELECT * FROM events WHERE event_id = :id');
        $this->db->bind(':id', $id);
        
        $event = $this->db->single();
        
        if($event) {
            $event->total_bookings = $this->getTotalBookings($id);
            $event->total_income = $this->getTotalIncome($id);
        }
        
        return $event;
    }

    // Create new event
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
                $this->db->query('INSERT INTO ticket_types (event_id, name, price, quantity) 
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
            return false;
        }
    }

    // Update event
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
                $this->db->query('INSERT INTO ticket_types (event_id, name, price, quantity) 
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
            return false;
        }
    }

    // Delete event
    public function deleteEvent($id) {
        $this->db->query('DELETE FROM events WHERE event_id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
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

    public function getEventTicketTypes($eventId) {
        $this->db->query('SELECT * FROM ticket_types WHERE event_id = :event_id');
        $this->db->bind(':event_id', $eventId);
        return $this->db->resultSet();
    }
} 