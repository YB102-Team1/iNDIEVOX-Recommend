<pre>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();
$sql_array = array(
    "user-buy_disc_record" => "SELECT buyer_id, COUNT(id) purchased, SUM(price) amount FROM buy_disc_record GROUP BY buyer_id",
    "disc-buy_disc_record" => "SELECT buyer_id, COUNT(id) purchased, SUM(price) amount FROM buy_disc_record GROUP BY disc_id",
    "user-buy_song_record" => "SELECT buyer_id, COUNT(id) purchased, SUM(price) amount FROM buy_song_record WHERE buy_type = 'single_song' GROUP BY buyer_id",
    "song-buy_song_record" => "SELECT buyer_id, COUNT(id) purchased, SUM(price) amount FROM buy_song_record WHERE buy_type = 'single_song' GROUP BY song_id"
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

    foreach ($clusters as $cluster) {
        if (count($cluster->getData()) < 10) {
            return false;
        }
    }
    unset($kmeans);

    return $clusters;
}

foreach ($sql_array as $title => $sql) {
    $query_instance = $db_obj->select($sql);
    $data = array();
    foreach ($query_instance as $instance_data) {
        $coordinate = array(
            "data_id" => $instance_data['buyer_id'],
            "x" => $instance_data['purchased'],
            "y" => $instance_data['amount']
        );
        array_push($data, $coordinate);
    }

    $clusers_number = 5;
    $clusters = eval_kmeans($data, $clusers_number);
    while (!$clusters) {
        $clusters = eval_kmeans($data, --$clusers_number);
    }

    echo "$title\t\t\t$clusers_number\n";
    echo "x\ty\titem_number\trecord_number\n";
    foreach ($clusters as $cluster) {
        echo $cluster->getX()."\t".$cluster->getY()."\t".count($cluster->getData())."\t".(count($cluster->getData()) * $cluster->getX())."\n";
    }
    echo "\n";
}
?>
</pre>