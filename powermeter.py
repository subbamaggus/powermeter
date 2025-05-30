import sys
import json
from datetime import datetime
import requests

with open("/home/pi/powermeter.json") as json_data_file:
    configdata = json.load(json_data_file)
    
url = 'http://www.weggefoehnt.de/sml/receive_sml_post.php?token=' + configdata['secret_token']
headers = {'Content-Type': 'application/json'}
single_data = {}
good_reading_oil=json.loads('{"StatusSNS":{"Time":"2025-04-24T15:55:38","VL53L0X-1":{"Distance":-1},"VL53L0X-2":{"Distance":-1}}}')
counter = 11
read_err_count = 0

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
                oil = requests.get("http://192.168.178.193/cm?cmnd=status%2010", timeout=3)
                temp_oil = json.loads(oil.text)
                
                if temp_oil['StatusSNS']['VL53L0X-1']['Distance'] is None:
                    read_err_count += 1
                if temp_oil['StatusSNS']['VL53L0X-2']['Distance'] is None:
                    read_err_count += 1

                if 0 == read_err_count:
                    good_reading_oil = temp_oil

            except BaseException as exception:
                read_err_count += 1
                print("oil sensor not available")        
            
            data['oil'] = good_reading_oil

            if 3 < read_err_count:
                try:
                    read_err_count = 0
                    restart = requests.get("http://192.168.178.193/cm?cmnd=Restart%201", timeout=10)
                    print("restarted")
                except BaseException as iexception:
                    print("restart failed")

            data_json = json.dumps(data, indent = 4)
            x = requests.post(url, json=data_json, headers=headers)
            print(x.text)
            
            counter = 0
        single_data = {}
        counter = counter + 1
