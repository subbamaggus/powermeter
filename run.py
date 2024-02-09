 import sys
while 1:
    line = sys.stdin.readline()
    if not line:
        break
    first = line.split(":")
    medium_kanal = first[0]
    print(medium_kanal)
    if(len(first)>1):
        second = first[1].split("*",1)
        messgroesse_messart_tarifstufe = second[0]
        wert = second[1]
        print(messgroesse_messart_tarifstufe)
        wert_roh = wert.replace("(","").replace(")","")
        wert_roh_a = wert_roh.split("*")
        wert_zahl = wert_roh_a[0]
        print(wert_zahl)
        if(len(wert_roh_a)>1):
            wert_einheit = wert_roh_a[1]
            print(wert_einheit)
    if 'DE1234560000000000000001298898157' == line.rstrip():
        print('found end')
