<?php
/*
 *  Â© CoinDice 
 *  Demo: http://www.btcircle.com/dice
 *  Please do not copy or redistribute.
 *  More licences we sell, more products we develop in the future.  
*/


header('X-Frame-Options: DENY'); 

$included=true;
include '../../inc/db-conf.php';
include '../../inc/wallet_driver.php';
$wallet=new jsonRPCClient($driver_login);
include '../../inc/functions.php';

if (empty($_GET['_unique']) || mysqli_num_rows(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id` FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"))==0) exit();
$player=mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `players` WHERE `hash`='".prot($_GET['_unique'])."' LIMIT 1"));
$player['server_seed_']=$player['server_seed'];
$player['server_seed']=(double)substr($player['server_seed'],27);

$settings=mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT * FROM `system` LIMIT 1"));

if (!isset($_GET['w']) || (double)$_GET['w']<0 || (double)$_GET['w']>$player['balance']) {     // bet amount
  echo json_encode(array('error'=>'yes','data'=>'invalid_bet'));
  exit();
}
if (!isset($_GET['m']) || !is_numeric((double)$_GET['m']) || (double)$_GET['m']<1.01202 || (double)$_GET['m']>9900) {      // multiplier
  echo json_encode(array('error'=>'yes','data'=>'invalid_m'));
  exit();
}
if (!isset($_GET['hl']) || !is_int((int)$_GET['hl']) || ($_GET['hl']!=0 && $_GET['hl']!=1)) {       // high / low
  echo json_encode(array('error'=>'yes','data'=>'invalid_hl'));
  exit();
}


$wager=(double)$_GET['w'];
if ($wager<0.00000001 && $wager!=0) {
  echo json_encode(array('error'=>'yes','data'=>'too_small'));
  exit();
}
$reservedBalance=mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT SUM(`balance`) AS `balance`, SUM(`bankroll`) AS `bankroll` FROM `players`"));
$reservedWaitingBalance=mysqli_fetch_array(mysqli_query($GLOBALS["___mysqli_ston"], "SELECT SUM(`amount`) AS `sum` FROM `deposits`"));
$serverBalance=$wallet->getbalance();
$serverFreeBalance=($serverBalance-$reservedBalance['sum']-$reservedWaitingBalance['sum']);

$coinpool = $serverFreeBalance;

$jakynasobekminimalne=$settings['bankroll_maxbet_ratio'];

if (($wager*$jakynasobekminimalne)>$serverFreeBalance) {
  echo json_encode(array('error'=>'yes','data'=>'too_big_bet','under'=>($serverFreeBalance/$jakynasobekminimalne)));
  exit();
}


$multiplier=round((double)$_GET['m'],2);
$under_over=(int)$_GET['hl'];

$newBalance=$player['balance']-$wager;
$profit=-$wager;

$chance['under']=floor((1/($multiplier/100)*((100-$settings['house_edge'])/100))*100)/100;
$chance['over']=100-$chance['under'];

$result=round($player['server_seed'],2);

$win_lose=(($under_over==0 && $result<=$chance['under']) || ($under_over==1 && $result>=$chance['over']))?1:0;

if ($win_lose==1) {
  $newBalance+=$wager*$multiplier;
  $profit+=$wager*$multiplier;
}

$bankrollmul = -($profit / $serverFreeBalance);
$bankplayers = mysqli_query($GLOBALS["___mysqli_ston"], "SELECT `id`, `bankroll` FROM `players` WHERE `bankroll` > 0;");
while ($bankplayer = mysqli_fetch_array($bankplayers))
{
  $bankplayerprofit = $bankrollmul * $bankplayer['bankroll'];
  mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `players` SET `bankroll`=TRUNCATE(ROUND((`bankroll`+$bankplayerprofit),9),8), `bankroll_profit`=TRUNCATE(ROUND((`bankroll_profit`+$bankplayerprofit),9),8) WHERE `id` = ".$bankplayer['id'].";");
}
          
mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `players` SET `balance`=TRUNCATE(ROUND($newBalance,9),8),`t_bets`=`t_bets`+1,`t_wagered`=TRUNCATE(ROUND((`t_wagered`+$wager),9),8),`t_wins`=`t_wins`+$win_lose,`t_profit`=TRUNCATE(ROUND((`t_profit`+$profit),9),8) WHERE `id`=$player[id] LIMIT 1");
mysqli_query($GLOBALS["___mysqli_ston"], "INSERT INTO `bets` (`player`,`under_over`,`bet_amount`,`multiplier`,`result`,`win_lose`) VALUES ($player[id],$under_over,$wager,$multiplier,$result,$win_lose)");
mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `system` SET `t_bets`=`t_bets`+1,`t_wagered`=TRUNCATE(ROUND((`t_wagered`+$wager),9),8),`t_wins`=`t_wins`+$win_lose,`t_player_profit`=TRUNCATE(ROUND((`t_player_profit`+$profit),9),8) LIMIT 1");

//new seed

$newSeed=generateServerSeed();
mysqli_query($GLOBALS["___mysqli_ston"], "UPDATE `players` SET `last_server_seed`='$player[server_seed_]',`server_seed`='$newSeed' WHERE `id`=$player[id] LIMIT 1");


echo json_encode(array('error'=>'no','result'=>$result,'win_lose'=>$win_lose));

?>