<?php
/**
 * RSS Generator class package to generate compliant and valid
 * XML DOM for RSS syndicated feeds
 *
 * This package contains classes which manipulate a DOM
 * DOM object to create an RSS feed for a website or any
 * syndicated content. The feed that is generated is
 * compliant with the RSS standards and validates
 * as well-formed XML data in accordance with the
 * standards as set by the W3C.
 *
 * RSS feeds can be created in accordance
 * with any of the following RSS standards:
 * - 0.90 (source: {@link http://www.purplepages.ie/RSS/netscape/rss0.90.html 0.90 specification})
 * - 0.91 (source: {@link http://backend.userland.com/rss091 0.91 specification})
 * - 1.0 (source: {@link http://purl.org/rss/1.0/ 1.0 specification})
 * - 2.0 (source: {@link http://cyber.law.harvard.edu/rss/rss.html 2.0 Specification})
 *
 * Validation of RSS feeds can be carried out using
 * the W3C Validation service at {@link http://validator.w3.org/feed/}.
 *
 * To make use of this class, insert a file include
 * statement into your php file as follows:
 * {@example examples/rss_example.php 8 1}
 *
 * @author Stuart Taylor <stuarttaylor79@gmail.com>
 * @copyright Copyright 2008 Stuart Taylor
 * @package RSS
 * @version 2.3
 *
 * This program is free software; you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation; either version 2 of the License, or
 * (at your option) any later version.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with this program; if not, write to the Free Software
 * Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston,
 * MA 02110-1301, USA.
 *
 * @todo Implement the use of the RSS_Module class
 * @todo Check the problems validating the atom:link element
 */
/**
 * RSS Class creates and manipulates the the XML DOM
 * to deliver valid XML formatted data as an RSS feed.
 *
 * The following gives two examples of how a new
 * {@link RSS} object can be created.
 *
 * {@example examples/rss_example.php 10 8}
 *
 * {@example examples/rss_example.php 19 7}
 *
 * @package RSS
 * @author Stuart Taylor <stuarttaylor79@gmail.com>
 */
class RSS
{
        // Private DOM objects
        /** The XML DOM object
         * @var DOMDocument
         */
        private $_dom;
       
        /** The <rss> DOM element object
         * @var DOMElement
         */
        private $_rss;
       
        /** The <channel> DOM element object
         * @var DOMElement
         */
        private $_channel;
       
        /** Version of RSS Standards used
         * @var string
         */
        private $_version;
       
        /** Document encoding type property
         * @var string
         */
        private $_encoding;
       
        // Public RSS objects
        /**
    * Variable to store a single {@link RSS_Channel} object
    * @var RSS_Channel Set using {@link appendChannel()}
    */
        public $channel;
       
        /**
    * Array to store multiple {@link RSS_Item} objects
    * @var RSS_Item Set using {@link appendItem()}
    */
        public $items = array();
       
        /**
    * Array to store multiple {@link RSS_Module} objects
    * @var RSS_Module
    */
        public $modules = array();
       
