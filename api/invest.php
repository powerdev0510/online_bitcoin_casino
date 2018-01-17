<?php
require("config.php");

$user = $_POST["user"];
$user_id = $_POST["user_id"];
$amount = round(abs($_POST["amount"]),8);

if(isset($user) && isset($user_id) && isset($amount)){  
  //Authenticate
  $query = $db->prepare("SELECT `user_id`, `balance`, `invested`, `invested_profit`, `wagered`, `bets`, `profit`, `server_hash`, `shuffle_hash` FROM `Users` WHERE `user`=:user");
  $query->bindParam(":user", $user);
  $query->execute();
  $user_data = $query->fetchAll(PDO::FETCH_ASSOC)[0];
  
  if($user_id == $user_data["user_id"]){
    //Continue If Has Balance
    if($amount <= $user_data["balance"]){  
      
      //Remove From Balance
      $new_balance = round($user_data["balance"] - $amount,8);
      $query = $db->prepare("UPDATE `Users` SET `balance`=:new_balance, `invested`=:new_amount WHERE `user`=:user;");
      $query->bindParam(":user", $user);
      $query->bindParam(":new_balance", $new_balance);
      
      //New Invested Amount
      $new_amount = $user_data["invested"] + abs($amount);
      
      //Update TABLE `Users`
      $query->bindParam(":new_amount", $new_amount);
      $query->execute();
      
      //Update Table Bankroll
      $query = $db->prepare("UPDATE `Bankroll` SET `invested`=:new_amount WHERE `user`=:user;");
      $query->bindParam(":user", $user);
      $query->bindParam(":new_amount", $new_amount);
      $query->execute();
      
      print('{"action": true, "message": "You Invested ฿' . number_format($amount, 8, '.', '') . '!"}');
    } else {
      print('{"action": false, "message": "You Can\'t Invest More Than Your Balance of: ฿' . number_format($user_data["balance"], 8, '.', '') . '"}');
    }
  } else {
    //Not Valid
    print('{"action": false, "message": "user_id was not valid"}');
  }
} else {
  print('{"action": false, "message": "user and user_id was not valid or an amount wasn\'t submitted"}');
}
?>