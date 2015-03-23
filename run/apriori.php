<pre>
<?php
ini_set('memory_limit', '2048M');
$start = microtime(TRUE);
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();

$time = microtime(TRUE);
$temp_tansactions = array();
$sql = "SELECT user_id, artist_id FROM train_model GROUP BY user_id, artist_id ORDER BY user_id, artist_id";
$query_instance = $db_obj->select($sql);
foreach ($query_instance as $instance_data) {
    if (is_array($temp_tansactions[$instance_data['user_id']])) {
        $temp_tansactions[$instance_data['user_id']][] = $instance_data['artist_id'];
    } else {
        $temp_tansactions[$instance_data['user_id']] = array($instance_data['artist_id']);
    }
}
$transactions = array();
foreach ($temp_tansactions as $tid => $t) {
    if (count($t) > 1) {
        $transactions[$tid] = implode(',', $t);
    }
}

echo "building transactions: ".(microtime(TRUE) - $time)." secs\n";
$time = microtime(TRUE);

$minOcc = 150;
$transactions_size = count($transactions);
$minSupp = ($minOcc / $transactions_size) * 100;
$minConf  = 1;
echo "[config]\n";
echo " - transactions: ".count($temp_tansactions)."\n";
echo " - cleaned transactions: ".count($transactions)."\n";
echo " - minOcc: $minOcc\n";
echo " - minSupp: $minSupp\n";
try {
    
    $apriori = new Apriori(Apriori::SRC_PLAIN, $transactions, $minSupp, $minConf);

    $apriori->solve();
echo "solve: ".(microtime(TRUE) - $time)." secs\n";
$time = microtime(TRUE);
    $apriori->generateRules();
echo "generateRules:".(microtime(TRUE) - $time)." secs\n";
$time = microtime(TRUE);
    $result_rules = array();
    echo "\n[result]\n";
    foreach ($apriori->getRules() as $X => &$rules) {
        foreach ($rules as $r_index => $r) {
            $r['set']  = $X.Apriori::ITEM_SEP.$r['Y'];
            $r['set']  = Apriori::_explode($r['set']);
            natcasesort($r['set']);
            $temp_set  = Apriori::_join($r['set']);
            // $keep_data = true;
            // foreach ($result_rules as $set) {
            //     if ($temp_set == $set['set']) {
            //         $keep_data = false;
            //     }
            // }
            // if ($keep_data) {
                $r['set'] = $temp_set;
                array_push($result_rules, $r);
                // echo $r['set']." (support: ".$r['supp'].")".($transactions_size * $r['supp'] / 100)."\n";
            // }
        }
    }
    unset($apriori);
    
} catch (Exception $e) {
    echo $e->getMessage();
}

echo " - rules: ".count($result_rules)."\n";

// $sql = "TRUNCATE similar_artist";
// $db_obj->query($sql);

$counter = 0;
foreach ($result_rules as $data) {
    $artist_array = explode(',', $data['set']);
    $artist1 = $artist_array[0];
    $artist2 = $artist_array[1];
    $support = $data['supp'];
    $occurrence = $transactions_size * $support / 100;
    if ($counter % 100 == 0) {
        if ($counter != 0) {
            $db_obj->query($sql);
        }
        $now = date('Y-m-d H:i:s');
        $sql = "INSERT INTO similar_artist (artist1, artist2, support, occurrence, create_time, modify_time) VALUES ('$artist1', '$artist2', '$support', '$occurrence', '$now', '$now'), ('$artist2', '$artist1', '$support', '$occurrence', '$now', '$now')";
    } else {
        $sql .= ", ('$artist1', '$artist2', '$support', '$occurrence', '$now', '$now'), ('$artist2', '$artist1', '$support', '$occurrence', '$now', '$now')";
    }
    $counter++;
}
$db_obj->query($sql);

unset($db_obj);
$end = microtime(TRUE);
echo "\nexcute time:".($end - $start)." secs\n";
?>
</pre>