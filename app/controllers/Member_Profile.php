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
        $userType = $_SESSION['user_type'];
        $user = $this->userModel->getUserData($userId);
    
        // If form submitted
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
    
            $data = [
                'member_id' => $user->member_id,
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'phone_number' => trim($_POST['phone']),
                'address' => trim($_POST['address']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'profile_pic' => $_FILES['profile_pic']['name'] ?? $user->profile_pic,
                // Error fields
                'username_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'address_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'profile_pic_err' => ''
            ];
    
            // Username validation
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter a username.';
            }
    
            // Email validation
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter an email.';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email address.';
            } else {
                $existingUser = $this->userModel->findUserByEmail($data['email'], $userType);
                if ($existingUser && $data['email'] != $user->email) {
                    $data['email_err'] = 'Email is already registered.';
                }
            }
    
            // Phone validation (optional, but realistic)
            if (!empty($data['phone_number']) && !preg_match('/^\+?\d{7,15}$/', $data['phone_number'])) {
                $data['phone_err'] = 'Please enter a valid phone number.';
            }
    
            // Address validation (optional)
            if (!empty($data['address']) && strlen($data['address']) < 5) {
                $data['address_err'] = 'Address is too short.';
            }
    
            // Password validation
            if (!empty($data['password'])) {
                if (strlen($data['password']) < 6) {
                    $data['password_err'] = 'Password must be at least 6 characters.';
                }
                if (empty($data['confirm_password'])) {
                    $data['confirm_password_err'] = 'Please confirm the password.';
                } elseif ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match.';
                }
            }
    
            // Profile picture validation
            if (!empty($_FILES['profile_pic']['name'])) {
                $target_dir = APPROOT . '/public/uploads/';
                $target_file = $target_dir . basename($_FILES['profile_pic']['name']);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $check = getimagesize($_FILES['profile_pic']['tmp_name']);
                if ($check === false) {
                    $data['profile_pic_err'] = 'File is not an image.';
                } elseif ($_FILES['profile_pic']['size'] > 2 * 1024 * 1024) {
                    $data['profile_pic_err'] = 'File size must be less than 2MB.';
                } elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $data['profile_pic_err'] = 'Only JPG, JPEG, PNG & GIF files are allowed.';
                } elseif (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
                    $data['profile_pic_err'] = 'There was an error uploading your profile picture.';
                } else {
                    $data['profile_pic'] = basename($_FILES['profile_pic']['name']);
                }
            }
    
            // If no errors, update user
            if (
                empty($data['username_err']) && empty($data['email_err']) &&
                empty($data['phone_err']) && empty($data['address_err']) &&
                empty($data['password_err']) && empty($data['confirm_password_err']) &&
                empty($data['profile_pic_err'])
            ) {
                $updateData = [
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'Phone_number' => $data['phone_number'],
                    'Address' => $data['address']
                ];
    
                // Only update password if provided
                if (!empty($data['password'])) {
                    $updateData['Password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }
    
                if ($this->userModel->updateUserInfo($userId, $updateData, $userType)) {
                    if (!empty($_FILES['profile_pic']['name'])) {
                        $this->userModel->updateProfilePic($userId, $data['profile_pic'], $userType);
                    }
                    $_SESSION['username'] = $data['username'];
                    die('profile_updated,Your profile has been updated.');
                } else {
                    die('error,Something went wrong.');
                }
            } else {
                $this->view('users/v_member_profile_update', $data);
            }
        } else {
            // GET request: load data
            $data = [
                'member_id' => $user->member_id,
                'username' => $user->Username,
                'email' => $user->email,
                'phone_number' => $user->Phone_number,
                'address' => $user->Address,
                'profile_pic' => $user->profile_pic,
                'username_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'address_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'profile_pic_err' => ''
            ];
            $this->view('users/v_member_profile_update', $data);
        }
    }
    
}
?>
