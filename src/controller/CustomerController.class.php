<?php
require_once 'model/CustomerModel.class.php';
class CustomerController extends Controller {

    protected function validates() {
        return array(
            'id'     => array( 'length'=>3, 'default'=>'', 'regex'=>null ),
            'name'   => array( 'length'=>160, 'default'=>'', 'regex'=>null ),
            'email'  => array( 'length'=>160, 'default'=>'', 'regex'=>null ),
            'mobile' => array( 'length'=>12, 'default'=>'', 'regex'=>null ),
        );
    }

    public function lists() {
        $this->action->execute($this->params);
    }

    public function create() {
        $this->action->execute($this->params);
    }
}
?>
