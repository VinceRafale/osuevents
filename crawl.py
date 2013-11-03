import re
from HTMLParser import HTMLParser
import csv
import datetime

def get_page(url):
	
	try:
		import urllib
		return urllib.urlopen(url).read()
	except:
		return ""

def get_event(url):

      name = []
      info = []
      datetime = []
      location = []
      contact = []
      phonenum = []
      category = []
      eventtype = []
      
      content = get_page(url)

      eventdetails = geteventname(content)
      name.append(eventdetails[0])
      content = eventdetails[1]

      eventdetails = geteventinfo(content)
      info.append(eventdetails[0])
      content = eventdetails[1]

      eventdetails = getdatetime(content)
      datetime.append(eventdetails[0])
      content = eventdetails[1]

      eventdetails = getlocation(content)
      location.append(eventdetails[0])
      content = eventdetails[1]

      eventdetails = getcontact(content)
      contact.append(eventdetails[0])
      content = eventdetails[1]

      eventdetails = getphonenum(content)
      phonenum.append(eventdetails[0])
      content = eventdetails[1]

      eventdetails = getcategory(content)
      category.append(eventdetails[0])
      content = eventdetails[1]

      eventdetails = geteventtype(content)
      eventtype.append(eventdetails[0])
      content = eventdetails[1]


      alldata = [name, info, datetime, location, contact, phonenum, category, eventtype]

      writer = csv.writer(f, delimiter = ',')
      writer.writerows([alldata])

      
      #return name, info, datetime, location, contact, phonenum, category, eventtype

def getdata(content,tag1,tag2,extratag,endtag,value):
      pos = content.find(tag1)
      pos2 = content.find(tag2,pos)
      pos3 = content.find(extratag,pos2)
      startpos = pos3 + value
      endpos = content.find(endtag,startpos + 1)
      data = content[startpos:endpos]
      data = strip_tags(data.strip())
      content = content[endpos:]
      return data,content

def geteventname(content):
      name,content = getdata(content,'<h1>','>','','</h1>',1)
      return name,content

def geteventinfo(content):

      info,content = getdata(content,'event_info','<br','','</td>',4)
      return info,content

def getdatetime(content):

      datetime,content = getdata(content,'<td','><td','>','</td>',1)
      return datetime,content

def getlocation(content):
      location,content = getdata(content,'<td','><td','>','</td>',1)
      return location,content

def getcontact(content):
      contact,content = getdata(content,'<td','><td','>','</td>',1)
      return contact,content

def getphonenum(content):
      phonenum,content = getdata(content,'<td','><td','>','</td>',1)
      return phonenum,content

def getcategory(content):
      category,content = getdata(content,'<td','><td','>','</td>',1)
      return category,content

def geteventtype(content):
      eventtype,content = getdata(content,'<td','><td','>','</td>',1)
      return eventtype,content
      

# To strip off HTML tags -------------------------------------------------

class MLStripper(HTMLParser):
    def __init__(self):
        self.reset()
        self.fed = []
    def handle_data(self, d):
        self.fed.append(d)
    def get_data(self):
        return ''.join(self.fed)


def strip_tags(html):
    s = MLStripper()
    s.feed(html)
    return s.get_data()

# ------------------------------------------------------------------------------------
def getallevents(url):

      page = get_page(url)
      tocrawl = []
      
      goto = page.find('<br clear="all"/>')
      page = page[goto:]
      
      while True:

            linkpos = page.find('<a href="')
            linkstart = page.find('"',linkpos)
            linkend = page.find('"',linkstart+ 1)
            link = page[linkstart+1:linkend]
            page = page[linkend+1:]
            completeurl = 'http://www.osu.edu/events/'+ link
            if 'www.osu.edu/events/event' not in completeurl:
                  break
            tocrawl.append(completeurl)

      return tocrawl

def run():

      url = "http://www.osu.edu/events/indexWeek.php"

      for i in range(6):
            allevents = getallevents(url)
            for each in allevents:
                  get_event(each)

            page = get_page(url)
            pos = page.find('Previous')
            pos2 = page.find('<a href=',pos)
            start = page.find('"',pos2+1)
            end = page.find('"',start + 1)
            nextweek = page[start+1:end]
            url = nextweek

if __name__ == '__main__':
        run()
