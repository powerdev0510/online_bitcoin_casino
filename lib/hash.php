<?php
function sha_hash($number){
  return hash('sha256', $number);
}

function fresh_number(){
  $random_number = rand(0,1000000000000000);
  return $random_number;
}
?>