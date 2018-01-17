<?php
session_start();
require("config.php");

$user = $_POST["login_user"];
$password = $_POST["login_password"];

$query = $db->prepare("SELECT `user`, `user_id`, `password` FROM `Users` WHERE `user` = :user OR `email` = :user");
$query->bindParam(":user", $user);
$query->execute();
$data = $query->fetchAll(PDO::FETCH_ASSOC)[0];

if($data["password"] == $password){
  $_SESSION["user"] = $data["user"];
  $_SESSION["user_id"] = $data["user_id"];
  $_SESSION["logged_in"] = TRUE;
  
  header("Location: index.php");
} else {
  header("Location: index.php?login=failed");
}
?>