        /** Instantiates a new {@link RSS} object and a required
         * {@link RSS_Channel} child object.
         *
         * An {@link RSS_Channel} object is created regardless of
         * whether the the title, description or url
         * parameters are set. This ensures that a compliant RSS
         * document structure is produced.
         *
         * All parameters are optional, and can be set
         * individually to comply with the relevant chosen
         * RSS standard.
         * @param string Title of the feed
         * @param string Short description of the feed
         * @param string URL of the website or feed
         * @param string Version number of RSS standard
         * @param string XML document encoding
         */
        public function __construct($channelTitle = false, $channelDesc = false, $channelUrl = false, $rssVersion = '2.0', $encoding = 'UTF-8')
        {
                // Create a new DOM object
                
            	// TODO: Review if encoding is not specified. Review all 
            	// parameters and fixi if they are missing
                $this->_dom = new DOMDocument('1.0', $encoding);
                $this->setVersion($rssVersion);
                $this->setEncoding($encoding);

                // Optional Namespaces for some common RSS modules
                /*
                $atomMod = $this->setModule('Atom Feed', 'atom', "http://www.w3.org/2005/Atom");
                $ccMod = $this->setModule('Creative Commons', 'creativeCommons', "http://backend.userland.com/creativeCommonsRssModule");
                $dcMod = $this->setModule('Dublin Core', 'dc', "http://purl.org/dc/elements/1.1/");
                $rdfMod = $this->setModule('RDF', 'rdf', "http://www.w3.org/1999/02/22-rdf-syntax-ns#");
                $slashMod = $this->setModule('Slash', 'slash', "http://purl.org/rss/1.0/modules/slash/");
                $syMod = $this->setModule('RSS 1.0 Syndication Module', 'sy', "http://purl.org/rss/1.0/modules/syndication/");
                $torrentMod = $this->setModule('Bittorrent Module', 'torrent', "http://my79.dnsalias.org/schema/torrent");
                */
               
                switch ($rssVersion) {
                        case '0.90':
                                /** Add the required <rdf:RDF> element and set
                                 * the version attribute to RSS version 0.90 standards
                                 * {@link http://my.netscape.com/rdf/simple/0.9/}
                                 */
                                $this->_rss = $this->_dom->appendChild($this->_dom->createElement('rdf:RDF'));
                                $this->_rss->setAttribute('xmlns', "http://my.netscape.com/rdf/simple/0.9/");
                               
                                // Declare the RDF Namespace
                                $this->setModule('RDF', 'rdf', "http://www.w3.org/1999/02/22-rdf-syntax-ns#");
                                //$this->declareNamespace($this->modules['rdf'], $this->_rss);
                                break;
                               
                        case '1.0':
                        case '1.1':
                                /** Add the required <rdf:RDF> element and set
                                 * the version attribute to RSS version 1.0 standards
                                 * {@link http://web.resource.org/rss/1.0/spec}
                                 */
                                $this->_rss = $this->_dom->appendChild($this->_dom->createElement('rdf:RDF'));
                                $this->_rss->setAttribute('xmlns', "http://purl.org/rss/1.0/");
                               
                                // Declare the RDF Namespace
                                $this->setModule('RDF', 'rdf', "http://www.w3.org/1999/02/22-rdf-syntax-ns#");
                                //$this->declareNamespace($this->modules['rdf'], $this->_rss);
                                break;
                               
                        case '0.91':
                        case '0.92':
                        case '0.93':
                        case '0.94':
                        case '2.0':
                                /** Add the required <rss> element and set
                                 * the version attribute to RSS version 2.0 standards
                                 * {@link http://cyber.law.harvard.edu/rss/rss.html}
                                 */
                                $this->_rss = $this->_dom->appendChild($this->_dom->createElement('rss'));
                                $this->_rss->setAttribute('version', $rssVersion);
                               
                                // Declare the Atom Feed Namespace
                                $this->setModule('Atom Feed', 'atom', "http://www.w3.org/2005/Atom");
                                //$this->declareNamespace($this->modules['atom'], $this->_rss);
                                
                                break;
                }
               
                // Instantiate a new RSS_Channel object
                $this->appendChannel(new RSS_Channel($channelTitle, $channelDesc, $channelUrl));
        }
       
       
        // Class Properties
        // ==========
        /** Set the RSS standards Version property
         * @param string RSS standards version number
         */
        public function setVersion($val) {$this->_version = $val;}
        /** Set the XML document character set encoding name
         * as described in {@link http://www.w3.org/TR/REC-xml/#charencoding}.
         * @param string Character set encoding alias
         */
        public function setEncoding($val) {$this->_encoding = $val;}
        /** Returns the RSS standards Version
        * @return string
        */
        public function getVersion() {return $this->_version;}
        /** Returns the RSS Encoding property
        * @return string
        */
        public function getEncoding() {return $this->_encoding;}
       
        // Class Methods
        // =========
        /**
        * Returns the total number of {@link RSS_Item} objects
        * @return integer
        */
        public function getNumItems() {return count($this->items);}
       
        /**
        * Returns the total number of {@link RSS_Module} objects
        * @return integer
        */
        public function getNumModules() {return count($this->modules);}
       
        /**
         * Returns an {@link RSS_Item} object from the items collection
         * @param integer Array index reference of the item to return
         * @return RSS_Item
         */
        public function getItem($index) {return ($this->getNumItems() > 0) ? $this->items[$index] : false;}

        /**
         * Returns an array of {@link RSS_Item} objects from the items collection
         * @return array
         */
        public function getItems() {return ($this->getNumItems() > 0) ? $this->items : false;}
       
        /**
         * Returns an {@link RSS_Module} object from the modules collection
         * @param string Name of the Module to return
         * @return RSS_Module
         */
        public function getModule($name) {return ($this->getNumModules() > 0) ? $this->modules[$name] : false;}
       
       
       
        /**
         * Returns an array of {@link RSS_Module} objects from the modules collection
         * @return array
         */    
        public function getModules() {return ($this->getNumModules() > 0) ? $this->modules : false;}
       
        /** Generic function to create and append a child
         * element to a parent element.
         * NOTE - This method can be used to create all
         * the DOM elements EXCEPT the initial wrapper RSS element
         * @param string The name of the element to be created
         * @param string The value of the new element
         * @param DOMElement The parent node onto which the new element will be appended
         * @return DOMElement
         */
        private function createElement($elementType, $elementValue = '', $parentNode)
        {
                $newElement = $this->_dom->createElement($elementType, $elementValue);
               
                //var_dump($elementType . "|" . $parentNode->tagName);
               
                return $parentNode->appendChild($newElement);
        }
       
        /** Create an new attribute of an element
         * @param DOMElement The element object to which the new attribute will be applied
         * @param string The name of the new attribute
         * @param string The value of the new attribute
         * @return DOMAttr
         */
        private function setElementAttribute($element, $name, $value)
        {
                $newAttr = $element->setAttribute($name, $value);
                return $newAttr;
        }
       
