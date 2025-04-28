<?php

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once APPROOT . '\libraries\PHPMailer-master\src\Exception.php';
require_once APPROOT . '\libraries\PHPMailer-master\src\PHPMailer.php';
require_once APPROOT . '\libraries\PHPMailer-master\src\SMTP.php';

class contact_us extends Controller {
    private $contactModel;

    public function __construct() {
        $this->contactModel = $this->model('m_Contact');
    }

    public function index() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize input
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'name' => trim($_POST['name']),
                'email' => trim($_POST['email']),
                'subject' => trim($_POST['subject']),
                'message' => trim($_POST['message']),
                'name_err' => '',
                'email_err' => '',
                'subject_err' => '',
                'message_err' => ''
            ];

            // Validate
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter your name';
            }

            if (empty($data['email'])) {
                $data['email_err'] = 'Please enter your email';
            } elseif (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
                $data['email_err'] = 'Invalid email format';
            }

            if (empty($data['subject'])) {
                $data['subject_err'] = 'Please enter a subject';
            }

            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter your message';
            }

            // If no errors
            if (empty($data['name_err']) && 
                empty($data['email_err']) && 
                empty($data['subject_err']) && 
                empty($data['message_err'])) {
                
                if ($this->contactModel->saveContact($data)) {
                    // Send email notification (optional)
                    $this->sendEmailNotification($data);
                    
                    flash('contact_message', 'Message sent successfully!');
                    redirect('contact');
                } else {
                    die('Something went wrong');
                }
            } else {
                // Load view with errors
                $this->view('users/contact_us', $data);
            }

        } else {
            // Initial load
            $data = [
                'name' => '',
                'email' => '',
                'subject' => '',
                'message' => '',
                'name_err' => '',
                'email_err' => '',
                'subject_err' => '',
                'message_err' => ''
            ];

            $this->view('users/contact_us', $data);
        }
    }

    private function sendEmailNotification($data) {
        // Requires PHPMailer installation
        $mail = new PHPMailer(true);
        
        try {
        // Server settings
        $mail->SMTPDebug = 0;
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'IndipaPerera5@gmail.com';
        $mail->Password = 'mxpt ybvk rgcb sbtv'; // Your app password
        $mail->SMTPSecure = 'tls';
        $mail->Port = 587;
        $mail->setFrom('MelodyLink.noreply@gmail.com', 'MelodyLink');

            // Recipients
            $mail->setFrom('stariyarathne814@gmail.com', 'Contact Form');
            $mail->addAddress('admin@example.com'); 

            // Content
            $mail->isHTML(true);
            $mail->Subject = 'New Contact Form Submission: ' . $data['subject'];
            $mail->Body    = sprintf(
                "Name: %s<br>Email: %s<br><br>Message:<br>%s",
                $data['name'],
                $data['email'],
                nl2br($data['message'])
            );

            $mail->send();
        } catch (Exception $e) {
            // Log email errors if needed
            error_log("Message could not be sent. Mailer Error: {$mail->ErrorInfo}");
        }
    }
}
?>
