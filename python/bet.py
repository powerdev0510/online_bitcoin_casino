from wrapper import *
import math
import time
import requests
from random import randint

for x in range(0, 10000):
  #request = requests.post('http://casino.ronen.io/casino/api/bet.php?user=bobpuccini&user_id=kE1Rx5aljnnJSBVfVjoa&amount=0.01&prediction=under&multiplier=2')
  random = randint(0,1)
  if random == 0:
    request = requests.post('http://casino.ronen.io/casino/api/bet.php', data = {'user':'bobpuccini', 
                                                               'user_id': 'SuwDGR8hO79CE1ocQv5A',
                                                               'amount': 1,
                                                               'prediction': 'under',
                                                               'multiplier': 1.1
                                                              })
  elif random == 1:
    request = requests.post('http://casino.ronen.io/casino/api/bet.php', data = {'user':'bobpuccini', 
                                                               'user_id': 'SuwDGR8hO79CE1ocQv5A',
                                                               'amount': 0.1,
                                                               'prediction': 'over',
                                                               'multiplier': 2
                                                              })