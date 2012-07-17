<?
function DayOfWeek($month,$year) {
	$firstofthemonth = strtotime("$month/01/$year");
	$firstofthemonthArray = getdate($firstofthemonth);
	$startday = $firstofthemonthArray['wday'];
	return $startday;
}

function DaysInMonth($month, $year) {
 for ($i = 31; $i > 0; $i--) {
  if (checkdate($month, $i, $year)) {
   return $i;
  }
 }
 return 0;
}

function DrawCalendar($month, $year) {

	$DateArray = getdate(strtotime("$month/01/$year"));
	
	$calendar.="<table class=\"calendar\" summary=\"A calendar for ".$DateArray['month']."\"> \n";
	$calendar.="<caption>";
	$calendar.= $DateArray['month']." ".$year;
	$calendar.="</caption>\n";
	$calendar.="<tr>\n";
	$calendar.="<th abbr=\"Sunday\">Sun</th>";
	$calendar.="<th abbr=\"Monday\">Mon</th>";
	$calendar.="<th abbr=\"Tuesday\">Tue</th>";
	$calendar.="<th abbr=\"Wednesday\">Wed</th>";
	$calendar.="<th abbr=\"Thursday\">Thu</th>";
	$calendar.="<th abbr=\"Friday\">Fri</th>";
	$calendar.="<th abbr=\"Saturday\">Sat</th>";
	$calendar.="\n</tr>\n";

	$dayofweek = DayOfWeek($month, $year);
	$daysinmonth = DaysInMonth($month, $year);
	$lastcell = ( ceil(($daysinmonth + $dayofweek) / 7 )*7 );
	for($i = 0; $i < $lastcell; $i = $i + 1) {
		if ( $i % 7 == 0)
		  $calendar.="<tr>\n";
		if ($i < $dayofweek OR $i > $daysinmonth + $dayofweek -1)
		  $calendar.= "<td>&nbsp;</td>";
		else
		{
			$date = $i - $dayofweek +1;
			$calendar.="<td>$date</td>";
		}
		if( (($i+1)%7) == 0)
			$calendar.="\n</tr>\n";
	}
	$calendar.="</table>\n";
	
	return $calendar;
}

?>