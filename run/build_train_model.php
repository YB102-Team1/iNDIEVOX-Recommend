<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();

// clear table
$sql = "TRUNCATE train_model";
$db_obj->query($sql);

$type = 'disc';
// disc purchased and liked
$sql = "SELECT b.buyer_id user_id, b.disc_id on_thing_id, t.genre ".
	   "FROM buy_disc_record b ".
	   "LEFT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='$type') f ".
	   "ON b.buyer_id = f.adder_id AND b.disc_id = f.on_thing_id ".
	   "LEFT JOIN $type t ON b.disc_id = t.id ".
	   "WHERE b.disc_id != 0 AND f.id IS NOT NULL";
$query_instance = $db_obj->select($sql);
$is_purchased = 1;
$is_liked = 1;
$sql = '';
$counter = 0;
foreach ($query_instance as $instance_data) {
	$user_id = $instance_data['user_id'];
	$on_thing_id = $instance_data['on_thing_id'];
	$genre = $instance_data['genre'];
	$now = date('Y-m-d H:i:s');
	if ($counter % 1000 == 0) {
		if ($counter > 0) {
			$db_obj->query($sql);
		}
		$sql = "INSERT INTO train_model (user_id, on_thing_id, type, is_purchased, is_liked, genre) ".
		       "VALUES ('$user_id', '$on_thing_id', '$type', '$is_purchased', '$is_liked', '$genre')";
	} else {
		$sql .= ", ('$user_id', '$on_thing_id', '$type', '$is_purchased', '$is_liked', '$genre')";
	}
	$counter++;
}
$db_obj->query($sql);

// disc purchased but not liked
$sql = "SELECT b.buyer_id user_id, b.disc_id on_thing_id, t.genre ".
	   "FROM buy_disc_record b ".
	   "LEFT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='$type') f ".
	   "ON b.buyer_id = f.adder_id AND b.disc_id = f.on_thing_id ".
	   "LEFT JOIN $type t ON b.disc_id = t.id ".
	   "WHERE f.id IS NULL";
$query_instance = $db_obj->select($sql);
$is_purchased = 1;
$is_liked = 0;
$sql = '';
$counter = 0;
foreach ($query_instance as $instance_data) {
	$user_id = $instance_data['user_id'];
	$on_thing_id = $instance_data['on_thing_id'];
	$genre = $instance_data['genre'];
	$now = date('Y-m-d H:i:s');
	if ($counter % 1000 == 0) {
		if ($counter > 0) {
			$db_obj->query($sql);
		}
		$sql = "INSERT INTO train_model (user_id, on_thing_id, type, is_purchased, is_liked, genre) ".
		       "VALUES ('$user_id', '$on_thing_id', '$type', '$is_purchased', '$is_liked', '$genre')";
	} else {
		$sql .= ", ('$user_id', '$on_thing_id', '$type', '$is_purchased', '$is_liked', '$genre')";
	}
	$counter++;
}
$db_obj->query($sql);

// disc liked but not purchased
$sql = "SELECT f.adder_id user_id, f.on_thing_id on_thing_id, t.genre ".
	   "FROM buy_disc_record b ".
	   "RIGHT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='$type') f ".
	   "ON b.buyer_id = f.adder_id AND b.disc_id = f.on_thing_id ".
	   "LEFT JOIN $type t ON f.on_thing_id = t.id ".
	   "WHERE b.id IS NULL";
$query_instance = $db_obj->select($sql);
$is_purchased = 0;
$is_liked = 1;
$sql = '';
$counter = 0;
foreach ($query_instance as $instance_data) {
	$user_id = $instance_data['user_id'];
	$on_thing_id = $instance_data['on_thing_id'];
	$genre = $instance_data['genre'];
	$now = date('Y-m-d H:i:s');
	if ($counter % 1000 == 0) {
		if ($counter > 0) {
			$db_obj->query($sql);
		}
		$sql = "INSERT INTO train_model (user_id, on_thing_id, type, is_purchased, is_liked, genre) ".
		       "VALUES ('$user_id', '$on_thing_id', '$type', '$is_purchased', '$is_liked', '$genre')";
	} else {
		$sql .= ", ('$user_id', '$on_thing_id', '$type', '$is_purchased', '$is_liked', '$genre')";
	}
	$counter++;
}
$db_obj->query($sql);

