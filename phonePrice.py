import requests
from bs4 import BeautifulSoup

#get the data from the website
website = 'https://www.apple.com/ca/iphone-se/'
data = requests.get(website)

print("The response from", website, "is", data)
print()

#load data into bs4
soup = BeautifulSoup(data.text, 'html.parser')

