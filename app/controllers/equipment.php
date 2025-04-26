<?php
require_once 'EquipmentModel.php';

class EquipmentController {
    private $model;
    private $db;

    public function __construct() {
        // Database connection
        $this->db = new mysqli('localhost', 'root', '', 'event_gear_db');
        if ($this->db->connect_error) {
            die("Connection failed: " . $this->db->connect_error);
        }
        $this->model = new EquipmentModel($this->db);
    }

    // Handle all equipment operations
    public function handleRequest() {
        // Add new equipment
        if (isset($_POST['add_equipment'])) {
            $this->addEquipment();
        }
        
        // Delete equipment
        if (isset($_GET['delete'])) {
            $this->deleteEquipment();
        }
        
        // Display all equipment
        $this->showEquipment();
    }

    private function addEquipment() {
        $this->model->addEquipment(
            $_POST['name'],
            $_POST['description'],
            $_POST['price'],
            $_POST['category'],
            $_POST['image_url'],
            $_POST['rating'],
            $_POST['reviews'],
            $_POST['status']
        );
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

    private function deleteEquipment() {
        $this->model->deleteEquipment($_GET['delete']);
        header("Location: ".$_SERVER['PHP_SELF']);
        exit;
    }

    private function showEquipment() {
        $equipment = $this->model->getAllEquipment();
        include 'EquipmentView.php';
    }
}

// Create controller and handle request
$controller = new EquipmentController();
$controller->handleRequest();
?>