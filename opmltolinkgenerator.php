<html>
<head><title>Opml RSS links</title></head>
<body>
<?php
/*
 * Created on May 29, 2010
 *
 * A simple script to output the RSS links as normal links, so that I can use firefox check links plug in to verify if they still respond
 */
 
$file = "http://brightonbloggers.com/brighton.opml";
$lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);

foreach ($lines as $line_num => $line) 
{
	// skip those that don't begin with <outline type="rss"
	if (stristr($line,"<outline type=\"rss\"") != FALSE)
	{
		// find xmlUrl=" and get content between start and end " - that should be the url to check
		$checkFor = "xmlUrl=\"";
		$starter = stripos($line, $checkFor);
		if ($starter > 0)
		{
			$starter = $starter + strlen($checkFor);
		}
		$end = stripos($line,"\"",$starter);
		$xmlurl = substr($line,$starter,$end - $starter); 
		
		//output the link, as a link
	    echo "<a href=\"".$xmlurl."\">".$xmlurl."</a><br />\n";
	}   
}

 
 
?>
</blody>
</html>