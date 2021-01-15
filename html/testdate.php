<?php
$m = 11;
$y=2011;
$limitday=21;
         $nenDo = date("Y",mktime(0,0,0,$m,$limitday,$y));
         $gatuDo = date("m",mktime(0,0,0,$m+1,$limitday,$y));
 echo "$nenDo / $gatuDo";
?>
