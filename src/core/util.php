<?php

function p($var) {
    $str = '';
    ob_start();
    var_dump($var);
    $str = ob_get_contents();
    ob_end_clean();
    return $str;
}
/*
  %1$s 時間
  %2$s 識別文字列
  %3$s ログレベル
  %4$s メッセージ
  %5$s ファイルパス
  %6$s 行数
  %7$s 関数名
  %8$sクラス名
2015/02/08 14:03:09 [debug]  [30]/vagrant/dev/simple-mvc-php/src/core/util.php - para key:admin 
 */
function get_log() {
    $conf = array('mode' => 0777,
                  'timeFormat' => '%Y/%m/%d %H:%M:%S',
                  //'lineFormat' => '%1$s [%3$s] [%5$s:%6$s] %4$s');
                  'lineFormat' => '%1$s [%3$s] [%8$s::%7$s:%6$s] %4$s');
    $log = Log::singleton("file", LOG_FILE, null, $conf, LOG_LEVEL);
    return $log;
}

function assoc_value($assoc, $key) {
    if (!array_key_exists($key, $assoc)) return null;
    return $assoc[$key];
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

function eh2($s, $d) {
    $val = htmlspecialchars($s, ENT_QUOTES);
    echo !empty($val)? $val: $d;
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

///////////////////////
// 以下 bootstrap用
///////////////////////

// $value にエラーメッセージがあれば、bootstrapのhas-error を出力する。
function bst_has_error(&$errors, $key) {
    if (!array_key_exists($key, $errors)) return;
    get_log()->debug("0 bst_error_msg ".$key);
    $value = $errors[$key];
    get_log()->debug("1 bst_error_msg ".$key);
    if (empty($value)) return;
    get_log()->debug("2 bst_error_msg ".$key);
    echo 'has-error';
    get_log()->debug("3 bst_error_msg ".$key);
}

function bst_error_msg(&$errors, $key) {
    if (!array_key_exists($key, $errors)) return;
    $value = $errors[$key];
    if (empty($value)) return;
    echo '<span class="help-block">'.$value.'</span>';
}
?>
