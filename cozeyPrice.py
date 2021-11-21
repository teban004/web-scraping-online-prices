import requests
import mysql.connector
import pricesConfig as cfg
import htmlentities

#get the data from the website
URL = 'https://www.cozey.ca/product/the-cozey-sofa/dark-grey-2-seat-arms-normal-with-ottoman'
data = requests.get(URL)

#print("The response from", URL, "is", data.status_code)

#get data by getting the span element inside div with class hero-description-group
pageBody = data.text
#print(pageBody)
pricePosition = pageBody.find('"navy-blue-2-seat-arms-normal-with-ottoman"')
rawText = pageBody[pricePosition:pricePosition+100]
#print(rawText)
priceStartPosition = 0
priceEndPosition = rawText.find("}") + 1
#print("priceStartPosition;",priceStartPosition,"priceEndPosition",priceEndPosition)
priceText = rawText[priceStartPosition : priceEndPosition].replace('"', '')
#print(priceText)

try:
    cnx = mysql.connector.connect(user=cfg.mysql["user"], password=cfg.mysql["passwd"], host=cfg.mysql["host"], database=cfg.mysql["db"])
    #print("The connection to the database has been established.")
    mycursor = cnx.cursor()
    sql = "INSERT INTO `price_history` (`product_id`, `text`) VALUES (%s, %s);"
    val = (2, htmlentities.encode(priceText))
    #print("Executing the query:\n",sql)
    #print("Value: ",htmlentities.encode(priceText))
    mycursor.execute(sql, val)
    cnx.commit()
    cnx.close()
    #print("The information has been written in the database.")
except mysql.connector.errors.InterfaceError as err:
    print("Not possible to connect to the database.")
 


