<pre>
<?php
$data = array( 
    array(10, 1),
    array(11, 0.9),
    array(12, 1.1),
    array(25, 25),
    array(27, 27),
    array(26, 28),
    array(0, 11), 
    array(0, 12),
    array(1, 12),
    array(1, 11)
);

// Lets normalise the input data
foreach($data as $key => $d) {
    $data[$key] = normaliseValue($d, sqrt($d[0]*$d[0] + $d[1] * $d[1]));
}

var_dump(kMeans($data, 3));

function initialiseCentroids(array $data, $k) {
    $dimensions = count($data[0]);
    $centroids = array();
    $dimmax = array();
    $dimmin = array(); 
    foreach($data as $document) {
        foreach($document as $dim => $val) {
            if(!isset($dimmax[$dim]) || $val > $dimmax[$dim]) {
                $dimmax[$dim] = $val;
            }
            if(!isset($dimmin[$dim]) || $val < $dimmin[$dim]) {
                $dimmin[$dim] = $val;
            }
        }
    }
    for($i = 0; $i < $k; $i++) {
        $centroids[$i] = initialiseCentroid($dimensions, $dimmax, $dimmin);
    }
    return $centroids;
}

function initialiseCentroid($dimensions, $dimmax, $dimmin) {
    $total = 0;
    $centroid = array();
    for($j = 0; $j < $dimensions; $j++) {
        $centroid[$j] = (rand($dimmin[$j] * 1000, $dimmax[$j] * 1000));
        $total += $centroid[$j] * $centroid[$j];
    }
    $centroid = normaliseValue($centroid, sqrt($total));
    return $centroid;
}

function kMeans($data, $k) {
    $centroids = initialiseCentroids($data, $k);
    $mapping = array();

    while(true) {
        $new_mapping = assignCentroids($data, $centroids);
        $changed = false;
        foreach($new_mapping as $documentID => $centroidID) {
            if(!isset($mapping[$documentID]) || $centroidID != $mapping[$documentID]) {
                $mapping = $new_mapping;
                $changed = true;
                break;
            }
        }
        if(!$changed){
            return formatResults($mapping, $data, $centroids); 
        }
        $centroids  = updateCentroids($mapping, $data, $k); 
    }
}

function formatResults($mapping, $data, $centroids) {
    $result  = array();
    $result['centroids'] = $centroids;
    foreach($mapping as $documentID => $centroidID) {
        $result[$centroidID][] = implode(',', $data[$documentID]);
    }
    return $result;
}

function assignCentroids($data, $centroids) {
    $mapping = array();

    foreach($data as $documentID => $document) {
        $minDist = 100;
        $minCentroid = null;
        foreach($centroids as $centroidID => $centroid) {
            $dist = 0;
            foreach($centroid as $dim => $value) {
                $dist += abs($value - $document[$dim]);
            }
            if($dist < $minDist) {
                $minDist = $dist;
                $minCentroid = $centroidID;
            }
        }
        $mapping[$documentID] = $minCentroid;
    }

    return $mapping;
}

function updateCentroids($mapping, $data, $k) {
    $centroids = array();
    $counts = array_count_values($mapping);

    foreach($mapping as $documentID => $centroidID) {
        foreach($data[$documentID] as $dim => $value) {
            if(!isset($cenntroids[$centroidID][$dim])) {
                $centroids[$centroidID][$dim] = 0;
            }
            $centroids[$centroidID][$dim] += ($value/$counts[$centroidID]); 
        }
    }

    if(count($centroids) < $k) {
        $centroids = array_merge($centroids, initialiseCentroids($data, $k - count($centroids)));
    }

    return $centroids;
}

function normaliseValue(array $vector, $total) {
    foreach($vector as &$value) {
        $value = $value/$total;
    }
    return $vector;
}

// include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
// $db_obj = new DatabaseAccess();
// $sql = "SELECT disc_id, COUNT(id) sold, SUM(price) momey FROM buy_disc_record GROUP BY disc_id";
// $query_instance = $db_obj->select($sql);
// $id_array = array();
// $co_array = array();
// foreach ($query_instance as $instance_data) {
//     $disc_id = $instance_data['disc_id'];
//     $total_sold = $instance_data['sold'];
//     $total_money = $instance_data['money'];
//     array_push($id_array, $disc_id);
//     array_push($co_array, array($total_sold, $total_money));
// }
?>
</pre>