import sys
import json

single_data = {}

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
        wert_roh = wert.replace("(","").replace(")","")
        wert_roh_a = wert_roh.split("*")
        wert_zahl = wert_roh_a[0]
        single_data[messgroesse_messart_tarifstufe] = wert_zahl
        if(len(wert_roh_a)>1):
            wert_einheit = wert_roh_a[1]
    if 'DE1234560000000000000001298898157' == line.rstrip():
        data_sml = {}
        data_sml['Time'] = 'mytime'
        data_sml['SML'] = single_data
        data = {}
        data['StatusSNS'] = data_sml
        data_json = json.dumps(data, indent = 4)
        print(data_json)
        single_data = {}
