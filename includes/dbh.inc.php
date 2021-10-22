<?php
$server = "localhost";
$user = "root";
$pass = "";
$dbname = "szkola.online";

$conn = mysqli_connect($server, $user, $pass, $dbname);

if(!$conn){
    die("Wystąpił nieznany błąd!");
}