//Alert Close Function
function alert_close(){
  $("#alert").fadeOut(1000);
}

//Alert Click to Close
$("#alert #message").click(function(){
  console.log("Alert Message Close");
  alert_close();
})

$('.numerical').keypress(function (e) {
    var regex = new RegExp("^[Z0-9.]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }

    e.preventDefault();
    return false;
});

//Bet Amount
$("#gamble_interface #bet_amount").on("keyup",function(){
  console.log("Bet Amount Keyup");
  var bet_amount = $("#gamble_interface #bet_amount").val();
  var multiplier = parseFloat($("#gamble_interface #bet_payout_multiple").val());
  var bet_profit_on_win = bet_amount * multiplier;
  $("#gamble_interface #bet_profit_on_win").val(bet_profit_on_win.toFixed(8));
});

//Invert Prediction
$("#bet_prediction").click(function(event){
  console.log("Invert prediction");
  event.preventDefault();
  
  var over_or_under = $("#bet_prediction #over_or_under").text();
  var number = parseFloat($("#bet_prediction #number").text());
  
  //Invert
  if(over_or_under == "UNDER"){
    over_or_under = "OVER";
    number = 100 - number;
  } else if (over_or_under == "OVER"){
    over_or_under = "UNDER";
    number = 100 - number;
  }
  
  $("#bet_prediction #over_or_under").text(over_or_under);
  $("#bet_prediction #number").text(number.toFixed(2));
});

//Payout Multiple
$("#bet_payout_multiple").change(function(){
  console.log("Bet Payout Multiple Keyup");
  
  //Add x to end
  var payout_multiple = $("#bet_payout_multiple").val().replace("x", "");
  payout_multiple = parseFloat(payout_multiple);
  $("#bet_payout_multiple").val(payout_multiple.toFixed(3).toString() + "x");
  
  //Win Chance
  var win_chance = 99 / payout_multiple;
  $("#bet_probability").val(win_chance.toFixed(2).toString() + "%");
  
  //Prediction
  var over_or_under = $("#bet_prediction #over_or_under").text();
  
  if(over_or_under == "UNDER"){
    $("#bet_prediction #number").text(win_chance.toFixed(2));
  } else if(over_or_under == "OVER"){
    $("#bet_prediction #number").text((100 - win_chance).toFixed(2));
  }
  
  //Profit on Win
  var bet_amount = $("#gamble_interface #bet_amount").val();
  var bet_profit_on_win = bet_amount * payout_multiple
  $("#gamble_interface #bet_profit_on_win").val(bet_profit_on_win.toFixed(8));
});

//Win Change
$("#bet_probability").change(function(){
  console.log("Bet Probability Change");
  
  //Add % to end
  var bet_probability = $("#bet_probability").val().replace("%", "");
  bet_probability = parseFloat(bet_probability);
  $("#bet_probability").val(bet_probability.toFixed(2).toString() + "%");
  
  //Invert
  var over_or_under = $("#bet_prediction #over_or_under").text();
  var number = parseFloat($("#bet_prediction #number").text());
  
  if(over_or_under == "UNDER"){
    var bet_number = bet_probability.toFixed(2);
    $("#bet_prediction #number").text(bet_number);
  } else if(over_or_under == "OVER"){
    var bet_number = (100 - bet_probability).toFixed(2);
    $("#bet_prediction #number").text(bet_number);
  }
  
  //Payout Multiple
  var payout_multiple = ((99 / bet_probability).toFixed(3));
  $("#bet_payout_multiple").val(payout_multiple.toString() + "x");
  
  //Profit On Win
  var bet_amount = $("#gamble_interface #bet_amount").val();
  var bet_profit_on_win = bet_amount * payout_multiple;
  $("#gamble_interface #bet_profit_on_win").val(bet_profit_on_win.toFixed(8));
});

//Submit Bet
$("#gamble_interface #submit_bet").click(function(event){
  console.log("Submit bet");
  event.preventDefault();
  
  //User Data
  var user_data = JSON.parse($("#user_data").text());
  var user = user_data.user;
  var user_id = user_data.user_id;
  
  var amount = parseFloat($("#bet_amount").val()).toFixed(8);
  var prediction = $("#bet_prediction #over_or_under").text().toLowerCase();
  var multiplier = parseFloat($("#bet_payout_multiple").val());
  
  //Low Bet
  if(parseFloat(amount) == 0){
    $("#alert #message").text("Please Bet An Amount Greater Than 0.00000000");
    $("#alert #message").attr("class", "bad_alert");
    
    $("#alert").fadeIn(250);
    //Alert Hide Timeout
    setTimeout(function(){alert_close();}, 2500);
    console.log("Bet Terminated: Low Amount");
    return
  }
  
  //Low Multiplier
  if(parseFloat(multiplier) < 1.02 || parseFloat(multiplier) >= 999){
    $("#alert #message").text("Please Enter A Multiplier Between 1.02 and 999");
    $("#alert #message").attr("class", "bad_alert");
    
    $("#alert").fadeIn(250);
    //Alert Hide Timeout
    setTimeout(function(){alert_close();}, 2500);
    console.log("Bet Terminated: Invalid Multiplier");
    return
  }
  
  console.log(amount, prediction, multiplier, user, user_id);
  
  var request = $.ajax({
    url: "api/bet.php",
    dataType: "JSON",
    return_data: self.responseText,
    type: "POST",
    data: {user:user, user_id:user_id, amount:amount, prediction:prediction, multiplier: multiplier},
    success: function(return_data){
      console.log(return_data);
    }
  })
  
  console.log("Finished function");
});