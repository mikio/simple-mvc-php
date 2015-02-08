<?php

require_once 'Log.php';

if (!defined('ROOT')){
    define('ROOT', dirname(dirname(dirname(__FILE__))).'/');
}

require_once("core/Controller.class.php");
require_once("core/Model.class.php");
require_once("core/DbModel.class.php");
require_once("core/View.class.php");
require_once("core/Action.class.php");
require_once("core/Parameter.class.php");
require_once("core/Database.class.php");
require_once("core/Response.class.php");
require_once("core/RedirectException.class.php");
require_once("core/HttpNotFoundException.class.php");
require_once("core/LoginException.class.php");
require_once("core/Session.class.php");
require_once("core/util.php");

define('CONTROLLER_DIR', 'controller');
define('MODEL_DIR', 'model');
define('ACTION_DIR', 'action');
define('TEMPLATE_DIR', 'template');
define('LAYOUT_FILE', 'layout.tmpl.php');
define('DUMMY_TEMPLATE_FILE', 'dummy.tmpl.php');

define('LOG_FILE', ROOT.'log/app.log');

define('DEFAULT_ENCODING', 'UTF-8');
?>
