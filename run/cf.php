<?php
function recommend($user) {

    // read the set
    $file = @fopen('small.csv', "r");
    $users = array();
    $items = array();
    $temp_prefs = array();
    $train = array();
    while (!feof($file)) {
        $data_string = fgets($file);
        $data_array = explode(', ', $data_string);
        $data = array(
            "user"=>$data_array[0],
            "item"=>$data_array[1],
            "pref"=>$data_array[2]
        );
        array_push($users, $data_array[0]);
        array_push($items, $data_array[1]);
        if ($data_array[0] == $user) {
            $temp_prefs[$data_array[1]] = $data_array[2];
        }
        array_push($train, $data);
    }

    // user and item list
    $users = array_unique($users);
    sort($users);
    $items = array_unique($items);
    sort($items);

    // complete the prefs array
    $prefs = array();
    foreach ($items as $item_index => $item_value) {
        if (isset($temp_prefs[$item_value])) {
            $prefs[$item_index] = (float)$temp_prefs[$item_value];
        } else {
            $prefs[$item_index] = 0.0;
        }
    }
    unset($temp_prefs);


    // establish item index in set
    foreach ($train as $data_index => $data) {
        $train[$data_index]['index'] = array_search($data['item'], $items);
    }

    // co-occurrence matrix
    $items_quantity = count($items);
    $co_occurrence = array();
    for ($i = 0; $i < $items_quantity; $i++) {
        for ($j = 0; $j < $items_quantity; $j++) {
            $co_occurrence[$i][$j] = 0;
        }
    }
    foreach ($train as $data_x) {
        foreach ($train as $data_y) {
            if ($data_x['user'] == $data_y['user']) {
                $co_occurrence[$data_x['index']][$data_y['index']] += 1;
            }
        }
    }

    // get score
    $score = array();
    for ($i = 0; $i < $items_quantity; $i++){
        $score[$i] = 0;
        for($k = 0; $k < $items_quantity; $k++){
            $score[$i] += $co_occurrence[$i][$k] * $prefs[$k];
        }
        if ($prefs[$i] != 0) {
            $score[$i] = 0;
        }
    }

    // combine item and score
    $result = array_combine($items, $score);
    arsort($result);
    foreach ($result as $key => $value) {
        echo "$key : $value\n";
    }

    echo '<pre>';
    // for ($i = 0; $i < $items_quantity; $i++) {
    //     for ($j = 0; $j < $items_quantity; $j++) {
    //         echo "\t".$co_occurrence[$i][$j];
    //     }
    //     echo '<br>';
    // }
    // print_r($train);
    // print_r($users);
    // print_r($items);
    // var_dump($prefs);
    // var_dump($score);
    echo '</pre>';

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