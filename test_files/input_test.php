<?php
/**
 * Test file to demonstrate a method of
 * retrieving traffic data from an RSS feed.
 */
/** include the PEAR XML_RSS class package */
require_once "XML/RSS.php";

/**
 * Trim a string and return the Road number
 */
function getRoadNumber($str) {
	$str = explode(" ", $str);
	return trim($str[0]);
}


//$rss = new XML_RSS("http://localhost/dev/rss/output_test.php");
//$rss = new XML_RSS("http://feeds.digg.com/digg/popular.rss");
$rss = new XML_RSS("http://www.highways.gov.uk/rssfeed/SouthWest.xml");
$rss->parse();

// echo "<pre>"; print_r($rss); echo "</pre>";

echo "<h1>Headlines from <a href=\"{$rss->channel['link']}\">{$rss->channel['title']}</a></h1>" . PHP_EOL;
echo "<ul>" . PHP_EOL;
if (isset($_GET['road'])) {
	foreach ($rss->getItems() as $item) {
		if (strtolower(getRoadNumber($item['title'])) == strtolower($_GET['road'])) {
			echo "<li>";
			echo "<div>";
			echo "<a href=\"" . $item['link'] . "\">" . $item['title'] . "</a><br />";
			echo $item['description'];
			echo "</div>";
			echo "</li>" . PHP_EOL;
		}
	}
}
else {
	foreach ($rss->getItems() as $item) {
		echo "<li>";
		echo "<div>";
		echo "<a href=\"" . $item['link'] . "\">" . $item['title'] . "</a><br />";
		echo $item['description'];
		echo "</div>";
		echo "</li>" . PHP_EOL;
	}
}
echo "</ul>" . PHP_EOL;
?>