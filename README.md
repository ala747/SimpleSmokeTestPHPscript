Simple Smoke Test PHP Script
========================

This is a quick&amp;dirty script to run simple smoke tests from the console using PHP CLI + cURL.

Requests a given URL and checks if the response HTTP Status is < 400 (in other words, anything but an error). It can also parse the response body to crawl linked URLs (it looks into script tags for JS, link tags for CSS and favicons and img tags) which will be tested too.

## Documentation
	-u <URL to test>       Required. The URL to check.
	-i [script|link|img]   Optional. HTML tag to look for child URLs to test. Specify one -i for each tag or none to collect all three.
	-v                     Optional. Verbose output
	-h                     Optional. Shows this help message

## Basic Usage
	./smokeTest.php -u <URL to test> [-i script] [-i link] [-i img] [-v]

## Examples
### Default
	 $ ./smokeTest.php -u http://www.olx.com
	 .........
	 
	 YAY! All tests passed OK!
	 


### Verbose
	$ ./smokeTest.php -u http://www.olx.com -v
	Testing: http://www.olx.com
	  OK   Status 200
	Testing: http://static03.olx-st.com/js/cookies-110.js
	  OK   Status 200
	Testing: http://static03.olx-st.com/js/common-110.js
	  OK   Status 200
	Testing: http://static02.olx-st.com/js/home-110.js
	  OK   Status 200
	Testing: http://static01.olx-st.com/js/ipaddialog-110.js
	  OK   Status 200
	Testing: http://static01.olx-st.com/css/v5/base-110.css
	  OK   Status 200
	Testing: http://static03.olx-st.com/images/favicon.ico
	  OK   Status 200
	Testing: http://static03.olx-st.com/images/logos/logo-en.png
	  OK   Status 200
	Testing: http://static02.olx-st.com/images/iphoneapp/olxmobile.jpg
	  OK   Status 200

### Only Document + Script Tags + Link Tags and Verbose
	$ ./smokeTest.php -u http://www.olx.com -i script -i link -v 
	Testing: http://www.olx.com
	  OK   Status 200
	Testing: http://static03.olx-st.com/js/cookies-110.js
	  OK   Status 200
	Testing: http://static03.olx-st.com/js/common-110.js
	  OK   Status 200
	Testing: http://static02.olx-st.com/js/home-110.js
	  OK   Status 200
	Testing: http://static01.olx-st.com/js/ipaddialog-110.js
	  OK   Status 200
	Testing: http://static01.olx-st.com/css/v5/base-110.css
	  OK   Status 200
	Testing: http://static03.olx-st.com/images/favicon.ico
	  OK   Status 200


