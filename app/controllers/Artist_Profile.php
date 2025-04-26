
<?php

require_once APPROOT . '/helpers/flash_helper.php';

class Artist_Profile extends Controller {
    private $artistModel;

    public function __construct() {
        if (!isset($_SESSION['user_id']) || $_SESSION['user_type'] !== 'artist') {
            redirect('users/login');
        }
        $this->artistModel = $this->model('m_artist_profile');
    }

    public function index() {
        $artist_id = $_SESSION['user_id'];
        $artist = $this->artistModel->getArtistProfile($artist_id);

        $data = [
            'artist' => $artist
        ];
        $this->view('users/v_artist_profile', $data);
    }
    
    public function artist_profile() {
        // This can be removed as index() handles the functionality
        $this->index();
    }

    public function upload_verification() {
        $artist_id = $_SESSION['user_id'];
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['verification_doc'])) {
            $target_dir = APPROOT . "/../public/uploads/verification_docs/";
            $filename = uniqid('doc_') . '_' . basename($_FILES["verification_doc"]["name"]);
            $target_file = $target_dir . $filename;
            if (move_uploaded_file($_FILES["verification_doc"]["tmp_name"], $target_file)) {
                $this->artistModel->updateVerificationDocument($artist_id, $filename);
                flash('profile_message', 'Document uploaded successfully. Verification is now pending.', 'success');
            } else {
                flash('profile_message', 'Failed to upload document.', 'error');
            }
        }
        redirect('Artist_Profile/index');
    }

    public function update() {
        $artist_id = $_SESSION['user_id'];
        $artist = $this->artistModel->getArtistProfile($artist_id);
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data = [
                'artist_id' => $artist_id,
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'phone_number' => trim($_POST['phone_number']),
                'address' => trim($_POST['address']),
                'profile_pic' => $_FILES['profile_pic']['name'] ?? $artist->profile_pic,
                'username_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'address_err' => '',
                'profile_pic_err' => ''
            ];
    
            // Validation...
            // (same as previous answers)
    
            // Profile picture upload
            if (!empty($_FILES['profile_pic']['name'])) {
                $target_dir = APPROOT . '/../public/uploads/artists/';
                if (!is_dir($target_dir)) mkdir($target_dir, 0777, true);
                $target_file = $target_dir . basename($_FILES['profile_pic']['name']);
                $imageFileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));
                $check = getimagesize($_FILES['profile_pic']['tmp_name']);
                if ($check === false) $data['profile_pic_err'] = 'File is not an image.';
                elseif ($_FILES['profile_pic']['size'] > 2 * 1024 * 1024) $data['profile_pic_err'] = 'File size must be less than 2MB.';
                elseif (!in_array($imageFileType, ['jpg', 'jpeg', 'png', 'gif'])) $data['profile_pic_err'] = 'Only JPG, JPEG, PNG & GIF files are allowed.';
                elseif (!move_uploaded_file($_FILES['profile_pic']['tmp_name'], $target_file)) $data['profile_pic_err'] = 'There was an error uploading your profile picture.';
                else $data['profile_pic'] = basename($_FILES['profile_pic']['name']);
            }
    
            if (
                empty($data['username_err']) && empty($data['email_err']) &&
                empty($data['phone_err']) && empty($data['address_err']) &&
                empty($data['profile_pic_err'])
            ) {
                if ($this->artistModel->updateArtistProfile($artist_id, $data)) {
                    if (!empty($_FILES['profile_pic']['name'])) {
                        $this->artistModel->updateArtistProfilePic($artist_id, $data['profile_pic']);
                    }
                    $_SESSION['username'] = $data['username'];
                    flash('profile_message', 'Your profile has been updated.', 'success');
                    redirect('Artist_Profile/update');
                } else {
                    flash('profile_message', 'Something went wrong.', 'error');
                    redirect('Artist_Profile/update');
                }
            } else {
                $this->view('users/v_artist_profile_update', $data);
            }
        } else {
            $data = [
                'artist_id' => $artist->Artist_id,
                'username' => $artist->Username,
                'email' => $artist->Email,
                'phone_number' => $artist->Phone_number,
                'address' => $artist->Address,
                'profile_pic' => $artist->profile_pic,
                'username_err' => '',
                'email_err' => '',
                'phone_err' => '',
                'address_err' => '',
                'profile_pic_err' => ''
            ];
            $this->view('users/v_artist_profile_update', $data);
        }
    }
    

    public function change_password() {
        $artist_id = $_SESSION['user_id'];
        $artist = $this->artistModel->getArtistProfile($artist_id);
    
        $data = [
            'new_password' => '',
            'confirm_password' => '',
            'new_password_err' => '',
            'confirm_password_err' => '',
            'success_msg' => ''
        ];
    
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            $data['new_password'] = $_POST['new_password'];
            $data['confirm_password'] = $_POST['confirm_password'];
    
            // Validate new password
            if (strlen($data['new_password']) < 6) {
                $data['new_password_err'] = 'New password must be at least 6 characters.';
            }
            if ($data['new_password'] !== $data['confirm_password']) {
                $data['confirm_password_err'] = 'Passwords do not match.';
            }
    
            if (empty($data['new_password_err']) && empty($data['confirm_password_err'])) {
                $hashedPassword = password_hash($data['new_password'], PASSWORD_DEFAULT);
                if ($this->artistModel->updateArtistPassword($artist_id, $hashedPassword)) {
                    $data['success_msg'] = 'Password updated successfully.';
                } else {
                    $data['new_password_err'] = 'Something went wrong. Please try again.';
                }
            }
        }
    
        // Reload profile info for display
        $artist = $this->artistModel->getArtistProfile($artist_id);
        $data = array_merge($data, [
            'artist_id' => $artist->Artist_id,
            'username' => $artist->Username,
            'email' => $artist->Email,
            'phone_number' => $artist->Phone_number,
            'address' => $artist->Address,
            'profile_pic' => $artist->profile_pic
        ]);
        $this->view('users/v_artist_profile_update', $data);
    }
    
    
}
?>
