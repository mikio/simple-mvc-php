<?php

require_once 'core/define.php';

define('DEFAULT_CONTROLLER', 'index');
define('DEFAULT_ACTION', 'index');

//define('LOG_LEVEL', PEAR_LOG_WARNING);
define('LOG_LEVEL', PEAR_LOG_DEBUG);

define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_DATABASE', 'sample');
define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');

// このアプリケーションで使用する全パラメータの型定義。
$g_type = array(
    'id'        => array('length'=>20, 'regex'=>'/\d.*/'),
    'timestamp' => array('length'=>100,'regex'=>null),
    'word'      => array('length'=>100,'regex'=>null),
    'password'  => array('length'=>8,  'regex'=>'/^[0-9a-zA-Z]+$/'),
    'email'     => array('length'=>50, 'regex'=>'/.*@.*/'),
    'phone'     => array('length'=>12, 'regex'=>'/\d{2}-\d{4}-\d{4}/'),
    'bool'      => array('length'=>1,  'regex'=>'/[01]/'),
);

// デフォルトルーティング
$g_routes = array(
    '/' => array('controller' => 'user', 'action' => 'list'),
    '/user' => array('controller' => 'user', 'action' => 'list'),
);
?>
