<?php
/*
	authoriseComment.php
	Jane		25 May 2004	Created

	Modification History
	~~~~~~~~~~~~~~~~~~~~
 */

include ("../comment/scripts/common.php");
include ("../comment/scripts/bbdb.php");
include ("../comment/scripts/comments.php");

$CommentID = $_GET["CommentID"];
$btnSubmitDisplay = $_POST["btnSubmitDisplay"];

if ($btnSubmitDisplay == "Display")
{
	MakeChanges($CommentID);
}
else
{
	DisplayForm($CommentID);
}

function DisplayForm($CommentID)
{
	print "<html>";
	print "<head>";
	print "<title>ADMIN - Authorise comment</title>";
	print "<LINK TITLE=\"Style\" REL=STYLESHEET HREF=\"../css/layout.css\" TYPE=\"text/css\">\n";
	print "</head>";
	print "<body>";
	print "<h1>Comment</h1>";
	DisplayCommentForm($CommentID);
	print "</body>";
	print "</html>";

}

function MakeChanges($CommentID)
{
	//do something
	$sqlUpdate = "Update `Comment` set hide = 'f' where commentid=".$CommentID;

	$db = InitialiseDB();

	if (ExecuteSQL($sqlUpdate))
	{
		$sqlSelect = "Select storyurl from `Comment` where commentid=".$CommentID;
		$result = mysql_query($sqlSelect, $db);

		if ($my_row = mysql_fetch_row($result))
		{
			print "<h1>Comment Authorised</h1>";
			print "<br />";
			notifyOfNewAuthorisedComment($my_row[0]);
		}

	}
	else
	{
		print "There has been an error updating";
	}
}

function DisplayCommentForm($CommentID)
{

	$db = InitialiseDB();


	$sqlSelect = "Select name, email, url, subject, comment, postdate, storyurl, hide FROM `Comment` where commentid = ".$CommentID;

	$result = mysql_query($sqlSelect, $db);

	if ($my_row = mysql_fetch_row($result))
	{
		print "<form name=\"frmComment\" method=\"post\">";
		print "<table>";
		print "<tr>";
		print "<td>Name:</td><td>".$my_row[0]."</td>";
		print "</tr><tr>";
		print "<td>Email:</td><td>".$my_row[1]."</td>";
		print "</tr><tr>";
		print "<td>URL:</td><td>".$my_row[2]."</td>";
		print "</tr><tr>";
		print "<td>Subject:</td><td>".$my_row[3]."</td>";
		print "</tr><tr>";
		print "<td>Comment:</td><td>".$my_row[4]."</td>";
		print "</tr><tr>";
		print "<td>Posted On:</td><td>".$my_row[5]."</td>";
		print "</tr><tr>";
		print "<td>Relating to:</td><td>".$my_row[6]."</td>";
		print "</tr><tr>";
		if ($my_row[7] == "f")
		{
			print "<td></td><td>Currently Displayed</td>";
		}
		else
		{
			print "<td></td><td>Currently Hidden</td>";
		}
		print "</tr>";
		print "<tr><td></td><td>";
		print "<input type=\"submit\" name=\"btnSubmitDisplay\" value=\"Display\">\n";
		print "<input type=\"hidden\" name=\"CommentID\" value=\"".$CommentID."\">";
		print "</td></tr>";
		print "</table>";
		print "</form>";
	}
}

//moved from comments.php to ensure this doesnt get sent out unless authorised
function notifyOfNewAuthorisedComment($storyurl)
{
	//when new post is made, check and see if anyone has set notify = true for this storyurl
	//get distinct list of email addresses and email them with default message to say "Someone has added a comment to a thread you were interested in."  Add storyurl.

	$sqlSelect = "Select distinct email from Comment where notify = 1 and hide = 'f' and storyurl = ".SQLSafeString($storyurl)." and email is not null ";

	$db = InitialiseDB();
	$result = mysql_query($sqlSelect, $db);

	$intCount = 0;
	//send emails out to those who requested notification
	while ($my_row = mysql_fetch_row($result))
	{
		generateEmail($my_row[0], $storyurl);
		$intCount++;
	}

	print "<br/>Email sent out to ".$intCount." email addresses<br />";
}

?>
