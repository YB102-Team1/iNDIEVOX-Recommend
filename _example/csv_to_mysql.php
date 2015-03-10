<?php
# 讀取 csv 寫入 MySQL 資料庫範例 PHP 版本
# 假設 test_table 有 id, column1, column2, is_deleted, create_time, modify_time, delete_time 七個欄位
# 其中 id 會由資料庫自己編號、is_deleted 跟 delete_time 會有預設值 '0000-00-00 00:00:00'

####################################################################################################
# 版本1：基本
####################################################################################################
$link = new mysqli('localhost', 'team1', 'yb102', 'YB102_Team1');
$file = @fopen('source.csv', "r");
while (!feof($file)) {
    $data_string = fgets($file);
    str_replace("\n", "", $data_string);
    $data_array = explode(',', $data_string);
    $column1 = $data_array[0];
    $column2 = $data_array[1];
    $now = date('Y-m-d H:i:s');
    $insert_sql = "INSERT INTO `test_table` (`column1`, `column2`, `create_time`, `modify_time`) VALUES ('$column1', '$column2', '$now', '$now')";
    $link->query($insert_sql);
}
fclose($file);

####################################################################################################
# 版本1延伸：加入迴圈
####################################################################################################
$file_prefix = 'fan_list_57613404340_';
$link = new mysqli('localhost', 'team1', 'yb102', 'YB102_Team1');
for ($segment = 1; $segment <= 487; $segment++) {
    $file_path = $file_prefix.sprintf('%05d', $segment).'.csv';
    $file = @fopen($file_path, "r");
    while (!feof($file)) {
        $data_string = fgets($file);
        str_replace("\n", "", $data_string);
        $data_array = explode(',', $data_string);
        $column1 = $data_array[0];
        $column2 = $data_array[1];
        $now = date('Y-m-d H:i:s');
        $insert_sql = "INSERT INTO `test_table` (`column1`, `column2`, `create_time`, `modify_time`) VALUES ('$column1', '$column2', '$now', '$now')";
        $link->query($insert_sql);
    }
    fclose($file);
}



####################################################################################################
# 版本2：使用 god class
####################################################################################################
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$test_table_god_obj = new TestTableGod();
$file = @fopen('source.csv', "r");
while (!feof($file)) {
    $data_string = fgets($file);
    str_replace("\n", "", $data_string);
    $data_array = explode(',', $data_string);
    $column1 = $data_array[0];
    $column2 = $data_array[1];
    $param = array(
        "column1" => $column1,
        "column2" => $column2
    );
    $test_table_god_obj->create($param)
}
fclose($file);
unset($test_table_god_obj);

####################################################################################################
# 版本2延伸：加入迴圈
####################################################################################################
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$file_prefix = 'fan_list_57613404340_';
$test_table_god_obj = new TestTableGod();
for ($segment = 1; $segment <= 487; $segment++) {
    $file_path = $file_prefix.sprintf('%05d', $segment).'.csv';
    $file = @fopen($file_path, "r");
    while (!feof($file)) {
        $data_string = fgets($file);
        str_replace("\n", "", $data_string);
        $data_array = explode(',', $data_string);
        $column1 = $data_array[0];
        $column2 = $data_array[1];
        $param = array(
            "column1" => $column1,
            "column2" => $column2
        );
        $test_table_god_obj->create($param)
    }
    fclose($file);
}
unset($test_table_god_obj);
?>