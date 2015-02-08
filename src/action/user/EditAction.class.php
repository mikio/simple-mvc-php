<?php
require_once 'action/user/AbstractUserAction.class.php';

// ユーザーレコード編集
class EditAction extends AbstractUserAction {

    // この画面で使用するパラメータ
    protected function parameter() {
        return array(
            'id' => array('type'=>'id', 'required'=>true),
            'name'  => array('type'=>'word', 'required'=>true),
            'password' => array('type'=>'password', 'required'=>true),
            'admin' => array('type'=>'checkbox', 'default'=>0),
        );
    }

    public function execute() {
        $params = $this->params;
        $view = $this->createView();

        $data = $this->model->emptyRecord();
        $errors = array();
        $id = $params->get('id');
        get_log()->debug('id:::'.$id);
        if ($this->params->isPost()) {
            if ($this->checkParams($errors)) {
                $this->updateUser($id, $data);
                header("Location: /user/list");
                return;
            } else {
                $this->setRecord($data);
            }
        } else {
            $data = $this->model->record($id);
        }

        $view->errors = $errors;
        $view->id = $id;
        $view->name = $data['name'];
        $view->password = $data['password'];
        $view->admin = $data['admin'];
        $view->render();
    }

    private function updateUser($id, &$data) {
        $this->setRecord($data);
        $this->model->update($id, $data);
    }
}
?>
