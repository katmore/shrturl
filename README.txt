shrturl is a RESTful 'URL shortener' service written in PHP

Social:
	* github.com/katmore/shrturl
	* shrturl@katmore.com

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
		* see url_code.sql for schema and example list of codes
	* edit shrt-config.php appropriately
	
Usage:
	* Display full (target) URL
		* GET REQUEST: http://example.com/shrt.php?code=abc
	* Shorten URL
		* POST REQUEST: http://example.com/shrt.php?target=http://my_really_long_uri
			* add a get var named 'POST' to the query string to will also invoke 'POST'
			
Notes:
I created this because I was not satisfied with the way codes were provided to
me in existing URL shortening scripts. I did not like code's created by
algorithms because of the following two problems. One, they are typically
sequential: they reveal how many target URLs your system is currently storing.
Two, codes can end up being comprised of  weird, confusing, or unwanted
character combinations.I evaluated the possibility of possibly creating an algo
that generated codes in a way that is non-sequential. Should non-sequential
id-to-code/code-to-id algo providen feasible, I would still be left with my 
second problem. Also, the algo does not eliminate the need for persistent
target URL storage. My solution was to eliminate the algo (as it is 
superfluous), and pedefine a set of codes. The url_code.sql provided with this
project has ALL english dictionary words removed, or at least this was the
intention. It also might be desirable to remove 'confusing' character
combinations (such as repeating chars, all numeric ones, etc) but I have not
done so here.

	