<?php
class EqpSupplierDashboard extends Controller {
    public function __construct() {
        // No login check needed as per request
    }

    public function index() {
        // Just load the view without backend data
        $this->view('eqpsupplier/v_eqpsupplier_dashboard');
    }
}
