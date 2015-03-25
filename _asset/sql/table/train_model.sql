CREATE TABLE IF NOT EXISTS `train_model` (
    `id` int(11) unsigned NOT NULL,
    `user_id` int(11) unsigned NOT NULL,
    `on_thing_id` int(11) unsigned NOT NULL,
    `artist_id` int(11) unsigned NOT NULL,
    `type` enum('disc','song') NOT NULL,
    `is_purchased` tinyint(1) unsigned NOT NULL,
    `is_liked` tinyint(1) unsigned NOT NULL,
    `genre` int(11) unsigned NOT NULL,
    `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
    `create_time` datetime NOT NULL,
    `modify_time` datetime NOT NULL,
    `delete_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;