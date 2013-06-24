<?php
/**
 * @author Olivarès Georges (https://github.com/Thiktak)
 */

define('DS', DIRECTORY_SEPARATOR);
define('FRAMEWORK', dirname(__FILE__) . DS);

if( !defined('APP') )
	define('APP', realpath(FRAMEWORK . '..') . DS . 'src' . DS);

include_once FRAMEWORK . 'libs/Frontend.php';
include_once FRAMEWORK . 'libs/Config.php';
include_once FRAMEWORK . 'libs/Router.php';
include_once FRAMEWORK . 'libs/Controller.php';
include_once FRAMEWORK . 'libs/Render.php';
include_once FRAMEWORK . 'libs/UndefinedFrameworkObjectException.php';
include_once FRAMEWORK . '../src/Controllers/IndexController.php';