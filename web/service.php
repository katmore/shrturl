<?php
/**
 * Web Service to Shorten a URL
 */

use \Katmore\Shrt\Service;
use \Katmore\Shrt\Factory;

call_user_func(function($config) {
   
   if(is_string($config)) {
      $config = require($config);
   }elseif(!is_array($config)) {
      $config = require("config.php");
   }
   
   if (!is_array($config)) {
      Service::errorSession(500,"Server Error");
      return;
   }
   
   if (isset($_REQUEST) && !empty($_REQUEST['url_base']) && !empty($config['url_base'][$_REQUEST['url_base']])) {
      $codeURLPrefix = $config["url_base"][$_REQUEST['url_base']];
   } else {
      $codeURLPrefix = $config["url_base"]["default"];
   }
   
   Service::setThemePath($config["theme_path"]);
   Service::setThemeURL($config["theme_url"]);
   
   (new Service(
      new Factory(
         Factory::loadPDO($config["pdo"])
      ),
      $codeURLPrefix,
      $_REQUEST,
      $_SERVER['REQUEST_METHOD']
   ));

},(isset($config))?$config:null);/*end self-executing anonymous function*/














