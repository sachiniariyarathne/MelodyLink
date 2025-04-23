<?php
class Member_Profile extends Controller{
    public function __construct(){
        // parent::__construct(); // Important to call parent constructor
        $this->userModel = $this->model('m_users'); // Use your actual model name
    }

    public function index(){
        // Your index logic
    }

    public function profile() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
            redirect('/users/login');
            exit;
        }
        
        $userId = $_SESSION['user_id'];
        $userType = $_SESSION['user_type'];
        
        // Get user data from model
        $user = $this->userModel->getUserData($userId);
        
        // Prepare data for view
        $data = [
            'member_id' => $user->member_id,
            'username' => $user->Username,
            'email' => $user->email,
            'phone_number' => $user->Phone_number,
            'address' => $user->Address,
            'profile_pic' => $user->profile_pic
        ];
        
        // Load view
        $this->view('users/v_member_profile', $data);
    }

    public function update() {
        // Check if user is logged in
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
            redirect('/users/login');
            exit;
        }
    
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserData($userId);
    
        $data = [
            'member_id' => $user->member_id,
            'username' => $user->Username,
            'email' => $user->email,
            'phone_number' => $user->Phone_number,
            'address' => $user->Address,
            'profile_pic' => $user->profile_pic
        ];
    
        $this->view('users/v_member_profile_update', $data);
    }
    
}
?>
