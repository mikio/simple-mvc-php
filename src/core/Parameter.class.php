<?php
class Parameter {
    const ERROR_LENGTH = '';
    const ERROR_REGEX = '';

    private $isPost_ = false;
    private $params = array();
    private $errors = array();
    private $ua_ = '';

    public function __construct(&$validates) {
        if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
            $this->ua_ = $_SERVER['HTTP_USER_AGENT'];
        }

        $request_method = strtolower(getenv('REQUEST_METHOD'));
        if ($request_method == 'post') {
            $form = $_POST;
            $this->isPost_ = true;
        } else if ($request_method == 'get') {
            $form = $_GET;
        }
        if ($form) {
            get_log()->debug('-----------------');
            foreach ($form as $key=>$val) {
                $this->params[$key] = $val;
                get_log()->debug($this->params[$key]);
            }
            get_log()->debug('-----------------');
        }
        $this->validate($validates);
    }

    private function validate(&$validates) {

        // 余計なパラメータは削除
        foreach ($this->params as $key=>$val) {
            if (!array_key_exists($key, $validates)) {
                unset($this->params[$key]);
            }
        }

        foreach ($validates as $key=>$s) {
            // 検証する対象のパラメータ(key)が必ず存在する状態にする
            if (!array_key_exists($key, $this->params)) {
                $this->params[$key] = $s['default'];
                continue;
            }
            $this->params[$key] = trim($this->params[$key]);
            $this->params[$key] = substr($this->params[$key], 0, $s['length']);
            if ($s['regex']) {
                if (!validates_format_of($this->params[$key], $s['regex'])) {
                    $this->params[$key] = $s['default'];
                }
            }
        }
    }

    public function set($key, $val) {
        $this->params[$key] = $val;
    }

    public function get($key) {
        return $this->params[$key];
    }

    public function isEmpty($key) {
        $value = $this->get($key);
        get_log()->debug('isEmpty:'.$value);
        return empty($value);
    }

    public function getError($key) {
        return $this->erros[$key];
    }

    public function isPost() {
        return $this->isPost_;
    }

    public function ua() {
        return $this->ua_;
    }
}
?>
