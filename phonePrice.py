import requests
from bs4 import BeautifulSoup
import mysql.connector
import pricesConfig as cfg
import htmlentities

from selenium import webdriver

#get the data from the website
URL = 'https://www.apple.com/ca/iphone-se/'
driver = webdriver.Chrome('/snap/bin/chromium.chromedriver')
driver.get(URL)

#load data into bs4
soup = BeautifulSoup(driver.page_source, 'html.parser')
driver.quit()

#get data by getting the span element inside div with class hero-description-group
data =[]
priceDiv = soup.find('div', {'class': 'hero-description-group'})
priceSpan = priceDiv.find("span")
print(priceSpan.text)

try:
    cnx = mysql.connector.connect(user=cfg.mysql["user"], password=cfg.mysql["passwd"], host=cfg.mysql["host"], database=cfg.mysql["db"])
    #print("The connection to the database has been established.")
    mycursor = cnx.cursor()
    sql = "INSERT INTO `price_history` (`product_id`, `text`) VALUES (%s, %s);"
    val = (1, htmlentities.encode(priceSpan.text))
    #print("Executing the query:\n",sql)
    mycursor.execute(sql, val)
    cnx.commit()
    cnx.close()
    #print("The information has been written in the database.")
except mysql.connector.errors.InterfaceError as err:
    print("Not possible to connect to the database.")
 
