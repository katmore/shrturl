<?php
/**
 * File:
 *    shrt-config.php
 *
 * Purpose:
 *    shrturl configuration file
 * 
 * For shrturl usage instructions:
 *    see README.txt
 *    see php files in 'examples' folder:
 *       these are applicable for *any* HTTP/REST client in any language/environment 
 * 
 */

/*
 * PDO connection parameters
 */
$config["pdo"]["dbname"] = "shrt"; //mysql schema
$config["pdo"]["host"] = "localhost"; //mysql host
$config["pdo"]["dsn"] = 'mysql:host='.$config["my_host"].';dbname='.$config["my_dbname"]; //PDO dsn
$config["pdo"]["user"] = "shrt"; //mysql username
$config["pdo"]["pass"] = "";  //mysql password
$config["pdo"]["options"] = null;

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
$config["url_base"]["default"] = 'https://example.com/shrt/shrt.php?code=';

/*
 * if you want a REALLY short URL, consider the following:
 * url_base when using something like
 * apache's mod_rewrite directives
 *
 * the following is an example .htaccess for use with the above url_base
 # RewriteEngine On
 #
 # RewriteCond %{REQUEST_FILENAME} -f [NC,OR]
 # RewriteCond %{REQUEST_FILENAME} -d [NC]
 # RewriteRule .* - [L]
 # 
 # RewriteRule ^(.*) /shrt.php?code=$1 [L]
 */
//$config["url_base"]["reallyshort"] = 'http://rlysh.rt';

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
$config["theme_path"] = __DIR__."/theme/".$config["error_theme"];

/*
 * $config["theme_url"]
 * the public URL to the error directory (for theme resources, such as an angry unicorn image)
 */
$config["theme_url"] = "https://example.com/shrt/theme/".$config["error_theme"];


return $config;







