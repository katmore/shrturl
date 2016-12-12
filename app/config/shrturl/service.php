<?php
return (function() {
   $url_base_prefix = "https://example.com/shrt";
   /*
    * $config["url_base"]["default"]
    * define a url base for short URLs
    *    the 'default' one is the one displayed by default after a successful POST
    *
    *    a relatively failsafe value for "default" is:
    *       "https://example.com/shrt/shrt.php?code="
    *       (where example.com/shrt points to this project's install)
    *
    */
   $config["url_base"]["default"] = "$url_base_prefix/shrt.php?code=";
   
   /*
    * $config["theme"]
    * the "theme" of error messages
    *    'unicorn' and 'emote' themes are included by default
    *    unicorn displays an angry unicorn along with brief description of error
    *    emote displays a sad face along with a brief description of the error
    */
   $config["theme"] = "unicorn";
   
   /*
    * $config["theme_path"]
    * system path to error folder (can be relative)
    *    no trailing slash
    */
   $config["theme_path"] = __DIR__."/../Resources/template/error_theme/".$config["error_theme"];
   
   /*
    * $config["theme_url"]
    * the public URL to the error directory (for theme resources, such as an angry unicorn image)
    */
   $config["theme_url"] = "$url_base_prefix/asset/error_theme/".$config["error_theme"];

   return $config;
})();