import mysql.connector
import pricesConfig as cfg

cnx = None

try:
    cnx = mysql.connector.connect(user=cfg.mysql["user"], password=cfg.mysql["passwd"], host=cfg.mysql["host"], database=cfg.mysql["db"])
    #print("The connection to the database has been established.")
    mycursor = cnx.cursor()
    sql = "SELECT * FROM `price_history` WHERE `product_id`=1 ORDER BY `registered_time` DESC LIMIT 0, 1000;"
    #print("Executing the query:\n",sql)
    mycursor.execute(sql)
    records = mycursor.fetchall()
    print("Total fetched records: ", len(records))
    prices = dict()
    for row in records:
        # look for the current record in the prices dictionary
        if(row[2]) in prices:
            prices[row[2]] = prices[row[2]] + 1 # increment the count if the price is already there
        else:
            prices[row[2]] = 1 # add the price if it's not there
    mycursor.close()

    #print the different prices with the number of appearances
    for price, count in prices.items():
        print("(", count, "): ", price)
    
except mysql.connector.errors.InterfaceError as err:
    print("Not possible to connect to the database.")

finally:
    if (cnx):
        cnx.close()
 


