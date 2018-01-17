<?php
require("config.php");
require("../lib/hash.php");

$user = $_POST["user"];
$user_id = $_POST["user_id"];

if(isset($user) && isset($user_id)){
  //Authenticate
  $query = $db->prepare("SELECT `user_id` FROM `Users` WHERE `user` = :user;");
  $query->bindParam(":user", $user);
  $query->execute();
  $user_data = $query->fetchAll(PDO::FETCH_ASSOC)[0];
  
  if($user_id == $user_data["user_id"]){
    //Continue
    //Change Hash
    $new_shuffle_num = fresh_number();
    $new_shuffle_hash = sha_hash($new_shuffle_num);
    $query = $db->prepare("UPDATE `Users` SET `shuffle_num`=:new_shuffle_num, `shuffle_hash`=:new_shuffle_hash WHERE `user`=:user");
    $query->bindParam(":user", $user);
    $query->bindParam(":new_shuffle_num", $new_shuffle_num);
    $query->bindParam(":new_shuffle_hash", $new_shuffle_hash);
    $query->execute();
    print('{"action": true, "message": "Shuffle Hash Successfully Changed", "new_shuffle_hash": "' . $new_shuffle_hash . '"}');
  } else {
    print('{"action": false, "message": "user_id was not valid"}');
  }
} else {
  print('{"action": false, "message": "user and user_id was not valid"}');
}
?>