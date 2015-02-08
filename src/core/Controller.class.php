<?php

// URLの/区切りの1番目をコントローラ(機能名)、2番目をアクション(画面名)として実行する。
// http://{domain}/{controller}/{action}
class Controller {

    protected $controllerName;
    protected $actionName;
    protected $action;

    // 振分け処理実行
    public function dispatch() {
        $this->parseUri();
        $this->initialize();
        $this->execute();
    }

    // アクションを実行する。 
    private function execute() {
        try {
            $content = $this->action->execute();
        } catch (Exception $e) {
            get_log()->err("action->execute():".$e);
        }
    }

    // URIから、コントローラ名とアクション名を取得する。
    private function parseUri() {
        $params = array();
        $uri = $_SERVER['REQUEST_URI'];
        get_log()->debug("uri raw:".$uri);
        if ('' != $uri && '/' != $uri) {
            $uri = preg_replace('/\?.*$/', '', $uri);
            $params = explode('/', trim($uri,'/'));
        }
        get_log()->debug("uri:".$uri);
        
        // コントローラー名を取得
        $this->controllerName = '';
        if (count($params) >= 1) {
            $this->controllerName = $params[0];
            get_log()->debug("controllerName from uri:".$this->controllerName);
        }
        
        // アクション名を取得
        $this->actionName = '';
        if (count($params) >= 2) {
            $this->actionName = $params[1];
            get_log()->debug("actionName from uri:".$this->actionName);
        }

        $cakey = '/';
        if ($this->controllerName) {
            $cakey .= $this->controllerName;
        }
        if ($this->actionName) {
            $cakey .= '/';
            $cakey .= $this->actionName;
        }
        get_log()->debug("ca key:".$cakey);
        global $g_routes;
        if (array_key_exists($cakey, $g_routes)) {
            $route = $g_routes[$cakey];
            if (array_key_exists('controller', $route)) {
                $this->controllerName = $route['controller'];
            }
            if (array_key_exists('action', $route)) {
                $this->actionName = $route['action'];
            }
        }
    }

    // アクションクラスの生成。
    private function initialize() {
        $this->action = $this->createAction($this->controllerName, $this->actionName);
        if (null == $this->action) {
            get_log()->debug("action is null");
            header("HTTP/1.0 404 Not Found");
            file_not_found();
            exit;
        }
        $this->action->initialize($this->controllerName, $this->actionName);
    }

    // アクションクラスのインスタンスを取得。
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