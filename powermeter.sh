#!/bin/bash
#

status() {
    ps -ef | grep powermeter | awk '{print $2}' | xargs echo
}

start() {
    while true
    do
        netcat 192.168.178.83 5000 | python /home/pi/powermeter.py
    done
}

stop() {
    ps -ef | grep powermeter | awk '{print $2}' | xargs kill
}

case "$1" in
    'start')
            start
            ;;
    'stop')
            
            stop
            ;;
    'restart')
            stop ; echo "Sleeping..."; sleep 1 ;
            start
            ;;
    'status')
            status
            ;;
    *)
            echo
            echo "Usage: $0 { start | stop | restart | status }"
            echo
            exit 1
            ;;
esac

exit 0
