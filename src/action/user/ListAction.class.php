<?php
require_once 'model/UserModel.class.php';

// 編集者一覧表示
class ListAction extends Action {
    public function execute() {
        $model = new UserModel();
        $view = $this->createView();
        $view->data = $model->userList();
        $view->render();
    }
}
?>
