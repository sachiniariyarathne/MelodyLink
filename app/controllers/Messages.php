<?php
class Messages extends Controller {
    public function __construct() {
        $this->messageModel = $this->model('m_message');
        $this->equipmentModel = $this->model('m_equipment');
        
        // Check if user is logged in
      /*  if(!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }*/
    }

    public function index() {
        // Get all messages/requests
        $messages = $this->messageModel->getAllMessages();
        
        $data = [
            'messages' => $messages
        ];

        $this->view('messages/v_messages_index', $data);
    }
    
    public function send() {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $data = [
                'equipment_id' => trim($_POST['equipment_id']),
                'message' => trim($_POST['message']),
                'sender_id' => $_SESSION['user_id'],
                'request_date' => date('Y-m-d H:i:s'),
                'status' => 'pending',
                'equipment_id_err' => '',
                'message_err' => ''
            ];
            
            // Validate equipment
            if(empty($data['equipment_id'])) {
                $data['equipment_id_err'] = 'Please select equipment';
            }
            
            // Validate message
            if(empty($data['message'])) {
                $data['message_err'] = 'Please enter message details';
            }
            
            // Make sure no errors
            if(empty($data['equipment_id_err']) && empty($data['message_err'])) {
                // Validated
                if($this->messageModel->addMessage($data)) {
                    $_SESSION['message_success'] = 'Your request has been sent';
                    redirect('equipment');
                } else {
                    $_SESSION['message_error'] = 'Something went wrong';
                    $this->view('messages/v_messages_send', $data);
                }
            } else {
                // Load view with errors
                $this->view('messages/v_messages_send', $data);
            }
        } else {
            // Get available equipment
            $equipment = $this->equipmentModel->getAvailableEquipment();
            
            $data = [
                'equipment' => $equipment,
                'equipment_id' => '',
                'message' => '',
                'equipment_id_err' => '',
                'message_err' => ''
            ];
            
            $this->view('messages/v_messages_send', $data);
        }
    }
    
    public function respond($id) {
        if($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);
            
            $response = $_POST['response'];
            
            if($response == 'accept') {
                // Update message status to accepted
                if($this->messageModel->updateMessageStatus($id, 'accepted')) {
                    // Update equipment status to booked
                    $message = $this->messageModel->getMessageById($id);
                    $this->equipmentModel->updateEquipmentStatus($message->equipment_id, 'booked');
                    
                    $_SESSION['message_success'] = 'Request accepted successfully';
                    redirect('messages');
                } else {
                    $_SESSION['message_error'] = 'Something went wrong';
                    redirect('messages');
                }
            } else if($response == 'reject') {
                // Update message status to rejected
                if($this->messageModel->updateMessageStatus($id, 'rejected')) {
                    $_SESSION['message_success'] = 'Request rejected successfully';
                    redirect('messages');
                } else {
                    $_SESSION['message_error'] = 'Something went wrong';
                    redirect('messages');
                }
            }
        } else {
            redirect('messages');
        }
    }
}
