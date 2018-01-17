(function($) {
  var sR = {
    defaults: {
      slideSpeed: 400,
      easing: false,
      callback: false
    },
    thisCallArgs: {
      slideSpeed: 400,
      easing: false,
      callback: false
    },
    methods: {
      up: function(arg1, arg2, arg3) {
        if (typeof arg1 == 'object') {
          for (p in arg1) {
            sR.thisCallArgs.eval(p) = arg1[p];
          }
        } else if (typeof arg1 != 'undefined' && (typeof arg1 == 'number' || arg1 == 'slow' || arg1 == 'fast')) {
          sR.thisCallArgs.slideSpeed = arg1;
        } else {
          sR.thisCallArgs.slideSpeed = sR.defaults.slideSpeed;
        }

        if (typeof arg2 == 'string') {
          sR.thisCallArgs.easing = arg2;
        } else if (typeof arg2 == 'function') {
          sR.thisCallArgs.callback = arg2;
        } else if (typeof arg2 == 'undefined') {
          sR.thisCallArgs.easing = sR.defaults.easing;
        }
        if (typeof arg3 == 'function') {
          sR.thisCallArgs.callback = arg3;
        } else if (typeof arg3 == 'undefined' && typeof arg2 != 'function') {
          sR.thisCallArgs.callback = sR.defaults.callback;
        }
        var $cells = $(this).find('td');
        $cells.wrapInner('<div class="slideRowUp" />');
        var currentPadding = $cells.css('padding');
        $cellContentWrappers = $(this).find('.slideRowUp');
        $cellContentWrappers.slideUp(sR.thisCallArgs.slideSpeed, sR.thisCallArgs.easing).parent().animate({
          paddingTop: '0px',
          paddingBottom: '0px'
        }, {
          complete: function() {
            $(this).children('.slideRowUp').replaceWith($(this).children('.slideRowUp').contents());
            $(this).parent().css({
              'display': 'none'
            });
            $(this).css({
              'padding': currentPadding
            });
          }
        });
        var wait = setInterval(function() {
          if ($cellContentWrappers.is(':animated') === false) {
            clearInterval(wait);
            if (typeof sR.thisCallArgs.callback == 'function') {
              sR.thisCallArgs.callback.call(this);
            }
          }
        }, 100);
        return $(this);
      },
      down: function(arg1, arg2, arg3) {
        if (typeof arg1 == 'object') {
          for (p in arg1) {
            sR.thisCallArgs.eval(p) = arg1[p];
          }
        } else if (typeof arg1 != 'undefined' && (typeof arg1 == 'number' || arg1 == 'slow' || arg1 == 'fast')) {
          sR.thisCallArgs.slideSpeed = arg1;
        } else {
          sR.thisCallArgs.slideSpeed = sR.defaults.slideSpeed;
        }

        if (typeof arg2 == 'string') {
          sR.thisCallArgs.easing = arg2;
        } else if (typeof arg2 == 'function') {
          sR.thisCallArgs.callback = arg2;
        } else if (typeof arg2 == 'undefined') {
          sR.thisCallArgs.easing = sR.defaults.easing;
        }
        if (typeof arg3 == 'function') {
          sR.thisCallArgs.callback = arg3;
        } else if (typeof arg3 == 'undefined' && typeof arg2 != 'function') {
          sR.thisCallArgs.callback = sR.defaults.callback;
        }
        var $cells = $(this).find('td');
        $cells.wrapInner('<div class="slideRowDown" style="display:none;" />');
        $cellContentWrappers = $cells.find('.slideRowDown');
        $(this).show();
        $cellContentWrappers.slideDown(sR.thisCallArgs.slideSpeed, sR.thisCallArgs.easing, function() {
          $(this).replaceWith($(this).contents());
        });

        var wait = setInterval(function() {
          if ($cellContentWrappers.is(':animated') === false) {
            clearInterval(wait);
            if (typeof sR.thisCallArgs.callback == 'function') {
              sR.thisCallArgs.callback.call(this);
            }
          }
        }, 100);
        return $(this);
      }
    }
  };

  $.fn.slideRow = function(method, arg1, arg2, arg3) {
    if (typeof method != 'undefined') {
      if (sR.methods[method]) {
        return sR.methods[method].apply(this, Array.prototype.slice.call(arguments, 1));
      }
    }
  };
})(jQuery);


function initial(){
  var request = $.ajax({
    url: "api/data.php",
    dataType: "JSON",
    return_data: self.responseText,
    type: "GET",
    success: function(return_data){
      //Site
      var site = return_data.site;
      
      var wagered = parseFloat(site.wagered);
      $("#site_wagered").text(wagered.toFixed(4) + "฿");
      
      var invested = parseFloat(site.invested);
      $("#site_invested").text(invested.toFixed(4) + "฿");
      
      var investor_profit = parseFloat(site.investor_profit);
      $("#site_investor_profit").text(investor_profit.toFixed(4) + "฿");
      
      var bets = site.bets;
      $("#site_bets").text(bets);
      
      //Bets
      var bets = return_data.bets;
      
      //Empty Table
      $("#all_bets table tbody").empty();
      
      for(x = 0; x < bets.length; x++){ 
        var bet = bets[x];
        
        //Row
        var random_id = parseInt(Math.random() * 1000000);
        var row_name = "#" + random_id;
        var row = "<tr id='" + random_id + "'>";
        row += "<td>" + bet.id + "</td>";
        row += "<td>" + bet.user + "</td>";
        row += "<td>" + bet.time + "</td>";
        row += "<td>" + bet["bet"] + "</td>";
        
        //Multiplier Format
        bet.multiplier = parseFloat(bet.multiplier);
        row += "<td>" + bet.multiplier.toFixed(4) + "x</td>";
        
        //Prediction Format
        bet.prediction = parseFloat(bet.prediction.split(" ")[0]).toFixed(2) + " " + bet.prediction.split(" ")[1];
        row += "<td>" + bet.prediction + "</td>";
        
        row += "<td>" + bet.roll + "</td>";
        
        //Profit
        if(parseFloat(bet.profit) > 0){
          row += "<td class='win'><b>+" + bet.profit + "</b></td>";
        } else {
          row += "<td class='loss'><b>" + bet.profit + "</b></td>";
        }
        row += "</tr>";
        
        //Add and Hide
        $("#all_bets table tbody").prepend(row);
        //$(row_name).fadeTo("1", 0.5);
        $(row_name).fadeTo("1000", 1);
        //$(row_name).hide();
        //$(row_name).slideRow('down', 500);
      }
    }
  })
}

initial();
setInterval(function(){initial();}, 500);