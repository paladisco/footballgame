<?php
setlocale(LC_TIME, 'de_CH');
ini_set('display_errors',1);
error_reporting(E_ALL ^ E_NOTICE ^ E_WARNING ^ E_DEPRECATED ^ E_STRICT);
// Define path to application directory
defined('APPLICATION_PATH')
    || define('APPLICATION_PATH', realpath(dirname(__FILE__) . '/../application'));

// Define application environment
defined('APPLICATION_ENV')
    || define('APPLICATION_ENV', (getenv('APPLICATION_ENV') ? getenv('APPLICATION_ENV') :
    file_get_contents(APPLICATION_PATH.'/configs/environment.ini')));

define('SITE_NAME', 'Fussball mit Freunden');
define('VERSION_NUMBER','1.0');
define('SITE_ID', 'rfbp');

define('GREETING_FORMULA',"Freundliche Grüsse\n\nRACERFISH - your brand force");
define('MAIL_SENDER_NAME','RF Engine Automailer');
define('MAIL_SENDER_ADDRESS','contact@racerfish.com');
define('MAIL_FOOTER',"

Freundliche Grüsse
RACERFISH - your brand force

Dies ist eine automatisch aus dem RACERFISH System generierte E-Mail.
_________________________________________

RACERFISH AG
Forchstrasse 234
8032 Zürich

Phone +41 43 399 88 88
E-mail contact@racerfish.com
http://www.racerfish.com");

switch(APPLICATION_ENV){

    case "development":
        define('HTTP_ROOT', 'http://'.$_SERVER['HTTP_HOST']);
        define('DOCUMENT_ROOT', dirname(__FILE__));
        define('IMAGE_ROOT', HTTP_ROOT.'/images');
        define('ZEND_LIB_PATH', '/Users/diegopaladino/Sites/libraries/ZF-1.10.2/library');
        define('FB_APP_ID', '140991419441283');
        define('FB_APP_SECRET', '55f59162174f60033e04cb7a9399d06c');
        break;

    case "testing":
    case "staging":
        define('HTTP_ROOT', 'http://'.$_SERVER['HTTP_HOST']);
        define('DOCUMENT_ROOT', dirname(__FILE__));
        define('IMAGE_ROOT', HTTP_ROOT.'/images');
        define('ZEND_LIB_PATH', '/home/paladise/library');
        define('ADMIN_MAILTO', 'paladino@racerfish.com');
        define('FB_APP_ID', '554646067916760');
        define('FB_APP_SECRET', 'ad147a64d9c70b6f55e4985470edddb7');
    break;
}


// Ensure library/ is on include_path
set_include_path(implode(PATH_SEPARATOR, array(
    realpath(APPLICATION_PATH . '/../library'),
    ZEND_LIB_PATH,
    get_include_path(),
)));

/** Zend_Application */
require_once 'Zend/Application.php';

// Create application, bootstrap, and run
$application = new Zend_Application(
    APPLICATION_ENV,
    APPLICATION_PATH . '/configs/application.ini'
);
$application->bootstrap()
            ->run();