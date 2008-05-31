<?php
/*
	comments.php
	Jane		26 May 2002	Created

	Modification History
	~~~~~~~~~~~~~~~~~~~~
 */
 
	 function generateAdminEmail($commentId)
 	{
 		
 		/* To send HTML mail, you can set the Content-type header. */
		$headers  = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";

		/* additional headers */
		$headers .= "From: Comments <comments@brightonbloggers.com>\n";

		$message = "A new comment has just been added to BrightonBloggers.com.<br /><a href='http://www.brightonbloggers.com/admin/authoriseComment.php?CommentID=".$commentId."'>Admininster that comment</a>";

		/* and now mail it */
		mail("jane@dallaway.com", "A new comment needs authorising at brightonbloggers.com", $message, $headers, "-fcomments@brightonbloggers.com");

	}


	function notifyOfNewComment($storyurl,$emailaddress)
	{
		//when new post is made, check and see if anyone has set notify = true for this storyurl
		//get distinct list of email addresses and email them with default message to say "Someone has added a comment to a thread you were interested in."  Add storyurl.

		$sqlSelect = "Select distinct email from Comment where notify = 1 and storyurl = ".SQLSafeString($storyurl)." and email is not null ";

		if (strlen($emailaddress) > 0)
		{
			$sqlSelect = $sqlSelect." and email <> ".SQLSafeString($emailaddress);
		}

		$db = InitialiseDB();
		$result = mysql_query($sqlSelect, $db);

		//send emails out to those who requested notification
		while ($my_row = mysql_fetch_row($result))
		{
			generateEmail($my_row[0], $storyurl);
		}
	}

	function generateEmail($emailaddress, $storyurl)
	{

		/* To send HTML mail, you can set the Content-type header. */
		$headers  = "MIME-Version: 1.0\n";
		$headers .= "Content-type: text/html; charset=iso-8859-1\n";

		/* additional headers */
		$headers .= "From: Comments <comments@brightonbloggers.com>\n";

		$message = "You once added a comment on brightonbloggers.com. That thread has just been updated.<br />View <a href=\"".$storyurl."\">the thread</a>.";

		/* and now mail it */
		mail($emailaddress, "A new comment has been added on brightonbloggers.com", $message, $headers, "-fcomments@brightonbloggers.com");

	}

	function showcomments($storyurl)
	{

		$sqlSelect = "Select postdate, name, url, subject, comment from Comment where storyurl = ".SQLSafeString($storyurl)." and hide ='f' order by postdate asc";
		$db = InitialiseDB();

	print "<!-- ";
	print $sqlSelect;
	print "-->";
		$result = mysql_query($sqlSelect, $db);
	print "<!-- results: -->";
		while ($my_row = mysql_fetch_row($result))
		{
			print "<span class=\"comment\">";
			print "<blockquote><p>";
			if (strlen($my_row[3]) > 0)
			{
				print "<i>".$my_row[3]."</i>";
				print "<br />";
			}
			print $my_row[4];
			print "<br />";
			print "// posted by ";
			if (strlen($my_row[2]) > 0)
			{
				print "<a href=\"".$my_row[2]."\">".$my_row[1]."</a>";
			}
			else
			{
				print $my_row[1];
			}
			print " @ ".$my_row[0];
			print "</p></blockquote>";
			print "</span>\n";

		}
	print "<!-- select done -->";
	}

?>
