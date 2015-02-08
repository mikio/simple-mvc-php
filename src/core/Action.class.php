<?php

// アクション。継承して使う。
// - ビューに依存するロジックを記述する。
abstract class Action {
    protected $params;
    protected $controllerName;
    protected $actionName;
    protected function parameter() {
        return array();
    }
    public function initialize($controllerName, $actionName) {
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;

        $this->params = new Parameter($this->parameter());
        if ($this->params->isPost()){
            get_log()->debug("this is POST");
        } else {
            get_log()->debug("this is GET");
        }
    }
    public function createView() {
        return new View($this->controllerName, $this->actionName);
    }
    abstract function execute();
}
?>