        /** Create an new <title> element within a parent
         * node of the DOM.
         * @param string The content of the <title> element
         * @param DOMElement The parent node object
         */
        private function createTitleElement($title, $parentNode)
        {
                $this->createElement('title', $title, $parentNode);
        }
       
        /** Create an new <link> element within a parent
         * node of the DOM.
         * @param string The content of the <link> element
         * @param DOMElement The parent node object
         */
        private function createLinkElement($url, $parentNode)
        {
                $this->createElement('link', $url, $parentNode);
        }
       
        /** Create an new <description> element within a parent
         * node of the DOM.
         * @param string The content of the <description> element
         * @param DOMElement The parent node object
         */
        private function createDescriptionElement($desc, $parentNode)
        {
                $this->createElement('description', $desc, $parentNode);
        }
       
        /** Create an new <category> element within a parent
         * node of the DOM.
         * @param string The content of the <category> element
         * @param DOMElement The parent node object
         */
        private function createCategoryElement($category, $parentNode)
        {
                $this->createElement('category', $category, $parentNode);
        }
       
        /** Iterate through all the categories, and create
         * an new <category> element within a parent
         * node of the DOM for each one.
         * @param string|array A single category, or an array of categories
         * @param DOMElement The parent node object
         */
        private function createCategories($categories, $parentNode)
        {
                foreach ($categories as $category) {
                        if (!is_array($category)) {
                                $this->createElement('category', $category, $parentNode);
                        }
                        else {
                                $cat = $this->createElement('category', $category['value'], $parentNode);
                                $this->setElementAttribute($cat, 'domain', $category['domain']);
                        }
                }
        }
       
        /** Assigns an {@link RSS_Channel} object to the RSS channel property.
         * @param RSS_Channel New {@link RSS_Channel} object
         * @return RSS_Channel
         */
        public function appendChannel(RSS_Channel $channel)
        {
                $this->channel = $channel;
                return $channel;
        }
       
        /** Create all elements and content for the <channel>
         * element from the {@link RSS_Channel} object, and append
         * to the DOM.
         * @param RSS_Channel The {@link RSS_Channel} object for parsing
         */
        private function append_RSS_Channel (RSS_Channel $channel)
        {
                $this->_channel = $this->createElement('channel', null, $this->_rss);
               
                if ($channel->getTitle()) {$this->createTitleElement($channel->getTitle(), $this->_channel);}
                if ($channel->getLink()) {$this->createLinkElement($channel->getLink(), $this->_channel);}
                if ($channel->getDescription()) {$this->createDescriptionElement($channel->getDescription(), $this->_channel);}
               
                switch ($this->getVersion()) {
                        case '0.90':
                                if ($channel->image['url']) {$img = $this->createElement('image', null, $this->_rss);}
                                break;
                       
                        case '1.0':
                        case '1.1';
                                $this->setElementAttribute($this->_channel, 'rdf:about', $channel->getLink());
                               
                                if ($channel->image['url']) {
                                        $img = $this->createElement('image', null, $this->_channel);
                                        $this->setElementAttribute($img, 'rdf:resource', $channel->image['url']);
                                       
                                        $img = $this->createElement('image', null, $this->_rss);
                                        $this->setElementAttribute($img, 'rdf:about', $channel->image['url']);
                                }
                               
                                $items = $this->createElement('items', null, $this->_channel);
                                $seq = $this->createElement('rdf:Seq', null, $items);
                                foreach ($this->items as $item) {
                                        $li = $this->createElement('rdf:li', null, $seq);
                                        $this->setElementAttribute($li, 'rdf:resource', $item->getLink());
                                }
                                break;
                               
                        case '0.91':
                        case '0.92':
                        case '0.93':
                        case '0.94':
                        case '2.0':
                                // <atom:link href="http://dallas.example.com/rss.XML" rel="self" type="application/rss+XML" />
                                $atomLink = $this->createElement('atom:link', null, $this->_channel);                               
                                $this->setElementAttribute($atomLink, 'href', $channel->getLink());
                                $this->setElementAttribute($atomLink, 'rel', "self");
                                $this->setElementAttribute($atomLink, 'type', "application/rss+xml");
                               
                                if ($channel->getLanguage()) {$this->createElement('language', $channel->getLanguage(), $this->_channel);}
                                if ($channel->getCopyright()) {$this->createElement('copyright', $channel->getCopyright(), $this->_channel);}
                                if ($channel->getManagingEditor()) {$this->createElement('managingEditor', $channel->getManagingEditor(), $this->_channel);}
                                if ($channel->getWebMaster()) {$this->createElement('webMaster', $channel->getWebMaster(), $this->_channel);}
                                if ($channel->getLastBuildDate()) {$this->createElement('lastBuildDate', $channel->getLastBuildDate(), $this->_channel);}
                                if ($channel->getGenerator()) {$this->createElement('generator', $channel->getGenerator(), $this->_channel);}
                                if ($channel->getDocs()) {$this->createElement('docs', $channel->getDocs(), $this->_channel);}
                                if ($channel->getCloud()) {
                                        $cloud = $this->createElement('cloud', null, $this->_channel);
                                        $this->setElementAttribute($cloud, 'domain', $channel->cloud['domain']);
                                        $this->setElementAttribute($cloud, 'port', strval($channel->cloud['port'])); // Set integer type to string type
                                        $this->setElementAttribute($cloud, 'path', $channel->cloud['path']);
                                        $this->setElementAttribute($cloud, 'registerProcedure', $channel->cloud['procedure']);
                                        $this->setElementAttribute($cloud, 'protocol', $channel->cloud['protocol']);
                                }
                                if ($channel->getTTL()) {$this->createElement('ttl', $channel->getTTL(), $this->_channel);}
                                if ($channel->image['url']) {
                                        $img = $this->createElement('image', null, $this->_channel);
                                        // Required Attributes
                                        $this->createElement('url', $channel->image['url'], $img);
                                        $this->createElement('title', $channel->image['title'], $img);
                                        $this->createElement('link', $channel->image['link'], $img);
                                       
                                        // Optional Attributes
                                        if ($channel->image['width']) {$this->createElement('width', $channel->image['width'], $img);}
                                        if ($channel->image['height']) {$this->createElement('height', $channel->image['height'], $img);}
                                        if ($channel->image['description']) {$this->createElement('description', $channel->image['description'], $img);}
                                }
                                if ($channel->getRating()) {$this->createElement('rating', $channel->getRating(), $this->_channel);}
                                if ($channel->textInput['title']) {
                                        $textInput = $this->createElement('textInput', null, $this->_channel);
                                        $this->createElement('title', $channel->image['title'], $textInput);
                                        $this->createElement('description', $channel->image['description'], $textInput);
                                        $this->createElement('name', $channel->image['name'], $textInput);
                                        $this->createElement('link', $channel->image['link'], $textInput);
                                }
                                if ($channel->getSkipHours()) {$this->createElement('skipHours', $channel->getSkipHours(), $this->_channel);}
                                if ($channel->getSkipDays()) {$this->createElement('skipDays', $channel->getSkipDays(), $this->_channel);}             
                                if (count($channel->categories) > 0) {
                                        $this->createCategories($channel->categories, $this->_channel);
                                }
                                break;
                }
                /*
                if ($img) {
                        $this->createElement('url', $channel->image['url'], $img);
                        $this->createElement('title', $channel->image['title'], $img);
                        $this->createElement('link', $channel->image['link'], $img);
                        if ($channel->image['width']) {$this->createElement('width', $channel->image['width'], $img);}
                        if ($channel->image['height']) {$this->createElement('height', $channel->image['height'], $img);}
                        if ($channel->image['description']) {$this->createElement('description', $channel->image['description'], $img);}
                }
                */
        }
       
