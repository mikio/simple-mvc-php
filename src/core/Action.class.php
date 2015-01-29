<?php

// アクション。継承して使う。
// - ビューに依存するロジックを記述する。
abstract class Action {
    protected $controllerName;
    protected $actionName;
    public function initialize($controllerName, $actionName) {
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;
    }
    public function createView() {
        $templateFile = sprintf(ROOT."src/%s/%s/%s.tmpl.php", TEMPLATE_DIR, $this->controllerName, $this->actionName);
        get_log()->debug("templateFile:".$templateFile);
        if (!file_exists($templateFile)) {
            $templateFile = sprintf(ROOT."src/%s/%s", TEMPLATE_DIR, DUMMY_TEMPLATE_FILE);
        }
        return new View($templateFile);
    }
    abstract function execute($params);
}
?>
