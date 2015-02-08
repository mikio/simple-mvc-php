<?php

// URLの/区切りの1番目をコントローラ(機能名)、2番目をアクション(画面名)として実行する。
// http://{domain}/{controller}/{action}
class Controller {
    private $controllerName;
    private $actionName;
    private $action;
    private $response;

    public function __construct() {
        get_log()->debug("hoge");
        $this->response = new Response();
    }

    // 振分け処理実行
    public function execute() {
        try {
            $content = '';
            $this->parseUri();
            $this->initialize();
            $content = $this->executeAction();

        } catch (HttpNotFoundException $e) {

            $content = $this->notFoundPage();

        } catch (Exception $e) {
            get_log()->err("action->execute():".$e);
        }
        $this->response->content($content);
        $this->response->send();
    }

    // アクションを実行する。 
    private function executeAction() {
        return $this->action->execute();
    }

    // 404 用エラーページ
    private function notFoundPage() {
        $this->response->statusCode('404', 'Not Found');
        return '<html><body><h1>ページが見つかりません</h1></body></html>';
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

        // ルーティング制御
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
            get_log()->warning("action is null");
            throw new HttpNotFoundException();
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