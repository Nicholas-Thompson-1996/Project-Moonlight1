<?php

//Define core paths.
//Define them as absolute paths.

//Directory_separator is php pre defined constant

defined('DS') ? null : define('DS', DIRECTORY_SEPARATOR);

defined('SITE_ROOT') ? null : define('SITE_ROOT', DS.'Apache'.DS.'htdocs'.DS.'moonlight');

defined('LIB_PATH') ? null : define('LIB_PATH', SITE_ROOT.DS.'includes');

//load config first
require_once(LIB_PATH.DS.'config.php');

//load basic functions next so that everything after uses them.
require_once(LIB_PATH.DS.'functions.php');

//load core objects
require_once(LIB_PATH.DS.'session.php');
require_once(LIB_PATH.DS.'member_platform.php');
require_once(LIB_PATH.DS.'database_object.php');


//load database related classes
require_once(LIB_PATH.DS.'admin.php');
require_once(LIB_PATH.DS.'member.php');

?>