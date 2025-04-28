<?php
class SupplierProfile extends Controller {
    private $userModel;
    private $supplierModel;
    
    public function __construct() {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if user is logged in and is a supplier
        if (!isset($_SESSION['user_id'])) {
            flash('access_denied', 'Please log in to access this page', 'alert alert-danger');
            redirect('users/login');
            return;
        }
        
        // Force check if user is supplier (similar to VendorMerchandise)
        $this->forceCheckSupplier();
        
        // Now check if user is a supplier
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'supplier') {
            flash('access_denied', 'You must be a supplier to access this area', 'alert alert-danger');
            redirect('users/login');
            return;
        }
        
        // Load models
        $this->userModel = $this->model('m_supplier_profile');
        $this->supplierModel = $this->model('m_Supplier');
    }
    
    // This function bypasses the normal role check by directly checking supplier table
    // and setting the role appropriately
    private function forceCheckSupplier() {
        if (isset($_SESSION['user_id'])) {
            $db = new Database();
            $db->query("SELECT * FROM supplier WHERE user_id = :user_id");
            $db->bind(':user_id', $_SESSION['user_id']);
            $result = $db->single();
            
            if ($result) {
                // User exists in supplier table, force set role
                $_SESSION['user_type'] = 'supplier';
            }
        }
    }
    
    // Display supplier profile
    public function index() {
        $userId = $_SESSION['user_id'];
        
        // Get user basic info
        $userInfo = $this->userModel->getUserById($userId);
        
        // Get supplier specific info
        $supplierInfo = $this->supplierModel->getSupplierByUserId($userId);
        
        // Combine the data
        $data = [
            'userInfo' => $userInfo,
            'supplierInfo' => $supplierInfo,
            'isLoggedIn' => true
        ];
        
        $this->view('vendors/v_profile', $data);
    }
    
    // Edit supplier profile
    public function edit() {
        $userId = $_SESSION['user_id'];
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Initialize data with POST values
            $data = [
                'username' => trim($_POST['username']),
                'email' => trim($_POST['email']),
                'phone_number' => trim($_POST['phone_number']),
                'address' => trim($_POST['address']),
                'company_name' => trim($_POST['company_name']),
                'business_type' => trim($_POST['business_type']),
                'current_password' => trim($_POST['current_password']),
                'new_password' => trim($_POST['new_password']),
                'confirm_password' => trim($_POST['confirm_password']),
                'username_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
                'address_err' => '',
                'company_name_err' => '',
                'business_type_err' => '',
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_password_err' => '',
                'isLoggedIn' => true
            ];
            
            // Validate username
            if (empty($data['username'])) {
                $data['username_err'] = 'Please enter username';
            }
            
            // Validate email
            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Please enter a valid email';
            }
            
            // Validate phone number
            if (empty($data['phone_number'])) {
                $data['phone_number_err'] = 'Please enter phone number';
            }
            
            // Validate address
            if (empty($data['address'])) {
                $data['address_err'] = 'Please enter address';
            }
            
            // Validate company name
            if (empty($data['company_name'])) {
                $data['company_name_err'] = 'Please enter company/shop name';
            }
            
            // Validate business type
            if (empty($data['business_type'])) {
                $data['business_type_err'] = 'Please enter business type';
            }
            
            // Only validate password if changing password
            if (!empty($data['new_password'])) {
                // Validate current password
                if (empty($data['current_password'])) {
                    $data['current_password_err'] = 'Please enter current password';
                } else {
                    // Check if current password is correct
                    $user = $this->userModel->getUserById($userId);
                    if (!password_verify($data['current_password'], $user->Password)) {
                        $data['current_password_err'] = 'Current password is incorrect';
                    }
                }
                
                // Validate new password
                if (strlen($data['new_password']) < 6) {
                    $data['new_password_err'] = 'Password must be at least 6 characters';
                }
                
                // Validate confirm password
                if ($data['new_password'] != $data['confirm_password']) {
                    $data['confirm_password_err'] = 'Passwords do not match';
                }
            }
            
            // Check for errors before updating
            if (empty($data['username_err']) && empty($data['email_err']) && 
                empty($data['phone_number_err']) && empty($data['address_err']) && 
                empty($data['company_name_err']) && empty($data['business_type_err']) && 
                empty($data['current_password_err']) && empty($data['new_password_err']) && 
                empty($data['confirm_password_err'])) {
                    
                // Update user information
                $userUpdate = [
                    'id' => $userId,
                    'username' => $data['username'],
                    'email' => $data['email'],
                    'phone_number' => $data['phone_number'],
                    'address' => $data['address']
                ];
                
                // Add password if being changed
                if (!empty($data['new_password'])) {
                    $userUpdate['password'] = password_hash($data['new_password'], PASSWORD_DEFAULT);
                }
                
                // Update user basic info
                $this->userModel->updateUser($userUpdate);
                
                // Update supplier specific info
                $supplierUpdate = [
                    'user_id' => $userId,
                    'company_name' => $data['company_name'],
                    'business_type' => $data['business_type']
                ];
                
                $this->supplierModel->updateSupplier($supplierUpdate);
                
                flash('profile_message', 'Profile updated successfully');
                redirect('supplierProfile');
            } else {
                // Load view with errors
                $this->view('vendors/v_edit_profile', $data);
            }
        } else {
            // Get existing user data
            $userInfo = $this->userModel->getUserById($userId);
            $supplierInfo = $this->supplierModel->getSupplierByUserId($userId);
            
            // Set data for the form
            $data = [
                'username' => $userInfo->Username,
                'email' => $userInfo->email,
                'phone_number' => $userInfo->Phone_number,
                'address' => $userInfo->Address,
                'company_name' => $supplierInfo->company_name ?? '',
                'business_type' => $userInfo->BusinessType,
                'current_password' => '',
                'new_password' => '',
                'confirm_password' => '',
                'username_err' => '',
                'email_err' => '',
                'phone_number_err' => '',
                'address_err' => '',
                'company_name_err' => '',
                'business_type_err' => '',
                'current_password_err' => '',
                'new_password_err' => '',
                'confirm_password_err' => '',
                'isLoggedIn' => true
            ];
            
            $this->view('vendors/v_edit_profile', $data);
        }
    }
}
?>