<?php
use Shrturl\Config;
(function() {
   
   $config = Config::loadAssoc("service");
   
   if (isset($_REQUEST) && !empty($_REQUEST['url_base']) && !empty($config['url_base'][$_REQUEST['url_base']])) {
      $codeURLPrefix = $config["url_base"][$_REQUEST['url_base']];
   } else {
      $codeURLPrefix = $config["url_base"]["default"];
   }
   
   Service::setThemePath($config["theme_path"]);
   Service::setThemeURL($config["theme_url"]);
   
   (new Service(
      new Factory(
         Factory::loadPDO(Config::loadAssoc("mysql"))
      ),
      $codeURLPrefix,
      $_REQUEST,
      $_SERVER['REQUEST_METHOD']
   ));

})();














