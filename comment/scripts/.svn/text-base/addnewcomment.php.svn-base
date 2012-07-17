<?php
/*
	addnewcomment.php
	Jane		26 May 2002	Created

	Modification History
	~~~~~~~~~~~~~~~~~~~~
	Jane Jan 9th Updated to use _GET rather than the insecure $ version
	Jane June 21st 2006 Updated to use administation functionality
 */


include ("common.php");
include ("bbdb.php");
include ("comments.php");
?>

<script language="javascript">
function Validate()
{

	if (document.frmComment.txtName.value.length == 0)
	{
		alert("Please enter a name");
		document.frmComment.txtName.focus();
		return false;
	}

	if (document.frmComment.txtComment.value.length == 0)
	{
		alert ("Please enter a comment");
		document.frmComment.txtComment.focus();
		return false;
	}

	if ((document.frmComment.chkNotify.checked) && (document.frmComment.txtEmail.value.length == 0))
	{
		alert ("You must enter an email address if you wish to be notified");
		document.frmComment.txtEmail.focus();
		return false;
	}

	return true;

}
</script>

<?php
// get all possible parameters
$storyurl = $_GET["storyurl"];
$txtURL = $_POST["txtURL"];
$txtComment = $_POST["txtComment"];
$txtName = $_POST["txtName"];
$txtEmail = $_POST["txtEmail"];
$txtSubject = $_POST["txtSubject"];
$btnSubmit = $_POST["btnSubmit"];
$chkNotify = $_POST["chkNotify"];

if (strlen($storyurl) == 0)
{

	//error, no point continuing
	print "Oops, sorry there doesn't seem to be a link to a story for this page.";
}
else
{

	//check that the $storyurl does not have a %23 in it, and has a # instead - to code around weird ns4 bug
	if (strstr($storyurl,"%23"))
	{
		$storyurl = str_replace("%23","#",$storyurl);
	}

	if ($btnSubmit == "Add comment")
	{

		$db = InitialiseDB();

		// insert the comment into the db
		if ($chkNotify == "on")
		{
			$blnNotify = 1;
		}
		else
		{
			$blnNotify = 0;
		}

		if ((strlen($txtURL) > 0) and (strstr($txtURL,"http") == false))
		{
			$txtURL = "http://".$txtURL;
		}

		$txtComment = PreserveBreaks(SortOutEntities($txtComment));

		$txtComment = preg_replace("/((http(s?):\/\/)|(www\.))([\w\.~\/-]+)/i",
					"<a href=\"http$3://$4$5\" target=\"_blank\">$2$4$5</a>", $txtComment);

		$datDate = date("Y-m-d H:i:s");

		$sqlInsert = "Insert into Comment (name, email, url, notify, subject, comment, storyurl, postdate)";
		$sqlInsert = $sqlInsert." values (".SQLSafeString(SortOutEntities($txtName)).",".SQLSafeString(SortOutEntities($txtEmail));
		$sqlInsert = $sqlInsert.",".SQLSafeString(SortOutEntities($txtURL)).",".$blnNotify.",".SQLSafeString(SortOutEntities($txtSubject));
		$sqlInsert = $sqlInsert.",".SQLSafeString($txtComment).",".SQLSafeString($storyurl);
		$sqlInsert = $sqlInsert.",'".$datDate."')";

		if (ExecuteSQL($sqlInsert))
		{

			//get the commentId
			$sqlSelect = "SELECT commentID FROM Comment WHERE ";
			$sqlSelect = $sqlSelect." storyurl = ".SQLSafeString($storyurl);
			$sqlSelect = $sqlSelect." and postdate = '".$datDate."'";

			//generate and send the Email to admin
			$result = mysql_query($sqlSelect, $db);
			while ($my_row = mysql_fetch_row($result))
			{
				//generateAdminEmail
				generateAdminEmail($my_row[0]);
			}
			
			print "Thank you, your comment has been recorded.  It will be reviewed and approved shortly.\n";

		}
		else
		{
			print "Sorry, can't seem to add your comment at the moment.\n";
		}

		if (strpos($storyurl,"#") > 0)
		{
			$tempStoryURL = substr($storyurl,0,strpos($storyurl,"#"));
			$tempStoryURL = $tempStoryURL."?".microtime();
			$tempStoryURL = $tempStoryURL.substr($storyurl,strpos($storyurl,"#"));
			$storyurl = $tempStoryURL;
		}

		print "<br /><br /><a href=\"javascript:window.opener.location.href = '".$storyurl."';window.close()\">Close</a>";
	}
	else
	{
		displayForm($storyurl);
	}

}


