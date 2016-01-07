<?php
/**
 * Web Service to Shorten a URL
 */

use \Katmore\Shrt\Service;
use \Katmore\Shrt\Factory;
use Katmore\Shrt\Katmore\Shrt;

$config = require("shrt-config.php");

$urlBaseConfig = "default";

if (isset($_REQUEST) && !empty($_REQUEST['url_base']) && isset($config['url_base'][$_REQUEST['url_base']])) {
   $urlBaseConfig = $_REQUEST['url_base'];
}

Service::setThemePath($config["error_theme"]);
Service::setThemeURL($config["error_url_base"]);

(new Service(
   new Factory(
      new \PDO(
         'mysql:host='.$config["my_host"].';dbname='.$config["my_dbname"], 
         $config["my_user"], 
         $config["my_pass"]
      )
   ),
   $config["url_base"][$urlBaseConfig],
   $_REQUEST,
   $_SERVER['REQUEST_METHOD']
));
















