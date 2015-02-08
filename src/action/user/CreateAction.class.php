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
            'user_id' => array('type'=>'user_id', 'required'=>true),
            'password' => array('type'=>'password', 'required'=>true),
            'admin' => array('type'=>'checkbox', 'default'=>0),
        );
    }

    public function execute() {
        $params = $this->params;
        $view = $this->createView();

        $data = $this->model->emptyRecord();
        $errors = array();

        if ($this->params->isPost()) {
            if ($this->checkParams($errors)) {
                $this->createUser($data);
                $this->redirect('/user/list');
            } else {
        get_log()->debug(p($errors));
                $this->setRecord($data);
            }
        }

        $view->errors = $errors;
        $view->name = $data['name'];
        $view->userId = $data['user_id'];
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
