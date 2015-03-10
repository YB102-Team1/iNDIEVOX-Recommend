<?php
$file_prefix = 'user_likes_57613404340_';
$link = new mysqli('localhost', 'team1', 'yb102', 'YB102_Team1');
for ($segment = 1; $segment <= 487; $segment++) {
    $file_path = $file_prefix.sprintf('%05d', $segment).'.csv';
    $file = @fopen($file_path, "r");
    while (!feof($file)) {
        $data_string = fgets($file);
        if ($data_string == "\n") {
        	break;
        }
        str_replace("\n", "", $data_string);
        $data_array = explode(',', $data_string);
        $column1 = $data_array[0];
        $column2 = $data_array[1];
        $column3 = $data_array[2];
        $now = date('Y-m-d H:i:s');
        $insert_sql = "INSERT INTO `fb_user_likes` (`fb_user_id`, `fb_fan_page_id`, `create_time`, `modify_time`) VALUES ('$column1', '$column2', '$now', '$now')";
        $link->query($insert_sql);
    }
    fclose($file);
}
?>
