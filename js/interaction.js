//Global
$('.key_limit').keypress(function (e) {
    var regex = new RegExp("^[a-zA-Z0-9]+$");
    var str = String.fromCharCode(!e.charCode ? e.which : e.charCode);
    if (regex.test(str)) {
        return true;
    }

    e.preventDefault();
    return false;
});

$("input").focus(function(){
  $(this).attr("class", "form_clicked");
})

//Sign Up
$("#sign_up_link").click(function(){
  $("#login_popup").hide(500);
  $("#sign_up_popup").show(500);
})

$("#sign_up_popup form button").click(function(event){
  var username = $("#sign_up_username").val();
  var password = $("#sign_up_password").val();
  
  if(username == "" || password == ""){
    event.preventDefault()
    alert("Please enter a valid username and passowrd");
  }
});

//Log In
$("#login_link").click(function(){
  $("#sign_up_popup").hide(500);
  $("#login_popup").show(500);
})

$("#login_popup form button").click(function(event){
  var user = $("#login_user").val();
  var password = $("#login_password").val();
  
  if(user == "" || password == ""){
    event.preventDefault()
    alert("Please enter a valid username and passowrd");
  }
});
