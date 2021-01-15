<?php
class HOGE{
var $a="11";
 function hage($test){
  $a = $test;
 }
}
$fuga = new HOGE;
$fuga->hage("bb");
echo $fuga->a;
echo "########";

?>

