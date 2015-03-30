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

#從user資料表中撈出user id
id_sql = 'SELECT id FROM user'
cur.execute(id_sql)
user = cur.fetchall()
count = 0

for u in user:	
	html = 'http://www.indievox.com/api/mobile/artist/profile/' + str(u[0]) + '?app_id=B300000038'
	res = requests.get(html)
	j = json.loads(res.text)['response']
	title = j['title'].encode('utf-8')
	url = j['url']
	icon = j['icon_m']
	fans = j['fans']
	description = j['description'].encode('utf-8')
		
	user_id = str(u[0])
	update_user_sql = "UPDATE user SET title= '%s', url='%s', icon='%s', fans='%s', description='%s' WHERE id=%s" %(conn.escape_string(title),url,icon,fans,conn.escape_string(description),user_id)

	cur.execute(update_user_sql.encode('utf-8'))
	conn.commit()

	event = j['event']
	
	# print len(event)
	for e in event:
		e_id = e['id']
		select_sql = 'SELECT id FROM event WHERE id=' + e_id
		cur.execute(select_sql)
		# conn.commit()
		result = cur.fetchall()
		# print e
		# print len(result)
		if len(result) == 0:
			insert_sql = 'INSERT INTO event(id,title,icon,place,city,`date`,detail_time) VALUES (\''+e_id+'\',\''+conn.escape_string(e['title'].encode('utf-8'))+'\',\''+e['icon_m']+'\',\''+conn.escape_string(e['place'])+'\',\''+conn.escape_string(e['city'])+'\',\''+e['date']+'\',\''+e['fine_date_time']+'\')'
			cur.execute(insert_sql.encode('utf-8'))
			conn.commit()

		select_sql = 'SELECT id FROM event_user WHERE event_id=' + e_id + ' AND artist_id=' + user_id
		cur.execute(select_sql)
		result = cur.fetchall()
		if len(result) == 0:
			insert_sql = 'INSERT INTO event_user(event_id, artist_id) VALUES (\''+e_id+'\',\''+user_id+'\')'
			
			cur.execute(insert_sql.encode('utf-8'))
			conn.commit()

	count = count + 1
	time.sleep(5)
	print str(count) + ' done'

conn.close()