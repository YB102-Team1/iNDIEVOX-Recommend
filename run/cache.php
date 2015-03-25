<pre>
<?php
ini_set('memory_limit', '2048M');
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();

// clear table
$sql = "TRUNCATE co_occurrence";
$db_obj->query($sql);

// disc genre
// $sql = "DELETE FROM co_occurrence WHERE method = 'genre' AND type = 'disc'";
// $db_obj->query($sql);
$start = microtime(TRUE);
for ($genre = 1; $genre <= 19; $genre++) {
    $sql = "SELECT * FROM train_model WHERE genre = $genre AND type = 'disc'";
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
    echo "[genre] $type $genre: $item_array_quantity\n";
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
        $sql = "INSERT INTO co_occurrence (`row_index`, `row_value`, `method`, `group`, `type`, `create_time`, `modify_time`) VALUES ('$row_index', '$row_value', 'genre', '$genre', 'disc', '$now', '$now')";
        $db_obj->query($sql);
    }
    unset($item_array);
    unset($train_model);
    unset($co_occurrence);
}
echo "caching disc genre method: ".(microtime() - $start)." secs\n";

// song genre
// $sql = "DELETE FROM co_occurrence WHERE method = 'genre' AND type = 'song'";
// $db_obj->query($sql);
$start = microtime(TRUE);
for ($genre = 1; $genre <= 19; $genre++) {
    $sql = "SELECT * FROM train_model WHERE genre = $genre AND type = 'song' AND price != 0";
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
    echo "[genre] $type $genre: $item_array_quantity\n";
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
        $sql = "INSERT INTO co_occurrence (`row_index`, `row_value`, `method`, `group`, `type`, `create_time`, `modify_time`) VALUES ('$row_index', '$row_value', 'genre', '$genre', 'song', '$now', '$now')";
        $db_obj->query($sql);
    }
    unset($item_array);
    unset($train_model);
    unset($co_occurrence);
}
echo "caching song genre method: ".(microtime() - $start)." secs\n";

// rank_disc
// $sql = "DELETE FROM co_occurrence WHERE method = 'cluster'";
// $db_obj->query($sql);
$start = microtime(TRUE);
$type_array = array('disc', 'song');
foreach ($type_array as $type) {
    for ($user_group = 1; $user_group <= 3; $user_group++) {
        $item_group = 4 - $user_group;
        $sql = "SELECT * FROM train_model WHERE type = '$type' AND user_group = $user_group AND item_group = $item_group";
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
        echo "[cluster] $type $user_group: $item_array_quantity\n";
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
            $sql = "INSERT INTO co_occurrence (`row_index`, `row_value`, `method`, `group`, `type`, `create_time`, `modify_time`) VALUES ('$row_index', '$row_value', 'cluster', '$user_group', '$type', '$now', '$now')";
            $db_obj->query($sql);
        }
        unset($item_array);
        unset($train_model);
        unset($co_occurrence);
    }
}
echo "caching cluster method: ".(microtime() - $start)." secs\n";
?>
</pre>