<?php
/*
	bbdb.php
	Jane		3 Sept 2003	Created

	Modification History
	~~~~~~~~~~~~~~~~~~~~
 */

	function InitialiseDB()
	{
		//set up the connection string
		$db = mysql_connect("localhost","brightonbloggers","cs1de");

		//specify the db
		mysql_select_db("brightonbloggers",$db);

		return $db;
	}

?>