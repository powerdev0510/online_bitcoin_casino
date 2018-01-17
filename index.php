<?php
session_start();
require("config.php");
?>
<html>
  <head>
    <title>Gambling</title>
    <meta charset="UTF-8">
    <meta name="description" content="Gamble for Bitcoins">
    <meta name="keywords" content="gamble,bitcoin,dice,bitcoin dice,bitcoin gambling">
    <meta name="author" content="The Syndicate">
    <link href="https://fonts.googleapis.com/css?family=Open+Sans:500,400,300,600,700|Roboto:400,100,300,700" rel="stylesheet" type="text/css">
    <link type="text/css" href="css/basic.css" rel="stylesheet">
    <link type="text/css" href="css/form.css" rel="stylesheet">
    <link type="text/css" href="css/gamble_interface.css" rel="stylesheet">
    <link type="text/css" href="css/bottom.css" rel="stylesheet">
    <link type="text/css" href="css/overlays.css" rel="stylesheet">
    <link type="text/css" href="css/alert.css" rel="stylesheet">
    <link type="text/css" href="css/bets.css" rel="stylesheet">    
    <script src="//code.jquery.com/jquery-1.11.3.min.js"></script>
  </head>
  <body background="img/bg.jpg">
    <div id="alert">
      <p id="message">
      </p>
    </div>
    <nav>
      <ul>
        <li>Total Wagered: <span id="site_wagered">฿0</span></li>
        <li>Bankroll: <span id="site_invested">฿0</span></li>
        <li>Investor Profit: <span id="site_investor_profit">฿0</span></li>
        <li>Bets: <span id="site_bets">0</span></li>        
        <?php
        if(isset($_SESSION["logged_in"])){
          $query = $db->prepare("SELECT * FROM `Users` WHERE `user`=:user");
          $query->bindParam(":user", $_SESSION["user"]);
          $query->execute();
          $user_data = $query->fetchAll(PDO::FETCH_ASSOC)[0];
          print('<li id="logout_link"><a href="logout.php">Logout</a></li>');
          print('<li id="fair">Provably Fair</li>');
          print('<li id="withdraw">Withdraw</li>');
          print('<li id="deposit">Deposit</li>');
          print('<li id="greeting">Welcome back, ' . $_SESSION["user"] . "</li>");
        } else {
          print('<li id="login_link">Login</li>');
          print('<li id="sign_up_link">Sign Up</li>');
        }
        ?>
      </ul>
    </nav>
    <center>
      <div id="sign_up_popup">
        <form action="sign_up.php" method="post">
          <input type="text" name="sign_up_username" id="sign_up_username" placeholder="What is your name?" class="key_limit">
          <input type="email" name="sign_up_email" id="sign_up_email" placeholder="What is your email?">
          <input type="password" name="sign_up_password" id="sign_up_password" placeholder="Choose a strong password">
          <button>
            Sign Up
          </button>
        </form>
      </div>
      <div id="login_popup">
        <form action="login.php" method="post">
          <input type="text" name="login_user" id="login_user" placeholder="Email or Username">
          <input type="password" name="login_password" id="login_password" placeholder="Password">
          <button>
            Log In
          </button>
        </form>
      </div>
      <div class="overlay">
        <span class="close"></span>
        <div id="deposit_overlay">
          <p>
            Your Personal Deposit Address Is:
          </p>
          <p id="user_deposit_address">
            <?php
            /*USER*/
            print($user_data["bitcoin_address"]);
            ?>
          </p>
          <img src="http://chart.apis.google.com/chart?cht=qr&chs=200x200&chl=<?php /*USER*/ print($user_data["bitcoin_address"]);?>&chld=H|0">
          <p>
            Deposits Will Credit After One Confirmation
          </p>
          <table>
            <thead>
              <tr>
                <th>Transaction ID</th>
                <th>Confirmations</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><a href="https://blockchain.info/tx/a268f26d0d140b69072c2bfc01af82af11c5edc4e32941fad2b1f9bbc76a520f">a268f26d0d...</a></td>
                <td>0</td>
              </tr>
            </tbody>
          </table>
        </div>
        <div id="withdraw_overlay">
          <form>
            <label for="withdraw_amount">Withdrawal Amount:</label>
            <input type="text" name="withdraw_amount" id="withdraw_amount" value="0.00001" class="numerical">
            <label for="withdraw_address">Bitcoin Address:</label>
            <input type="text" name="withdraw_address" id="withdraw_address" placeholder="Eg. 1D4gDDfa9XzqJCHaAvysBu4buFYoKb4ffw">
            <p>
              A Transaction Fee of 0.0001 Will Be Deducted From This Withdrawal
            </p>
            <button id="submit_withdrawal">
              SUBMIT WITHDRAWAL
            </button>
          </form>
        </div>
        <div id="provably_fair_overlay">
          <form>
            <h1>
              PROVABLY FAIR
            </h1>
            <div>
              <label for="server_hash">Server Hash:</label>
              <input type="text" name="server_hash" id="server_hash" value="0" size="80" disabled>
            </div>
            <div>
              <label for="shuffle_hash">Shuffle Hash:</label>
              <input type="text" name="shuffle_hash" id="shuffle_hash" value="0" size="80" disabled>
              <button id="change_shuffle_seed">
                CHANGE SHUFFLE SEED
              </button>
            </div>
          </form>
        </div>
        <div id="invest_in_house_overlay">
          <form>
            <h1>
              INVEST IN THE HOUSE BANKROLL
            </h1>
            <p>
              Investing in the house allows you to profit when other gamblers lose. Since the house has an edge of 1%, investors should expect to make a profit of about 1% of the total amount wagered.
            </p>
            <label for="invest_amount">Investment Amount:</label>
            <input type="text" name="invest_amount" id="invest_amount" placeholder="Maximum Investment: ฿0.000000000" class="numerical">
            <button id="submit_investment">
              SUBMIT INVESTMENT
            </button>
          </form>
        </div>
        <div id="divest_from_house_overlay">
          <form>
            <h1>
              DIVEST FROM THE HOUSE BANKROLL
            </h1>
            <p>
              10% of your investment <b>profit</b> will be taken as our fee when you divest.
            </p>
            <label for="divest_amount">Divestment Amount:</label>
            <input type="text" name="divest_amount" id="divest_amount" placeholder="Maximum Divestment: ฿0.000000000" class="numerical">
            <button id="submit_divestment">
              DIVEST
            </button>
          </form>
        </div>
      </div>
      <div id="gamble_interface">
        <form>
          <div>
            <label for="bet_amount">Bet Amount</label>
            <input type="text" name="bet_amount" id="bet_amount" value="0.00000001" class="numerical">
          </div>
          <div>
            <label for="bet_profit_on_win">Profit On Win</label>
            <input type="text" name="bet_profit_on_win" id="bet_profit_on_win" placeholder="0.00000001" disabled>
          </div>
          <div id="balance">
            <label for="user_balance">Balance</label>
            <input type="text" name="user_balance" id="user_balance" placeholder="฿0.00000000" disabled>
          </div>
          <br>
          <div id="gray_overlay">
            <div class="margin_r">
              <label for="bet_prediction">Prediction</label>
              <button id="bet_prediction">
                <span id="over_or_under">UNDER</span>
                <span id="number">49.50</span>
              </button>
            </div>
            <div class="margin_r">
              <label for="bet_payout_multiple">Payout Multiple X</label>
              <input type="text" name="bet_payout_multiple" id="bet_payout_multiple" value="2.000x" class="numerical">
            </div>
            <div>
              <label for="bet_probability">Win Chance</label>
              <input type="text" name="bet_probability" id="bet_probability" value="49.50%"  class="numerical">
            </div>
          </div>
          <br>
          <button id="submit_bet">
            ROLL DICE
          </button>
        </form>
      </div>
      <div id="bottom_nav">
        <ul id="widgets">
          <li widget="invest">INVEST</li>
          <li widget="my_bets">MY BETS</li>
          <li widget="all_bets" class="widget_selected">ALL BETS</li>
          <li widget="stats">STATS</li>
        </ul>
      </div>
      <div id="invest">
        <table>
          <thead>
            <tr>
              <th></th>
              <th></th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>CURRENTLY INVESTED:</td>
              <td id="current_investment">0</td>
            </tr>
            <tr>
              <td>PERCENT OF THE SITE'S BANKROLL:</td>
              <td id="percent_of_bankroll">0%</td>
            </tr>
            <tr>
              <td>INVESTMENT PROFIT:</td>
              <td id="investment_profit">+0.00000000</td>
            </tr>
            <tr>
              <td colspan="2">
                <button id="invest_invest">
                  INVEST
                </button>
                <button id="invest_divest">
                  DIVEST
                </button>
              </td>
            </tr>
          </tbody>
        </table>
      </div>
      <div id="my_bets">
        <table>
          <thead>
            <tr>
              <th>BET #</th>
              <th>USER</th>
              <th>TIME</th>
              <th>BET</th>
              <th>MULTIPLIER</th>
              <th>PREDICTION</th>
              <th>ROLL</th>
              <th>PROFIT</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>1</td>
              <td>ronen</td>
              <td>00:00:01</td>
              <td>1.25</td>
              <td>1.98x</td>
              <td>50 < </td>
              <td>49</td>
              <td>+1.98</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div id="all_bets">
        <table>
          <thead>
            <tr>
              <th>BET #</th>
              <th>USER</th>
              <th>TIME</th>
              <th>BET</th>
              <th>MULTIPLIER</th>
              <th>PREDICTION</th>
              <th>ROLL</th>
              <th>PROFIT</th>
            </tr>
          </thead>
          <tbody>
          </tbody>
        </table>
      </div>
      <div id="stats">
        <table>
          <thead>
            <tr>
              <th>CATEGORY</th>
              <th>YOU</th>
              <th>SITE</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>WAGERED</td>
              <td id="user_wagered">0</td>
              <td id="site_wagered">0</td>
            </tr>
            <tr>
              <td>BETS</td>
              <td id="user_bets">0</td>
              <td id="site_bets">0</td>
            </tr>
            <tr>
              <td>PROFIT</td>
              <td id="user_profit">0</td>
              <td id="site_profit">0</td>
            </tr>
            <tr>
              <td>INVESTMENT PROFIT</td>
              <td id="user_investment_profit">0</td>
              <td id="site_investment_profit">0</td>
            </tr>
            <tr>
              <td>INVESTED</td>
              <td id="user_invested">0</td>
              <td id="site_invested">0</td>
            </tr>
          </tbody>
        </table>
      </div>
    </center>
    <div id="user_data">
      <?php
      if(isset($_SESSION["logged_in"])){
        print(json_encode($_SESSION));
      } else {
        print('{"logged_in": false}');
      }
      ?>
    </div>
    <footer>
        Programmed by Ronen Singer<br>
        <a href='http://github.com/ronensinger'>http://github.com/ronensinger</a>
    </footer>
    <style>
      footer {
        text-align: center;
        color: white;
        background-color: black;
        position: fixed;
        bottom: 0;
        width: 100%;
        height: 40px;
      }
      
      a {
        color: #2980b9;
        text-decoration: underline;
      }
    </style>
  </body>
  <script type="text/javascript" src="js/interaction.js"></script>
  <script type="text/javascript" src="js/gamble_interface.js"></script>
  <script type="text/javascript" src="js/widgets.js"></script>
  <script type="text/javascript" src="js/overlays.js"></script>
  <script type="text/javascript" src="js/handler.js"></script>
  <script type="text/javascript" src="js/streaming.js"></script>
</html>