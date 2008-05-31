<?php

include ("common.php");
include ("bbdb.php");
include ("comments.php");

  print "Included";
  showcomments("me");
  print "me done";
  showcomments("http://www.brightonbloggers.com/blog/archives/2003_09_01_index.php#106257996090903948");
?>