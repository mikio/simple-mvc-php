<?php

abstract class Controller {
    protected $controllerName;
    protected $actionName;
    protected $action;
    protected $params;

    protected function validates() {
        return array();
    }

    public function initialize($controllerName, $actionName) {

        $this->params = new Parameter($this->validates());
        $this->controllerName = $controllerName;
        $this->actionName = $actionName;

        $this->action = $this->createAction($this->controllerName, $this->actionName);
        if (null == $this->action) {
            get_log()->debug("action is null");
            header("HTTP/1.0 404 Not Found");
            file_not_found();
            exit;
        }
        $this->action->initialize($this->controllerName, $this->actionName);

        if ($this->params->isPost()){
            get_log()->debug("this is POST");
        } else {
            get_log()->debug("this is GET");
        }
    }

    public function execute() {
        try {
            // Controllerのアクションメソッドを実行する。 
            $action = $this->actionName;
            $this->$action();            
        } catch (Exception $e) {
            // ログ出力等の処理を記述
            get_log()->error("Controller->execute():".$e);
        }
    }

    // アクションクラスのインスタンスを取得
    private function createAction($controllerName, $actionName) {
        $className = ucfirst(strtolower($actionName)) . 'Action';
        $fileName = sprintf(ROOT.'src/%s/%s/%s.class.php', ACTION_DIR, $controllerName, $className);
        get_log()->debug("actionFileName:".$fileName);
        if (false == file_exists($fileName)) {
            return null;
        }
        get_log()->debug("file exists");
        require_once $fileName;
        if (false == class_exists($className)) {
            return null;
        }
        get_log()->debug("class exists");
        return new $className();
    }
}

?>
