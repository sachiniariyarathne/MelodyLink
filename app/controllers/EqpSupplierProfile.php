<?php
class EqpSupplierProfile extends Controller {
    public function __construct() {
        $this->profileModel = $this->model('m_eqpsupplier_profile');
    }

    public function index() {
        // Get supplier profile data
        $supplierId = 1; // This would normally come from the session
        $profile = $this->profileModel->getSupplierProfile($supplierId);
        
        $data = [
            'profile' => $profile
        ];

        $this->view('eqpsupplier/v_eqpsupplier_profile', $data);
    }
    
    public function uploadImage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process image upload
            $supplierId = 1; // This would normally come from the session
            
            // Check if file was uploaded without errors
            if(isset($_FILES["profile_image"]) && $_FILES["profile_image"]["error"] == 0) {
                $allowed = ["jpg" => "image/jpg", "jpeg" => "image/jpeg", "png" => "image/png"];
                $filename = $_FILES["profile_image"]["name"];
                $filetype = $_FILES["profile_image"]["type"];
                $filesize = $_FILES["profile_image"]["size"];
            
                // Verify file extension
                $ext = pathinfo($filename, PATHINFO_EXTENSION);
                if(!array_key_exists($ext, $allowed)) {
                    $_SESSION['profile_error'] = "Error: Please select a valid file format.";
                    redirect('eqpsupplierprofile');
                    exit;
                }
            
                // Verify file size - 5MB maximum
                $maxsize = 5 * 1024 * 1024;
                if($filesize > $maxsize) {
                    $_SESSION['profile_error'] = "Error: File size is larger than the allowed limit.";
                    redirect('eqpsupplierprofile');
                    exit;
                }
            
                // Verify MIME type of the file
                if(in_array($filetype, $allowed)) {
                    // Generate unique filename
                    $new_filename = uniqid() . '.' . $ext;
                    $upload_path = 'uploads/profiles/' . $new_filename;
                    
                    // Create directory if it doesn't exist
                    if (!file_exists('uploads/profiles/')) {
                        mkdir('uploads/profiles/', 0777, true);
                    }
                    
                    // Move the uploaded file
                    if(move_uploaded_file($_FILES["profile_image"]["tmp_name"], $upload_path)) {
                        // Update profile image in database
                        if($this->profileModel->updateProfileImage($supplierId, $new_filename)) {
                            $_SESSION['profile_success'] = "Profile image updated successfully.";
                        } else {
                            $_SESSION['profile_error'] = "Error: Failed to update profile image in database.";
                        }
                    } else {
                        $_SESSION['profile_error'] = "Error: Failed to upload file.";
                    }
                } else {
                    $_SESSION['profile_error'] = "Error: There was a problem with the upload. Please try again.";
                }
            } else {
                $_SESSION['profile_error'] = "Error: " . $_FILES["profile_image"]["error"];
            }
            
            redirect('eqpsupplierprofile');
        } else {
            redirect('eqpsupplierprofile');
        }
    }
    
    public function updateProfile() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Process form
            $supplierId = 1; // This would normally come from the session
            
            $data = [
                'company_name' => trim($_POST['company_name']),
                'owner_name' => trim($_POST['owner_name']),
                'email' => trim($_POST['email']),
                'phone' => trim($_POST['phone']),
                'address' => trim($_POST['address']),
                'bio' => trim($_POST['bio']),
                'company_name_err' => '',
                'owner_name_err' => '',
                'email_err' => '',
                'phone_err' => ''
            ];
            
            // Validate company name
            if(empty($data['company_name'])) {
                $data['company_name_err'] = 'Please enter company name';
            }
            
            // Validate owner name
            if(empty($data['owner_name'])) {
                $data['owner_name_err'] = 'Please enter owner name';
            }
            
            // Validate email
            if(empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } elseif(!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email';
            }
            
            // Validate phone
            if(empty($data['phone'])) {
                $data['phone_err'] = 'Please enter phone number';
            }
            
            // Make sure no errors
            if(empty($data['company_name_err']) && empty($data['owner_name_err']) && 
               empty($data['email_err']) && empty($data['phone_err'])) {
                
                // Update profile
                if($this->profileModel->updateProfile($supplierId, $data)) {
                    $_SESSION['profile_success'] = 'Profile updated successfully';
                } else {
                    $_SESSION['profile_error'] = 'Something went wrong';
                }
                
                redirect('eqpsupplierprofile');
            } else {
                // Return with errors
                $profile = $this->profileModel->getSupplierProfile($supplierId);
                $data['profile'] = $profile;
                
                $this->view('eqpsupplier/v_eqpsupplier_profile', $data);
            }
        } else {
            redirect('eqpsupplierprofile');
        }
    }
}
