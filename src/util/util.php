<?php

function get_log() {
    $log_conf = array('mode'=>0664);
    return Log::singleton("file", LOG_FILE, null, $log_conf, LOG_LEVEL);
}

function str_normalize($str) {
    //$str = strtoupper($str);
    $str = strtolower($str);
    $str = mb_convert_kana($str, 'a');
    return $str;
}

// inhibitテーブルにあてるための文字列正規化
function norm4inhibit($str) {
    $str = str_replace('_', ' ', $str);
    $str = str_replace('”','"', $str);
    $str = str_replace("’","'", $str);
    $str = str_replace("￥","\\", $str);
    $str = mb_convert_kana($str, 'rnas'); // 全角を半角にする
    $str = preg_replace('/[\s\t]+/', ' ', $str); // 連続する空白を一文字にする。
    $str = strtoupper($str);
    $str = trim($str);
    return $str;
}

function redirect_to($uri) {
    //$log = get_log();
    //$log->debug('redirect_to:'.$uri);
    if (!strstr($uri, 'http')){ 
        if (strpos($uri, '/') == 0){ 
            //$log->debug("/で始まる:$uri");
            $uri = 'http://'.$_SERVER['HTTP_HOST'].$uri;
        } else {
            //$log->debug("/で始まらない:$uri");
            $uri = 'http://'.$uri;
        }
    }
    header('HTTP/1.1 301 Moved Permanently');
    header('Location: '.$uri);
    exit();
}

function validates_format_of($value, $with) {
    if( !preg_match($with,$value) ){
        return false;
    }
    return true;
}

function e($str) {
    echo $str;
}

// 空だったらデフォルト表示
function e2($s, $d) {
    echo !empty($s)? $s: $d;
}

function h($str) {
    return htmlspecialchars($str, ENT_QUOTES);
}

function u($str) {
    return urlencode($str);
}

function u_eucjp($str) {
    return urlencode(mb_convert_encoding($str, 'EUC-JP', 'UTF-8'));
}

function eh($str) {
    echo htmlspecialchars($str, ENT_QUOTES);
}

// シングルクォーテーションをエスケープ
function ehe($str) {
    echo htmlspecialchars(preg_replace("/'/", "\\'", $str));
}

function eu($str) {
    echo urlencode($str);
}

function u_e_encode($str) {
    $target_str = mb_convert_encoding($str, 'EUC-JP', 'UTF-8');
    echo urlencode($target_str);
}

// 404 用エラーページ
function file_not_found() {
    echo '<html><body><h1>ページが見つかりません</h1></body></html>';
}

///////////////////////
// 以下 bootstrap用
///////////////////////

// $value にエラーメッセージがあれば、bootstrapのhas-error を出力する。
function has_error(&$errors, $key) {
    if (!array_key_exists($key, $errors)) return;
    $value = $errors[$key];
    if (empty($value)) return;
    echo 'has-error';
}

function error_msg(&$errors, $key) {
    if (!array_key_exists($key, $errors)) return;
    $value = $errors[$key];
    if (empty($value)) return;
    echo '<span class="help-block">'.$value.'</span>';
}
?>
