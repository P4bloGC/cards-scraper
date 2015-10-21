<?php
$dbhost = "localhost";
$database="vanguard";
$user="root";
$password="";

// Conectarse a la base de datos
$dbconn = new mysqli($dbhost,$user,$password,$database);
$dbconn->set_charset("utf8");
?>