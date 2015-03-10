# -*- coding:utf-8 -*-
import sys
reload(sys)
sys.setdefaultencoding("utf-8")

# 讀取 csv 寫入 MySQL 資料庫範例 Python 版本
#
# 需要安裝 pymysql：
# pip install pymysql
#
# 假設 test_table 有 id, column1, column2, is_deleted, create_time, modify_time, delete_time 七個欄位
# 其中 id 會由資料庫自己編號、is_deleted 跟 delete_time 會有預設值 '0000-00-00 00:00:00'

####################################################################################################
# 基本
####################################################################################################
import pymysql, time
conn = pymysql.connect(host='localhost', port=3306, user='team1', passwd='yb102', db='YB102_Team1')
cur = conn.cursor()
src_file = open('source.csv', 'r')
for line in src_file.readlines():
    data_list = line.replace('\n', '').split(',')
    column1 = data_list[0]
    column2 = data_list[1]
    now = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(time.time()))
    insert_sql = "INSERT INTO `test_table` (`column1`, `column2`, `create_time`, `modify_time`) VALUES ('%s', '%s', '%s', '%s')" %(column1, column2, now, now)
    cur.execute(insert_sql)
    conn.commit()
src_file.close()
cur.close()
conn.close()

####################################################################################################
# 延伸：加入迴圈
####################################################################################################
import pymysql, time
file_prefix = 'fan_list_57613404340_'
conn = pymysql.connect(host='localhost', port=3306, user='team1', passwd='yb102', db='YB102_Team1')
cur = conn.cursor()
for segment_number in range(1, 488):
    file_path = file_prefix + str('%05d' %segment_number) + '.csv'
    src_file = open(file_path, 'r')
    for line in src_file.readlines():
        data_list = line.replace('\n', '').split(',')
        column1 = data_list[0]
        column2 = data_list[1]
        now = time.strftime('%Y-%m-%d %H:%M:%S', time.localtime(time.time()))
        insert_sql = "INSERT INTO `test_table` (`column1`, `column2`, `create_time`, `modify_time`) VALUES ('%s', '%s', '%s', '%s')" %(column1, column2, now, now)
        cur.execute(insert_sql)
        conn.commit()
    src_file.close()
cur.close()
conn.close()