<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();

$type_array = array('disc');
foreach ($type_array as $type) {
	for ($genre = 1; $gerne <= 20; $genre++) {
		if ($genre == 20) {
			$sql = "SELECT * FROM train_model WHERE type = '$type'";
		} else {
			$sql = "SELECT * FROM train_model WHERE genre = $genre AND type = '$type'";
		}
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
	        $model_counter++;
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