<pre>
1
<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';

$db_obj = new DatabaseAccess();

// // download
// $select_sql = "SELECT ".
//                               "d.id, ".
//                               "SUM(bd.price) price_sum ".
//                               "FROM disc d ".
//                               "INNER JOIN buy_disc_record bd ".
//                               "ON (d.id=bd.disc_id) ".
//                               "WHERE d.is_released='1' ".
//                               "AND d.is_deleted='0' ".
//                               "GROUP BY d.id ".
//                               "ORDER BY price_sum DESC";
// $insert_sql = "INSERT IGNORE INTO disc_icon (disc_id) SELECT id FROM ($select_sql LIMIT 15) a";
// echo $insert_sql;
// $db_obj->query($insert_sql);
// for ($i = 1; $i <= 19; $i++) {
//     $select_sql = "SELECT ".
//                               "d.id, ".
//                               "SUM(bd.price) price_sum ".
//                               "FROM disc d ".
//                               "INNER JOIN buy_disc_record bd ".
//                               "ON (d.id=bd.disc_id) ".
//                               "WHERE d.is_released='1' ".
//                               "AND d.is_deleted='0' ".
//                               "AND d.genre=$i ".
//                               "GROUP BY d.id ".
//                               "ORDER BY price_sum DESC";
//     $insert_sql = "INSERT IGNORE INTO disc_icon (disc_id) SELECT id FROM ($select_sql LIMIT 15) a";
//     $db_obj->query($insert_sql);
// }
// echo "download done\n";

// // favorite
// $select_sql = "SELECT ".
//                               "d.id, ".
//                               "COUNT(d.id) fav_num ".
//                               "FROM disc d ".
//                               "INNER JOIN favorite f ".
//                               "ON (".
//                                   "d.id=f.on_thing_id ".
//                                   "AND f.type='disc'".
//                                ") ".
//                                "WHERE ".
//                                "d.is_released='1' ".
//                                "AND d.is_deleted='0' ".
//                                "GROUP BY d.id ".
//                                "ORDER BY fav_num DESC";
// $insert_sql = "INSERT IGNORE INTO disc_icon (disc_id) SELECT id FROM ($select_sql LIMIT 15) a";
// $db_obj->query($insert_sql);

// for ($i = 1; $i <= 19; $i++) {
//     $select_sql = "SELECT ".
//                               "d.id, ".
//                               "COUNT(d.id) fav_num ".
//                               "FROM disc d ".
//                               "INNER JOIN favorite f ".
//                               "ON (".
//                                   "d.id=f.on_thing_id ".
//                                   "AND f.type='disc'".
//                                ") ".
//                                "WHERE ".
//                                "d.is_released='1' ".
//                                "AND d.is_deleted='0' ".
//                                "AND d.genre=$i ".
//                                "GROUP BY d.id ".
//                                "ORDER BY fav_num DESC";
//     $insert_sql = "INSERT IGNORE INTO disc_icon (disc_id) SELECT id FROM ($select_sql LIMIT 15) a";
//     $db_obj->query($insert_sql);
// }
// echo "favorite done\n";

// release
$select_sql = "SELECT id ".
                              "FROM disc ".
                              "WHERE is_released='1' ".
                              "AND is_deleted='0' ".
                              "ORDER BY release_time DESC";
$insert_sql = "INSERT IGNORE INTO disc_icon (disc_id) SELECT id FROM ($select_sql LIMIT 15) a";
$db_obj->query($insert_sql);

for ($i = 1; $i <= 19; $i++) {
    $select_sql = "SELECT id ".
                              "FROM disc ".
                              "WHERE is_released='1' ".
                              "AND is_deleted='0' ".
                              "AND genre=$i ".
                              "ORDER BY release_time DESC";
    $insert_sql = "INSERT IGNORE INTO disc_icon (disc_id) SELECT id FROM ($select_sql LIMIT 15) a";
    $db_obj->query($insert_sql);
}
echo "release done\n";
?>
</pre>