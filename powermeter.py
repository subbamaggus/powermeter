import sys
import json
from datetime import datetime
import requests

with open("/home/pi/powermeter.json") as json_data_file:
    configdata = json.load(json_data_file)
    
url = 'http://www.weggefoehnt.de/sml/receive_sml_post.php?token=' + configdata['secret_token']
headers = {'Content-Type': 'application/json'}
single_data = {}
counter = 11

while 1:
    line = sys.stdin.readline()
    if not line:
        break
    first = line.split(":")
    medium_kanal = first[0]
    if(len(first)>1):
        second = first[1].split("*",1)
        
        messgroesse_messart_tarifstufe = second[0].replace(".","_")
        
        wert = second[1]
        wert_roh = wert.replace("255(","").replace(")","")
        wert_roh_a = wert_roh.split("*")
        wert_zahl = wert_roh_a[0]
        
        single_data[messgroesse_messart_tarifstufe] = wert_zahl
        if(len(wert_roh_a)>1):
            wert_einheit = wert_roh_a[1]
            
    if 'DE1234560000000000000001298898157' == line.rstrip():

        print("data set processed ...")
        #only send every 10th line
        if(counter > 10):
            now = datetime.now()
            
            data_sml = {}
            data_sml['Time'] = now.strftime("%Y-%m-%dT%H:%M:%S")
            data_sml['SML'] = single_data
            
            data = {}
            data['StatusSNS'] = data_sml
            try: 
            	oil = requests.get("http://192.168.177.66/cm?cmnd=status%2010", timeout=3)
            	data['oil'] = json.loads(oil.text)
	    except BaseException as exception:
                print("oil sensor not available")	
            
            data_json = json.dumps(data, indent = 4)
            x = requests.post(url, json=data_json, headers=headers)
            print(x.text)
            
            counter = 0
        single_data = {}
        counter = counter + 1
