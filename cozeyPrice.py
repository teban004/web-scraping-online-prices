import requests
from bs4 import BeautifulSoup
import mysql.connector
import pricesConfig as cfg
import htmlentities

#get the data from the website
URL = 'https://www.cozey.ca/product/the-cozey-sofa/dark-grey-2-seat-arms-normal-with-ottoman'
data = requests.get(URL)

print("The response from", URL, "is", data)

#load data into bs4
soup = BeautifulSoup(data.content, 'html.parser')

#get data by getting the span element inside div with class hero-description-group
data =[]
priceA = soup.find('a', {'class': 'Button-sc-fnbduq-0 handle__ATCButton-sc-1lv6ec8-13 cxmnWs kLvtvX'})
priceDiv = priceA.find("div")
print(priceDiv.text)




