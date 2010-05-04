<?php
/** Example File demonstrating the use of the RSS class
 * @author Stuart Taylor <stuarttaylor79@gmail.com>
 * @copyright Copyright 2008 Stuart Taylor
 * @package RSS
 */
/** Example include statement */
include "../RSS.class.php";

// Example 1 - Setting optional parameters at class instantiation 
$rss = new RSS(
	"GoUpstate.com News Headlines",
	"The latest news from GoUpstate.com, a Spartanburg Herald-Journal Web site.",
	"http://www.goupstate.com",
	"0.91",
	"ISO-8859-1"
);

//Example 2 - Setting each property indivudually
$rss = new RSS();
$rss->channel->setTitle("RSS Class Example Feed");
$rss->channel->setDescription("The latest news from our web site.");
$rss->channel->setLink("http://www.google.co.uk");
$rss->setVersion("0.91");
$rss->setEncoding("ISO-8859-1");

$rss->channel->setCopyright('Copyright 2008, Stuart Taylor');
$rss->channel->setGenerator('RSS Class by Stuart Taylor');




// Generate a new RSS_Item object
// Method 1 - Instantiate a new RSS_Item object
//  and specify the required elements in a single pass.

$item = new RSS_Item (
	"This is an RSS_Item object Example Number 1", 
	"This is the description for the RSS_Item object Number 1. It was set as a parameter when the RSS class was instantiated.", 
	"http://localhost/dev/rss/test.php#1"
);
$item->setAuthor('stuarttaylor79@gmail.com <Stuart Taylor>');

// Then append the RSS_Item object 
// to the RSS object
$rss->appendItem($item);



// Method 2 - Instantiate a new RSS_Item object
//  then specify the required elements individually.

$item = new RSS_Item;
$item->setTitle("This is an RSS_Item object Example Number 2");
$item->setDescription("This is the description for the RSS_Item object Number 2. It was set as a single command");
$item->setLink("http://localhost/dev/rss/test.php#2");
$item->setAuthor('stuarttaylor79@gmail.com <Stuart Taylor>');

// Then append the RSS_Item object 
// to the RSS object.
$rss->appendItem($item);


// Method 3 - Instantiate a new RSS_Item object and
// specify the required elements, while appending
// to the RSS object in a single pass. Returning an object
// to a variable allows further changes or additions to be made.

$item = $rss->appendItem(new RSS_Item("This is an RSS_Item object Example Number 3", "It was created using a single command to create a new RSS_Item object, and append it to the RSS object in a single pass.", "http://localhost/dev/rss/test.php#3"));
$item->setAuthor('stuarttaylor79@gmail.com <Stuart Taylor>');

// Change the MIME type to instruct the
// browser it is receiving RSS XML content.
header('Content-Type: application/rss+xml');

// Output the RSS object to the browser.
echo $rss->saveRSS();
?>
