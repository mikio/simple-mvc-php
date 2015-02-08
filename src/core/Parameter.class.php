<?php
class Parameter {
    const ERROR_NONE = 0;
    const ERROR_LENGTH = 1;
    const ERROR_REGEX = 2;
    const ERROR_REQUIRED = 3;
    const ERROR_NO_TYPE = 4;

    private $isPost_ = false;
    private $params;
    private $errors;
    private $ua_ = '';

    public function __construct(&$validates) {
        if (array_key_exists('HTTP_USER_AGENT', $_SERVER)) {
            $this->ua_ = $_SERVER['HTTP_USER_AGENT'];
        }

        $rawParams = null;
        $requestMethod = strtolower(getenv('REQUEST_METHOD'));
        get_log()->debug('METHOD:'.$requestMethod);
        if ($requestMethod == 'post') {
            $rawParams = $_POST;
            $this->isPost_ = true;
        } else if ($requestMethod == 'get') {
            $rawParams = $_GET;
        }
        if ($rawParams) {
            $this->validate($rawParams, $validates);
        }

        if (empty($this->params)) {
            $this->params = array();
        }
        if (empty($this->errors)) {
            $this->errors = array();
        }
    }

    private function validate(&$rawParams, &$validates) {
        $params = array();
        $errors = array();

        foreach ($validates as $key => $s) {
            $type_key = assoc_value($s, 'type');
            $default = assoc_value($s, 'default');
            $required = assoc_value($s, 'required');
            get_log()->debug('para key:'.$key);

            // checkbox型は特殊処理
            if ($type_key == 'checkbox') {
                $value = '';
                if (array_key_exists($key, $rawParams)) {
                    $value = trim($rawParams[$key]);
                }
                $errors[$key] = self::ERROR_NONE;
                $params[$key] = $value == 'on'? 1: 0;
                continue;
            }

            // 必須入力チェック
            if (!array_key_exists($key, $rawParams)) {
                if ($required) {
                    $params[$key] = null;
                    $errors[$key] = self::ERROR_REQUIRED;
                } else {
                    $params[$key] = $default;
                    $errors[$key] = self::ERROR_NONE;
                }
                continue;
            }
            $value = trim($rawParams[$key]);
            if ($required && $value == '') {
                $params[$key] = null;
                $errors[$key] = self::ERROR_REQUIRED;
                continue;
            }
            if ($value == $default) {
                // 入力値がデフォルトと同じ場合はOK
                $errors[$key] = self::ERROR_NONE;
                $params[$key] = $value;
                continue;
            }

            //
            // 型チェック
            //

            if (empty($type_key)) {
                $params[$key] = $value;
                $errors[$key] = self::ERROR_NO_TYPE;
                continue;
            }

            global $g_type;
            $type = assoc_value($g_type, $type_key);
            if (empty($type)) {
                // 型が定義されていない。
                $params[$key] = $value;
                $errors[$key] = self::ERROR_NO_TYPE;
                continue;
            }

            $length = assoc_value($type, 'length');
            $regex = assoc_value($type, 'regex');

            // 文字数チェック
            if (strlen($value) > $length) {
                $errors[$key] = self::ERROR_LENGTH;
                $params[$key] = $value;
                continue;
            }

            // 正規表現チェック
            if ($regex) {
                $res = preg_match($regex, $value);
                if ($res === false || $res === 0) {
                    $errors[$key] = self::ERROR_REGEX;
                    $params[$key] = $value;
                    continue;
                }
            }
            $errors[$key] = self::ERROR_NONE;
            $params[$key] = $value;
        }

        $this->params = $params;
        $this->errors = $errors;
    }

    public function set($key, $val) {
        $this->params[$key] = $val;
    }

    public function get($key) {
        if (!array_key_exists($key, $this->params)) {
            get_log()->err('no exists params key:'.$key);
            return '';
        }
        return $this->params[$key];
    }

    public function error($key) {
        if (!array_key_exists($key, $this->errors)) {
            get_log()->err('no exists eror key:'.$key);
            return '';
        }
        return $this->errors[$key];
    }

    public function isPost() {
        return $this->isPost_;
    }

    public function ua() {
        return $this->ua_;
    }
}
?>
