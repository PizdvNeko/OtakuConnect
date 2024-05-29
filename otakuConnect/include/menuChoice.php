<?php
// read pages.json into $json which is a string
/*echo $_SERVER['PHP_SELF'];
echo substr_count($_SERVER['PHP_SELF'], '/');
if(substr_count($_SERVER['PHP_SELF'], '/') == 4){
    echo 'dentro lo if!!!!';*/
$json = file_get_contents('../include/pages.json');

// get the name of the current page
$pageName = basename($_SERVER['PHP_SELF']);

$obj = json_decode($json);
// in_array(el, arr) checks if el is in array arr

if(in_array($pageName, $obj->userpages)){
    require 'session.php';
    // include ad.php;
}elseif(in_array($pageName, $obj->phponlypages)){
    include 'DBhandler.php';
}
//}