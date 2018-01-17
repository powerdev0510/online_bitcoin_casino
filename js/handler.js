//Alert Close Function
function alert_close(){
  $("#alert").fadeOut(1000);
}

//Alert Click to Close
$("#alert #message").click(function(){
  console.log("Alert Message Close");
  alert_close();
})

//Withdraw
$("#submit_withdrawal").click(function(event){
  console.log("Submit withdrawal click");
  event.preventDefault();
});

//Invest
$("#submit_investment").click(function(event){
  console.log("Submit investment click");
  event.preventDefault();
  
  var user_data = JSON.parse($("#user_data").text());
  var user = user_data.user;
  var user_id = user_data.user_id;
  
  //Invest Amount
  var amount = Math.abs(parseFloat($("#invest_amount").val())).toFixed(8);
  
  if(amount > 0){
    var request = $.ajax({
      url: "api/invest.php",
      dataType: "JSON",
      return_data: self.responseText,
      type: "POST",
      data: {
        user: user,
        user_id: user_id,
        amount: amount
      },
      success: function(return_data) {
        //Alert
        //Text
        $("#alert #message").text(return_data.message);
        //Status (Good or Bad)
        if(return_data.action === true){
          $("#alert #message").attr("class", "good_alert");
          
          //Update Maximum Investment
          var previous_max = $("#invest_amount").attr("placeholder");
          var max_invest = parseFloat(previous_max.replace(/[^\d.-]/g, ''));
          max_invest = (max_invest - amount).toFixed(8);
          
          //Set Maximum Investment
          var text = "Maximum Investment: ฿" + max_invest;
          $("#invest_amount").attr("placeholder", text);
        } else {
          $("#alert #message").attr("class", "bad_alert");
        }
        $("#alert").fadeIn(250);
        
        //Alert Hide Timeout
        setTimeout(function(){alert_close();}, 2500);
      }
    })
  } else {
    $("#alert #message").text("Please Enter a Valid Investment Amount");
    $("#alert #message").attr("class", "bad_alert");
    
    $("#alert").fadeIn(250);
    //Alert Hide Timeout
    setTimeout(function(){alert_close();}, 2500);
  }
  
  $("#invest_amount").val("")
});

//Divest
$("#submit_divestment").click(function(event){
  console.log("Submit divestment click");
  event.preventDefault();
  
  var user_data = JSON.parse($("#user_data").text());
  var user = user_data.user;
  var user_id = user_data.user_id;
  
  //Divest Amount
  var amount = Math.abs(parseFloat($("#divest_amount").val())).toFixed(8);
  
  if(amount > 0){
    var request = $.ajax({
      url: "api/divest.php",
      dataType: "JSON",
      return_data: self.responseText,
      type: "POST",
      data: {
        user: user,
        user_id: user_id,
        amount: amount
      },
      success: function(return_data) {
        //Alert
        //Text
        $("#alert #message").text(return_data.message);
        //Status (Good or Bad)
        if(return_data.action === true){
          $("#alert #message").attr("class", "good_alert");
          
          //Update Maximum Divestment
          var previous_max = $("#divest_amount").attr("placeholder");
          var max_invest = parseFloat(previous_max.replace(/[^\d.-]/g, ''));
          max_invest = (max_invest - amount).toFixed(8);
          
          //Set Maximum Divestment
          var text = "Maximum Divestment: ฿" + max_invest;
          $("#divest_amount").attr("placeholder", text);
        } else {
          $("#alert #message").attr("class", "bad_alert");
        }
        $("#alert").fadeIn(250);
        
        //Alert Hide Timeout
        setTimeout(function(){alert_close();}, 2500);
      }
    })
  } else {
    $("#alert #message").text("Please Enter a Valid Divestment Amount");
    $("#alert #message").attr("class", "bad_alert");
    
    $("#alert").fadeIn(250);
    //Alert Hide Timeout
    setTimeout(function(){alert_close();}, 2500);
  }
  
  $("#divest_amount").val("");
})

//Change Shuffle Hash
$("#change_shuffle_seed").click(function(event){
  console.log("Change shuffle seed click");
  event.preventDefault();
  
  var user_data = JSON.parse($("#user_data").text());
  var user = user_data.user;
  var user_id = user_data.user_id;
  
  var request = $.ajax({
    url: "api/change_hash.php",
    dataType: "JSON",
    return_data: self.responseText,
    type: "POST",
    data: {user:user, user_id:user_id},
    success: function(return_data){
      //console.log(return_data);
      //Replace new_shuffle_hash
      $("#shuffle_hash").val(return_data.new_shuffle_hash);
      
      //Alert
      //Text
      $("#alert #message").text(return_data.message);
      //Status (Good or Bad)
      if(return_data.action == true){
        $("#alert #message").attr("class", "good_alert");
      } else {
        $("#alert #message").attr("class", "bad_alert");
      }
      $("#alert").fadeIn(250);
      
      //Alert Hide Timeout
      setTimeout(function(){alert_close();}, 2500);
    }
  })
});