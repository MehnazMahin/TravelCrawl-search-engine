import sys
sys.path.append('/home/mishkat/.local/lib/python3.7/site-packages')
sys.path.append('/home/mishkat/.local/lib/python3/site-packages')

from pymongo import MongoClient
import re


def main_function(queries):
    client = MongoClient('localhost', 27017)

    db = client.IR
    collection = db.Index
    
    query=['']*3
    query[0]=queries[0]
    query[1]=queries[1]
    query[2]=queries[2]

    s = str(collection.find({'word': query[0]}, {'_id': 0, 'attr.postId': 1, 'attr.tf': 1, 'attr.upvotes': 1, 'attr.cmnts': 1})[0])
    s = ''.join(s)
    output0 = re.findall(r'{\'tf\': (\d+), \'upvotes\': (\d+), \'cmnts\': (\d+), \'postId\': \'([a-zA-Z0-9]+)\'}', s)

    s = str(collection.find({'word': query[1]}, {'_id': 0, 'attr.postId': 1, 'attr.tf': 1, 'attr.upvotes': 1, 'attr.cmnts': 1})[0])
    s = ''.join(s)
    output1 = re.findall(r'{\'tf\': (\d+), \'upvotes\': (\d+), \'cmnts\': (\d+), \'postId\': \'([a-zA-Z0-9]+)\'}', s)

    s = str(collection.find({'word': query[2]}, {'_id': 0, 'attr.postId': 1, 'attr.tf': 1, 'attr.upvotes': 1, 'attr.cmnts': 1})[0])
    s = ''.join(s)
    output2 = re.findall(r'{\'tf\': (\d+), \'upvotes\': (\d+), \'cmnts\': (\d+), \'postId\': \'([a-zA-Z0-9]+)\'}', s)

    dict = {}

    for out in output0:
        if out[3] in dict:
            arr = dict[out[3]]
            cnt = arr[0] + 1
            upvt = arr[1] + int(out[0])
            dict[out[3]] = [cnt, upvt, int(out[1]), int(out[2]), out[3]]
        else:
            dict[out[3]] = [1, int(out[0]), int(out[1]), int(out[2]), out[3]]
    for out in output1:
        if out[3] in dict:
            arr = dict[out[3]]
            cnt = arr[0] + 1
            upvt = arr[1] + int(out[0])
            dict[out[3]] = [cnt, upvt, int(out[1]), int(out[2]), out[3]]
        else:
            dict[out[3]] = [1, int(out[0]), int(out[1]), int(out[2]), out[3]]
    for out in output2:
        if out[3] in dict:
            arr = dict[out[3]]
            cnt = arr[0] + 1
            upvt = arr[1] + int(out[0])
            dict[out[3]] = [cnt, upvt, int(out[1]), int(out[2]), out[3]]
        else:
            dict[out[3]] = [1, int(out[0]), int(out[1]), int(out[2]), out[3]]

    arr = list(dict.values())

    arr = sorted(arr, key=lambda x: (-x[0], -x[1], -x[2], -x[3]))
    # print(arr)
    collection2 = db.Dataset

    # outfin = collection2.find({'id': '6qrm2f'}, {'_id': 0, 'title': 1, 'link': 1, 'body': 1, 'comments': 1})
    # for out in outfin:
    #     print(out)

    cnt = 0
    for i in arr:
        outfin = collection2.find({'id': i[4]}, {'_id':0, 'title':1, 'link':1, 'body': 1, 'comments':1})
        for out in outfin:
            print(out)
        cnt += 1
        if cnt >= 10:
            break


    # print(query[0] + '---------------------------------------')
    # for out in output0:
    #     print(out)
    # print(query[1] + '---------------------------------------')
    # for out in output1:
    #     print(out)
    # print(query[2] + '---------------------------------------')
    # for out in output2:
    #     print(out)



if __name__ == "__main__":
    main_function(sys.argv[1:])