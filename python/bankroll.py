from wrapper import *
import math
import time

while True:
  unrealized = float(select("SELECT `unrealized` FROM `Site`;UPDATE `Site` SET `unrealized`=0;")[0]["unrealized"])
  investors = select("SELECT `user`, `invested`, `invested_profit` FROM `Bankroll`")
  total_invested = float(select("SELECT SUM(`invested`) FROM `Bankroll`")[0]["SUM(`invested`)"])
  
  #Update Bankroll
  #query = "UPDATE `Site` SET `unrealized`=0;"
  #insert(query)

  #If Unrealized and investors
  if total_invested > 0 and unrealized != 0:
    for investor in investors:
      percentage_of_bankroll = round((investor["invested"] / total_invested), 8)

      gain = percentage_of_bankroll * unrealized
      invested = investor["invested"] + gain
      invested_profit = investor["invested_profit"] + gain

      #Bankroll
      query = "UPDATE `Bankroll` SET `invested`={}, `invested_profit`={} WHERE `user`='{}'".format(invested, invested_profit, investor["user"])
      insert(query)

      #Users
      query = "UPDATE `Users` SET `invested`={}, `invested_profit`={} WHERE `user`='{}'".format(invested, invested_profit, investor["user"])
      insert(query)
    print("Updated")
  else:
    print("No bets")

  #Site
  invested = float(select("SELECT SUM(`invested`) FROM `Bankroll`")[0]["SUM(`invested`)"])
  query = "UPDATE `Site` SET `invested`={}".format(invested)
  insert(query)

  time.sleep(1)