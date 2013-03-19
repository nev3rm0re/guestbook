<?php
error_reporting(E_ALL);
ini_set('display_errors', true);

date_default_timezone_set('Europe/Tallinn');

set_include_path(get_include_path()
    . PATH_SEPARATOR . dirname(__FILE__).'/guestbook/'
    . PATH_SEPARATOR . dirname(__FILE__).'/guestbook/forms'
    . PATH_SEPARATOR . dirname(__FILE__).'/guestbook/model'
    . PATH_SEPARATOR . dirname(__FILE__).'/guestbook/views');

function autoload($class_name) {
    include(strtolower($class_name).'.class.php');
}
spl_autoload_register('autoload');
// Redirect all errors to exceptions
function exception_error_handler($errno, $errstr, $errfile, $errline) {
    throw new ErrorException($errstr, $errno, 0, $errfile, $errline);
}
set_error_handler('exception_error_handler');

session_start();
$service_locator = ServiceLocator::getInstance('config/config.php');
$service_locator->getController()->handle();