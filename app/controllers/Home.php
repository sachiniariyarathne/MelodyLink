<?php
class Home extends Controller{
public function __construct(){
   // echo 'this is the pages controller';
//$this->pagesModel=$this->model('m_Pages');
}
public function index(){
    



}
public function Home(){
//     $users=$this->pagesModel->getUsers();
// $data=[
//     'users'=>$users
// ];

$this->view('users/v_landing_page');

}


}
?>