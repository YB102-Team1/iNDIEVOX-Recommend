<pre>
<?php
ini_set('memory_limit', '2048M');
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();

function recommend($user_id, $item_id, $artist_id, $genre, $segment) {
        
    $link = new DatabaseAccess();

    $similar_artist_god_obj = new SimilarArtistGod();
    $similar_artist = $similar_artist_god_obj->getSimilarArtistArray($artist_id);
    $similar_artist_list = $artist_id.','.implode(',', $similar_artist);
    unset($similar_artist_god_obj);
    
    // cf init
    $user_array = array();
    $item_array = array();
    $temp_pref_array = array();
    $train_model = array();

    if (count($similar_artist)) {
        $sql = "SELECT * ".
               "FROM train_set_$segment ".
               "WHERE type = 'disc' ".
               "AND genre = $genre ".
               "AND artist_id IN ($similar_artist_list) ";
    } else {
        $sql = "SELECT * ".
               "FROM train_set_$segment ".
               "WHERE type = 'disc' ".
               "AND genre = $genre ".
               "AND price!= 0";
    }
    $query_instance = $link->select($sql);

    // read tarin model
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

    // make this item pref higher
    $temp_pref_array[$item_id] += 10;

    // purify user array and item array
    $user_array = array_unique($user_array);
    sort($user_array);
    $item_array = array_unique($item_array);
    sort($item_array);
    $item_index_array = array_flip($item_array);
    $item_array_quantity = count($item_array);

    // complete the prefs array
    $pref_array = array();
    foreach ($item_array as $item_index => $item_value) {
        if (isset($temp_pref_array[$item_value])) {
            $pref_array[$item_index] = (float)$temp_pref_array[$item_value];
        } else {
            $pref_array[$item_index] = 0.0;
        }
    }

    // co-occurrence matrix
    $co_occurrence = array();
    for ($i = 0; $i < $item_array_quantity; $i++) {
        for ($j = 0; $j < $item_array_quantity; $j++) {
            $co_occurrence[$i][$j] = 0;
        }
    }
    $transactions = array();
    foreach ($train_model as $instance_data) {
        if (is_array($transactions[$instance_data['user']])) {
            $transactions[$instance_data['user']][] = $instance_data['item'];
        } else {
            $transactions[$instance_data['user']] = array($instance_data['item']);
        }
    }
    foreach ($transactions as $tid => $t) {
        foreach ($t as $t1) {
            foreach ($t as $t2) {
                $i = $item_index_array[$t1];
                $j = $item_index_array[$t2];
                $co_occurrence[$i][$j]++;
                $co_occurrence[$j][$i]++;
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

    unset($link);

    return array_slice($result, 0, 10, true);

}

for ($i = 0; $i <= 4; $i++) {
    // $sql = "SELECT * FROM train_set_$i WHERE type = 'disc' AND RAND() < 0.0035 LIMIT 100";
    $sql = "SELECT * FROM train_set_$i WHERE type = 'disc' AND RAND() < 0.01 LIMIT 300";
    $query_instance = $db_obj->select($sql);
    $test_time = 0;
    $occurrence = 0;
    $is_purchased = 0;
    $priced = 0;

    foreach ($query_instance as $instance_data) {
        $user_id = $instance_data['user_id'];
        $result = recommend(
            $user_id, 
            $instance_data['on_thing_id'], 
            $instance_data['artist_id'],
            $instance_data['genre'],
            $i
        );
        $disc_array = array();
        foreach ($result as $disc_id => $score) {
            array_push($disc_array, $disc_id);
        }
        $disc_list = implode(',', $disc_array);
        $sql = "SELECT id, price, is_purchased FROM test_set_$i WHERE user_id = $user_id AND type = 'disc' AND on_thing_id IN ($disc_list)";
        $query_instance = $db_obj->select($sql);
        foreach ($query_instance as $instance_data) {
            if ($instance_data['is_purchased']) {
                $is_purchased++;
            }
            if ($instance_data['price'] != '0') {
                $priced++;
            }
            $occurrence++;
        }
        $test_time++;
    }

    echo "set $i: ".(100 * $occurrence / $test_time)." %, ".(100 * $is_purchased / $test_time)." %, ".(100 * $priced / $test_time)." %\n";
}

unset($db_obj);
?>
</pre>