        /** Append an {@link RSS_Item} object to the RSS items collection
         * @param RSS_Item New {@link RSS_Item} object
         * @return RSS_Item
         */
        public function appendItem(RSS_Item $item)
        {
                $this->items[] = $item;
               
                if ($this->getVersion() == '2.0' && count($this->items) >= 0) {
                        $this->items[count($this->items) - 1]->setLink($item->getLink());
                }
               
                return $item;
        }
       
        /** Create all elements and content for a new <item>
         * element from an {@link RSS_Item object}, and append
         * to the DOM.
         * @param RSS_Item An {@link RSS_Item} object for parsing
         */
        private function append_RSS_Item (RSS_Item $item)
        {
                // Create the parent element for the new item
                switch ($this->getVersion()) {
                        case '0.90':
                                $newItem = $this->createElement("item", null, $this->_rss);
                                break;
                       
                        case '1.0':
                        case '1.1':
                                $newItem = $this->createElement("item", null, $this->_rss);
                                $this->setElementAttribute($newItem, 'rdf:about', $item->getLink());
                                break;
                       
                        case '0.91':
                        case '0.92':
                        case '0.93':
                        case '0.94':
                        case '2.0':
                                $newItem = $this->createElement("item", null, $this->_channel);
                                break;
                }
               
                if ($item->getTitle()) {$this->createTitleElement($item->getTitle(), $newItem);}
                if ($item->getDescription()) {$this->createDescriptionElement($item->getDescription(), $newItem);}
                if ($item->getLink()) {$this->createLinkElement($item->getLink(), $newItem);}
                if ($item->getAuthor()) {$this->createElement('author', $item->getAuthor(), $newItem);}
                if ($item->getComments()) {$this->createElement('comments', $item->getComments(), $newItem);}
                if ($item->getPubDate()) {$this->createElement('pubDate', $item->getPubDate(), $newItem);}
                if ($item->getGuid()) {
                	$guid = $this->createElement('guid', $item->guid['id'], $newItem);
                	if ($item->isGuidPermalink()) {
                		 $this->setElementAttribute($guid, 'isPermalink', "true");
                	}
                }
                
                if ($item->source['value']) {
                        $source = $this->createElement('source', $item->source['value'], $newItem);
                        $this->setElementAttribute($source, 'url', $item->source['url']);
                }
				// TODO: Update the element type for v0.91 and 2.0 feeds
				// v0.91 enclosures are meant to be link elements with rel="enclosure" attribute
				// v2.0 enclosures are meant to be dedicated elements <enclosure>
                if ($item->enclosure['url']) {
                        $enclosure = $this->createElement('enclosure', null, $newItem);
                        $this->setElementAttribute($enclosure, 'url', $item->enclosure['url']);
                        $this->setElementAttribute($enclosure, 'type', $item->enclosure['type']);
                        $this->setElementAttribute($enclosure, 'length', $item->enclosure['length']);
                }
                if (count($item->categories) > 0) {
                        $this->createCategories($item->categories, $newItem);
                }
                
                //Create custom namespace elements if added
                if ($item->hasCustomElements()) {
                	$nsElements = $item->getCustomElements();
                	foreach ($nsElements as $module) {
                		foreach ($module->getElements() as $element) {

                			$curElement = $this->createElement($module->extension . ":" . $element->name, $element->data, $newItem);
                		}
                	}
                }
                
        }      
       
