<?php
class Events extends Controller {
    private $eventModel;

    public function __construct() {
        $this->eventModel = $this->model('Event');
    }

    public function index() {
        // Get all events from the database
        $events = $this->eventModel->getAllEvents();
        
        $data = [
            'events' => $events
        ];

        $this->view('users/v_events', $data);
    }

    public function details($id) {
        // Get event details from the database
        $event = $this->eventModel->getEventById($id);
        
        if(!$event) {
            redirect('events');
        }

        $data = [
            'event' => $event
        ];

        $this->view('users/v_event_details', $data);
    }

    public function book($id) {
        // Check if user is logged in
        if(!isLoggedIn()) {
            redirect('users/login');
        }

        // Get event details
        $event = $this->eventModel->getEventById($id);
        
        if(!$event) {
            redirect('events');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process booking
            $data = [
                'event_id' => $id,
                'user_id' => $_SESSION['user_id'],
                'tickets' => $_POST['tickets'],
                'total_price' => $event->ticket_price * $_POST['tickets']
            ];

            if($this->eventModel->bookEvent($data)) {
                // Booking successful
                flash('booking_success', 'Your tickets have been booked successfully!');
                redirect('users/dashboard');
            } else {
                // Booking failed
                flash('booking_error', 'Something went wrong. Please try again.', 'alert alert-danger');
                redirect('events/details/' . $id);
            }
        } else {
            // Show booking form
            $data = [
                'event' => $event
            ];

            $this->view('users/v_event_booking', $data);
        }
    }

    // public function save($id) {
    //     // Check if user is logged in
    //     if(!isLoggedIn()) {
    //         if($this->isAjaxRequest()) {
    //             header('Content-Type: application/json');
    //             echo json_encode(['success' => false, 'message' => 'Please log in to save events']);
    //             return;
    //         }
    //         redirect('users/login');
    //     }
    
    //     $userId = $_SESSION['user_id'];
        
    //     // Save the event
    //     if($this->eventModel->saveEvent($userId, $id)) {
    //         if($this->isAjaxRequest()) {
    //             header('Content-Type: application/json');
    //             echo json_encode(['success' => true, 'message' => 'Event saved to your list']);
    //             return;
    //         }
    //         flash('event_message', 'Event saved to your list', 'alert alert-success');
    //         redirect('events/details/' . $id);
    //     } else {
    //         if($this->isAjaxRequest()) {
    //             header('Content-Type: application/json');
    //             echo json_encode(['success' => false, 'message' => 'Something went wrong']);
    //             return;
    //         }
    //         flash('event_message', 'Something went wrong', 'alert alert-danger');
    //         redirect('events/details/' . $id);
    //     }
    // }
    
    // public function unsave($id) {
    //     // Check if user is logged in
    //     if(!isLoggedIn()) {
    //         if($this->isAjaxRequest()) {
    //             header('Content-Type: application/json');
    //             echo json_encode(['success' => false, 'message' => 'Please log in to manage saved events']);
    //             return;
    //         }
    //         redirect('users/login');
    //     }
    
    //     $userId = $_SESSION['user_id'];
        
    //     // Unsave the event
    //     if($this->eventModel->unsaveEvent($userId, $id)) {
    //         if($this->isAjaxRequest()) {
    //             header('Content-Type: application/json');
    //             echo json_encode(['success' => true, 'message' => 'Event removed from your saved list']);
    //             return;
    //         }
    //         flash('event_message', 'Event removed from your saved list', 'alert alert-success');
    //         redirect('my_tickets');
    //     } else {
    //         if($this->isAjaxRequest()) {
    //             header('Content-Type: application/json');
    //             echo json_encode(['success' => false, 'message' => 'Something went wrong']);
    //             return;
    //         }
    //         flash('event_message', 'Something went wrong', 'alert alert-danger');
    //         redirect('my_tickets');
    //     }
    // }
    
    // private function isAjaxRequest() {
    //     return isset($_SERVER['HTTP_X_REQUESTED_WITH']) && 
    //            strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    // }
    
} 