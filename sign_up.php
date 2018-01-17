<?php
session_start();
require("config.php");
require("lib/hash.php");
require("lib/easy_bitcoin.php");

$username = $_POST["sign_up_username"];
$email = $_POST["sign_up_email"];
$password = $_POST["sign_up_password"];

if(isset($username) && isset($email) && isset($password)){
  //Continue
  //User Info
  
  //Id
  $user_id = generateRandomString(20);
  
  //Sha Hashes
  $server_num = abs(fresh_number());
  $server_hash = sha_hash($server_num);
  $shuffle_num = fresh_number();
  $shuffle_hash = sha_hash($shuffle_num);

  //Bitcoin Address
  $bitcoin_address = $bitcoin->getaccountaddress($username);
  
  try {
    //Create User In TABLE `Users`
    $query = $db->prepare("
      INSERT INTO `Users`(`user`, `email`, `password`, `user_id`, `balance`, `invested`, `invested_profit`, `wagered`, `bets`, `profit`, `bitcoin_address`, `server_num`, `server_hash`, `shuffle_num`, `shuffle_hash`) 
      VALUES(:user, :email, :password, :user_id, 50, 0, 0, 0, 0, 0, :bitcoin_address, :server_num, :server_hash, :shuffle_num, :shuffle_hash)
    ;");
    $query->bindParam(":user", $username);
    $query->bindParam(":email", $email);
    $query->bindParam(":password", $password);
    $query->bindParam(":user_id", $user_id);
    $query->bindParam(":bitcoin_address", $bitcoin_address);
    $query->bindParam(":server_num", $server_num);
    $query->bindParam(":server_hash", $server_hash);
    $query->bindParam(":shuffle_num", $shuffle_num);
    $query->bindParam(":shuffle_hash", $shuffle_hash);
    $query->execute();

    $_SESSION["user"] = $username;
    $_SESSION["user_id"] = $user_id;
    $_SESSION["logged_in"] = TRUE;
    
    //Create User in TABLE `Bankroll`
    $query = $db->prepare("
    INSERT INTO `Bankroll`(`user`, `invested`, `invested_profit`)
    VALUES(:user,0,0)
    ;");
    $query->bindParam(":user", $username);
    $query->execute();

    header("Location: index.php");
  } catch (Exception $e) {
    header("Location: index.php?message=This username has already been taken");
    exit;
  }
} else {
  header("Location: index.php?message=The username, email, or password supplied was invalid");
  exit;
}
?>