        /** Add an {@link RSS_Module} object to the RSS modules collection
         * @param string Full name of the {@link RSS Module}
         * @param string Abbreviated extension to uniquly identify the namespace elements
         * @param string URI of the module namespace specification
         * @return RSS_Module
         */
        public function setModule($name = '', $extension, $namespaceURI)
        {
                // If the $name parameter is blank, set the extension as the name.
                if ($name == '') {$name = $extension;}
               
                $this->modules[$extension] = new RSS_Module($extension, $name, $namespaceURI);

                $this->declareNamespace($this->modules[$extension], $this->_rss);

                return $this->modules[$extension];
        }
       
        /** Declare a namespace reference to an RSS Module
         * @param RSS_Module The module object to be declared
         * @param DOMElement The parent node object
         * @return DOMAttribute
         */
        public function declareNamespace(RSS_Module $ns, $parentNode) {
                $newAttr = $parentNode->setAttribute('xmlns:' . $ns->extension, $ns->namespace);
                return $newAttr;
        }
       
        /** Parse the {@link RSS} object and create the XML DOM
         * @param boolean Specify if line breaks and spaces are
         * to be presereved to improve readability of the output
         * @return string
         */
        public function saveRSS($formatOutput = true)
        {
                /** @todo Break out adding the RSS/RDF root element to the DOM */
                // something a bit like this: $this->append_Document_Root($this->rss);
               
                /** Add the Channel DOM element */
                $this->append_RSS_Channel($this->channel);
               
                /** Add Item DOM elements */
                foreach ($this->items as $item) {
                        $this->append_RSS_Item($item);
                }
               
                /** Format the Output XML */
                if ($formatOutput) {
                        if (!$this->_dom->formatOutput) {
                                $tmp = $this->_dom->saveXML();
                                $this->_dom->formatOutput = true;
                                $this->_dom->loadXML($tmp);
                        }
                }
               
                /** Return the XML DOM */
                return $this->_dom->saveXML();
        }
}

/**
 * Contains the common properties and
 * methods associated with the {@link RSS_Channel}
 * and {@link RSS_Item} classes.
 * @package RSS
 * @author Stuart Taylor <stuarttaylor79@gmail.com>
 * @todo Show input examples for method parameters
 */
abstract class RSS_Element
{
        // Optional for Channel element
        // Compulsory for Item element
        /** The title of the element
         * Either a title or description MUST be present
         * for <item> elements
         * @var string
         */
        private $title;
        /** The description of the element
         * Either a title or description MUST be present
         * for <item> elements
         * @var string
         */
        private $description;
        /** The URL of the element
        * @var string */
        private $link;
       
        // Optional for Both Element Types
        /** All date-times in RSS conform to
         * the Date and Time Specification of {@link http://asg.web.cmu.edu/rfc/rfc822.html RFC 822}.
         * The year may be expressed with two
         * characters or four characters (four preferred).
         *
         * example: Sat, 07 Sep 2002 16:25:01 GMT
         */
        private $pubDate;
       
        /** Specify one or more categories that the
         * element belongs to.
         * example: <category>Newspapers</category>
         *
         * example : <category domain="http://www.fool.com/cusips">MSFT</category>
         */
        public $categories = array();           // More than one allowed
       
        /** Array to hold custom Namespace Elements
         *  
         * @var RSS_Extension
         */
        private $nsElements = array();          // More than one allowed
       
        /**
         * @param string Title of the <channel> or <item> element
         * @param string Description of the <channel> or <item> element
         * @param string URL of the <channel> or <item> element
         */
        public function __construct($title = false, $description = false, $link = false) {
                if ($title) {$this->setTitle($title);}
                if ($description) {$this->setDescription($description);}
                if ($link) {$this->setLink($link);}
        }
       
       
        /** Set the property {@link $title}
        * @param string Element title */
        public function setTitle($val) {$this->title = $val;}
       
        /** Set the property {@link $link}
        * @param string Element link */
        public function setLink($val) {$this->link = $val;}
       
