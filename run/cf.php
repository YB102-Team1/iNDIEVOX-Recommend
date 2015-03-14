<?php
// running data
$user_id = 1;
$item_type = 'disc';
$item_id = 98;
$debug = false;

include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';

function recommend($instance_user, $type, $item_id, $genre, $debug = false) {

    $user_array = array();
    $item_array = array();
    $temp_pref_array = array();
    $train_model = array();

    // read the set (from database)
    $db_obj = new DatabaseAccess();
    $sql = "SELECT * FROM train_model WHERE genre = $genre AND type = '$type'";
    $query_instance = $db_obj->select($sql);
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
        array_push($user_array, $user);
        array_push($item_array, $item);
        if ($user == $instance_user) {
            $temp_pref_array[$item] = $pref;
        }
        array_push($train_model, $data);
    }

    // make viewing item's pref higher
    $temp_pref_array[$item_id] += 10;

    // read the set (from file)
    // $file = @fopen('small.csv', "r");
    // while (!feof($file)) {
    //     $data_string = fgets($file);
    //     $data_array = explode(', ', $data_string);
    //     $user = $data_array[0];
    //     $item = $data_array[1];
    //     $pref = $data_array[2];

    //     $data = array(
    //         "user"=>$user,
    //         "item"=>$item,
    //         "pref"=>$pref
    //     );
    //     array_push($user_array, $user);
    //     array_push($item_array, $item);
    //     if ($user == $instance_user) {
    //         $temp_pref_array[$item] = $pref;
    //     }
    //     array_push($train_model, $data);
    // }

    // user and item list
    $user_array = array_unique($user_array);
    sort($user_array);
    $item_array = array_unique($item_array);
    sort($item_array);

    // complete the prefs array
    $pref_array = array();
    foreach ($item_array as $item_index => $item_value) {
        if (isset($temp_pref_array[$item_value])) {
            $pref_array[$item_index] = (float)$temp_pref_array[$item_value];
        } else {
            $pref_array[$item_index] = 0.0;
        }
    }

    // establish item index in set
    foreach ($train_model as $data_index => $data) {
        $train_model[$data_index]['index'] = array_search($data['item'], $item_array);
    }

    // co-occurrence matrix
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

    // get score
    $score = array();
    for ($i = 0; $i < $item_array_quantity; $i++){
        $score[$i] = 0;
        for($k = 0; $k < $item_array_quantity; $k++){
            $score[$i] += $co_occurrence[$i][$k] * $pref_array[$k];
        }
        if ($pref_array[$i] != 0 || $item[$i] == $item_id) {
            $score[$i] = 0;
        }
    }

    // combine item and score
    $result = array_combine($item_array, $score);
    arsort($result);

    if ($debug) {
        echo '<pre>';
        echo "co-occurrence matrix:\n";
        for ($i = 0; $i < $item_array_quantity; $i++) {
            for ($j = 0; $j < $item_array_quantity; $j++) {
                echo "\t".$co_occurrence[$i][$j];
            }
            echo '<br>';
        }
        echo "train model:\n";
        print_r($train_model);
        echo "user vector:\n";
        print_r($user_array);
        echo "item vector:\n";
        print_r($item_array);
        echo "temp pref vector:\n";
        print_r($temp_pref_array);
        echo "pref vector:\n";
        print_r($pref_array);
        echo "score vector:\n";
        var_dump($score);
        echo '</pre>';
    }

    unset($db_obj);

    return array_slice($result, 0, 10, true);

}

echo '<pre>';
$start = microtime(TRUE);

switch ($item_type) {
case 'song':
    $item_obj = new Song($item_id);
    break;

default:
    $item_obj = new Disc($item_id);
    break;
}
$item_genre = $item_obj->genre;

echo "user: ".$user_id."\n";
echo "type: ".$item_type."\n";
echo "id: ".$item_id."\n";
echo "title: ".$item_obj->title."\n";
echo "genre: ".$item_obj->genre."\n";
echo "artist_id: ".$item_obj->artist_id."\n";

echo "=============================================\n";
echo "User $x recommend top 10 result:\n";
echo "=============================================\n";
$result = recommend($user_id, $item_type, $item_id, $item_genre, $debug);
unset($item_obj);

$c = 1;
foreach ($result as $key => $value) {
    switch ($item_type) {
    case 'song':
        $item_obj = new Song($key);
        break;

    default:
        $item_obj = new Disc($key);
        break;
    }
    echo "Recommend  item $c:\n";
    echo "id: $key($value)\n";
    echo "title: ".$item_obj->title."\n";
    echo "genre: ".$item_obj->genre."\n";
    echo "artist_id: ".$item_obj->artist_id."\n";
    echo "\n";
    echo "---------------------------------------------\n";
    unset($item_obj);
    $c++;
}

$end = microtime(TRUE);
echo "Excute time: ".($end - $start);
echo '</pre>';
?>