function displayForm($storyurl)
{

	print "<html>\n";
	print "<head>\n";
	print "<title>Add a new comment</title>\n";
	print "<LINK TITLE=\"Style\" REL=STYLESHEET HREF=\"http://www.brightonbloggers.com/styles/basic.css\" TYPE=\"text/css\">\n";
	print "</head>\n";
	print "<body>\n";

	print "<form name=\"frmComment\" method=\"post\" onSubmit=\"return Validate()\">\n";
	print "<table width=\"100%\">\n";
	print "<tr valign=\"top\">\n";
	print "<td>\n";
	print "Name\n";
	print "</td>\n";
	print "<td>\n";
	print "<input type=\"text\" name=\"txtName\" size=\"40\" maxlength=\"100\" />\n";
	print "</td>\n";
	print "</tr>\n";
	print "<tr valign=\"top\">\n";
	print "<td>\n";
	print "Email\n";
	print "</td>\n";
	print "<td>\n";
	print "<input type=\"text\" name=\"txtEmail\" size=\"40\" maxlength=\"100\" />\n";
	print "</td>\n";
	print "</tr>\n";
	print "<tr valign=\"top\">\n";
	print "<td>\n";
	print "URL\n";
	print "</td>\n";
	print "<td>\n";
	print "<input type=\"text\" name=\"txtURL\" size=\"40\" maxlength=\"255\" />\n";
	print "</td>\n";
	print "</tr>\n";
	print "<tr valign=\"top\">\n";
	print "<td>\n";
	print "Subject\n";
	print "</td>\n";
	print "<td>\n";
	print "<input type=\"text\" name=\"txtSubject\" size=\"40\" maxlength=\"255\" />\n";
	print "</td>\n";
	print "</tr>\n";
	print "<tr valign=\"top\">\n";
	print "<td>\n";
	print "Comment\n";
	print "</td>\n";
	print "<td>\n";
	print "<textarea name=\"txtComment\" cols=\"40\" rows=\"5\"></textarea>\n";
	print "</td>\n";
	print "</tr>\n";
	print "<tr valign=\"top\">\n";
	print "<td>\n";
	print "</td>\n";
	print "<td>\n";
	print "<i>No html please.</i>";
	print "</td>\n";
	print "</tr>\n";
	print "<tr valign=\"top\">\n";
	print "<td>\n";
	print "</td>\n";
	print "<td>\n";
	print "</td>\n";
	print "</tr>\n";
	print "<tr valign=\"top\">\n";
	print "<td>\n";
	print "&nbsp;\n";
	print "</td>\n";
	print "<td>\n";
	print "<input type=\"checkbox\" name=\"chkNotify\" />Notify me when someone replies\n";
	print "</td>\n";
	print "</tr>\n";
	print "<input type=\"hidden\" name=\"txtStoryURL\" value=\"".$storyurl."\" />\n";
	print "<tr valign=\"top\">\n";
	print "<td>\n";
	print "</td>\n";
	print "<td>\n";
	print "</td>\n";
	print "</tr>\n";
	print "<tr valign=\"top\">\n";
	print "<td>\n";
	print "</td>\n";
	print "<td>\n";
	print "<input type=\"submit\" name=\"btnSubmit\" value=\"Add comment\">\n";
	print "</td>\n";
	print "</tr>\n";
	print "</form>\n";
	print "</table>\n";
	print "</body>\n";
	print "</html>\n";

}
?>