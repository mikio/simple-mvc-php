<?php
class IndexAction extends Action {

    public function execute() {
        $view = $this->createView();
        $view->render();
    }
}
?>
