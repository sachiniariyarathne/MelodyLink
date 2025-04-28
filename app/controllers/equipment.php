<?php
class Equipment extends Controller {
    public function __construct() {
        $this->equipmentModel = $this->model('m_equipment');
    }

    public function index() {
        // Get all equipment
        $data = [
            'equipment' => $this->equipmentModel->getAllEquipment()
        ];

        $this->view('equipment/v_equipment_index', $data);
    }

    public function add() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'category' => trim($_POST['category']),
                'image_url' => trim($_POST['image_url']),
                'rating' => trim($_POST['rating']),
                'reviews' => trim($_POST['reviews']),
                'status' => trim($_POST['status']),
                'name_err' => '',
                'description_err' => '',
                'price_err' => '',
                'category_err' => '',
                'image_url_err' => '',
                'rating_err' => '',
                'reviews_err' => '',
                'status_err' => ''
            ];

            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter equipment name';
            }

            // Validate description
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter equipment description';
            }

            // Validate price
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter equipment price';
            } elseif (!is_numeric($data['price']) || $data['price'] <= 0) {
                $data['price_err'] = 'Please enter a valid price';
            }

            // Validate category
            if (empty($data['category'])) {
                $data['category_err'] = 'Please select a category';
            }

            // Check if all errors are empty
            if (empty($data['name_err']) && empty($data['description_err']) && 
                empty($data['price_err']) && empty($data['category_err'])) {
                
                // Add equipment
                if ($this->equipmentModel->addEquipment($data)) {
                    $_SESSION['equipment_message'] = 'Equipment added successfully';
                    redirect('/equipment');
                } else {
                    $data['submit_err'] = 'Something went wrong';
                    $this->view('equipment/v_equipment_add', $data);
                }
                
            } else {
                // Load view with errors
                $this->view('equipment/v_equipment_add', $data);
            }
        } else {
            // Initial form load
            $data = [
                'name' => '',
                'description' => '',
                'price' => '',
                'category' => '',
                'image_url' => '',
                'rating' => '4.5',
                'reviews' => '0',
                'status' => 'available',
                'name_err' => '',
                'description_err' => '',
                'price_err' => '',
                'category_err' => '',
                'image_url_err' => '',
                'rating_err' => '',
                'reviews_err' => '',
                'status_err' => ''
            ];

            $this->view('equipment/v_equipment_add', $data);
        }
    }

    public function edit($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $_POST = filter_input_array(INPUT_POST, FILTER_SANITIZE_STRING);

            $data = [
                'id' => $id,
                'name' => trim($_POST['name']),
                'description' => trim($_POST['description']),
                'price' => trim($_POST['price']),
                'category' => trim($_POST['category']),
                'image_url' => trim($_POST['image_url']),
                'rating' => trim($_POST['rating']),
                'reviews' => trim($_POST['reviews']),
                'status' => trim($_POST['status']),
                'name_err' => '',
                'description_err' => '',
                'price_err' => '',
                'category_err' => '',
                'image_url_err' => '',
                'rating_err' => '',
                'reviews_err' => '',
                'status_err' => ''
            ];

            // Validate name
            if (empty($data['name'])) {
                $data['name_err'] = 'Please enter equipment name';
            }

            // Validate description
            if (empty($data['description'])) {
                $data['description_err'] = 'Please enter equipment description';
            }

            // Validate price
            if (empty($data['price'])) {
                $data['price_err'] = 'Please enter equipment price';
            } elseif (!is_numeric($data['price']) || $data['price'] <= 0) {
                $data['price_err'] = 'Please enter a valid price';
            }

            // Validate category
            if (empty($data['category'])) {
                $data['category_err'] = 'Please select a category';
            }

            // Check if all errors are empty
            if (empty($data['name_err']) && empty($data['description_err']) && 
                empty($data['price_err']) && empty($data['category_err'])) {
                
                // Update equipment
                if ($this->equipmentModel->updateEquipment($data)) {
                    $_SESSION['equipment_message'] = 'Equipment updated successfully';
                    redirect('/equipment');
                } else {
                    $data['submit_err'] = 'Something went wrong';
                    $this->view('equipment/v_equipment_edit', $data);
                }
                
            } else {
                // Load view with errors
                $this->view('equipment/v_equipment_edit', $data);
            }
        } else {
            // Get equipment by ID
            $equipment = $this->equipmentModel->getEquipmentById($id);

            // Check if equipment exists
            if (!$equipment) {
                redirect('/equipment');
            }

            $data = [
                'id' => $equipment->id,
                'name' => $equipment->name,
                'description' => $equipment->description,
                'price' => $equipment->price,
                'category' => $equipment->category,
                'image_url' => $equipment->image_url,
                'rating' => $equipment->rating,
                'reviews' => $equipment->reviews,
                'status' => $equipment->status,
                'name_err' => '',
                'description_err' => '',
                'price_err' => '',
                'category_err' => '',
                'image_url_err' => '',
                'rating_err' => '',
                'reviews_err' => '',
                'status_err' => ''
            ];

            $this->view('equipment/v_equipment_edit', $data);
        }
    }

    public function delete($id) {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            // Delete equipment
            if ($this->equipmentModel->deleteEquipment($id)) {
                $_SESSION['equipment_message'] = 'Equipment deleted successfully';
                redirect('/equipment');
            } else {
                $_SESSION['equipment_message'] = 'Something went wrong';
                redirect('/equipment');
            }
        } else {
            redirect('/equipment');
        }
    }
    
}
