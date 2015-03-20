<pre>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();
// $sql = "CREATE TABLE IF NOT EXISTS `co_occurrence` (
//     `id` int(11) unsigned NOT NULL,
//     `row_index` int(11) unsigned NOT NULL ,
//     `row_value` text NOT NULL ,
//     `method` varchar(30) NOT NULL ,
//     `group` int(11) unsigned NOT NULL ,
//     `type` enum('disc','song') NOT NULL ,
//     `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
//     `create_time` datetime NOT NULL,
//     `modify_time` datetime NOT NULL,
//     `delete_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
// ) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
// $db_obj->query($sql);
// $sql = "ALTER TABLE `co_occurrence` ADD PRIMARY KEY (`id`)";
// $db_obj->query($sql);
// $sql = "ALTER TABLE `co_occurrence` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT;";
// $db_obj->query($sql);

// clear table
$sql = "TRUNCATE co_occurrence";
$db_obj->query($sql);

// genre
$type_array = array('disc', 'song');
foreach ($type_array as $type) {
    for ($genre = 1; $gerne <= 19; $genre++) {
        $sql = "SELECT * FROM train_model WHERE genre = $genre AND type = '$type'";
        $query_instance = $db_obj->select($sql);
        $item_array = array();
        $train_model = array();
        foreach ($query_instance as $instance_data) {
            $user = $instance_data['user_id'];
            $item = $instance_data['on_thing_id'];
            $pref = 0;
            if ($instance_data['is_purchased'] == 1) {
                $pref += 3;
            }
            if ($instance_data['is_liked'] == 1) {
                $pref += 2;
            }

            $data = array(
                "user"=>$user,
                "item"=>$item,
                "pref"=>$pref
            );
            array_push($item_array, $item);
            array_push($train_model, $data);
        }
        $item_array = array_unique($item_array);
        sort($item_array);
        foreach ($train_model as $data_index => $data) {
            $train_model[$data_index]['index'] = array_search($data['item'], $item_array);
        }
        $item_array_quantity = count($item_array);
        $co_occurrence = array();
        for ($i = 0; $i < $item_array_quantity; $i++) {
            for ($j = 0; $j < $item_array_quantity; $j++) {
                $co_occurrence[$i][$j] = 0;
            }
        }
        foreach ($train_model as $data_x) {
            foreach ($train_model as $data_y) {
                if ($data_x['user'] == $data_y['user']) {
                    $co_occurrence[$data_x['index']][$data_y['index']] += 1;
                }
            }
        }
        for ($row_index = 0; $row_index < $item_array_quantity; $row_index++) {
            $row_value = implode(',', $co_occurrence[$row_index]);
            $now = date('Y-m-d H:i:s');
            $sql = "INSERT INTO co_occurrence (`row_index`, `row_value`, `method`, `group`, `type`, `create_time`, `modify_time`) VALUES ('$row_index', '$row_value', 'genre', '$genre', '$type', '$now', '$now')";
            $db_obj->query($sql);
        }
    }
}
?>
</pre>