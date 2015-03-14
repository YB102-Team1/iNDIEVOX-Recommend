<pre>
<?php
$mode = 'not_free';
switch ($mode) {

case 'not_free':

    $disc_purchased_and_liked = "SELECT b.buyer_id user_id, b.disc_id on_thing_id, t.genre ".
                                "FROM buy_disc_record b ".
                                "LEFT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='disc') f ".
                                "ON b.buyer_id = f.adder_id AND b.disc_id = f.on_thing_id ".
                                "LEFT JOIN disc t ON b.disc_id = t.id ".
                                "WHERE b.price != 0 AND f.id IS NOT NULL AND t.is_deleted = 0";

    $disc_only_purchased = "SELECT b.buyer_id user_id, b.disc_id on_thing_id, t.genre ".
                           "FROM buy_disc_record b ".
                           "LEFT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='disc') f ".
                           "ON b.buyer_id = f.adder_id AND b.disc_id = f.on_thing_id ".
                           "LEFT JOIN disc t ON b.disc_id = t.id ".
                           "WHERE b.price != 0 AND f.id IS NULL AND t.is_deleted = 0";

    $disc_only_liked = "SELECT f.adder_id user_id, f.on_thing_id on_thing_id, t.genre ".
                       "FROM buy_disc_record b ".
                       "RIGHT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='disc') f ".
                       "ON b.buyer_id = f.adder_id AND b.disc_id = f.on_thing_id ".
                       "LEFT JOIN disc t ON f.on_thing_id = t.id ".
                       "WHERE b.id IS NULL AND t.is_deleted = 0";

    $song_purchased_and_liked= "SELECT b.buyer_id user_id, b.song_id on_thing_id, t.genre ".
                               "FROM buy_song_record b ".
                               "LEFT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='song') f ".
                               "ON b.buyer_id = f.adder_id AND b.song_id = f.on_thing_id ".
                               "LEFT JOIN song t ON b.song_id = t.id ".
                               "WHERE b.price != 0 AND f.id IS NOT NULL AND t.is_deleted = 0";

    $song_only_purchased = "SELECT b.buyer_id user_id, b.song_id on_thing_id, t.genre ".
                           "FROM buy_song_record b ".
                           "LEFT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='song') f ".
                           "ON b.buyer_id = f.adder_id AND b.song_id = f.on_thing_id ".
                           "LEFT JOIN song t ON b.song_id = t.id ".
                           "WHERE b.price != 0 AND f.id IS NULL AND t.is_deleted = 0";

    $song_only_liked = "SELECT f.adder_id user_id, f.on_thing_id on_thing_id, t.genre ".
                       "FROM buy_song_record b ".
                       "RIGHT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='song') f ".
                       "ON b.buyer_id = f.adder_id AND b.song_id = f.on_thing_id ".
                       "LEFT JOIN song t ON f.on_thing_id = t.id ".
                       "WHERE b.id IS NULL AND t.is_deleted = 0";

    break;

case 'original':
default:

    $disc_purchased_and_liked = "SELECT b.buyer_id user_id, b.disc_id on_thing_id, t.genre ".
                                "FROM buy_disc_record b ".
                                "LEFT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='disc') f ".
                                "ON b.buyer_id = f.adder_id AND b.disc_id = f.on_thing_id ".
                                "LEFT JOIN disc t ON b.disc_id = t.id ".
                                "WHERE f.id IS NOT NULL AND t.is_deleted = 0";

    $disc_only_purchased = "SELECT b.buyer_id user_id, b.disc_id on_thing_id, t.genre ".
                           "FROM buy_disc_record b ".
                           "LEFT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='disc') f ".
                           "ON b.buyer_id = f.adder_id AND b.disc_id = f.on_thing_id ".
                           "LEFT JOIN disc t ON b.disc_id = t.id ".
                           "WHERE f.id IS NULL AND t.is_deleted = 0";

    $disc_only_liked = "SELECT f.adder_id user_id, f.on_thing_id on_thing_id, t.genre ".
                       "FROM buy_disc_record b ".
                       "RIGHT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='disc') f ".
                       "ON b.buyer_id = f.adder_id AND b.disc_id = f.on_thing_id ".
                       "LEFT JOIN disc t ON f.on_thing_id = t.id ".
                       "WHERE b.id IS NULL AND t.is_deleted = 0";

    $song_purchased_and_liked= "SELECT b.buyer_id user_id, b.song_id on_thing_id, t.genre ".
                               "FROM buy_song_record b ".
                               "LEFT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='song') f ".
                               "ON b.buyer_id = f.adder_id AND b.song_id = f.on_thing_id ".
                               "LEFT JOIN song t ON b.song_id = t.id ".
                               "WHERE f.id IS NOT NULL AND t.is_deleted = 0";

    $song_only_purchased = "SELECT b.buyer_id user_id, b.song_id on_thing_id, t.genre ".
                           "FROM buy_song_record b ".
                           "LEFT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='song') f ".
                           "ON b.buyer_id = f.adder_id AND b.song_id = f.on_thing_id ".
                           "LEFT JOIN song t ON b.song_id = t.id ".
                           "WHERE f.id IS NULL AND t.is_deleted = 0";

    $song_only_liked = "SELECT f.adder_id user_id, f.on_thing_id on_thing_id, t.genre ".
                       "FROM buy_song_record b ".
                       "RIGHT JOIN (SELECT id, adder_id, on_thing_id FROM favorite WHERE type='song') f ".
                       "ON b.buyer_id = f.adder_id AND b.song_id = f.on_thing_id ".
                       "LEFT JOIN song t ON f.on_thing_id = t.id ".
                       "WHERE b.id IS NULL AND t.is_deleted = 0";

    break;

}
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();

// create table if not exists
$sql = 
"CREATE TABLE IF NOT EXISTS `train_model` (
    `id` int(11) unsigned NOT NULL,
    `user_id` int(11) unsigned NOT NULL,
    `on_thing_id` int(11) unsigned NOT NULL,
    `type` enum('artist','disc','song','playlist') NOT NULL,
    `is_purchased` tinyint(1) unsigned NOT NULL,
    `is_liked` tinyint(1) unsigned NOT NULL,
    `genre` int(11) unsigned NOT NULL,
    `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
    `create_time` datetime NOT NULL,
    `modify_time` datetime NOT NULL,
    `delete_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;";
$db_obj->query($sql);

// clear table
$sql = "TRUNCATE train_model";
$db_obj->query($sql);

$item_type = array(
    "disc",
    "song"
);
$model_detail_array = array(
    // purchased and liked
    array(
        "sql_suffix" => '_purchased_and_liked',
        "is_purchased" => 1,
        "is_liked" => 1
    ),
    // purchased but not liked
    array(
        "sql_suffix" => '_only_purchased',
        "is_purchased" => 1,
        "is_liked" => 0
    ),
    // liked but not purchased
    array(
        "sql_suffix" => '_only_liked',
        "is_purchased" => 0,
        "is_liked" => 1
    )
);

foreach ($item_type as $type) { 

    foreach ($model_detail_array as $model_detail) {

        $model_sql    = ${$type.$model_detail['sql_suffix']};
        $is_purchased = $model_detail['is_purchased'];
        $is_liked     = $model_detail['is_liked'];

        $query_instance = $db_obj->select($model_sql);
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

    }

}

unset($db_obj);
?>
</pre>