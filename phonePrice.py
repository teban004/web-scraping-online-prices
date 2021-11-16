import requests
from bs4 import BeautifulSoup

#get the data from the website
URL = 'https://www.apple.com/ca/iphone-se/'
data = requests.get(URL)

print("The response from", URL, "is", data)
print()

#load data into bs4
soup = BeautifulSoup(data.content, 'html.parser')

#get data by getting the span element inside div with class hero-description-group
data =[]
priceDiv = soup.find('div', {'class': 'hero-description-group'})
priceSpan = priceDiv.find("span")
print(priceSpan.text)

