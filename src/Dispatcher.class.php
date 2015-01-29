<?php

// URLの/区切りの1番目をコントローラ、2番目をアクションメソッドとして実行する。
// {domain}/{controller}/{action}
class Dispatcher {

    /**
     * 振分け処理実行
     */
    public function dispatch() {

        $params = array();
        $uri = $_SERVER['REQUEST_URI'];
        get_log()->debug("uri raw:".$uri);
        if ('' != $uri && '/' != $uri) {
            $uri = preg_replace('/\?.*$/', '', $uri);
            $params = explode('/', trim($uri,'/'));
        }
        
        // コントローラー名を取得
        $controllerName = DEFAULT_CONTROLLER;
        if (count($params) >= 1) {
            get_log()->debug("controllerName from uri");
            $controllerName = $params[0];
        }
        get_log()->debug("controllerName:".$controllerName);
        $controller = $this->createController($controllerName);
        if (null == $controller) {
            get_log()->debug("controller is null");
            header("HTTP/1.0 404 Not Found");
            file_not_found();
            exit;
        }
        
        // アクション名を取得
        $actionName = DEFAULT_ACTION;
        if (count($params) >= 2) {
            get_log()->debug("actionName from uri");
            $actionName = $params[1];
        }
        get_log()->debug("actionName:".$actionName);
        if (false == method_exists($controller, $actionName)) {
            get_log()->debug("methodName no exists");
            header("HTTP/1.0 404 Not Found");
            file_not_found();
            exit;
        }

        $controller->initialize($controllerName, $actionName);
        $controller->execute();
    }

    // コントローラークラスのインスタンスを取得
    private function createController($controllerName) {
        $className = ucfirst(strtolower($controllerName)) . 'Controller';
        $fileName = sprintf(ROOT.'src/%s/%s.class.php', CONTROLLER_DIR, $className);
        get_log()->debug("controllerFileName:".$fileName);
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