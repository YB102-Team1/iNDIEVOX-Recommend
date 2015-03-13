<?php
function recommend($instance_user, $item_id, $genre, $debug = false) {

    $user_array = array();
    $item_array = array();
    $temp_pref_array = array();
    $train_model = array();
    
    // read the set
    $file = @fopen('small.csv', "r");
    while (!feof($file)) {
        $data_string = fgets($file);
        $data_array = explode(', ', $data_string);
        $user = $data_array[0];
        $item = $data_array[1];
        $pref = $data_array[2];

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
    unset($temp_pref_array);


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
    foreach ($result as $key => $value) {
        echo "$key : $value\n";
    }

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
        echo "pref vector:\n";
        print_r($pref_array);
        echo "score vector:\n";
        var_dump($score);
        echo '</pre>';
    }

}


echo '<pre>';
$start = microtime(TRUE);
for ($x = 1; $x <= 5; $x ++) {
    echo "user $x recommend result:<br>";
    recommend($x);
    echo '<br>';
}
$end = microtime(TRUE);
echo "excute time: ".($end - $start);
echo '</pre>';
?>