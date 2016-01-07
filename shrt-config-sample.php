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
 * mysql connection parameters
 */
$config["my_user"] = "shrt"; //mysql username
$config["my_pass"] = "";  //mysql password
$config["my_dbname"] = "shrt"; //mysql database
$config["my_host"] = "localhost"; //mysql host

/*
 * define a url base for short URLs
 *    the 'default' one is the one displayed by default after a successful POST
 *    
 *    a relatively failsafe value for "default" is:
 *       "http://example.com/shrt/shrt.php?code=" 
 *       (where example.com/shrt points to this project's install)
 *    
 */
$config["url_base"]["default"] = 'http://example.com/shrt/shrt.php?code=';

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
$config["url_base"]["reallyshort"] = 'http://rlysh.rt';

/*
 * access control: (who can add new URLs to shorten)
 *    it's just IP based
 * 
 *    i suggest keeping this simple: 
 *       if you want a public facing URL shortener
 *       write a backend client to this service
 *       and add the IP(s) of your webservers here 
 */
$config["allow_POST"][] = "192.168.0.1"; //IP address of 1st device allowed to add URLs to shorten
$config["allow_POST"][] = "192.168.0.2"; //IP address of 2nd device allowed to add URLs to shorten


/*
 * the m-type for the output of this script
 */
$config["Content-type"] = "text/plain";


/*
 * error reporting options
 */
$config["change_error_reporting"] = true;
$config["error_reporting"] = E_ALL;

/*
 * the "theme" of error messages
 *    'unicorn' is the only one that included by default
 *    it displays an angry unicorn along with brief description of error
 */
$config["error_theme"] = "unicorn";

/*
 * system path to error folder (can be relative)
 *    no trailing slash
 */
$config["theme_path"] = __DIR__."/error/".$config["error_theme"];



/*
 * the public URL to the error directory (for theme resources, such as an angry unicorn image)
 */
$config["theme_url"] = "http://example.com/shrt/theme/".$config["error_theme"];








return $config;







