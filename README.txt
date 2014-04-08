shrturl is a RESTful 'URL shortener' service written in PHP

Latest Version:
	* http://github.com/katmore/shrturl

Documentation:
	* http://github.com/katmore/shrturl/wiki

License:
	* 2-clause "Simplified BSD License" or "FreeBSD License" - see LICENSE.txt

Description:
	* It DOES NOT use an algorithm to convert an ID to the short url code
	* It relies on a predifined list of codes existing in a database.
	* On a shorten request, it persistently associates a code with the target.
	* It uses MySQL innoDB engine for transaction support.
	* It will display the target URL when provided with the 'code'.

Definitions:
	* 'code' or 'short code': the unique portion (token) contained in a short URL
	that serves as reference to full URL. The typically jibberish looking sequence of alpha numeric
	characters used in most URL shorteners.

Installation:
	* extract/copy this project somewhere
	* have a mysql database ready with access for a user/pass
	* create database structure with url_table.sql
	* edit shrt-config.php appropriately
	* run url_codes.sql OR
	* optionally run 'make_codes.php' to add possible codes to your database, takes much longer but:
		* will create short code allocation order unique to your implementation
		* can safely run on a live running implementation to create more available short codes
	
REST API usage:
	* Display full (target) URL
		* GET REQUEST: http://example.com/shrt.php?code=abc
		* where 'abc' is the code returned by "Shorten URL" request
		
	* Shorten URL
		* POST REQUEST: http://example.com/shrt.php?target=http://my_really_long_uri
		* adding a query var named 'POST' to the query string to will also invoke 'POST'
		
	* Change Target URL
		* POST REQUEST: http://example.com/shrt.php?changeTarget=http://a_different_really_long_uri&code=abc
		* adding a query var named 'POST' to the query string to will also invoke 'POST'
	
	* Shorten Multiple URLs
		* POST REQUEST: http://example.com/shrt.php?request={JSON_document}
		* where 'target' is a valid JSON document with the following structure:
		* {"shrt":{"target":['http://target_url_1','http://target_url_2','http://etc']}}
		* adding a query var named 'POST' to the query string to will also invoke 'POST'
		

Examples:
see the "examples" folder included in this project

Notes:
The goal of this project is to make a web service that behaves similar to enterprise level URL shortening services (such as bit.ly).
Learn more at http://github.com/katmore/shrturl/wiki

Practical Implementation Hints:
	It is recommended (but not required) to use 2 different FQDs (fully qualified domains) for this service.
		1) FQD for 'end use' the short code, such as: 
			http://examp.le
		2) FQD for API calls to short code service, such as:
			https://shrturl.example.com
	For Example:
		1) on the 'end use' FQD
			* install/configure project as described in installation section of this document
			* additionally, configure your HTTP server with the equivilent of the following .htaccess:
				RewriteEngine on
				RewriteRule ^([^/\.]+)/?$ /shrt.php?code=$0 [L]
			* http://examp.le/abc will work now
				where 'abc' is the short code provided by previous call to short API
				
		2) on the API FQD use as described in usage section
		



	
