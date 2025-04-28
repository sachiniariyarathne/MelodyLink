<?php
class EventEquipmentController extends Controller {
    private $equipmentModel;

    public function __construct() {
        // Start session if not already started
        if (session_status() === PHP_SESSION_NONE) {
            session_start();
        }

        // Load helpers
        require_once '../app/helpers/session_helper.php';

        // Check if user is logged in
        if (!isset($_SESSION['user_id'])) {
            redirect('users/login');
        }
        
        // Check if user is an organizer
        if (!isset($_SESSION['user_type']) || $_SESSION['user_type'] !== 'organizer') {
            redirect('events');
        }

        $this->equipmentModel = $this->model('EventEquipment');
    }

    public function index() {
        $data = [
            'equipment' => $this->equipmentModel->getAllEquipment(),
            'category' => 'all'
        ];

        $this->view('users/event_management/equipment/index', $data);
    }

    public function category($category) {
        $data = [
            'equipment' => $this->equipmentModel->getEquipmentByCategory($category),
            'category' => $category
        ];

        $this->view('users/event_management/equipment/index', $data);
    }

    public function view($view, $data = []) {
        if (is_numeric($view)) {
            // Handle equipment view case
            $equipment = $this->equipmentModel->getEquipmentById($view);
            
            if (!$equipment) {
                redirect('eventequipmentcontroller');
            }

            $data = [
                'equipment' => $equipment
            ];

            parent::view('users/event_management/equipment/view', $data);
        } else {
            // Handle normal view case
            parent::view($view, $data);
        }
    }

    public function request($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'equipment_id' => $id,
                'sender_id' => $_SESSION['user_id'],
                'message' => trim($_POST['message']),
                'request_date' => trim($_POST['request_date']),
                'message_err' => '',
                'request_date_err' => ''
            ];

            // Validate data
            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter your request message';
            }

            if (empty($data['request_date'])) {
                $data['request_date_err'] = 'Please select a date';
            }

            // Make sure no errors
            if (empty($data['message_err']) && empty($data['request_date_err'])) {
                if ($this->equipmentModel->createRentalRequest($data)) {
                    flash('equipment_message', 'Rental request sent successfully');
                    redirect('eventequipmentcontroller/myrequests');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/event_management/equipment/request', $data);
            }
        } else {
            $equipment = $this->equipmentModel->getEquipmentById($id);
            
            if (!$equipment) {
                redirect('eventequipmentcontroller');
            }

            $data = [
                'equipment' => $equipment,
                'message' => '',
                'request_date' => '',
                'message_err' => '',
                'request_date_err' => ''
            ];

            $this->view('users/event_management/equipment/request', $data);
        }
    }

    public function myrequests() {
        $data = [
            'requests' => $this->equipmentModel->getUserRequests($_SESSION['user_id'])
        ];

        $this->view('users/event_management/equipment/myrequests', $data);
    }

    public function editrequest($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Sanitize POST data
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'sender_id' => $_SESSION['user_id'],
                'message' => trim($_POST['message']),
                'request_date' => trim($_POST['request_date']),
                'message_err' => '',
                'request_date_err' => ''
            ];

            // Validate data
            if (empty($data['message'])) {
                $data['message_err'] = 'Please enter your request message';
            }

            if (empty($data['request_date'])) {
                $data['request_date_err'] = 'Please select a date';
            }

            // Make sure no errors
            if (empty($data['message_err']) && empty($data['request_date_err'])) {
                if ($this->equipmentModel->updateRequest($data)) {
                    flash('equipment_message', 'Request updated successfully');
                    redirect('eventequipmentcontroller/myrequests');
                } else {
                    die('Something went wrong');
                }
            } else {
                $this->view('users/event_management/equipment/editrequest', $data);
            }
        } else {
            $request = $this->equipmentModel->getRequestById($id);
            
            if (!$request || $request->sender_id != $_SESSION['user_id']) {
                redirect('eventequipmentcontroller/myrequests');
            }

            $data = [
                'id' => $id,
                'request' => $request,
                'message' => $request->message,
                'request_date' => $request->request_date,
                'message_err' => '',
                'request_date_err' => ''
            ];

            $this->view('users/event_management/equipment/editrequest', $data);
        }
    }

    public function deleterequest($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            if ($this->equipmentModel->deleteRequest($id, $_SESSION['user_id'])) {
                flash('equipment_message', 'Request deleted successfully');
            } else {
                flash('equipment_message', 'Something went wrong', 'alert alert-danger');
            }
        }
        redirect('eventequipmentcontroller/myrequests');
    }
} 