<?php
require_once 'model/CustomerModel.class.php';

// カスタマーレコード生成(表示＆登録)
class CreateAction extends Action {
    public function execute($params) {
        $log = get_log();
        $view = $this->createView();

        $errors = array();
        if ($params->isPost()){
            if ($this->checkParams($params, $errors)) {
                $this->createRecord($params);
                header("Location: /");
                return;
            }
        }

        $view->errors = $errors;
        $view->name   = $params->get('name');
        $view->email  = $params->get('email');
        $view->mobile = $params->get('mobile');
        $view->render();
    }

    private function checkParams($params, &$errors) {
        $valid = true;
        if ($params->isEmpty('name')) {
            $errors['name'] = 'Please enter Name';
            $valid = false;
        }
         
        if ($params->isEmpty('email')) {
            $errors['email'] = 'Please enter Email Address';
            $valid = false;
        }
         
        if ($params->isEmpty('mobile')) {
            $errors['mobile'] = 'Please enter Mobile Number';
            $valid = false;
        }
        return $valid;
    }

    private function createRecord($params) {
        $model = new CustomerModel();
        $model->create(array(
            'name'   => $params->get('name'),
            'email'  => $params->get('email'),
            'mobile' => $params->get('mobile'),
        ));
    }
}
?>
