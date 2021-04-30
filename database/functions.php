<?php
session_start();
include 'db.php';
$mysqli = new mysqli($dbServer,$dbUser,$dbPass,$db);
	
	if (mysqli_connect_errno()) 
	{
		echo "Connect failed: " . mysqli_connect_error();
		exit();
	}

class Card
{
	public $name;
	public $type;
	public $number;
	public $count;
	public $hit_dice;
	public $health;
	public $cost;
	public $ranged;
}
$r=rand(0,5);
echo $r;
echo "<br>";
$card= new Card();
$sql="SELECT * FROM cards WHERE cards.number = '".$r."'";
echo $sql;
		$result=$mysqli->query($sql);

			$rows=$result->fetch_assoc();
			$card->name=$rows['name'];
			$card->type=$rows['type'];
			$card->number=$rows['number'];
			$card->count=$rows['count'];
			$card->hit_dice=$rows['hit_dice'];
			$card->health=$rows['health'];
			$card->cost=$rows['cost'];
			$card->ranged=$rows['ranged'];
echo "<br>";
echo "Name: ";
echo $card->name;
echo "<br>";
echo "Type: ";
echo $card->type;
echo "<br>";
echo "Number For Sprite: ";
echo $card->number;
echo "<br>";
echo "Amount Of Cards: ";
echo $card->count;
echo "<br>";
echo "Hit Dice: ";
echo $card->hit_dice;
echo "<br>";
echo "Health: ";
echo $card->health;
echo "<br>";
echo "Cost: ";
echo $card->cost;
echo "<br>";
echo "Ranged: ";
echo $card->ranged;
echo "<br>";




?>

<form action= "output.php" method="POST" id="formresults">

<input type="hidden" name="name" value="<?=$card->name?>">
<input type="hidden" name="type" value="<?=$card->type?>">
<input type="hidden" name="hit_dice" value="<?=$card->hit_dice?>">


	<button type="submit"> Submit </button>