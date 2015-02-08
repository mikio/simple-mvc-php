<?php
require_once 'action/user/AbstractUserAction.class.php';

// ユーザーレコード生成(表示＆登録)
class CreateAction extends AbstractUserAction {

    public function __construct() {
        parent::__construct();
    }

    // この画面で使用するパラメータ
    protected function parameter() {
        return array(
            'name'  => array('type'=>'word', 'required'=>true),
            'password' => array('type'=>'password', 'required'=>true),
            'admin' => array('type'=>'checkbox', 'default'=>0),
        );
    }

    public function execute() {
        $params = $this->params;
        get_log()->debug('admin:'.$params->get('admin'));
        get_log()->debug('admin error:'.$params->error('admin'));
        $view = $this->createView();

        $data = $this->model->emptyRecord();
        $errors = array();

        if ($this->params->isPost()) {
            if ($this->checkParams($errors)) {
                $this->createUser($data);
                header("Location: /user/list");
                return;
            } else {
                $this->setRecord($data);
            }
        }

        $view->errors = $errors;
        $view->name = $data['name'];
        $view->password = $data['password'];
        $view->admin = $data['admin'];
        $view->render();
    }

    private function createUser(&$data) {
        $this->setRecord($data);
        $this->model->create($data);
    }
}
?>
