# -*- coding:utf-8 -*-
import sys
reload(sys)
sys.setdefaultencoding("utf-8")

# 讀取 MySQL 資料庫範例 Python 版本
#
# 需要安裝 pymysql：
# pip install pymysql
#
# 假設 test_table 有 id, column1, column2, is_deleted, create_time, modify_time, delete_time 七個欄位

####################################################################################################
# 基本
####################################################################################################
import pymysql, time, datetime
conn = pymysql.connect(host='localhost', port=3306, user='team1', passwd='yb102', db='YB102_Team1')
cur = conn.cursor()
select_sql = "SELECT * FROM `test_table`"
cur.execute(select_sql)
for row in cur:
    sys.stdout.write('id="' + str(row[0]) + '"')
    sys.stdout.write(', column2="' + row[1] + '"')
    sys.stdout.write(', column2="' + row[2] + '"')
    sys.stdout.write(', is_deleted="' + str(row[3]) + '"')
    sys.stdout.write(', create_time="' + row[4].strftime('%Y-%m-%d %H:%M:%S') + '"')
    sys.stdout.write(', modify_time="' + row[5].strftime('%Y-%m-%d %H:%M:%S') + '"\n')
cur.close()
conn.close()