<?php
ini_set('display_errors', 1);
error_reporting(~0);

require('easy_bitcoin.php');
var_dump($bitcoin->getaccountaddress("1asd.1"));
?>
KO