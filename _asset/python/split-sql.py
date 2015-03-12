# -*- coding:utf-8 -*-
# __author__ = 'Samas Lin<samas0120@gmail.com>'
import sys

reload(sys)
sys.setdefaultencoding("utf-8")

tables = ['buy_disc_record', 'buy_song_record', 'disc', 'favorite', 'song']
for table in tables:
    source_file_name = '../sql/data/' + table + '.sql'
    source_file = open(source_file_name, 'r')
    contents = source_file.read().split('INSERT INTO');
    source_file.close();
    counter = 1
    for content in contents:
        target_file_name = '../sql/data/' + str('%03d' %counter) + '.sql';
        target_file = open(target_file, 'w')
        target_file.write(content)
        target_file.close()
        counter = counter + 1
