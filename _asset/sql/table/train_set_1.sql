CREATE TABLE IF NOT EXISTS `train_set_1` (
    `id` int(11) unsigned NOT NULL,
    `user_id` int(11) unsigned NOT NULL,
    `on_thing_id` int(11) unsigned NOT NULL,
    `artist_id` int(11) NOT NULL,
    `price` float NOT NULL,
    `type` enum('disc','song') NOT NULL,
    `is_purchased` tinyint(1) unsigned NOT NULL,
    `is_liked` tinyint(1) unsigned NOT NULL,
    `genre` int(11) unsigned NOT NULL,
    `user_group` int(11) unsigned NOT NULL DEFAULT '0',
    `item_group` int(11) unsigned NOT NULL DEFAULT '0',
    `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
    `create_time` datetime NOT NULL,
    `modify_time` datetime NOT NULL,
    `delete_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `train_set_1` ADD PRIMARY KEY (`id`);
ALTER TABLE `train_set_1` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;