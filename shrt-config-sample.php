<?php
/**
 * File:
 * 	shrt-config.php
 *
 * Purpose:
 * 	shrturl configuration file
 * 
 * 
 */

/*
 * path to folder with shrt include files
 */
$shrtconfig["include_path"] = "./include"; 

/*
 * base URL used for displaying the short URL:
 * 	 a URL that is accessible by shrt.php
 *	 or a copy with access to the same database
 */
$shrtconfig["url_base"] = 'http://www.example.com/shrt.php?code=';

/*
 * the following is a replacement url_base when using something like
 * apache's mod_rewrite directives
 *
 */
//$shrtconfig["url_base"] = 'http://www.example.com/';
/*
 * the following is an example .htaccess for use with the above url_base
 # RewriteEngine On
 #
 # RewriteCond %{REQUEST_FILENAME} -f [NC,OR]
 # RewriteCond %{REQUEST_FILENAME} -d [NC]
 # RewriteRule .* - [L]
 # 
 # RewriteRule ^(.*) /shrt.php?code=$1 [L]
 */

/*
 * mysql connection parameters
 */
$shrtconfig["my_user"] = "shrt"; //mysql username
$shrtconfig["my_pass"] = "";  //mysql password
$shrtconfig["my_dbname"] = "shrt"; //mysql database
$shrtconfig["my_host"] = "localhost"; //mysql host

/*
 * the m-type for the output of this script
 */
$shrtconfig["Content-type"] = "text/plain";

/*
 * error reporting options
 */
$shrtconfig["change_error_reporting"] = true;
$shrtconfig["error_reporting"] = E_ALL;