        /** @param string Element description */
        public function setDescription($val) {$this->description = $val;}
       
        /** @param string Date and time of publication in RFC 822 format */
        public function setPubDate($val) {$this->pubDate = $val;}
       
        /** Set a category
        * Optionally, a category may be assigned a URI link
        * @param string The name of the category
        * @param string The URI of the category
        */
        public function setCategory($val, $domain = false) {
                if (!$domain) {
                        $this->categories[] = $val;
                }
                else {
                        $this->categories[] = array('value'=>$val, 'domain'=>$domain);
                }
        }
       
        /** @return string */
        public function getTitle() {return ($this->title) ? $this->title : false;}
       
        /** @return string */
        public function getLink() {return ($this->link) ? $this->link : false;}
       
        /** @return string */
        public function getDescription() {return ($this->description) ? $this->description : false;}
       
        /** @return string */
        public function getPubDate() {return ($this->pubDate) ? $this->pubDate : false;}
       
        /** @return array */
        public function getCategories() {return ($this->categories) ? $this->categories : false;}
       
        /** Instantiates a new RSS_Module_Element and adds it
         * to the RSS_Element's RSS_Module_Element collection
         *
         * @return RSS_Module_Element
         * @todo This method SERIOUSLY needs improving so that is is actually used
         */
        public function setCustomElement($extension, $elementName, $elementData) {
                $newElement = new RSS_Module_Element($elementName, $elementData);
                $this->appendCustomElement($extension, $newElement);
                return $newElement;
        }
       
        /**
         * Append an RSS_Module_Element to the custom
         * elements collection
         *
         * @param string Namespace extension
         * @param RSS_Module_Element Custom Element object
         */
        public function appendCustomElement($extension, RSS_Module_Element $customElement) {
        		if (!$this->nsElements[$extension]) {    		
	        		$newExtension = new RSS_Extension($extension);
	        		$this->nsElements[$extension] = $newExtension;
        		}
        		        		
        		$this->nsElements[$extension]->appendExtensionElement($customElement);
        }
        
        /** @return integer */
        public function hasCustomElements() {return (count($this->nsElements) > 0) ? count($this->nsElements) : false;}
        
        /** @return array */
        public function getCustomElements() {return $this->nsElements;}
        
        /** @return array */
        public function getCustomElementsByModule($extension) {return $this->nsElements[$extension];}
}

/**
 * Handles the variables associated with an
 * RSS <item> element.
 *
 * The following gives three examples of how a new
 * {@link RSS_Item} object can be created.
 *
 * {@example examples/rss_example.php 33 14}
 *
 * {@example examples/rss_example.php 50 12}
 *
 * {@example examples/rss_example.php 64 7}
 *
 * @package RSS
 * @author Stuart Taylor <stuarttaylor79@gmail.com>
 * @todo Show input examples for method parameters
 */
class RSS_Item Extends RSS_Element
{
        // Optional Elements
        // ===========
        /** Email address of the Author.
         * example: joe@gmail.net <Joe Bloggs>
         */
        private $author;
       
        /** Link to comments page.
         * example: http://www.myblog.org/cgi-local/mt/mt-comments.cgi?entry_id=290
         */
        private $comments;
       
        /** A string that uniquely identifies the item.
         * example: http://inessential.com/2002/09/01.php#a2
         */
        public $guid = array();
       
        /** Required attribute url for correct accreditation
         * of a referenced story.
         */
        public $source = array();
       
        /** Describes a media object that is attached to
         * the item.
         */
        public $enclosure = array();
       
        /** @param string The email address of the author */
        public function setAuthor($val) {$this->author = $val;}
       
        /** @param string The link to a comments page */
        public function setComments($val) {$this->comment = $val;}
       
        /**
         * @param string The unique identifier for the item
         * @param boolean Set as true if the guid is a Permalink
         */
        public function setGuid($val, $isPermalink = false) {
                $this->guid['id'] = $val;
                $this->guid['isPermalink'] = $isPermalink;
        }
       
        /**
         * @param string Name of the RSS channel that the item came from
         * @param string URL of the xml RSS source
         */
        public function setSource($val, $url) {
                $this->source['value'] = $val;
                $this->source['url'] = $url;
        }
       
        /**
         * @param string URL of where the enclosure is located on the server
         * @param string MIME type of the enclosure
         * @param integer Size of the enclosure in bytes
         */
        public function setEnclosure($url, $type, $length) {
                $this->enclosure['url'] = $url;
                $this->enclosure['type'] = $type;
                $this->enclosure['length'] = $length;
        }      
       
        /** @return string */
        public function getAuthor() {return ($this->author) ? $this->author : false;}
       
        /** @return string */
        public function getComments() {return ($this->comment) ? $this->comment : false;}
       
        /** @return array */
        public function getEnclosure() {return ($this->enclosure) ? $this->enclosure : false;}
       
        /** @return array */
        public function getGuid() {return ($this->guid['id']) ? $this->guid['id'] : false;}
       
