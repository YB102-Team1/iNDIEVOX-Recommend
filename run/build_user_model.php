<pre>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();
$sql = "SELECT buyer_id, COUNT(id) purchased, SUM(price) amount FROM buy_song_record GROUP BY buyer_id";
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

$clusers_number = 10;
$clusters = eval_kmeans($data, $clusers_number);
while (!$clusters) {
    $clusters = eval_kmeans($data, --$clusers_number);
}

echo "clusters: $clusers_number\n";
foreach ($clusters as $cluster) {
    echo "(".$cluster->getX().','.$cluster->getY()."):".count($cluster->getData())."\n";
}
echo "\n";
?>
</pre>