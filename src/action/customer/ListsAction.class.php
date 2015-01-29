<?php
require_once 'model/CustomerModel.class.php';

// カスタマー  一覧表示
class ListsAction extends Action {
    public function execute($params) {
        $model = new CustomerModel();
        $view = $this->createView();
        $view->data = $model->lists();
        $view->render();
    }
}
?>
