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
 * path to folder with shrt include files
 */
$shrtconfig["include_path"] = "./include"; 

/*
 * mysql connection parameters
 */
$shrtconfig["my_user"] = "shrt"; //mysql username
$shrtconfig["my_pass"] = "";  //mysql password
$shrtconfig["my_dbname"] = "shrt"; //mysql database
$shrtconfig["my_host"] = "localhost"; //mysql host

/*
 * define a url base for short URLs
 *    the 'default' one is the one displayed by default after a successful POST
 *    
 *    a relatively failsafe value for "default" is:
 *       "http://example.com/shrt/shrt.php?code=" 
 *       (where example.com/shrt points to this project's install)
 *    
 */
$shrtconfig["url_base"]["default"] = 'http://example.com/shrt/shrt.php?code=';

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
$shrtconfig["url_base"]["reallyshort"] = 'http://rlysh.rt';

/*
 * access control: (who can add new URLs to shorten)
 *    it's just IP based
 * 
 *    i suggest keeping this simple: 
 *       if you want a public facing URL shortener
 *       write a backend client to this service
 *       and add the IP(s) of your webservers here 
 */
$shrtconfig["allow_POST"][] = "192.168.0.1"; //IP address of 1st device allowed to add URLs to shorten
$shrtconfig["allow_POST"][] = "192.168.0.2"; //IP address of 2nd device allowed to add URLs to shorten


/*
 * the m-type for the output of this script
 */
$shrtconfig["Content-type"] = "text/plain";


/*
 * error reporting options
 */
$shrtconfig["change_error_reporting"] = true;
$shrtconfig["error_reporting"] = E_ALL;

/*
 * system path to error folder (can be relative)
 *    no trailing slash
 */
$shrtconfig["error_path"] = "error";

/*
 * the "theme" of error messages
 *    'unicorn' is the only one that included by default
 *    it displays an angry unicorn along with brief description of error
 */
$shrtconfig["error_theme"] = "unicorn";

/*
 * the public URL to the error directory (for theme resources, such as an angry unicorn image)
 */
$shrtconfig["error_url_base"] = "http://example.com/shrt/error";
















