import pymysql


connection = pymysql.connect(host='REPLACE WITH YOUR OWN',
                             user='REPLACE WITH YOUR OWN',
                             passwd='REPLACE WITH YOUR OWN',
                             db='REPLACE WITH YOUR OWN',
                             charset='utf8mb4',
                             cursorclass=pymysql.cursors.DictCursor)
cursor = connection.cursor()

def insert(query):
  cursor.execute(query)
  connection.commit()

def select(query):
  cursor.execute(query)
  data = cursor.fetchall()

  return data