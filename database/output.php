<?php
session_start();
include 'db.php'


$type=filter_input(INPUT_GET, 'type');
$name=filter_input(INPUT_GET, 'name');
$hit_dice=filter_input(INPUT_GET, 'hit_dice');

$mysqli = new mysqli($dbServer, $dbUser, $dbPass, $db);

if (mysqli_connect_errno()) {
    echo "connection failed";
    exit();
}
echo $type;
echo $name;
echo $hit_dice;

?>