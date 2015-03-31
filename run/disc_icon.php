<pre>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';

$db_obj = new DatabaseAccess();

// download
$select_sql = "SELECT ".
                              "d.id, ".
                              "SUM(bd.price) price_sum ".
                              "FROM disc d ".
                              "INNER JOIN buy_disc_record bd ".
                              "ON (d.id=bd.disc_id) ".
                              "WHERE d.is_released='1' ".
                              "AND d.is_deleted='0' ".
                              "GROUP BY d.id ".
                              "ORDER BY price_sum DESC";
$insert_sql = "INSERT IGNORE INTO disc_icon (id) SELECT id FROM ($select_sql LIMIT 15) a";
$db_obj->query($select_sql);
for ($i = 1; $i <= 19; $i++) {
    $select_sql = "SELECT ".
                              "d.id, ".
                              "SUM(bd.price) price_sum ".
                              "FROM disc d ".
                              "INNER JOIN buy_disc_record bd ".
                              "ON (d.id=bd.disc_id) ".
                              "WHERE d.is_released='1' ".
                              "AND d.is_deleted='0' ".
                              "AND d.genre=$i ".
                              "GROUP BY d.id ".
                              "ORDER BY price_sum DESC";
    $insert_sql = "INSERT IGNORE INTO disc_icon (id) SELECT id FROM ($select_sql LIMIT 15) a";
    $db_obj->query($select_sql);
}

// favorite

// release

?>
</pre>