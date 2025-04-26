<?php
require_once '../app/models/m_equipment.php';

class Equipment {
    private $equipmentModel;
    private $db;

    public function __construct($db) {
        $this->equipmentModel = new M_Equipment($db);
        $this->db = $db; // Use the database connection passed in
    }

    // Main view for equipment listing
    public function index() {
        $equipment = $this->equipmentModel->getAllEquipment();
        require_once '../app/views/v_equipment.php';
    }

    // Handle adding new equipment
    public function add() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_equipment'])) {
            $data = [
                'name' => $_POST['name'],
                'description' => $_POST['description'],
                'price' => $_POST['price'],
                'category' => $_POST['category'],
                'image_url' => $_POST['image_url'],
                'rating' => $_POST['rating'],
                'reviews' => $_POST['reviews'],
                'status' => $_POST['status']
            ];
            
            $this->equipmentModel->addEquipment($data);
            header("Location: /equipment");
            exit;
        }
    }

    // Handle deleting equipment
    public function delete($id) {
        if (isset($id)) {
            $this->equipmentModel->deleteEquipment($id);
            header("Location: /equipment");
            exit;
        }
    }
    
    // Process all form submissions
    public function processRequest() {
        // Process add equipment request
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_equipment'])) {
            $this->add();
        }
        
        // Process delete equipment request
        if (isset($_GET['delete'])) {
            $this->delete($_GET['delete']);
        }
        
        // Show equipment list
        $this->index();
    }
}

// Establish database connection - assuming this is done elsewhere in your project
$db_host = 'localhost';
$db_name = 'melodylink';
$db_user = 'root';
$db_pass = '';

// Create connection
$db = new mysqli($db_host, $db_user, $db_pass, $db_name);

// Check connection
if ($db->connect_error) {
    die("Connection failed: " . $db->connect_error);
}

// Initialize and run controller, passing the database connection
$controller = new Equipment($db);
$controller->processRequest();

// Clean up database connection
$db->close();
?>
