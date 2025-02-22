<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require_once APPROOT . '\libraries\PHPMailer-master\src\Exception.php';
require_once APPROOT . '\libraries\PHPMailer-master\src\PHPMailer.php';
require_once APPROOT . '\libraries\PHPMailer-master\src\SMTP.php';

class PasswordReset extends Controller {
    private $passwordResetModel;
    private $mail;

    public function __construct() {
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }

        $this->passwordResetModel = $this->model('m_password_reset');
        
        $this->mail = new PHPMailer(true);
        $this->setupMailer();
    }

    private function setupMailer() {
        try {
            $this->mail->SMTPDebug = 0;
            $this->mail->isSMTP();
            $this->mail->Host = 'smtp.gmail.com';
            $this->mail->SMTPAuth = true;
            $this->mail->Username = 'IndipaPerera5@gmail.com';
            $this->mail->Password = 'mxpt ybvk rgcb sbtv';
            $this->mail->SMTPSecure = 'tls';
            $this->mail->Port = 587;
            $this->mail->setFrom('MelodyLink.noreply@gmail.com', 'MelodyLink');
        } catch (Exception $e) {
            error_log("PHPMailer Error: " . $e->getMessage());
            die("Mailer configuration failed: " . $e->getMessage());
        }
    }

    public function forgot() {
        $data = [
            'email' => '',
            'email_err' => ''
        ];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $email = filter_input(INPUT_POST, 'email', FILTER_SANITIZE_EMAIL);

            $data['email'] = $email;
            $data['email_err'] = '';

            if (empty($email)) {
                $data['email_err'] = 'Please enter an email address';
                $this->view('users/v_forgot', $data);
                return;
            }

            $user = $this->passwordResetModel->findUserByEmail($email);

            if ($user) {
                $token = bin2hex(random_bytes(32));

                if ($this->passwordResetModel->storeResetToken($user->member_id ?? $user->user_id, $user->user_type, $token)) {
                    $resetLink = URLROOT . "/passwordreset/reset/{$token}";
                    
                    try {
                        $this->mail->addAddress($email);
                        $this->mail->isHTML(true);
                        $this->mail->Subject = 'Password Reset Request';
                        $this->mail->Body = "
                            <h2>Password Reset</h2>
                            <p>You have requested to reset your password. Click the link below to reset:</p>
                            <a href='{$resetLink}'>Reset Password</a>
                            <p>If you did not request this, please ignore this email.</p>
                        ";

                        $this->mail->send();

                        flash('reset_email', 'A password reset link has been sent to your email.');
                        redirect('passwordreset/sent');
                    } catch (Exception $e) {
                        $data['email_err'] = 'Unable to send reset email. Please try again.';
                        $this->view('users/v_forgot', $data);
                    }
                } else {
                    $data['email_err'] = 'Something went wrong. Please try again.';
                    $this->view('users/v_forgot', $data);
                }
            } else {
                $data['email_err'] = 'No account found with this email address';
                $this->view('users/v_forgot', $data);
            }
        } else {
            $this->view('users/v_forgot', $data);
        }
    }

    public function sent() {
        $this->view('users/v_reset_sent');
    }

    public function reset($token = '') {
        // Validate token with detailed logging
        error_log("Attempting to validate token: " . $token);

        $user = $this->passwordResetModel->validateResetToken($token);

        if (!$user) {
            error_log("Invalid or expired token: " . $token);
            flash('reset_error', 'Invalid or expired reset token.');
            redirect('passwordreset/forgot');
            return;
        }

        error_log("User found for token. User details: " . print_r($user, true));

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $password = trim($_POST['password']);
            $confirm_password = trim($_POST['confirm_password']);

            $data = [
                'token' => $token,
                'password' => $password,
                'confirm_password' => $confirm_password,
                'password_err' => '',
                'confirm_password_err' => ''
            ];

            if (empty($password)) {
                $data['password_err'] = 'Please enter a new password';
            } elseif (strlen($password) < 6) {
                $data['password_err'] = 'Password must be at least 6 characters';
            }

            if (empty($confirm_password)) {
                $data['confirm_password_err'] = 'Please confirm your password';
            } elseif ($password != $confirm_password) {
                $data['confirm_password_err'] = 'Passwords do not match';
            }

            if (empty($data['password_err']) && empty($data['confirm_password_err'])) {
                // Ensure we use the correct user ID
                $userId = $user->id ?? $user->member_id ?? $user->user_id;
                
                if ($this->passwordResetModel->resetPassword($userId, $user->user_type, $password)) {
                    flash('login_message', 'Your password has been reset. Please log in.');
                    redirect('users/login');
                } else {
                    flash('reset_error', 'Something went wrong. Please try again.');
                    $this->view('users/v_reset', $data);
                }
            } else {
                $this->view('users/v_reset', $data);
            }
        } else {
            $data = [
                'token' => $token,
                'password' => '',
                'confirm_password' => '',
                'password_err' => '',
                'confirm_password_err' => ''
            ];
            $this->view('users/v_reset', $data);
        }
    }
}
?>