        /** @return boolean */
        public function isGuidPermalink() {return ($this->guid['isPermalink']) ? $this->guid['isPermalink'] : false;}
       
        /** @return array */
        public function getSource() {return ($this->source) ? $this->source : false;}
}

/**
 * Handles the variables associated with
 * the RSS <channel> element.
 *
 * The following examples shows how the {@link RSS_Channel}
 * object of an {@link RSS} object can be used:
 *
 * {@example examples/rss_example.php 27 2}
 *
 * @package RSS
 * @author Stuart Taylor <stuarttaylor79@gmail.com>
 * @todo Modify the Cloud element set function to include all 5 required attributes as detailed at {@link http://cyber.law.harvard.edu/rss/soapMeetsRss.html#rsscloudInterface}
 * @todo Show input examples for method parameters
 */
class RSS_Channel Extends RSS_Element
{
// Optional Elements
        /** The language the channel is written in. This allows
         * aggregators to group all Italian language sites, for
         * example, on a single page. A list of allowable values
         * for this element, as provided by {@link http://cyber.law.harvard.edu/rss/languages.html Netscape} or the {@link http://www.w3.org/TR/REC-html40/struct/dirlang.html#langcodes W3C}.
         *
         * example: en-us
         */
        private $language;
       
        /** Copyright notice for content in the channel.
         * example: Copyright 2002, Spartanburg Herald-Journal
         */
        private $copyright;
       
        /** Email address for person responsible for editorial
         * content.
         * example: geo@herald.com (George Matesky)
         */
        private $managingEditor;
       
        /** Email address for person responsible for technical
         * issues relating to channel.
         * example: betty@herald.com (Betty Guernsey)
         */
        private $webMaster;
       
        /** The last time the content of the channel changed.
         *
         * example: Sat, 07 Sep 2002 09:42:31 GMT
         */
        private $lastBuildDate;
       
        /** A string indicating the program used to generate
         * the channel.
         * example: MightyInHouse Content System v2.3
         */
        private $generator = 'RSS Generator Class v2.2 by Stuart Taylor';
       
        /** A URL that points to the documentation for the
         * format used in the RSS file.
         * example: http://blogs.law.harvard.edu/tech/rss
         */
        private $docs;
       
        /** Allows processes to register with a cloud to be
         * notified of updates to the channel, implementing
         * a lightweight publish-subscribe protocol for RSS
         * feeds.
         *
         * example: <cloud domain="rpc.sys.com" port="80" path="/RPC2" registerProcedure="pingMe" protocol="xml-rpc"/>
         */
        public $cloud = array();
       
        /** ttl stands for time to live. It's a number of minutes
         * that indicates how long a channel can be cached
         * before refreshing from the source.
         * example: <ttl>60</ttl>
         */
        private $ttl;
       
        /** Specifies a GIF, JPEG or PNG image that can be
         * displayed with the channel.
         */
        public $image = array();
       
        /** The PICS rating for the channel.   
         */
        private $rating;
       
        /** Specifies a text input box that can be displayed
         * with the channel.
         */
        public $textInput = array();
       
        /** A hint for aggregators telling them which hours
         * they can skip.
         */
        private $skipHours;
       
        /** A hint for aggregators telling them which days
         * they can skip.
         */
        private $skipDays;
       
        /** @param string The language of the channel */
        public function setLanguage($val) {$this->language = $val;}
       
        /** @param string Copyright notice for the channel content */
        public function setCopyright($val) {$this->copyright = $val;}
       
        /** @param string Email address and name */
        public function setManagingEditor($val) {$this->managingEditor = $val;}
       
        /** @param string Email address and name */
        public function setWebMaster($val) {$this->webMaster = $val;}
       
        /** @param string Date and time of the last publication in RFC 822 format */
        public function setLastBuildDate($val) {$this->lastBuildDate = $val;}
       
        /** @param string Name of the package used to generate the RSS feed */
        public function setGenerator($val) {$this->generator = $val;}
       
        /** @param string URL that points to the documentation for the format used in the RSS file */
        public function setDocs($val) {$this->docs = $val;}
       
        /** @todo Modify the setCloud function to handle errors when a parameter is missing */
        /** Set the attributes for the <cloud> element
         * @param string The domain name of the cloud server
         * @param integer The port number of the cloud server
         * @param string The path to the procedure which is to be called
         * @param string Name of registered procedure to call at the path
         * @param string Name of protocol type to be used (HTTP-POST, XML-RPC or SOAP 1.1)
         */
        public function setCloud($domain, $port, $path, $registerProcedure, $protocol) {
                $this->cloud['domain'] = $domain;
                $this->cloud['port'] = $port;
                $this->cloud['path'] = $path;
                $this->cloud['procedure'] = $registerProcedure;
                $this->cloud['protocol'] = $protocol;
        }
       
        /** @param integer Number of minutes the feed can be cached, before refreshing */
        public function setTTL($val) {$this->ttl = $val;}
       
