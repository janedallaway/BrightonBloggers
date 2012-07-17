<?php
/*
	common.php
	Jane		26 May 2002	Created

	Modification History
	~~~~~~~~~~~~~~~~~~~~
 */


	function ExecuteSQL($sql)

	{

		$result = mysql_query ($sql) or die ("Invalid query -".$sql);
		return $result;

	}



	function SQLValue($sql,$key = 0)
	{

		$bpdb = InitialiseDB();

		if ($result = mysql_query($sql, $bpdb))
		{
			//should be just the one row

			if ($row = mysql_fetch_row($result))
			{
				return $row[$key];
			}
		}
		else
		{
			print "Error happened<br />\n";
		}
	}

	function PreserveBreaks($string)
	{

		//assume that string contains line breaks.  Want to convert those into <br /> or <p /> tags
		return nl2br($string);

	}


	function SQLSafeString($string)
	{

		if (!get_magic_quotes_gpc())
		{
			$string = mysql_escape_string($string);
		}
		if (strlen($string) == 0)
		{
			$string = "NULL";
		}
		else
		{
			$string = "'".$string."'";
		}

		return $string;
	}

	function SortOutEntities($string)
	{
		$string = htmlentities($string);
		//replace &pound; with &#163;

		$string = str_replace("&pound;","&#163;",$string);
		$string = str_replace("&acute;",";",$string);

		return $string;
	}

?>

