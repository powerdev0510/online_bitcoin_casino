<?php
require("config.php");

$data = [];

//Site
$query = $db->prepare("SELECT `bets`, `wagered`, `investor_profit`, `invested` FROM `Site`;");
$query->execute();
$data["site"] = $query->fetchAll(PDO::FETCH_ASSOC)[0];

//Bets
$query = $db->prepare("SELECT `id`, `user`, `time`,  `bet`, `multiplier`, `prediction`, `roll`, `profit` FROM `Bets` ORDER BY `id` DESC LIMIT 30");
$query->execute();
$data["bets"] = array_reverse($query->fetchAll(PDO::FETCH_ASSOC));
  
print(json_encode($data));
?>
