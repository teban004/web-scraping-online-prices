import mysql.connector
import pricesConfig as cfg

try:
    cnx = mysql.connector.connect(user=cfg.mysql["user"], password=cfg.mysql["passwd"], host=cfg.mysql["host"], database=cfg.mysql["db"])
    print("The connection to the database has been established.")
    mycursor = cnx.cursor()
    sql = "SELECT * FROM `price_history` WHERE `product_id`=1 ORDER BY `registered_time` DESC LIMIT 0, 100;"
    print("Executing the query:\n",sql)
    mycursor.execute(sql)

    cnx.close()
    
except mysql.connector.errors.InterfaceError as err:
    print("Not possible to connect to the database.")
 


