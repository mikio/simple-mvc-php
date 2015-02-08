<?php
// セッションのラッパー
class Session {
    protected static $started = false;
    protected static $idRegenerated = false;

    // セッションを自動的に開始する
    public function __construct() {
        if (!self::$started) {
            session_start();
            self::$started = true;
        }
    }

    public function set($name, $value) {
        $_SESSION[$name] = $value;
    }

    public function get($name, $default = null) {
        if (isset($_SESSION[$name])) {
            return $_SESSION[$name];
        }
        return $default;
    }

    public function remove($name) {
        unset($_SESSION[$name]);
    }

    // セッションを空にする
    public function clear() {
        $_SESSION = array();
    }

    // セッションIDを再生成する
    // $destroy == true の場合は古いセッションを破棄する
    public function regenerate($destroy = true) {
        if (!self::$idRegenerated) {
            session_regenerate_id($destroy);
            self::$idRegenerated = true;
        }
    }

    // ログイン状態を設定
    public function login($bool) {
        $this->set('_login', (bool)$bool);
        $this->regenerate();
    }

    // ログイン済みか判定
    public function isLogin() {
        return $this->get('_login', false);
    }
}
?>