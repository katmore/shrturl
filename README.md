# shrturl #
## a RESTful 'URL shortener' service written in PHP ##

### About ###
* The goal of this project is to make a web service that behaves similar to enterprise level URL shortening services (such as bit.ly).
* In many situations, it is more ideal than other URL shorteners, including bit.ly
* DOES NOT use an algorithm to convert an ID to the short url code
   * relies on a predifined list of codes existing in a database.
   * On a shorten request, it persistently associates a code with the target.
   * uses innoDB engine for transaction support.
   * It can redirect or display the target URL when provided with the 'code'.

* Terminology
   * 'code' or 'short code': the unique portion (token) contained in a short URL
	that serves as reference to full URL. The typically jibberish looking sequence of alpha numeric
	characters used in most URL shorteners.
   * short url: the full URL that includes the short code (ie: http://example.com/shrt.php?code=abc)
   * target url: the full URL that needs to be changed into a short URL

* Latest Version:
   * [http://github.com/katmore/shrturl]

* Documentation
   * [http://github.com/katmore/shrturl/wiki]

* License
   * 2-clause "Simplified BSD License" a.k.a. "FreeBSD License" - see LICENSE.txt
   * [https://github.com/katmore/shrturl/blob/master/LICENSE.txt]

* Author
   * Copyright (c) 2012-2014 Doug Bird, All Rights Reserved.

### Installation ###
   1. extract/copy this project somewhere
   2. have a MariaDB server ready with access for a user/pass 
   3. prepare database structure with url_table.sql
   
   ``` sh
   mysql < url_table.sql
   ```
 
   4. edit shrt-config.php appropriately
   ``` sh
   vi shrt-config.php
   ```
   5. choose ONE of the following methods to populate the database with random short codes
      1. run url_codes.sql
      ``` sh
      mysql < url_codes.sql
      ```
      
      2. optionally run 'make_codes.php' to add possible codes to your database, takes much longer but will create short code allocation order unique to your implementation
      ``` sh
      php make_codes.php
      ```
      
   6. at anytime it is safe to run 'make_codes.php" run on a live running implementation to create more available short codes
     
      ``` sh
      php make_codes.php
      ```
	
### REST API usage ###
	* Redirect to target URL
		* GET REQUEST METHOD
         > https://example.com/shrt.php?code=abc
	* where 'abc' is the code returned by "Shorten URL" request
		
	* Display short URL, Create if it does not exist
		* POST REQUEST METHOD
         > https://example.com/shrt.php?target=http://my_really_long_uri
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
		{"shrt":{"target":['http://target_url_1','http://target_url_2','http://etc']}}
      ```
      
		* adding a query var named 'POST' to the query string to will also invoke 'POST' (if using GET REQUEST METHOD)
		

### Deployment Guidelines ###
* use 2 different FQDNs (fully qualified domain names) for this service
   1. FQDN for 'end use' the short code, such as: 
      > http://rlysh.rt
   2. FQDN for API calls to short code service, such as:
      > https://shrturl.example.com
* example:
   1. on the 'end use' FQD
      1. install/configure project as described in installation section of this document
      2. configure a url_base in addition to the 'default'
      > see url_base section in shrt-config-example.php
	* contains example of .htaccess or equivelent configuration for web server
	* http://rlysh.rt/abc will work now
	* where 'abc' is the short code provided by previous call to short API
				
   2. on the API FQD use as described in usage section
   > in API requests to obtain a short code ensure that:
   > provide a url_base parameter in query that corresponds to the 'end use' 
   > that is set in your shrt-config-example.php
				
		



	
