<?php

require_once 'Log.php';

define('DEFAULT_CONTROLLER', 'customer');
define('DEFAULT_ACTION', 'lists');

if (!defined('DS')){
    define('DS', DIRECTORY_SEPARATOR);
}
if (!defined('ROOT')){
    define('ROOT', dirname(dirname(__FILE__)).DS);
}
define('LOG_FILE',ROOT.'log/app.log');
//define('LOG_LEVEL', PEAR_LOG_WARNING);
define('LOG_LEVEL', PEAR_LOG_DEBUG);

define('DB_USERNAME', 'root');
define('DB_PASSWORD', 'root');
define('DB_HOST', 'localhost');
define('DB_PORT', '3306');
define('DB_SOCKET', '/var/lib/mysql/mysql.sock');
define('DB_DATABASE', 'sample');

define('CONTROLLER_DIR', 'controller');
define('MODEL_DIR', 'model');
define('ACTION_DIR', 'action');
define('TEMPLATE_DIR', 'template');
define('LAYOUT_FILE', 'layout.tmpl.php');
define('DUMMY_TEMPLATE_FILE', 'dummy.tmpl.php');

if (!defined('WITHOUT_REQUIRE')){
    require_once("Dispatcher.class.php");
    require_once("core/Controller.class.php");
    require_once("core/Model.class.php");
    require_once("core/DbModel.class.php");
    require_once("core/View.class.php");
    require_once("core/Action.class.php");
    require_once("core/Parameter.class.php");
    require_once("core/Database.class.php");
    require_once("util/util.php");
}
?>
