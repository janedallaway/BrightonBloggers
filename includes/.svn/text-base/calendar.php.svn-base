<?

$meetup["day"] = 18;
$meetup["month"] = 12;
$meetup["year"] = 2003;

require "drawcalendar.php";

$calendar = DrawCalendar($meetup["month"],$meetup["year"]);

echo str_replace("<td>".$meetup["day"]."</td>","<td><a href=\"meetup/\" title=\"the next meetup\">".$meetup["day"]."</a></td>",$calendar);

?>