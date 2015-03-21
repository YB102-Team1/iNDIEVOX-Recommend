<pre>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();

$sql = "TRUNCATE artist_model";
$db_obj->query($sql);

$records_number = 0;
$sql = "SELECT COUNT(id) records_number FROM buy_disc_record";
$query_instance = $db_obj->select($sql);
foreach ($query_instance as $instance_data) {
	$records_number = $instance_data['records_number'];
}

for ($i = 0; $i < $records_number; $i += 1000) {
	$now = date('Y-m-d H:i:s');
	$sql = "INSERT INTO artist_model (user_id, on_thing_id, artist_id, type, source, record_time, create_time, modify_time) SELECT b.buyer_id, b.disc_id, d.artist_id, 'disc', 'buy', b.create_time, '$now', '$now' FROM buy_disc_record b LEFT JOIN disc d ON b.disc_id = d.id WHERE b.price != 0 ORDER BY b.buyer_id, b.disc_id LIMIT $i, 1000";
	$db_obj->query($sql);
}

$records_number = 0;
$sql = "SELECT COUNT(id) records_number FROM favorite WHERE type = 'disc'";
$query_instance = $db_obj->select($sql);
foreach ($query_instance as $instance_data) {
	$records_number = $instance_data['records_number'];
}

for ($i = 0; $i < $records_number; $i += 1000) {
	$now = date('Y-m-d H:i:s');
	$sql = "INSERT INTO artist_model (user_id, on_thing_id, artist_id, type, source, record_time, create_time, modify_time) SELECT adder_id, on_thing_id, owner_id, 'disc', 'favorite', create_time, '$now', '$now' FROM favorite WHERE type = 'disc' ORDER BY adder_id, on_thing_id LIMIT $i, 1000";
	$db_obj->query($sql);
}
?>
</pre>