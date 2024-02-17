# System overview

## Components

1. Smart Meter
2. Adapter Serial to Network
3. Raspberry PI
4. DB
5. PHP Webserver
 

## powermeter.* (service/py/sh)

### Get Data from Network adapter

Sample SML via

getting data via netcat:

> netcat 192.168.178.83 5000

data that is returned looks like:

>  /ESY5Q3DA1004 V3.03
>  
>  1-0:0.0.0*255(1027000455)
>  1-0:1.8.0*255(00020995.8225132*kWh)
>  1-0:21.7.255*255(000002.59*W)
>  1-0:41.7.255*255(000094.40*W)
>  1-0:61.7.255*255(000097.82*W)
>  1-0:1.7.255*255(000194.81*W)
>  1-0:96.5.5*255(82)
>  0-0:96.1.255*255(1ESY1027000455)
>  !
>  1346538668
>  DE1234560000000000000001298898157


### convert it into json (similar to data structure of tasmota)

>     {
>         "StatusSNS": {
>             "Time": "2023-03-22T21:42:51",
>             "SML": {
>                 "1_8_0": 20995.8225132,
>                 "1_7_255": 194.81
>             }
>         }
>     }

and send it via http post to

> http://www.weggefoehnt.de/sml/receive_sml_post.php?token=XXXXXXXX

## sml/ (receive and store data)

receive_sml_post.php will process further

index.php shows the data via google chart

history.php shows past data incl data browsing