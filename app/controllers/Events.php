<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

// Load PHPMailer classes
require_once APPROOT . '/libraries/PHPMailer-master/src/Exception.php';
require_once APPROOT . '/libraries/PHPMailer-master/src/PHPMailer.php';
require_once APPROOT . '/libraries/PHPMailer-master/src/SMTP.php';

class Events extends Controller {
    private $eventModel;
    private $mail;

    public function __construct() {
        $this->eventModel = $this->model('Event');
        
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }
        
        // Load helpers
        require_once '../app/helpers/session_helper.php';
        
        // Initialize PHPMailer
        $this->mail = new PHPMailer(true);
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

            // Get form data
            $ticketTypeId = $_POST['ticket_type_id'];
            $quantity = $_POST['quantity'];
            $totalPrice = $_POST['total_price'];

            // Get ticket details
            $ticket = $this->eventModel->getTicketTypeById($ticketTypeId);
            
            if(!$ticket) {
                flash('booking_error', 'Selected tickets are not available.', 'alert alert-danger');
                redirect('events/details/' . $id);
            }

            // Generate secret code
            $secretCode = $this->generateSecretCode();

            // Create booking with mock payment data
            $bookingData = [
                'event_id' => $id,
                'user_id' => $_SESSION['user_id'],
                'ticket_type_id' => $ticketTypeId,
                'quantity' => $quantity,
                'total_price' => $totalPrice,
                'payment_status' => 'completed',
                'payment_method' => 'card',
                'card_last_four' => '4242', // Mock last 4 digits
                'secret_code' => $secretCode
            ];

            // Create booking and get booking ID
            $bookingId = $this->eventModel->createBooking($bookingData);
            
            if($bookingId) {
                // Get booking details
                $booking = $this->eventModel->getBookingById($bookingId);
                
                if (!$booking) {
                    flash('booking_error', 'Failed to retrieve booking details.', 'alert alert-danger');
                    redirect('events/details/' . $id);
                }

                // Get event details
                $event = $this->eventModel->getEventById($id);
                
                if (!$event) {
                    flash('booking_error', 'Failed to retrieve event details.', 'alert alert-danger');
                    redirect('events/details/' . $id);
                }
                
                // Send confirmation email
                $this->sendBookingConfirmation($booking, $event, $ticket, $quantity);
                
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

    private function sendBookingConfirmation($booking, $event, $ticket, $quantity) {
        try {
            // Get user details using the Event model
            $user = $this->eventModel->getUserById($_SESSION['user_id']);
            
            if (!$user) {
                error_log("User not found for ID: " . $_SESSION['user_id']);
                return false;
            }

            // Server settings
            $this->mail->SMTPDebug = 0;
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'IndipaPerera5@gmail.com';
            $this->mail->Password = 'mxpt ybvk rgcb sbtv';
            $this->mail->SMTPSecure = 'tls';
            $this->mail->Port = 587;
            $this->mail->setFrom('MelodyLink.noreply@gmail.com', 'MelodyLink');
            
            // Recipients
            $this->mail->addAddress($user->email, $user->Username);
            
            // Content
            $this->mail->isHTML(true);
            $this->mail->Subject = 'Your Event Booking Confirmation';
            
            // Create HTML email body
            $emailBody = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; }
                    .booking-container { max-width: 600px; margin: 0 auto; }
                    .header { background-color: #f8f9fa; padding: 20px; text-align: center; }
                    .content { padding: 20px; }
                    .booking-details { background-color: #f8f9fa; padding: 15px; margin-top: 20px; }
                    .secret-code { background-color: #e9ecef; padding: 15px; margin-top: 20px; text-align: center; }
                    .footer { background-color: #f8f9fa; padding: 20px; text-align: center; font-size: 12px; }
                </style>
            </head>
            <body>
                <div class='booking-container'>
                    <div class='header'>
                        <h2>Booking Confirmation</h2>
                        <p>Thank you for booking with MelodyLink!</p>
                    </div>
                    <div class='content'>
                        <p>Dear {$user->Username},</p>
                        <p>Your booking for {$event->title} has been confirmed.</p>
                        
                        <div class='booking-details'>
                            <h3>Event Details</h3>
                            <p><strong>Event:</strong> {$event->title}</p>
                            <p><strong>Date:</strong> " . date('F j, Y', strtotime($event->event_date)) . "</p>
                            <p><strong>Time:</strong> " . date('g:i A', strtotime($event->event_time)) . "</p>
                            <p><strong>Venue:</strong> {$event->venue}</p>
                            
                            <h3>Booking Details</h3>
                            <p><strong>Ticket Type:</strong> {$ticket->name}</p>
                            <p><strong>Quantity:</strong> {$quantity}</p>
                            <p><strong>Total Amount:</strong> Rs." . number_format($booking->total_price) . "</p>
                        </div>
                        
                        <div class='secret-code'>
                            <h3>Your Secret Code</h3>
                            <p style='font-size: 24px; font-weight: bold;'>{$booking->secret_code}</p>
                            <p>Please present this code at the event entrance.</p>
                        </div>
                        
                        <p>If you have any questions about your booking, please contact our support team.</p>
                    </div>
                    <div class='footer'>
                        <p>© " . date('Y') . " MelodyLink. All rights reserved.</p>
                    </div>
                </div>
            </body>
            </html>";
            
            $this->mail->Body = $emailBody;
            $this->mail->AltBody = "Your booking for {$event->title} has been confirmed. Your secret code is: {$booking->secret_code}";
            
            $this->mail->send();
            return true;
        } catch (Exception $e) {
            error_log("Error sending booking confirmation email: " . $e->getMessage());
            return false;
        }
    }
} 