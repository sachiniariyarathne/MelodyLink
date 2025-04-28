<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

class VendorMerchandise extends Controller {
    private $vendorMerchandiseModel;
    private $memberModel;
    
    public function __construct() {
        // Start session if not already started
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Check if supplier by looking directly at supplier table
        $this->forceCheckSupplier();
        
        // Now check if user is a supplier
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'supplier') {
            error_log('VendorMerchandise: User is not a supplier - ID: ' . ($_SESSION['user_id'] ?? 'none'));
            flash('access_denied', 'You must be a supplier to access this area', 'alert alert-danger');
            redirect('users/login');
            return;
        }
        
        $this->vendorMerchandiseModel = $this->model('m_VendorMerchandise');
        
        // Load member model to access member emails
        $this->memberModel = $this->model('m_Member');
        
        // Include PHPMailer dependencies
        require_once APPROOT . '\libraries\PHPMailer-master\src\Exception.php';
        require_once APPROOT . '\libraries\PHPMailer-master\src\PHPMailer.php';
        require_once APPROOT . '\libraries\PHPMailer-master\src\SMTP.php';
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
                $_SESSION['user_type'] = 'supplier'; // Changed from user_role to user_type
                error_log('VendorMerchandise: User found in supplier table, setting type to supplier');
            } else {
                error_log('VendorMerchandise: User NOT found in supplier table');
            }
        }
    }
    
    // Vendor dashboard to manage products
    public function index() {
        $supplierId = $_SESSION['user_id'];
        $merchandise = $this->vendorMerchandiseModel->getMerchandiseBySupplier($supplierId);
        
        $data = [
            'merchandise' => $merchandise,
            'isLoggedIn' => true
        ];
        
        $this->view('vendors/v_dashboard', $data);
    }
    
    // Show form to add new merchandise
    public function add() {
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Process form
            $data = [
                'name' => trim($_POST['name']),
                'price' => trim($_POST['price']),
                'description' => trim($_POST['description']),
                'user_id' => $_SESSION['user_id'], // Changed from supplier_id to user_id
                'name_err' => '',
                'price_err' => '',
                'description_err' => '',
                'image_err' => ''
            ];
            
            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter product name';
            }
            
            // Validate price
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter product price';
            } elseif (!is_numeric($data['price']) || $data['price'] <= 0) {
                $data['price_err'] = 'Please enter a valid price';
            }
            
            // Validate description
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter product description';
            }
            
            // Handle file upload
            $uploadDir = APPROOT . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;
            $imageName = '';
            
            // Make sure directory exists
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            if (!empty($_FILES['image']['name'])) {
                $imageName = time() . '_' . $_FILES['image']['name'];
                $uploadFile = $uploadDir . $imageName;
                
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES['image']['tmp_name']);
                if ($check === false) {
                    $data['image_err'] = 'File is not an image';
                }
                
                // Check file size (limit to 5MB)
                if ($_FILES['image']['size'] > 5000000) {
                    $data['image_err'] = 'Image file is too large (max 5MB)';
                }
                
                // Allow certain file formats
                $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $data['image_err'] = 'Only JPG, JPEG, PNG & GIF files are allowed';
                }
            } else {
                $data['image_err'] = 'Please select an image for the product';
            }
            
            // Make sure no errors
            if (empty($data['name_err']) && empty($data['price_err']) &&
                empty($data['description_err']) && empty($data['image_err'])) {
                
                // Upload image
                if (move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                    // Add product with image
                    $productData = [
                        'name' => $data['name'],
                        'price' => $data['price'],
                        'description' => $data['description'],
                        'image' => $imageName,
                        'user_id' => $data['user_id'] // Changed from supplier_id to user_id
                    ];
                    
                    if ($this->vendorMerchandiseModel->addMerchandise($productData)) {
                        // Successfully added merchandise, now notify members
                        $this->notifyMembersAboutNewMerchandise($productData);
                        
                        flash('merchandise_message', 'Product added successfully');
                        redirect('vendorMerchandise');
                    } else {
                        die('Something went wrong');
                    }
                } else {
                    $data['image_err'] = 'Error uploading image';
                    $this->view('vendors/v_add_merchandise', $data);
                }
            } else {
                // Load view with errors
                $this->view('vendors/v_add_merchandise', $data);
            }
        } else {
            $data = [
                'name' => '',
                'price' => '',
                'description' => '',
                'name_err' => '',
                'price_err' => '',
                'description_err' => '',
                'image_err' => '',
                'isLoggedIn' => true
            ];
            
            $this->view('vendors/v_add_merchandise', $data);
        }
    }
    
    // Get all active members' emails
    private function getAllMemberEmails() {
        return $this->memberModel->getAllMemberEmails();
    }
    
    // Send notification to all members about new merchandise
    private function notifyMembersAboutNewMerchandise($productData) {
        // Get all member emails
        $members = $this->getAllMemberEmails();
        
        if (empty($members)) {
            error_log('No members found to notify about new merchandise');
            return false;
        }
        
        try {
            // Create a new PHPMailer instance
            $mail = new PHPMailer(true);
            
            // Server settings - Add more debugging
            $mail->SMTPDebug = 2; // Change from 0 to 2 for detailed debug output
            $mail->Debugoutput = function($str, $level) {
                error_log("PHPMailer: $str");
            };
            $mail->isSMTP();
            $mail->Host = 'smtp.gmail.com';
            $mail->SMTPAuth = true;
            $mail->Username = 'IndipaPerera5@gmail.com';
            $mail->Password = 'mxpt ybvk rgcb sbtv'; // Your app password
            $mail->SMTPSecure = 'tls';
            $mail->Port = 587;
            $mail->setFrom('MelodyLink.noreply@gmail.com', 'MelodyLink');
            
            // Add a test email address to verify if emails are being sent
            // Replace with your actual email for testing
            $mail->addAddress('your-test-email@example.com'); 
            
            // Add BCC for all members 
            $validEmails = 0;
            foreach ($members as $member) {
                if (filter_var($member->email, FILTER_VALIDATE_EMAIL)) {
                    $mail->addBCC($member->email);
                    $validEmails++;
                } else {
                    error_log('Invalid email address skipped: ' . $member->email);
                }
            }
            
            error_log("Adding $validEmails valid email addresses as BCC recipients");
            
            // Email content
            $mail->isHTML(true);
            $mail->Subject = 'New Merchandise Available at MelodyLink!';
            
            // Product image URL
            $imageUrl = URLROOT . '/public/img/' . $productData['image'];
            
            // Format price with two decimal places
            $formattedPrice = number_format($productData['price'], 2);
            
            // Create an attractive, non-spammy email
            $emailBody = "
            <html>
            <head>
                <style>
                    body { font-family: Arial, sans-serif; margin: 0; padding: 0; }
                    .container { max-width: 600px; margin: 0 auto; background-color: #ffffff; padding: 20px; }
                    .header { background-color: #f8f9fa; padding: 20px; text-align: center; border-bottom: 2px solid #6c757d; }
                    .content { padding: 20px; }
                    .product { background-color: #f8f9fa; padding: 15px; border-radius: 5px; margin-top: 15px; }
                    .product-image { text-align: center; margin-bottom: 15px; }
                    .product-image img { max-width: 250px; border-radius: 5px; }
                    .product-details { text-align: center; }
                    .price { font-size: 18px; font-weight: bold; color: #28a745; }
                    .button { display: inline-block; background-color: #007bff; color: white; padding: 10px 20px; 
                              text-decoration: none; border-radius: 5px; margin-top: 15px; }
                    .footer { background-color: #f8f9fa; padding: 15px; text-align: center; font-size: 12px; color: #6c757d; margin-top: 20px; }
                    .unsubscribe { font-size: 11px; margin-top: 10px; }
                </style>
            </head>
            <body>
                <div class='container'>
                    <div class='header'>
                        <h2>New at MelodyLink!</h2>
                    </div>
                    <div class='content'>
                        <p>We're excited to announce a new item has been added to our collection!</p>
                        
                        <div class='product'>
                            <div class='product-image'>
                                <img src='$imageUrl' alt='{$productData['name']}'>
                            </div>
                            <div class='product-details'>
                                <h3>{$productData['name']}</h3>
                                <p>{$productData['description']}</p>
                                <p class='price'>$" . $formattedPrice . "</p>
                                <a href='" . URLROOT . "/merchandise' class='button'>Shop Now</a>
                            </div>
                        </div>
                        
                        <p style='margin-top: 20px;'>Thank you for being a valued MelodyLink member!</p>
                    </div>
                    <div class='footer'>
                        <p>&copy; " . date('Y') . " MelodyLink. All rights reserved.</p>
                        <p class='unsubscribe'>If you'd prefer not to receive these notifications, please update your preferences in your account settings.</p>
                    </div>
                </div>
            </body>
            </html>";
            
            $mail->Body = $emailBody;
            $mail->AltBody = "New merchandise available at MelodyLink: {$productData['name']} - \${$formattedPrice}. Shop now at " . URLROOT . "/merchandise";
            
            // Send email and capture result
            if ($mail->send()) {
                error_log('Notification emails sent to all members about new merchandise: ' . $productData['name']);
                return true;
            } else {
                error_log('Email sending failed: ' . $mail->ErrorInfo);
                return false;
            }
        } catch (Exception $e) {
            error_log('Error sending merchandise notification emails: ' . $e->getMessage());
            return false;
        }
    }
    
    // Show form to edit merchandise
    public function edit($id) {
        // Get merchandise by ID
        $merchandise = $this->vendorMerchandiseModel->getMerchandiseById($id);
        
        // Check if merchandise exists
        if (!$merchandise) {
            redirect('vendorMerchandise');
            return;
        }
        
        // Check if the supplier owns this product
        if ($merchandise->user_id != $_SESSION['user_id']) { // Changed from supplier_id to user_id
            redirect('vendorMerchandise');
            return;
        }
        
        // Check for POST
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            // Process form
            $data = [
                'merch_id' => $id,
                'name' => trim($_POST['name']),
                'price' => trim($_POST['price']),
                'description' => trim($_POST['description']),
                'current_image' => $merchandise->image,
                'image' => '',
                'name_err' => '',
                'price_err' => '',
                'description_err' => '',
                'image_err' => '',
            ];
            
            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter product name';
            }
            
            // Validate price
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter product price';
            } elseif (!is_numeric($data['price']) || $data['price'] <= 0) {
                $data['price_err'] = 'Please enter a valid price';
            }
            
            // Validate description
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter product description';
            }
            
            // Handle file upload if a new image is provided
            $uploadDir = APPROOT . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR;
            $newImage = false;
            
            // Make sure directory exists
            if (!file_exists($uploadDir)) {
                mkdir($uploadDir, 0777, true);
            }
            
            if (!empty($_FILES['image']['name'])) {
                $newImage = true;
                $imageName = time() . '_' . $_FILES['image']['name'];
                $uploadFile = $uploadDir . $imageName;
                
                // Check if image file is a actual image or fake image
                $check = getimagesize($_FILES['image']['tmp_name']);
                if ($check === false) {
                    $data['image_err'] = 'File is not an image';
                }
                
                // Check file size (limit to 5MB)
                if ($_FILES['image']['size'] > 5000000) {
                    $data['image_err'] = 'Image file is too large (max 5MB)';
                }
                
                // Allow certain file formats
                $imageFileType = strtolower(pathinfo($uploadFile, PATHINFO_EXTENSION));
                if ($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg" && $imageFileType != "gif") {
                    $data['image_err'] = 'Only JPG, JPEG, PNG & GIF files are allowed';
                }
                
                $data['image'] = $imageName;
            }
            
            // Make sure no errors
            if (empty($data['name_err']) && empty($data['price_err']) &&
                empty($data['description_err']) && empty($data['image_err'])) {
                
                // If new image is provided, upload it
                if ($newImage) {
                    if (!move_uploaded_file($_FILES['image']['tmp_name'], $uploadFile)) {
                        $data['image_err'] = 'Error uploading image';
                        $this->view('vendors/v_edit_merchandise', $data);
                        return;
                    }
                }
                
                // Prepare data for update
                $updateData = [
                    'id' => $data['merch_id'],
                    'name' => $data['name'],
                    'price' => $data['price'],
                    'description' => $data['description'],
                    'image' => $newImage ? $data['image'] : $data['current_image']
                ];
                
                // Update merchandise
                if ($this->vendorMerchandiseModel->updateMerchandise($updateData)) {
                    flash('merchandise_message', 'Product updated successfully');
                    redirect('vendorMerchandise');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('vendors/v_edit_merchandise', $data);
            }
        } else {
            $data = [
                'merch_id' => $merchandise->merch_id,
                'name' => $merchandise->Name,
                'price' => $merchandise->Price,
                'description' => $merchandise->Description,
                'image' => $merchandise->image,
                'name_err' => '',
                'price_err' => '',
                'description_err' => '',
                'image_err' => '',
                'isLoggedIn' => true
            ];
            
            $this->view('vendors/v_edit_merchandise', $data);
        }
    }
    
    // Delete merchandise
    public function delete($id) {
        // Check for POST request (to prevent direct URL access)
        if ($_SERVER['REQUEST_METHOD'] != 'POST') {
            redirect('vendorMerchandise');
            return;
        }
        
        // Get merchandise by ID
        $merchandise = $this->vendorMerchandiseModel->getMerchandiseById($id);
        
        // Check if merchandise exists and belongs to the logged-in supplier
        if (!$merchandise || $merchandise->user_id != $_SESSION['user_id']) { // Changed from supplier_id to user_id
            redirect('vendorMerchandise');
            return;
        }
        
        // Delete the merchandise
        if ($this->vendorMerchandiseModel->deleteMerchandise($id)) {
            // Delete the image file
            $imagePath = APPROOT . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'public' . DIRECTORY_SEPARATOR . 'img' . DIRECTORY_SEPARATOR . $merchandise->image;
            if (file_exists($imagePath)) {
                unlink($imagePath);
            }
            
            flash('merchandise_message', 'Product deleted successfully');
        } else {
            flash('merchandise_message', 'Failed to delete product', 'alert alert-danger');
        }
        
        redirect('vendorMerchandise');
    }
}
?>