        /** Set the attributes for a channel image
        * @param string The src attribute of the image
        * @param integer The width attribute of the image
        * @param integer The height attribute of the image
        * @param string The alt attribute of the image
        */
        public function setImage($src, $width = false, $height = false, $description = false)
        {
                // Required Elements
                $this->image['url'] = $src;
                $this->image['title'] = $this->getTitle();
                $this->image['link'] = $this->getLink();
               
                // Optional Elements
                $this->image['width'] = $width;
                $this->image['height'] = $height;
                $this->image['description'] = $description;
        }
       
        /** @param string PICS rating for the channel */
        public function setRating($val) {$this->rating = $val;}
       
        /**
         * @param string Label of the Submit button
         * @param string Explaination the text input area
         * @param string Name of the text object
         * @param string URL of the script that processes text input requests
         */
        public function setTextInput($title, $description, $name, $link)
        {
                $this->textInput['title'] = $title;
                $this->textInput['description'] = $description;
                $this->textInput['name'] = $name;
                $this->textInput['link'] = $link;
        }
       
        /**
         * @param string Set an hour when the RSS feed is not to be read by aggregators
         * @todo Expand the setSkipHours to include <hour> sub-elements
         */
        public function setSkipHours($val) {$this->skipHours = $val;}
       
        /**
         * @param string Set a day when the RSS feed is not to be read by aggregators
         * @todo Expand the setSkipDays to include <day> sub-elements
         */
        public function setSkipDays($val) {$this->skipDays = $val;}
       
        /** @return string */
        public function getLanguage() {return ($this->language) ? $this->language : false;}
       
        /** @return string */
        public function getCopyright() {return ($this->copyright) ? $this->copyright : false;}
       
        /** @return string */
        public function getManagingEditor() {return ($this->managingEditor) ? $this->managingEditor : false;}
       
        /** @return string */
        public function getWebMaster() {return ($this->webMaster) ? $this->webMaster : false;}
       
        /** @return string */
        public function getLastBuildDate() {return ($this->lastBuildDate) ? $this->lastBuildDate : false;}
       
        /** @return string */
        public function getGenerator() {return ($this->generator) ? $this->generator : false;}
       
        /** @return string */
        public function getDocs() {return ($this->docs) ? $this->docs : false;}
       
        /** @return string */
        public function getCloud() {return ($this->cloud) ? $this->cloud : false;}
       
        /** @return string */
        public function getTTL() {return ($this->ttl) ? $this->ttl : false;}
       
        /** @return array */
        public function getImage() {return ($this->image) ? $this->image : false;}
       
        /** @return string */
        public function getRating() {return ($this->rating) ? $this->rating : false;}
       
        /** @return array */
        public function getTextInput() {return ($this->textInput) ? $this->textInput : false;}
       
        /** @return string */
        public function getSkipHours() {return ($this->skipHours) ? $this->skipHours : false;}
       
        /** @return string */
        public function getSkipDays() {return ($this->skipDays) ? $this->skipDays : false;}
}

/**
 * Handles the variables associated with
 * a custom RSS module.
 *
 * This module is currently under development
 *
 * @package RSS
 * @author Stuart Taylor <stuarttaylor79@gmail.com>
 * @todo Implement the use of this RSS_Module class
 */
class RSS_Module
{
        /** Unique identifier of the RSS Module
         * @var string
         */
        public $extension;
        /** Full Name of the RSS Module
         * @var string
         */
        public $name;
        /** URI of the definition specfication for the RSS Module
         * @var string
         */
        public $namespace;
       
        /**
         * @param string Name of the RSS Module
         * @param string URI of the module namespace specification
         * @return RSS_Module
         */
        public function __construct ($extension, $name, $namespaceURI) {
                $this->extension = $extension;
                $this->name = $name;
                $this->namespace = $namespaceURI;
        }
}

/**
 * Object to accommodate custom Elements
 * as defined in the assocaited namespace
 * schema documentation.
 *
 * This module may need further improving
 * 
 * @package RSS
 * @author Stuart Taylor <stuarttaylor79@gmail.com>
 *
 */
class RSS_Extension
{
		/** Extension reference
		 * @var string
		 */
		public $extension;
		
		/** Array to hold custom Namespace Elements
         *  @todo Implement custom element parameters: Namespace Extension, Element Name, Element Data
         *  
         * @var RSS_Module_Element
         */
		public $nsElements = array();
	
		public function __construct($extension){
			$this->extension = $extension;
		}
	
		public function appendExtensionElement(RSS_Module_Element $customElement) {
			$this->nsElements[] = $customElement;
		}
		
		public function getElements() {return $this->nsElements;}
}

/**
 * Custom Namespace Element class
 *
 * This module is currently under development
 *
 * @package RSS
 * @author Stuart Taylor <stuarttaylor79@gmail.com>
 * @todo Implement the use of this RSS_Module_Element class
 */
class RSS_Module_Element
{
        /** Element name
         * @var string
         */
        public $name;
        /** Element data
         */
        public $data;

        /**
         * @param string Name of the custom element
         * @return RSS_Module_Element
         */
        public function __construct ($name, $data) {
                $this->name = $name;
                $this->data = $data;
        }
}
?>