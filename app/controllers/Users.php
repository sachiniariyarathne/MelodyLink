<?php
class Users extends Controller {
    public function __construct() {
        $this->userModel = $this->model('m_users');
    }

    public function index() {
        echo "This is the index method of the Users controller.";
    }

    public function register() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            // Prepare base data
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'userType' => trim($_POST['userType'] ?? 'member'),
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'userType_err' => ''
            ];


            // Validate userType
            if (empty($data['userType'])) {
                $data['userType_err'] = 'Please select a user type';
            }

            // Add optional fields if they exist

            if (isset($_POST['genre'])) {
                $data['genre'] = trim($_POST['genre']);
            }
            if (isset($_POST['organization'])) {
                $data['organization'] = trim($_POST['organization']);
            }
            if (isset($_POST['business_type'])) {
                $data['business_type'] = trim($_POST['business_type']);
            }
            if (isset($_POST['phone_number'])) {
                $data['phone_number'] = trim($_POST['phone_number']);
            }
            if (isset($_POST['product_category'])) {
                $data['product_category'] = trim($_POST['product_category']);
            }

            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter a name';
            }

            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter an email';
            } else {
                // Check if email exists in any user table
                $userType = trim($_POST['userType'] ?? 'member'); // Default to member if not set
                $existingUser = $this->userModel->findUserByEmail($data['email'], $userType);
                if ($existingUser) {

                    $data['email_err'] = 'Email is already registered';
                }
            }

            // Validate password
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter a password';
            } elseif (empty($data['confirm_password'])) {
                $data['confirm_password_err'] = 'Please confirm the password';
            } else {
                if ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }

            // Check if all errors are empty
            if (empty($data['name_err']) && empty($data['email_err']) 
                && empty($data['password_err']) && empty($data['confirm_password_err'])
                && empty($data['userType_err'])) {

                
                // Hash the password
                $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);

                // Register user
                if ($this->userModel->register($data, $data['userType'])) {

                    die('register_success,You are now registered and can log in');
                } else {
                    die('register_error,Something went wrong');
                }
            } else {
                $this->view('users/v_register', $data);
            }
        } else {
            // Initial form load
            $data = [
                'name' => '',
                'email' => '',
                'password' => '',
                'confirm_password' => '',
                'userType' => 'member', // Default to member
                'name_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'userType_err' => ''
            ];


            $this->view('users/v_register', $data);
        }
    }

    public function login() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'email_err' => '',
                'password_err' => ''
            ];
            
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            }
            
            if (empty($data['password'])) {
                $data['password_err'] = 'Please enter password';
            }
            
            // Check if user exists
            $loggedInUser = $this->userModel->loginAcrossAllTables($data['email'], $data['password']);
            
            if ($loggedInUser) {
                // Create session variables based on user type
                switch($_SESSION['user_type']) {
                    case 'organizer':
                        // Set organizer specific session data
                        $_SESSION['user_id'] = $loggedInUser->organiser_id;
                        $_SESSION['user_type'] = 'organizer';
                        $_SESSION['username'] = $loggedInUser->username;
                        $_SESSION['email'] = $loggedInUser->email;
                        redirect('eventmanagement');
                        break;
                        
                    case 'member':
                        $_SESSION['user_id'] = $loggedInUser->member_id;
                        $_SESSION['user_type'] = 'member';
                        $_SESSION['username'] = $loggedInUser->Username;
                        $_SESSION['email'] = $loggedInUser->email;
                        redirect('/Member_Homepage/Homepage');
                        break;
                        
                    case 'artist':
                        $_SESSION['user_id'] = $loggedInUser->Artist_id;
                        $_SESSION['user_type'] = 'artist';
                        $_SESSION['username'] = $loggedInUser->username;
                        $_SESSION['email'] = $loggedInUser->email;
                        redirect('Artist_Home/artist_home');
                        break;
                        
                    case 'supplier':
                        // Changed from supplier_id to user_id to match the database column
                        $_SESSION['user_id'] = $loggedInUser->user_id; 
                        $_SESSION['user_type'] = 'supplier';
                        $_SESSION['username'] = $loggedInUser->Username;
                        $_SESSION['email'] = $loggedInUser->email;
                        redirect('VendorMerchandise');
                        break;
                    case 'merchandise_vendor':
                        $_SESSION['user_id'] = $loggedInUser->user_id;
                        $_SESSION['user_type'] = 'merchandise_vendor';
                        $_SESSION['username'] = $loggedInUser->Username;
                        $_SESSION['email'] = $loggedInUser->email;
                        redirect('merchandise_vendor/dashboard');
                        break;
            }
            } else {
                $data['password_err'] = 'Invalid credentials';
                $this->view('users/v_login', $data);
            }
        } else {
            $data = [
                'email' => '',
                'password' => '',
                'email_err' => '',
                'password_err' => ''
            ];
            
            $this->view('users/v_login', $data);
        }
    }
    
    


            

    public function dashboard() {
        // Check if user is logged in and is a member
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'member') {
            if (isset($_SESSION['user_type'])) {
                // Redirect to appropriate dashboard based on user type
                switch($_SESSION['user_type']) {
                    case 'artist':
                        redirect('/artist/dashboard');
                        break;
                    case 'organizer':
                        redirect('/organizer/dashboard');
                        break;
                    case 'supplier':
                        redirect('/VendorMerchandise');
                        break;
                    case 'supplier':
                        redirect('merchandise_vendor/dashboard');
                        break;
                }
            } else {
                redirect('/users/login');
            }
            exit;
        }
    
        $userId = $_SESSION['user_id'];
        $userType = $_SESSION['user_type'];
    
        $data = $this->userModel->getDashboardData($userId, $userType);
        $data['user_type'] = $userType;
        $data['username'] = $_SESSION['username'];
    
        $this->view('users/v_member_dashboard', $data);
    }

    public function logout() {
        $_SESSION = array();
        session_destroy();
        redirect('/users/login');
        exit;
    }

    public function settings() {
        if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_type'])) {
            redirect('/users/login');
            exit;
        }

        $userId = $_SESSION['user_id'];
        $userType = $_SESSION['user_type'];

        $userData = $this->userModel->getUserData($userId, $userType);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'password' => trim($_POST['password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'profile_pic' => $_FILES['profile_pic']['name'] ?? $userData->profile_pic,
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'profile_pic_err' => ''
            ];

            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter a username.';
            }

            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter an email.';
            } else {
                $existingUser = $this->userModel->findUserByEmail($data['email'], $userType);
                if ($existingUser && $data['email'] != $userData->email) {

                    $data['email_err'] = 'Email is already registered.';
                }
            }

            if (!empty($data['password'])) {
                if (empty($data['confirm_password'])) {
                    $data['confirm_password_err'] = 'Please confirm the password.';
                } elseif ($data['password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match.';
                }
            }

            if (!empty($_FILES['profile_pic']['name'])) {
                $target_dir = APPROOT . '/public/uploads/';
                $target_file = $target_dir . basename($_FILES['profile_pic']['name']);
                if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
                    $data['profile_pic'] = basename($_FILES['profile_pic']['name']);
                } else {
                    $data['profile_pic_err'] = 'There was an error uploading your profile picture.';
                }
            }

            if (empty($data['username_err']) && empty($data['email_err']) && 
                empty($data['password_err']) && empty($data['confirm_password_err']) && 
                empty($data['profile_pic_err'])) {

                if (!empty($data['password'])) {
                    $data['password'] = password_hash($data['password'], PASSWORD_DEFAULT);
                }

                $updateData = [
                    'username' => $data['username'],
                    'email' => $data['email']
                ];

                if (!empty($data['password'])) {
                    $updateData['Password'] = $data['password'];
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
                $this->view('users/v_settings', $data);
            }
        } else {
            $data = [
                'username' => $userData->Username ?? $userData->username,
                'email' => $userData->email,
                'profile_pic' => $userData->profile_pic,
                'username_err' => '',
                'email_err' => '',
                'password_err' => '',
                'confirm_password_err' => '',
                'profile_pic_err' => ''
            ];

            $this->view('users/v_settings', $data);
        }
    }
}