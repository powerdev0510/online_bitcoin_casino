<?php
require("config.php");
require("../lib/hash.php");

$start_time = microtime();
usleep(250000);

$user = $_POST["user"];
$user_id = $_POST["user_id"];
$amount = abs(floatval($_POST["amount"]));
$multiplier = abs(floatval($_POST["multiplier"]));
$prediction = strtolower($_POST["prediction"]);
$profit_on_win = $multiplier * $amount;

//Not signed in
if(isset($user)){
  //Nothing
} else {
  print('{"action": false, "message": "Create an account before betting"}');
  exit;
}

//No multiplier
if($multiplier <= 1.02 || $multiplier >= 999){
  print('{"action": false, "message": "Multiplier needs to be greater than 1.02 and less than 999"}');
  exit;
}

if(isset($user) && isset($user_id) && isset($amount) && isset($multiplier) && isset($prediction)){
  //Authenticate
  $query = $db->prepare("SELECT * FROM `Users` WHERE `user` = :user");
  $query->bindParam(":user", $user);
  $query->execute();
  $user_data = $query->fetchAll(PDO::FETCH_ASSOC)[0];
  
  if($user_id == $user_data["user_id"]){
    //Continue
    //Balance
    if($amount > $user_data["balance"]){
      print('{"action": false, "message": "You Do Not Have The Sufficient Funds To Make This Wager"}');
      exit;
    }
    
    //Prediction
    if($prediction != "under" && $prediction != "over"){
      print('{"action": false, "message": "prediction not valid"}');
      exit;
    }
    
    //Get Bankroll Sum
    $query = $db->prepare("SELECT SUM(`invested`) FROM `Bankroll`");
    $query->execute();
    $bankroll = floatval($query->fetchAll(PDO::FETCH_ASSOC)[0]["SUM(`invested`)"]);
    $max_win = $bankroll / 200;
    
    //Exceeds Max Win
    if($profit_on_win - $amount > $max_win){
      print('{"action": false, "message": "Your Bet\'s Potential Win Exceeds 0.5% of the Bankroll: ฿' . number_format($max_win, 8, '.', '') .  '"}');
      exit;
    }
    
    
    //If Bet Makes It To Here, Continue
    $number = 99 / $multiplier;
    
    //Get Roll
    $roll = abs($user_data["server_num"]);
    
    $shuffle_num = floatval($user_data["shuffle_num"][0]);
    $roll = floatval(substr($roll, $shuffle_num, 4)) / 100;
    
    $outcome = NULL;
    $format_prediction = NULL;
    //Determine Win
    if($prediction == "under"){
      //UNDER
      $number = $number;
      $format_prediction = "$number <";
      if($roll < $number){
        $outcome = "WIN";
      } else {
        $outcome = "LOSS";
      }
    } else {
      //OVER
      $number = 100 - $number;
      $format_prediction = "$number >";
      if($roll > $number){
        $outcome = "WIN";
      } else {
        $outcome = "LOSS";
      }
    }
    
    //Bet Info
    //Times
    $time = $date->format('H:i:s');
    $timestamp = time();
    //Profit
    if($outcome == "WIN"){
      $profit = $profit_on_win - $amount;
    } else {
      $profit = -$amount;
    }
    $profit = $profit;
        
    //Update User Balance
    $user_balance = $user_data["balance"] + $profit;
    $query = $db->prepare("UPDATE `Users` SET `balance`=:balance WHERE `user`=:user");
    $query->bindParam(":user", $user);
    $query->bindParam(":balance", $user_balance);
    $query->execute();
    
    
    //Bet ID
    $query = $db->prepare("SELECT `id` FROM `Bets` ORDER BY `id` DESC LIMIT 1");
    $query->execute();
    $id = floatval($query->fetchAll(PDO::FETCH_ASSOC)[0]["id"]) + 1;
    
    //Site and Bankroll Update
    $query = $db->prepare("UPDATE `Site` SET `bets`=`bets`+1,`wagered`=`wagered`+:amount, `investor_profit`=`investor_profit`-:profit, `unrealized`=`unrealized`-:profit");
    $query->bindParam(":amount", $amount);
    $query->bindParam(":profit", $profit);
    $query->execute();
    
    //Multiplier
    $multiplier = (string)$multiplier . "x";
    
    //Format Variables
    $id = intval($id);
    $timestamp = intval($timestamp);
    $amount = number_format($amount, 8, '.', '');
    $roll = number_format($roll, 2, '.', '');
    $profit = number_format($profit, 8, '.', '');
    
    //Roll Info
    $server_num = abs($user_data["server_num"]);
    $server_hash = $user_data["server_hash"];
    $shuffle_num = abs($user_data["shuffle_num"]);
    $shuffle_hash = $user_data["shuffle_hash"];
    
    //Insert Into Bets
    $query = $db->prepare("
    INSERT INTO `Bets`(`id`, `user`, `time`, `timestamp`, `bet`, `multiplier`, `prediction`, `roll`, `profit`, `outcome`, `server_num`, `server_hash`, `shuffle_num`, `shuffle_hash`)
    VALUES(:id, :user, :time, :timestamp, :bet, :multiplier, :prediction, :roll, :profit, :outcome, :server_num, :server_hash, :shuffle_num, :shuffle_hash)
    ");
    $query->bindParam(":id", $id);
    $query->bindParam(":user", $user);
    $query->bindParam(":time", $time);
    $query->bindParam(":timestamp", $timestamp);
    $query->bindParam(":bet", $amount);
    $query->bindParam(":multiplier", $multiplier);
    $query->bindParam(":prediction", $format_prediction);
    $query->bindParam(":roll", $roll);
    $query->bindParam(":profit", $profit);
    $query->bindParam(":outcome", $outcome);
    $query->bindParam(":server_num", $server_num);
    $query->bindParam(":server_hash", $server_hash);
    $query->bindParam(":shuffle_num", $shuffle_num);
    $query->bindParam(":shuffle_hash", $shuffle_hash);
    $query->execute();
    
    //New Hashes
    $new_server_num = fresh_number();
    $new_server_hash = sha_hash($new_server_num);
    
    $new_shuffle_num = fresh_number();
    $new_shuffle_hash = sha_hash($new_shuffle_num);
    
    //Users
    $query = $db->prepare("UPDATE `Users` SET `wagered`=`wagered` + :wager, `bets`=`bets` + 1, `profit`=`profit`+:profit, `server_num`=:new_server_num, `server_hash`=:new_server_hash, `shuffle_num`=:new_shuffle_num, `shuffle_hash`=:new_shuffle_hash WHERE `user`=:user;");
    $query->bindParam(":user", $user);
    $query->bindParam(":wager", $amount);
    $query->bindParam(":profit", $profit);
    $query->bindParam(":new_server_num", $new_server_num);
    $query->bindParam(":new_server_hash", $new_server_hash);
    $query->bindParam(":new_shuffle_num", $new_shuffle_num);
    $query->bindParam(":new_shuffle_hash", $new_shuffle_hash);
    $query->execute();
    
    //Bet Info
    $return_array = [];
    $return_array["action"] = true;
    $return_array["id"] = $id;
    $return_array["user"] = $user;
    $return_array["time"] = $time;
    $return_array["bet"] = $amount;
    $return_array["multiplier"] = $multiplier;
    $return_array["prediction"] = $prediction;
    $return_array["roll"] = $roll;
    $return_array["profit"] = $profit;
    $return_array["outcome"] = $outcome;
    print(json_encode($return_array));
  } else {
    print('{"action": false, "message": "user_id was not valid"}');
  }
} else {
  print('{"action": false, "message": "a parameter is missing"}');
}

//print(microtime() - $start_time);
?>