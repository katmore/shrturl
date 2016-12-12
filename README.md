# shrturl #
*a URL shortener webservice*

Shrturl is a RESTful web service that behaves similar to enterprise level URL shortening services (such as bit.ly).

* Latest Version:
   * http://github.com/katmore/shrturl

* Documentation
   * http://github.com/katmore/shrturl/wiki

## Terminology ##
 * **code** or **short code**: the unique portion (token) contained in a short URL
that serves as reference to full URL. The typically jibberish looking sequence of alpha numeric
characters used in most URL shorteners.
 * **short url**: the full URL that includes the short code (ie: http://example.com/shrt.php?code=abc)
 * **target url**: the full URL that needs to be changed into a short URL

## Installation ##
   1. extract/copy the **shrturl project** somewhere
   ```bash
   git clone https://github.com/katmore/shrturl.git
   cd shrturl
   ```
   
   2. create autoloader and resolve dependencies using Composer...
   ```bash
   composer update
   ```
   
   3. run the db-install.php script...
   ```bash
   php bin/db-install.php
   ```
   
   4. copy *app/config/shrturl/mysql-sample.php* to **mysql.php** and edit...
   ```bash
   cp app/config/shrturl/mysql-sample.php app/config/shrturl/mysql.php
   vi app/config/shrturl/mysql.php
   ```
   
   5. populate the database with initial set of randomly generated short codes...
   ```bash
   php bin/make-codes.php 1000
   ```
   
## Webservice Usage ##
* Redirect to target URL:

   `https://example.com/shrt.php?code=abc`

	(where 'abc' is the code returned by "Shorten URL" request)
	
* Display short URL, Create if it does not exist
	* POST REQUEST METHOD
      	`https://example.com/shrt.php?target=http://my_really_long_uri`
      * adding a query var named 'POST' to the query string to will also invoke 'POST' (if using GET REQUEST METHOD)
         > https://example.com/shrt.php?POST&target=http://my_really_long_uri
   	* add a query var and value for 'url_base' if you want to display a short url with a base
   		> URL other than what is configured in the 'default' section of shrt-config.
         > A value for 'url_base' should correspond with a config var in shrt-config.php
   		
   * POST REQUEST METHOD
      > https://example.com/shrt.php?target=http://my_really_long_uri&url_base=reallyshort
		* would display/create: http://rlysh.rt/abc
      * see example in shrt-config-example.php
         > [https://github.com/katmore/shrturl/blob/master/shrt-config-example.php]
	
* Change Target URL
	* POST REQUEST METHOD
      	> https://example.com/shrt.php?changeTarget=http://a_different_really_long_uri&code=abc
	* adding a query var named 'POST' to the query string to will also invoke 'POST' (if using GET REQUEST METHOD)

* Shorten Multiple URLs
	* POST REQUEST METHOD
   > https://example.com/shrt.php?request={JSON_document}
	* where 'target' is a valid JSON document with the following structure:

   ``` json
	{"shrt":{"target":["http://target_url_1","http://target_url_2","http://etc"]}}
   ```
   
	* adding a query var named 'POST' to the query string to will also invoke 'POST' (if using GET REQUEST METHOD)
		

## Webservice Deployment Suggestions ###
* use 2 different FQDNs (fully qualified domain names) for this service
   * FQDN #1 for 'end use' the short code, such as: 
   > http://rlysh.rt

   * FQDN #2 for API calls to short code service, such as:
   > https://shrturl.example.com

* for example:
   1. on the 'end use FQDN' (#1)
      1. install/configure project as described in installation section of this document
      2. configure a url_base in addition to the 'default'
      > see url_base section in shrt-config-example.php
	* contains example of .htaccess or equivelent configuration for web server
	* http://rlysh.rt/abc will work now
	* where 'abc' is the short code provided by previous call to short API
				
   1. on the 'API FQDN' (#2) use as described in usage section
   > in API requests to obtain a short code ensure that:
   > provide a url_base parameter in query that corresponds to the 'end use' 
   > that is set in your shrt-config-example.php
				
## Legal ##
   * License: "2-clause Simplified BSD License", see [LICENSE.txt](https://github.com/katmore/shrturl/blob/master/LICENSE.txt)
   * Copyright (c) 2012-2014 Doug Bird, All Rights Reserved.
