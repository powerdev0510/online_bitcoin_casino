<?php
//ini_set('display_errors', 1);
//error_reporting(~0);

date_default_timezone_set("America/New_York");
$date = new DateTime();

$servername = "Put your own host here";
$serverusername = "Put your own user here";
$serverDatabase = "Put your own database here";
$serverpassword = "Put your own password here";

try {
	$db = new PDO("mysql:host=$servername;dbname=$serverDatabase", $serverusername, $serverpassword);
	$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
	$db->exec("SET NAMES 'utf8'");
}	catch(PDOException $e) {
	echo "Connection failed: " . $e->getMessage();
	exit;
}

function insert($query){
	global $db;
	$db->query($query);
}

function select($query){
	global $db;
	$data = $db->query($query);
	$data = $data->fetchAll(PDO::FETCH_ASSOC);
	return $data;
}

function generateRandomString($length) {
  $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
  $charactersLength = strlen($characters);
  $randomString = '';
  for ($i = 0; $i < $length; $i++) {
    $randomString .= $characters[rand(0, $charactersLength - 1)];
  }
  return $randomString;
}
?>