<?php
session_start();
unset($_SESSION["idUser"]);
header("Location: ./firstpage.php");
exit();