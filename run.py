import sys
while 1:
    line = sys.stdin.readline()
    if not line:
        break
    print(line)
    if 'DE1234560000000000000001298898157' == line.rstrip():
        print('found end')