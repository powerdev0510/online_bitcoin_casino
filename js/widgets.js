$("#widgets li").click(function(){
  var widgets = ["#my_bets, #all_bets, #stats, #invest"];
  
  //Hide Widgets
  for (var i = 0; i < widgets.length; i++) { 
    var widget = widgets[i];
    //$(widget).hide(250);
    $(widget).hide();
  }
  
  //Remove Selection
  $(".widget_selected").attr("class", "");
  
  //Show Widget
  var widget = "#" + $(this).attr("widget");
  //$(widget).show(250);
  $(widget).show();
  
  //Select Widget
  $(this).attr("class", "widget_selected");
});