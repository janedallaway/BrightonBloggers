<?php
/*  PHP RSS Reader v1.1
    By Richard James Kendall 
    Bugs to richard@richardjameskendall.com 
    Free to use, please acknowledge me 
    
    Place the URL of an RSS feed in the $file variable.
   	
   	The $rss_channel array will be filled with data from the feed,
   	every RSS feed is different by by and large it should contain:
   	
   	Array {
   		[TITLE] = feed title
   		[DESCRIPTION] = feed description
   		[LINK] = link to their website
   		
   		[IMAGE] = Array {
   					[URL] = url of image
   					[DESCRIPTION] = alt text of image
   				}
   		
   		[ITEMS] = Array {
   					[0] = Array {
   							[TITLE] = item title
   							[DESCRIPTION] = item description
   							[LINK] = a link to the story
   							[PUBDATE] = date published
   							[SOURCE] = name of web site it was published on
   							[AUTHOR] = name of the person who wrote the blog
   						}
   					.
   					.
   					.
   				}
   	}
   	
   	By default it retrives the Reuters Oddly Enough RSS feed. The data is put into the array
   	structure so you can format the information as you see fit.
*/

$debug = $_GET["debug"];
set_time_limit(0);

$file = "http://pipes.yahoo.com/pipes/pipe.run?_id=f7dd07fa5dd1961408d7323cc42dcb46&_render=rss";

$rss_channel = array();
$currently_writing = "";
$main = "";
$item_counter = 0;

function startElement($parser, $name, $attrs) {
   	global $rss_channel, $currently_writing, $main;
   	switch($name) {
   		case "RSS":
   		case "RDF:RDF":
   		case "ITEMS":
   			$currently_writing = "";
   			break;
   		case "CHANNEL":
   			$main = "CHANNEL";
   			break;
   		case "IMAGE":
   			$main = "IMAGE";
   			$rss_channel["IMAGE"] = array();
   			break;
   		case "ITEM":
   			$main = "ITEMS";
   			break;
   		default:
   			$currently_writing = $name;
   			break;
   	}
}

function endElement($parser, $name) {
   	global $rss_channel, $currently_writing, $item_counter;
   	$currently_writing = "";
   	if ($name == "ITEM") {
   		$item_counter++;
   	}
}

function characterData($parser, $data) {
	global $rss_channel, $currently_writing, $main, $item_counter;
	if ($currently_writing != "") {
		switch($main) {
			case "CHANNEL":
				if (isset($rss_channel[$currently_writing])) {
					$rss_channel[$currently_writing] .= $data;
				} else {
					$rss_channel[$currently_writing] = $data;
				}
				break;
			case "IMAGE":
				if (isset($rss_channel[$main][$currently_writing])) {
					$rss_channel[$main][$currently_writing] .= $data;
				} else {
					$rss_channel[$main][$currently_writing] = $data;
				}
				break;
			case "ITEMS":
				if (isset($rss_channel[$main][$item_counter][$currently_writing])) {
					$rss_channel[$main][$item_counter][$currently_writing] .= $data;
				} else {
					//print ("rss_channel[$main][$item_counter][$currently_writing] = $data<br>");
					$rss_channel[$main][$item_counter][$currently_writing] = $data;
				}
				break;
		}
	}
}

$xml_parser = xml_parser_create();
xml_set_element_handler($xml_parser, "startElement", "endElement");
xml_set_character_data_handler($xml_parser, "characterData");
if (!($fp = fopen($file, "r"))) {
	die("could not open XML input");
}

if ($debug==1)
	print("File is open\n<br />");
	
while ($data = fread($fp, 8192)) {
	if (!xml_parse($xml_parser, $data, feof($fp))) {
		// only output the error if we're running in debug mode, otherwise carry on regardless
		if ($debug==1)
			(sprintf("XML error: %s at line %d",
					xml_error_string(xml_get_error_code($xml_parser)),
					xml_get_current_line_number($xml_parser)));
					
	}
}
xml_parser_free($xml_parser);

?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN"
        "http://www.w3.org/TR/2000/REC-xhtml1-20000126/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
<meta http-equiv="content-type" content="text/html; charset=windows-1252" />
<title>Brighton Bloggers: An Aggregated display of Brighton Blogs</title>
<link rel="stylesheet" type="text/css" media="screen" href="../styles/basic.css" />
<link rel="stylesheet" type="text/css" media="print" href="../styles/print.css" />
<link rel="alternate" href="http://pipes.yahoo.com/pipes/pipe.run?_id=f7dd07fa5dd1961408d7323cc42dcb46&_render=rss" type="application/rss+xml" />
</head>
<body>

<div id="page">

<div id="masthead">
<h1><span>Brighton Bloggers</span></h1>
</div>

<div id="navigation">

<ul>
<!-- <li><a href="../">Home</a></li> -->
<li><a href="../blog/">Brighton Blogs</a></li>
<li><a href="../podcasts/">Podcasts</a></li>
<li><a href="../blog/">Blog</a></li>
<li><a href="../meetup/">Meetup</a></li>
<li><a href="../links/">Links</a></li>
<li><span>Aggregated</span></li>
</ul>

</div>

<div id="extra">
<p>Search all Brighton Blogger sites</p>
<!-- Google CSE Search Box Begins -->
  <form id="searchbox_010203337706838772091:swtsglkyrmi" action="http://www.brightonbloggers.com/search.php">
    <input type="hidden" name="cx" value="010203337706838772091:swtsglkyrmi" />
    <input type="hidden" name="cof" value="FORID:11" />
    <input name="q" type="text" size="20" />
    <input type="submit" name="sa" value="Search" />
  </form>
<!-- Google CSE Search Box Ends -->
</div>

<div id="content">

<?php
print ("<h2>The most recent " . count($rss_channel["ITEMS"]) . " posts to the <a href=\"http://pipes.yahoo.com/pipes/pipe.run?_id=f7dd07fa5dd1961408d7323cc42dcb46&amp;_render=rss&amp;max_items=500\">Brighton Bloggers aggregated RSS feed</a></h2>");

if (isset($rss_channel["ITEMS"])) {
	if (count($rss_channel["ITEMS"]) > 0) {
		for($i = 0;$i < count($rss_channel["ITEMS"]);$i++) {
			print ("\n<div class=\"blogpost\"><h3>" . $rss_channel["ITEMS"][$i]["TITLE"] . "</h3></a>");
			print (html_entity_decode($rss_channel["ITEMS"][$i]["DESCRIPTION"]));			
			print (" <div class=\"byline\">Posted at " . $rss_channel["ITEMS"][$i]["SOURCE"] . " by " . $rss_channel["ITEMS"][$i]["AUTHOR"] . " on <a href=\"" . $rss_channel["ITEMS"][$i]["LINK"] . "\">"  . $rss_channel["ITEMS"][$i]["PUBDATE"] . "</a></div>");
			print ("</div>");
		}
	} else {
		print ("<b>There are no articles in this feed.</b>");
	}
}
?> 
<p>Want to read these in an rss reader?  Download the <a href="/brighton.opml">opml file</a> or subscribe to the <a href="http://pipes.yahoo.com/pipes/pipe.run?_id=f7dd07fa5dd1961408d7323cc42dcb46&_render=rss&max_items=500">RSS feed</a>.</p>
<p><a href="http://validator.opml.org/?url=http%3A%2F%2Fwww.brightonbloggers.com%2Fbrighton.opml"><img src="http://images.scripting.com/archiveScriptingCom/2005/10/31/valid3.gif" width="114" height="20" border="0" alt="OPML checked by validator.opml.org."></a></p>

</div>

</body>
</html>