<?php
class main extends Controller {
    // public function __construct() {
    //     $this->userModel = $this->model('m_Pages');
    // }

   
    public function index() {
        echo "This is the index method of the Users controller.";
    }
public function Music(){
    
$this->view('v_music');

}
}
?>