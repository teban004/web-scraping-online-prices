import requests
from bs4 import BeautifulSoup
import mysql.connector
import pricesConfig as cfg
import htmlentities

#get the data from the website
URL = 'https://www.cozey.ca/product/the-cozey-sofa/dark-grey-2-seat-arms-normal-with-ottoman'
data = requests.get(URL)

print("The response from", URL, "is", data.status_code)

#load data into bs4
soup = BeautifulSoup(data.content, 'html.parser')

#get data by getting the span element inside div with class hero-description-group
data =[]
pageBody = soup.find('body').text
pricePosition = pageBody.find("navy-blue-2-seat-arms-normal-with-ottoman")
priceText = pageBody[pricePosition:pricePosition+200]
priceStartPosition = priceText.find("{") + 1
priceEndPosition = priceText.find("}")
priceText = priceText[priceStartPosition:priceEndPosition]
print(priceText)




""" try:
    cnx = mysql.connector.connect(user=cfg.mysql["user"], password=cfg.mysql["passwd"], host=cfg.mysql["host"], database=cfg.mysql["db"])
    #print("The connection to the database has been established.")
    mycursor = cnx.cursor()
    sql = "INSERT INTO `price_history` (`product_id`, `text`) VALUES (%s, %s);"
    val = (1, htmlentities.encode(priceDiv.text))
    #print("Executing the query:\n",sql)
    mycursor.execute(sql, val)
    cnx.commit()
    cnx.close()
    #print("The information has been written in the database.")
except mysql.connector.errors.InterfaceError as err:
    print("Not possible to connect to the database.") """
 


