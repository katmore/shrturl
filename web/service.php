<?php
/**
 * Web Service to Shorten a URL
 */
$app_dir = __DIR__.'/../app';

call_user_func(function($config) use ($app_dir) {
   
   require_once "$app_dir/autoload.php";
   
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














