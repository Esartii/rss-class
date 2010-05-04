<?php
/**
 * @author Stuart Taylor <stuarttaylor79@gmail.com>
 * Testing svn authentication
 */

/** Include the RSS generator class file */
include "../RSS.class.php";

/** Instantiate a new RSS object
*  and specify the required channel elements
*  in a single pass.
*/
$rss = new RSS(
		'Example Test RSS Feed', 
		'This is my test RSS feed running from my custom php RSS class.', 
		'http://' . $_SERVER['SERVER_NAME'] . $_SERVER['PHP_SELF']
	);
// Set optional elements for the Channel node for RSS v2.0
//$rss->channel->setLanguage("en-GB");
//$rss->channel->setWebMaster("stuarttaylor79@gmail.com (Stuart Taylor)");
//$rss->channel->setDocs("http://blogs.law.harvard.edu/tech/rss");
//$rss->channel->setGenerator("Stuart Taylor's RSS Tester rss.class.php");
$rss->channel->setImage("http://localhost/stuart/images/pir_button.jpg");

//$rss->setModule('Dublin Core', 'dc', 'http://purl.org/dc/elements/1.1/');
$rss->channel->setCustomElement('dc', 'type', 'Test Feed Data');

// Iterate through each item, and append 
// to the RSS feed using the RSS_Item class.
for ($i = 1; $i < 4; $i++) {
	
	// Generate a new RSS_Item object
	/* Method 1 - Instantiate a new RSS_Item object
	*  and specify the required elements in a single pass.
	*/
	$item = new RSS_Item (
		"This is an RSS_Item object Number {$i}", 
		"This is the description for the RSS_Item object Number {$i}", 
		"http://localhost/dev/rss/test.php#{$i}"
	);
	
	// Then append the RSS_Item object 
	// to the RSS object
	$rss->appendItem($item);
}

for ($i = 4; $i < 7; $i++) {
	/* Method 2 - Instantiate a new RSS_Item object
	*  then specify the required elements individually.
	*/
	$item = new RSS_Item;
	$item->setTitle("This is an RSS_Item object Number {$i}");
	$item->setDescription("This is the description for the RSS_Item object Number {$i}");
	$item->setLink("http://localhost/dev/rss/test.php#{$i}");
	
	// Then append the RSS_Item object 
	// to the RSS object.
	$rss->appendItem($item);
}

	/* Method 3 - Instantiate a new RSS_Item object and
	* specifying the required elements, while appending
	* to the RSS object in a single pass. Return to a variable
	* allows for further changes or additions to be made.
	*/
$item = $rss->appendItem(new RSS_Item("This is the last item Number 7", "It was created using a single line of PHP code.", "http://localhost/dev/rss/test.php#7"));

// Change the MIME type to instruct the
// browser it is receiving RSS XML content.
if ($_GET['debug'] != true) {
	header('Content-Type: application/rss+xml');	
}
else {
	header('Content-Type: text/plain');
	print_r($rss);
}

// Output the RSS object to the browser.
echo $rss->saveRSS();
?>