$type = 'song';
// song purchased and liked
$sql = "SELECT b.buyer_id user_id, b.song_id on_thing_id, t.genre ".
	   "FROM buy_song_record b ".
	   "LEFT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='$type') f ".
	   "ON b.buyer_id = f.adder_id AND b.song_id = f.on_thing_id ".
	   "LEFT JOIN $type t ON b.song_id = t.id ".
	   "WHERE f.id IS NOT NULL";
$query_instance = $db_obj->select($sql);
$is_purchased = 1;
$is_liked = 1;
$sql = '';
$counter = 0;
foreach ($query_instance as $instance_data) {
	$user_id = $instance_data['user_id'];
	$on_thing_id = $instance_data['on_thing_id'];
	$genre = $instance_data['genre'];
	$now = date('Y-m-d H:i:s');
	if ($counter % 1000 == 0) {
		if ($counter > 0) {
			$db_obj->query($sql);
		}
		$sql = "INSERT INTO train_model (user_id, on_thing_id, type, is_purchased, is_liked, genre) ".
		       "VALUES ('$user_id', '$on_thing_id', '$type', '$is_purchased', '$is_liked', '$genre')";
	} else {
		$sql .= ", ('$user_id', '$on_thing_id', '$type', '$is_purchased', '$is_liked', '$genre')";
	}
	$counter++;
}
$db_obj->query($sql);

// song purchased but not liked
$sql = "SELECT b.buyer_id user_id, b.song_id on_thing_id, t.genre ".
	   "FROM buy_song_record b ".
	   "LEFT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='$type') f ".
	   "ON b.buyer_id = f.adder_id AND b.song_id = f.on_thing_id ".
	   "LEFT JOIN $type t ON b.song_id = t.id ".
	   "WHERE f.id IS NULL";
$query_instance = $db_obj->select($sql);
$is_purchased = 1;
$is_liked = 0;
$sql = '';
$counter = 0;
foreach ($query_instance as $instance_data) {
	$user_id = $instance_data['user_id'];
	$on_thing_id = $instance_data['on_thing_id'];
	$genre = $instance_data['genre'];
	$now = date('Y-m-d H:i:s');
	if ($counter % 1000 == 0) {
		if ($counter > 0) {
			$db_obj->query($sql);
		}
		$sql = "INSERT INTO train_model (user_id, on_thing_id, type, is_purchased, is_liked, genre) ".
		       "VALUES ('$user_id', '$on_thing_id', '$type', '$is_purchased', '$is_liked', '$genre')";
	} else {
		$sql .= ", ('$user_id', '$on_thing_id', '$type', '$is_purchased', '$is_liked', '$genre')";
	}
	$counter++;
}
$db_obj->query($sql);

// song liked but not purchased
$sql = "SELECT f.adder_id user_id, f.on_thing_id on_thing_id, t.genre ".
	   "FROM buy_song_record b ".
	   "RIGHT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='$type') f ".
	   "ON b.buyer_id = f.adder_id AND b.song_id = f.on_thing_id ".
	   "LEFT JOIN $type t ON f.on_thing_id = t.id ".
	   "WHERE b.id IS NULL";
$query_instance = $db_obj->select($sql);
$is_purchased = 0;
$is_liked = 1;
$sql = '';
$counter = 0;
foreach ($query_instance as $instance_data) {
	$user_id = $instance_data['user_id'];
	$on_thing_id = $instance_data['on_thing_id'];
	$genre = $instance_data['genre'];
	$now = date('Y-m-d H:i:s');
	if ($counter % 1000 == 0) {
		if ($counter > 0) {
			$db_obj->query($sql);
		}
		$sql = "INSERT INTO train_model (user_id, on_thing_id, type, is_purchased, is_liked, genre) ".
		       "VALUES ('$user_id', '$on_thing_id', '$type', '$is_purchased', '$is_liked', '$genre')";
	} else {
		$sql .= ", ('$user_id', '$on_thing_id', '$type', '$is_purchased', '$is_liked', '$genre')";
	}
	$counter++;
}
$db_obj->query($sql);

unset($db_obj);
?>