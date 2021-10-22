<?php
session_start();
if(!isset($_SESSION["uuidv4"])){
    header("location: ../login/");
    exit();
}

if($_SESSION["type"] != 2){
    header("location: ../settings/");
    exit();
}

$NAME = $_GET["n"];
$SURNAME = $_GET["s"];
$UUID = $_GET["u"];
$EMAIL = $_GET["e"];
$PASSWORD = $_GET["p"];
$BIRTH = $_GET["b"];
$CLASS = $_GET["c"];
$SCHOOL_ID = $_GET["sid"];
$MEMBER = 0; //student


include_once '../includes/dbh.inc.php';
include_once '../includes/functions.inc.php';

createMember($conn, $NAME, $SURNAME, $UUID, $EMAIL, $PASSWORD, $BIRTH, $CLASS, $SCHOOL_ID, $MEMBER);
