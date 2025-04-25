<?php
class My_Tickets extends Controller {
    private $ticketModel;
    private $eventModel;
    private $userModel;

    public function __construct() {
        // Check login
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }
        
        // Load models
        $this->ticketModel = $this->model('m_mytickets');
        $this->eventModel = $this->model('Event');
        $this->userModel = $this->model('m_users');
    }

    public function index() {
        $userId = $_SESSION['user_id'];
        
        // Get user data
        $memberInfo = $this->userModel->getUserData($userId);
        
        // Get ticket data
        $data = [
            'member_info' => [
                'username' => $memberInfo->Username,
                'email' => $memberInfo->email,
                'profile_pic' => $memberInfo->profile_pic
            ],
            'upcoming_bookings' => $this->ticketModel->getUpcomingBookings($userId),
            'past_bookings' => $this->ticketModel->getPastBookings($userId),
            'saved_events' => $this->eventModel->getSavedEvents($userId)
        ];

        $this->view('users/v_member_my_tickets', $data);
    }

    public function mytickets() {
        // This can be removed as index() handles the functionality
        $this->index();
    }
}
?>


