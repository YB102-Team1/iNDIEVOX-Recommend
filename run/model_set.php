<pre>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();

for ($i = 0; $i <= 4; $i++) {
	$sql = "INSERT INTO model_set_$i (id, user_id, on_thing_id, artist_id, price, type, is_purchased, is_liked, genre, user_group, item_group, is_deleted, create_time, modify_time, delete_time) SELECT * FROM train_model WHERE id % 5 = $i";
	echo "$sql\n";
	$db_obj->query($sql);
}

unset($db_obj);
?>
</pre>