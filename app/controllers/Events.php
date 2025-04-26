<?php
class Events extends Controller {
    private $eventModel;

    public function __construct() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Load helpers
        require_once '../app/helpers/flash_helper.php';

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
            'event' => $event,
            'ticket_types' => $this->eventModel->getEventTicketTypes($id)
        ];

        $this->view('users/v_event_details', $data);
    }

    public function book($id) {
        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        // Get event details
        $event = $this->eventModel->getEventById($id);
        
        if(!$event) {
            redirect('events');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Get ticket type and quantity
            $ticketTypeId = $_POST['ticket_type_id'];
            $quantity = $_POST['quantity'];
            
            // Get ticket details
            $ticket = $this->eventModel->getTicketTypeById($ticketTypeId);
            
            if(!$ticket) {
                flash('booking_error', 'Selected ticket type not found.', 'alert alert-danger');
                redirect('events/details/' . $id);
            }

            // Calculate total price
            $totalPrice = $ticket->price * $quantity;

            $data = [
                'event' => $event,
                'ticket' => $ticket,
                'quantity' => $quantity,
                'total_price' => $totalPrice
            ];

            $this->view('users/v_event_payment', $data);
        } else {
            $data = [
                'event' => $event,
                'ticket_types' => $this->eventModel->getEventTicketTypes($id)
            ];

            $this->view('users/v_event_booking', $data);
        }
    }

    public function createPaymentIntent() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            try {
                // Get POST data
                $input = file_get_contents('php://input');
                error_log('Raw input: ' . $input);
                
                $data = json_decode($input, true);
                error_log('Decoded data: ' . print_r($data, true));
                
                if (!$data) {
                    throw new Exception('Invalid JSON data received');
                }
                
                if (!isset($data['amount'])) {
                    throw new Exception('Amount is required');
                }
                
                // Initialize Stripe
                $stripe = new StripePayment();
                
                // Create payment intent
                $result = $stripe->createPaymentIntent($data['amount']);
                error_log('Stripe result: ' . print_r($result, true));
                
                if ($result['success']) {
                    echo json_encode([
                        'success' => true,
                        'paymentIntentId' => $result['paymentIntentId']
                    ]);
                } else {
                    throw new Exception($result['error'] ?? 'Failed to create payment intent');
                }
            } catch (Exception $e) {
                error_log('Payment intent creation error: ' . $e->getMessage());
                error_log('Stack trace: ' . $e->getTraceAsString());
                echo json_encode([
                    'success' => false,
                    'error' => $e->getMessage()
                ]);
            }
        } else {
            error_log('Invalid request method: ' . $_SERVER['REQUEST_METHOD']);
            echo json_encode([
                'success' => false,
                'error' => 'Invalid request method'
            ]);
        }
    }

    public function processPayment($id) {
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }

        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Get payment intent ID and other data
            $paymentIntentId = $_POST['payment_intent_id'];
            $ticketTypeId = $_POST['ticket_type_id'];
            $quantity = $_POST['quantity'];
            $totalPrice = $_POST['total_price'];

            // Initialize Stripe
            $stripe = new StripePayment();
            
            // Confirm payment
            $result = $stripe->confirmPayment($paymentIntentId);
            
            if($result['success'] && $result['status'] === 'succeeded') {
                // Get ticket details
                $ticket = $this->eventModel->getTicketTypeById($ticketTypeId);
                
                if(!$ticket) {
                    flash('booking_error', 'Selected tickets are not available.', 'alert alert-danger');
                    redirect('events/details/' . $id);
                }

                // Generate secret code
                $secretCode = $this->generateSecretCode();

                // Create booking
                $bookingData = [
                    'event_id' => $id,
                    'user_id' => $_SESSION['user_id'],
                    'ticket_type_id' => $ticketTypeId,
                    'quantity' => $quantity,
                    'total_price' => $totalPrice,
                    'payment_status' => 'completed',
                    'payment_method' => 'card',
                    'card_last_four' => substr($result['paymentIntent']->payment_method_details->card->last4, -4),
                    'secret_code' => $secretCode
                ];

                if($this->eventModel->createBooking($bookingData)) {
                    // Get booking details
                    $booking = $this->eventModel->getBookingById($this->eventModel->lastInsertId());
                    $event = $this->eventModel->getEventById($id);
                    
                    // Send confirmation email
                    $this->sendBookingConfirmation($bookingData, $secretCode);
                    
                    // Show confirmation page
                    $data = [
                        'event' => $event,
                        'ticket' => $ticket,
                        'quantity' => $quantity,
                        'total_price' => $totalPrice,
                        'booking' => $booking
                    ];
                    
                    $this->view('users/v_booking_confirmation', $data);
                } else {
                    flash('booking_error', 'Something went wrong. Please try again.', 'alert alert-danger');
                    redirect('events/details/' . $id);
                }
            } else {
                flash('payment_error', 'Payment failed. Please try again.', 'alert alert-danger');
                redirect('events/book/' . $id);
            }
        } else {
            redirect('events');
        }
    }

    private function generateSecretCode() {
        $characters = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $code = '';
        for ($i = 0; $i < 8; $i++) {
            $code .= $characters[rand(0, strlen($characters) - 1)];
        }
        return $code;
    }

    private function processCardPayment($cardNumber, $expiryDate, $cvv, $amount) {
        // In a real application, this would integrate with a payment gateway
        // For now, we'll simulate a successful payment
        return true;
    }

    private function sendBookingConfirmation($bookingData, $secretCode) {
        // Get user details
        $user = $this->eventModel->getUserById($bookingData['user_id']);
        $event = $this->eventModel->getEventById($bookingData['event_id']);
        $ticket = $this->eventModel->getTicketTypeById($bookingData['ticket_type_id']);

        // Email content
        $to = $user->email;
        $subject = 'Your Event Ticket Booking Confirmation';
        
        $message = "
            <h2>Booking Confirmation</h2>
            <p>Dear {$user->Username},</p>
            <p>Thank you for booking tickets for {$event->title}.</p>
            <p>Booking Details:</p>
            <ul>
                <li>Event: {$event->title}</li>
                <li>Date: {$event->event_date}</li>
                <li>Time: {$event->event_time}</li>
                <li>Venue: {$event->venue}</li>
                <li>Ticket Type: {$ticket->name}</li>
                <li>Quantity: {$bookingData['quantity']}</li>
                <li>Total Amount: Rs.{$bookingData['total_price']}</li>
            </ul>
            <p>Your secret code for entry: <strong>{$secretCode}</strong></p>
            <p>Please present this code at the event entrance.</p>
            <p>Thank you for choosing our service!</p>
        ";

        // Send email
        $headers = "MIME-Version: 1.0" . "\r\n";
        $headers .= "Content-type:text/html;charset=UTF-8" . "\r\n";
        $headers .= "From: noreply@yourevent.com" . "\r\n";

        mail($to, $subject, $message, $headers);
    }
} 