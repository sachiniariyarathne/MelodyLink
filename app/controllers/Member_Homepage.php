<?php
class Member_Homepage extends Controller{
public function __construct(){
   // echo 'this is the pages controller';
//$this->pagesModel=$this->model('m_Pages');
}

public function index(){
    


}
public function Homepage(){
//     $users=$this->pagesModel->getUsers();
// $data=[
//     'users'=>$users
// ];

$this->view('users/v_member_homepage');

}

}
?>