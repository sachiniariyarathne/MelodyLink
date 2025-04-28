<?php
require_once APPROOT . '/helpers/flash_helper.php';

class Member_Profile extends Controller {
    private $userModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'member') {
            redirect('users/login');
        }
        $this->userModel = $this->model('m_member');
    }

    public function profile() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserData($userId);
        
        $data = [
            'member_id' => $user->member_id,
            'username' => $user->Username,
            'email' => $user->email,
            'phone_number' => $user->Phone_number,
            'address' => $user->Address,
            'profile_pic' => $user->profile_pic ?? 'default-avatar.png'
        ];
        
        $this->view('users/v_member_profile', $data);
    }

    public function update() {
        $userId = $_SESSION['user_id'];
        $user = $this->userModel->getUserData($userId);

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'member_id' => $userId,
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'phone_number' => trim($_POST['phone']),
                'address' => trim($_POST['address']),
                'profile_pic' => $user->profile_pic,
                'username_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'address_err' => '',
                'profile_pic_err' => ''
            ];

            // Validate username
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            }

            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Invalid email format';
            } else {
                $existingUser = $this->userModel->findMemberByEmail($data['email']);
                if ($existingUser && $existingUser->member_id != $userId) {
                    $data['email_err'] = 'Email already registered';
                }
            }

            // Handle file upload
            if (!empty($_FILES['profile_pic']['name'])) {
                $uploadResult = $this->handleFileUpload();
                if ($uploadResult['success']) {
                    $data['profile_pic'] = $uploadResult['filename'];
                } else {
                    $data['profile_pic_err'] = $uploadResult['error'];
                }
            }

            // Proceed if no errors
            if (empty($data['username_err']) && empty($data['email_err']) && 
                empty($data['phone_err']) && empty($data['address_err']) &&
                empty($data['profile_pic_err'])) {
                
                // Update profile data
                $updateData = [
                    'id' => $userId,
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'phone' => $data['phone_number'],
                    'address' => $data['address']
                ];

                if ($this->userModel->updateProfile($updateData)) {
                    // Update profile picture if changed
                    if ($data['profile_pic'] != $user->profile_pic) {
                        $this->userModel->updateProfilePic($userId, $data['profile_pic']);
                    }
                    
                    // Update session data
                    $_SESSION['username'] = $data['username'];
                    $_SESSION['profile_pic'] = $data['profile_pic'];
                    
                    // Set success message
                    flash('profile_message', 'Profile updated successfully', 'alert alert-success');
                    redirect('Member_Profile/profile');
                } else {
                    flash('profile_message', 'Failed to update profile', 'alert alert-danger');
                }
            } else {
                // Show errors
                $this->view('users/v_member_profile_update', $data);
            }
        } else {
            // Initial form load
            $data = [
                'member_id' => $user->member_id,
                'username' => $user->Username,
                'email' => $user->email,
                'phone_number' => $user->Phone_number,
                'address' => $user->Address,
                'profile_pic' => $user->profile_pic ?? 'default-avatar.png'
            ];
            $this->view('users/v_member_profile_update', $data);
        }
    }

    private function handleFileUpload() {
        $target_dir = APPROOT . '/public/uploads/';
        $filename = uniqid() . '_' . basename($_FILES['profile_pic']['name']);
        $target_file = $target_dir . $filename;
        $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
        $max_size = 2 * 1024 * 1024; // 2MB

        // Create directory if needed
        if (!is_dir($target_dir)) {
            mkdir($target_dir, 0777, true);
        }

        // Check if image file
        $check = getimagesize($_FILES['profile_pic']['tmp_name']);
        if ($check === false) {
            return ['success' => false, 'error' => 'File is not an image'];
        }

        // Check file size
        if ($_FILES['profile_pic']['size'] > $max_size) {
            return ['success' => false, 'error' => 'File too large (max 2MB)'];
        }

        // Allow certain file formats
        $allowed_types = ['jpg', 'jpeg', 'png', 'gif'];
        if (!in_array($imageFileType, $allowed_types)) {
            return ['success' => false, 'error' => 'Only JPG, JPEG, PNG & GIF allowed'];
        }

        // Upload file
        if (move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) {
            return ['success' => true, 'filename' => $filename];
        } else {
            return ['success' => false, 'error' => 'Error uploading file'];
        }
    }
}
?>

