shrturl is a RESTful 'URL shortener' service written in PHP

Social:
	* github.com/katmore/shrturl
	* shrturl@katmore.com

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

Installation:
	* extract/copy this project somewhere
	* have a mysql database ready with access for a user/pass
	* export a predefined list of 'codes' to a table named 'url'
		* see url_table.sql for schema
	* edit shrt-config.php appropriately
	* run url_codes.sql OR
	* optionally run 'make_codes.php' to add possible codes to your database
	
Usage:
	* Display full (target) URL
		* GET REQUEST: http://example.com/shrt.php?code=abc
	* Shorten URL
		* POST REQUEST: http://example.com/shrt.php?target=http://my_really_long_uri
			* add a get var named 'POST' to the query string to will also invoke 'POST'
			
Notes:
I created this project because I was not satisfied with the way short URLs were
generated in existing shortening scripts.



	