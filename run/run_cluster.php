<pre>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();
$sql_array = array(
    "Disc-user" => "SELECT buyer_id data_id, COUNT(id) purchased, SUM(price) amount FROM buy_disc_record WHERE price != 0 GROUP BY buyer_id",
    "Disc-disc" => "SELECT disc_id data_id, COUNT(id) purchased, SUM(price) amount FROM buy_disc_record WHERE price != 0 GROUP BY disc_id",
    "Song-user" => "SELECT buyer_id data_id, COUNT(id) purchased, SUM(price) amount FROM buy_song_record WHERE buy_type = 'single_song' AND price != 0 GROUP BY buyer_id",
    "Song-song" => "SELECT song_id data_id, COUNT(id) purchased, SUM(price) amount FROM buy_song_record WHERE buy_type = 'single_song' AND price != 0 GROUP BY song_id"
);

function eval_kmeans($data, $clusers_number) {
    $kmeans = new KMeans();
    $kmeans
        ->setData($data)
        ->setXKey('x')
        ->setYKey('y')
        ->setClusterCount($clusers_number)
        ->solve();
    $clusters = $kmeans->getClusters();

    unset($kmeans);

    return $clusters;
}

$sql = "TRUNCATE shopping_cluster";
$db_obj->query($sql);

foreach ($sql_array as $title => $sql) {
    $query_instance = $db_obj->select($sql);
    $data = array();
    foreach ($query_instance as $instance_data) {
        $coordinate = array(
            "data_id" => $instance_data['data_id'],
            "x" => $instance_data['purchased'],
            "y" => $instance_data['amount']
        );
        array_push($data, $coordinate);
    }

    $clusers_number = 3;
    $clusters = eval_kmeans($data, $clusers_number);

    $cluster_index_array = array();
    foreach ($clusters as $cluster) {
        $cluster_index_array[] = $cluster->getX();
    }
    asort($cluster_index_array);

    echo "$title\t\t\t$clusers_number\n";
    echo "x\ty\titem_number\trecord_number\n";

    $group_number = 1;
    foreach ($cluster_index_array as $cluster_index => $x) {
        $cluster = $clusters[$cluster_index];
        echo $cluster->getX()."\t".$cluster->getY()."\t".count($cluster->getData())."\t".(count($cluster->getData()) * $cluster->getX())."\n";
        switch ($title) {

        case 'Disc-user':
            $data_type = 'disc';
            $cluster_type = 'user';
            $where_column = 'user_id';
            $group_column = 'user_group';
            break;

        case 'Disc-disc':
            $data_type = 'disc';
            $cluster_type = 'item';
            $where_column = 'on_thing_id';
            $group_column = 'item_group';
            break;

        case 'Song-user':
            $data_type = 'song';
            $cluster_type = 'user';
            $where_column = 'user_id';
            $group_column = 'user_group';
            break;

        case 'Song-song':
            $data_type = 'song';
            $cluster_type = 'item';
            $where_column = 'on_thing_id';
            $group_column = 'item_group';
            break;

        }

        $now = date('Y-m-d H:i:s');
        $sql = "INSERT INTO shopping_cluster (cluster_type, item_type, x, y, item_count, record_count, group_serial, create_time, modify_time) VALUES ('$cluster_type', '$data_type', '".$cluster->getX()."', '".$cluster->getY()."', '".count($cluster->getData())."', '".(count($cluster->getData()) * $cluster->getX())."', '$group_number', '$now', '$now')";
        $db_obj->query($sql);

        $where_condition_array = array();
        foreach ($cluster->getData() as $data) {
            $where_condition_array[] = $data['data_id'];
        }
        $where_condition = implode(',', $where_condition_array);
        $now = date('Y-m-d H:i:s');
        $sql = "UPDATE train_model SET $group_column = '$group_number', modify_time = '$now' WHERE type = '$data_type' AND $where_column IN ($where_condition)";
        $db_obj->query($sql);
        $group_number++;
    }
    echo "\n";
}
?>
</pre>