<?php

$json = file_get_contents('../include/pages.json');
$pageName = basename($_SERVER['PHP_SELF']);
$obj = json_decode($json);

if(in_array($pageName, $obj->userpages)){
    require 'append.php';
}