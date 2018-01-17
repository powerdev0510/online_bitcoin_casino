<?php
require("config.php");

$user = $_POST["user"];
$user_id = $_POST["user_id"];

if(isset($user) && isset($user_id)){
  //Authenticate
  $query = $db->prepare("SELECT `user_id`, `balance`, `invested`, `invested_profit`, `wagered`, `bets`, `profit`, `server_hash`, `shuffle_hash` FROM `Users` WHERE `user`=:user;");
  $query->bindParam(":user", $user);
  $query->execute();
  $user_data = $query->fetchAll(PDO::FETCH_ASSOC)[0];
  
  if($user_id == $user_data["user_id"]){
    //Print Data
    print(json_encode($user_data));
  } else {
    print('{"action": false, "message": "user_id was not valid"}');
  }
} else {
  print('{"action": false, "message": "user and user_id was not valid"}');
}
?>