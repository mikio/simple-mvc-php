<?php
require_once 'model/UserModel.class.php';

// ユーザー
abstract class AbstractUserAction extends Action {
    protected $model;

    public function __construct() {
        $this->model = new UserModel();
    }

    protected function setRecord(&$data) {
        $data['name'] = $this->params->get('name');
        $data['password'] = $this->params->get('password');
        $data['admin'] = $this->params->get('admin');
    }

    // 返り値=> true:OK false:NG
    protected function checkParams(&$errors) {
        $params = $this->params;
        $valid = true;

        $error = $params->error('name');
        if ($error > 0) {
            $errors['name'] = $this->getErrorMessage($error);
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
