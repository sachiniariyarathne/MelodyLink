<?php
class Messages extends Controller {
    public function __construct() {
        $this->messageModel = $this->model('m_messages');
    }

    public function index() {
        $messages = $this->messageModel->getPendingMessages();
        $data = ['messages' => $messages];
        $this->view('messages/v_messages', $data);
    }

    public function respond() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $messageId = $_POST['message_id'];
            $response = $_POST['response'];
            if ($this->messageModel->updateMessageStatus($messageId, $response)) {
                $_SESSION['message_success'] = "Request " . ($response == 'accepted' ? "accepted" : "rejected") . " successfully";
            } else {
                $_SESSION['message_error'] = "Something went wrong";
            }
            redirect('messages');
        } else {
            redirect('messages');
        }
    }
}
