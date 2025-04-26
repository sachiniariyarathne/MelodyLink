<?php
session_start();
//load configurations

//load helpers
require_once 'helpers/URL_Helper.php';
// Load helpers
require_once 'helpers/session_helper.php';
// require_once 'helpers/flash_helper.php';

require_once 'config/config.php';
//load libraries
require_once 'libraries/Core.php';
require_once 'libraries/Database.php';
require_once 'libraries/Controller.php';
?>