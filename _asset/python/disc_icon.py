# -*- coding:utf-8 -*-
import pymysql
import requests
import json
import sys
import time
reload(sys)
sys.setdefaultencoding("utf-8")


conn = pymysql.connect(host='localhost', port=3306, user='team1', passwd='yb102', db='YB102_Team1')
cur = conn.cursor()

#資料庫編碼都為UTF8
cur.execute('SET NAMES UTF8')
conn.commit()

#從資料表中撈出disc_id
id_sql = 'SELECT disc_id FROM disc_icon'
cur.execute(id_sql)
disc_id = cur.fetchall()

count = 0
for d in disc_id:
	html = "http://www.indievox.com/api/mobile/disc/profile/"+str(d[0])+"?app_id=B300000038"
	res = requests.get(html)
	j = json.loads(res.text)['response']
	icon_180 = j['icon_m']
	icon_480 = j['icon_480']

	disc_id = str(d[0])
	update_sql = "UPDATE disc_icon SET icon_180= '%s', icon_480='%s' WHERE disc_id=%s" %(icon_180,icon_480,disc_id)

	cur.execute(update_sql)
	conn.commit()

	count = count + 1
	print str(count) + ' done'

conn.close()