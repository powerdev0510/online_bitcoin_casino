# Showcase-Casino
A semi-functional Bitcoin Casino, where people can invest in the house.

![](http://ronen.io/casino.PNG)

## Is There A Demo?
Hell yeah there is: http://casino.ronen.io

While you can register and place bets, expect the site to be real buggy.

## What's So Special About This Casino?
This casino has user based investment. What does this mean?
It means users can actually invest in the house, so whenever a gambler loses (due to the house edge of 1%) an investor can win!

Example:
99 bitcoins are invested. Joe invests one bitcoin. Now 100 bitcoins are invested. If someone bets 1 bitcoin and loses, the 1 bitcoin is divided up among the investors.
Since Joe makes up 1% of the bankroll, Joe gets 0.01 in profit!

## How Does the Casino Work?
Users bet whether a randomly generated number between 0 and 100 will be above or below a number they pick.

## Getting Started
Getting Started is easy. Steps:
1. Clone the Repository
2. Install MYSQL
3. Create a sql database. 
3. Run the tables.sql file (located in the initalize folder) in the database you create. This creates the sql tables needed to keep track of user balances and investments.
4. Edit the config.php file, and the api/config.php file. Replace 

```
$servername = "Put your own host here";
$serverusername = "Put your own user here";
$serverDatabase = "Put your own database here";
$serverpassword = "Put your own password here";
```

with your own MYSQL username and password

5. You're all set! Have fun gambling on your own casino.
6. Maybe one day you'll finish what I started.

## Things that need to be done
1. Enable bitcoin deposits
2. Fix an insane amount of bugs
3. Finish the house investment system
4. Create an API Function to update the user balance

## Disclaimer
This casino is just a prototype, and is probably not secure. If you're interested in actually running a Bitcoin casino, I recommend you write your own software.
Besides, this is written in PHP, and is slow as hell.

If you are interested in me.
Please contact me via skype.
my id is "power.dev2017" or "tgsssi2017"
I am open minded to finish this project with you.
