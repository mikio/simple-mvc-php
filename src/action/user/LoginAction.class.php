<?php
require_once 'model/UserModel.class.php';

// ログイン
class LoginAction extends Action {
    protected $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    // この画面で使用するパラメータ
    protected function parameter() {
        return array(
            'user_id'  => array('type'=>'word', 'required'=>true),
            'password' => array('type'=>'password', 'required'=>true),
        );
    }

    protected function setRecord(&$data) {
    }

    public function execute() {
        $params = $this->params;
        $view = $this->createView();

        $userId = $this->params->get('user_id');
        $password = $this->params->get('password');

        $data = $this->model->record($userId);
        $errors = array();

        if ($this->params->isPost()) {
            if ($this->checkParams($errors)) {
                header("Location: /user/list");
                return;
            } else {
                $this->setRecord($data);
            }
        }

        $view->errors = $errors;
        $view->userId = $userId;
        $view->password = $password;
        $view->render();
    }

    // 返り値=> true:OK false:NG
    private function checkParams(&$errors) {
        $params = $this->params;
        $valid = true;

        $error = $params->error('user_id');
        if ($error > 0) {
            $errors['user_id'] = $this->getErrorMessage($error);
            $valid = false;
        }

        $error = $params->error('password');
        if ($error > 0) {
            $errors['password'] = $this->getErrorMessage($error);
            $valid = false;
        }
        return $valid;
    }

    private function getErrorMessage($error) {
        $message = '';
        switch ($error) {
        case Parameter::ERROR_LENGTH:
            $message = 'error length';
            break;
        case Parameter::ERROR_REGEX:
            $message = 'error regex';
            break;
        case Parameter::ERROR_REQUIRED:
            $message = 'error required';
            break;
        case Parameter::ERROR_NO_TYPE:
            $message = 'error no type';
            break;
        }
        return $message;
    }
}
?>
