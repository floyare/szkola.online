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
$SUBJECT = $_GET["sub"];
$SCHOOL_ID = $_GET["sid"];
$MEMBER = 1; //teacher


include_once '../includes/dbh.inc.php';
include_once '../includes/functions.inc.php';

createMember($conn, $NAME, $SURNAME, $UUID, $EMAIL, $PASSWORD, $SUBJECT, null, $SCHOOL_ID, $MEMBER);
