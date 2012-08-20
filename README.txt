shorturl is a 'URL shortener' service written in PHP
	* It does NOT use an algorithm to convert an ID to the short url code
	* It relies on a predifined list of codes existing in a database.
	* On a shorten request, it associates one of these codes persistently with the target url.
	* It uses MySQL innoDB engine for transaction support.
	* It will display the target URL when provided with the 'code'.

Installation:
	* extract/copy this project somewhere
	* have a mysql database ready with access for a user/pass
	* export a predefined list of 'codes' to a table named 'url'
		* see url_code.sql for schema and example list of codes
	* edit shrt-config.php appropriately
	
Usage:
	* Display full (target) URL
		* http://example.com/shrt.php?code=abc
	* Shorten URL
		* http://example.com/shrt.php?target=http://example.com/my_really_long_uri