<pre>
<?php
ini_set('memory_limit', '2048M');
$start = microtime(TRUE);
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();
$debug = true;

$minOcc = 50;

$time = microtime(TRUE);
$sql = "SELECT artist_id, COUNT(id) occ_count FROM train_model GROUP BY artist_id HAVING occ_count > $minOcc";
$query_instance = $db_obj->select($sql);
$artist_array = array();
foreach ($query_instance as $instance_data) {
    $artist_array[] = $instance_data['artist_id'];
}
$artist_list = implode(',', $artist_array);
echo "building item list: ".(microtime(TRUE) - $time)." secs\n";

$time = microtime(TRUE);
$temp_tansactions = array();
$sql = "SELECT user_id, artist_id FROM train_model WHERE artist_id IN ($artist_list) GROUP BY user_id, artist_id ORDER BY user_id, artist_id";
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
        $transactions[$tid] = $t;
    }
}
echo "build transactions: ".(microtime(TRUE) - $time)." secs\n";

if ($debug) {
    $result = "<?php\n\$transactions = array(\n";
    $first = true;
    foreach ($transactions as $tid => $t) {
        if ($first) {
            $result .= "\t'$tid' => array(".implode(', ', $t).")";
            $first = false;
        } else {
            $result .= ",\n\t'$tid' => array(".implode(', ', $t).")";
        }
    }
    file_put_contents('transactions.dat', $result."\n);\n?>");
}

$time = microtime(TRUE);
$patterns = array();
foreach ($transactions as $tid => $t) {
    foreach ($t as $t1) {
        foreach ($t as $t2) {
            if ($t1 != $t2) {
                if ($t1 < $t2) {
                    $patterns[$t1.'.'.$t2]++;
                } else {
                    $patterns[$t2.'.'.$t1]++;
                }
            }
        }
    }
}
$patterns = array_map(function ($x) {
    return $x / 2;
}, $patterns);
ksort($patterns);
echo "build patterns: ".(microtime(TRUE) - $time)." secs\n";

if ($debug) {
    $result = "<?php\n\$patterns = array(\n";
    $first = true;
    foreach ($patterns as $pattern => $count) {
        if ($first) {
            $result .= "\t'$pattern' => $count";
            $first = false;
        } else {
            $result .= ",\n\t'$pattern' => $count";
        }
    }
    file_put_contents('patterns.dat', $result."\n);\n?>");
}

$transactions_size = count($transactions);
$minSupp = ($minOcc / $transactions_size) * 100;

$time = microtime(TRUE);
$rules = array();
foreach ($patterns as $pattern => $count) {
    if ($count > $minOcc) {
        $rule = array(
            "pattern" => $pattern,
            "support" => $count / $transactions_size,
            "occurrence" => $count
        );
        array_push($rules, $rule);
    }
}
echo "filter rules: ".(microtime(TRUE) - $time)." secs\n";

if ($debug) {
    $result = "<?php\n\$rules = array(\n";
    $first = true;
    foreach ($rules as $rule) {
        if ($first) {
            $result .= "\tarray(\n";
            $result .= "\t\t'pattern' => '".$rule['pattern']."',\n";
            $result .= "\t\t'support' => '".$rule['support']."',\n";
            $result .= "\t\t'occurrence' => '".$rule['occurrence']."',\n";
            $result .= "\t)";
            $first = false;
        } else {
            $result .= ",\n\tarray(\n";
            $result .= "\t\t'pattern' => '".$rule['pattern']."',\n";
            $result .= "\t\t'support' => '".$rule['support']."',\n";
            $result .= "\t\t'occurrence' => '".$rule['occurrence']."',\n";
            $result .= "\t)";
        }
    }
    file_put_contents('rules.dat', $result."\n);\n?>");
}

echo "\n";
echo "[config]\n";
echo " - minOcc: $minOcc\n";
echo "\n";
echo "[statistics]\n";
echo " - transactions: ".count($transactions)."\n";
echo " - patterns: ".count($patterns)."\n";
echo " - rules: ".count($rules)."\n";

echo "\ntotal execute time: ".(microtime(TRUE) - $start)." secs\n";

$sql = "TRUNCATE similar_artist";
$db_obj->query($sql);

$time = microtime(TRUE);
$counter = 0;
foreach ($rules as $data) {
    $artist_array = explode('.', $data['pattern']);
    $artist1 = $artist_array[0];
    $artist2 = $artist_array[1];
    $support = $data['support'];
    $occurrence = $data['occurrence'];
    if ($counter % 500 == 0) {
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
echo "\ndump time: ".(microtime(TRUE) - $time)." secs\n";

unset($db_obj);
?>
</pre>