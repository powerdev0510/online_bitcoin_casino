//Deposit
$("#deposit").click(function(){
  console.log("Show deposit overlay");
  $(".overlay").fadeIn(500);
  $("#deposit_overlay").fadeIn(500);
});

//Withdraw
$("#withdraw").click(function(){
  console.log("Show withdraw overlay");
  $(".overlay").fadeIn(500);
  $("#withdraw_overlay").fadeIn(500);
});

//Provably Fair
$("#fair").click(function(){
  console.log("Show provably fair overlay");
  
  var user_data = JSON.parse($("#user_data").text());
  var user = user_data.user;
  var user_id = user_data.user_id;
  
  var request = $.ajax({
    url: "api/user_data.php",
    dataType: "JSON",
    return_data: self.responseText,
    type: "POST",
    data: {user:user, user_id:user_id},
    success: function(return_data){
      var server_hash = return_data.server_hash;
      var shuffle_hash = return_data.shuffle_hash;
      
      $("#server_hash").val(server_hash);
      $("#shuffle_hash").val(shuffle_hash);
    }
  })
  
  $(".overlay").fadeIn(500);
  $("#provably_fair_overlay").fadeIn(500);
})

//Invest In House
$("#invest_invest").click(function(){
  console.log("Show invest overlay");
  
  var user_data = JSON.parse($("#user_data").text());
  var user = user_data.user;
  var user_id = user_data.user_id;
  
  var request = $.ajax({
    url: "api/user_data.php",
    dataType: "JSON",
    return_data: self.responseText,
    type: "POST",
    data: {user:user, user_id:user_id},
    success: function(return_data){
      //Format Balance
      var balance = parseFloat(return_data.balance).toFixed(8);
      
      //Set Maximum Investment
      var text = "Maximum Investment: ฿" + balance;
      $("#invest_amount").attr("placeholder", text);
    }
  })
  
  $(".overlay").fadeIn(500);
  $("#invest_in_house_overlay").fadeIn(500);
});

//Divest From House
$("#invest_divest").click(function(){
  console.log("Show divest overlay");
  
  var user_data = JSON.parse($("#user_data").text());
  var user = user_data.user;
  var user_id = user_data.user_id;
  
  var request = $.ajax({
    url: "api/user_data.php",
    dataType: "JSON",
    return_data: self.responseText,
    type: "POST",
    data: {user:user, user_id:user_id},
    success: function(return_data){
      //Format Balance
      var invested = parseFloat(return_data.invested).toFixed(8);
      
      //Set Maximum Investment
      var text = "Maximum Divestment: ฿" + invested;
      $("#divest_amount").attr("placeholder", text);
    }
  })
  
  $(".overlay").fadeIn(500);
  $("#divest_from_house_overlay").fadeIn(500);
});

//Close Overlay
$(".overlay .close").click(function(){
  console.log("Overlay Close");
  var overlay_tabs = ["#deposit_overlay, #withdraw_overlay", "#provably_fair_overlay", "#invest_in_house_overlay", "#divest_from_house_overlay"];
  
  for (var i = 0; i < overlay_tabs.length; i++) { 
    var overlay_tab = overlay_tabs[i];
    $(overlay_tab).fadeOut(500);
  }
  $(".overlay").fadeOut(500);
});