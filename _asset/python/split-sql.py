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
    for content in contents[1:]:
        target_file_name = '../sql/data/' + table + '_' + str('%05d' %counter) + '.seg';
        target_file = open(target_file_name, 'w')
        target_file.write('INSERT INTO' + content)
        target_file.close()
        counter = counter + 1
    print table + ' => done'