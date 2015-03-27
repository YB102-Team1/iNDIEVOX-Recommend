CREATE TABLE IF NOT EXISTS `shopping_cluster` (
    `id` int(11) unsigned NOT NULL,
    `cluster_type` enum('user','item') NOT NULL,
    `item_type` enum('disc','song') NOT NULL,
    `x` double NOT NULL,
    `y` double NOT NULL,
    `item_count` int(10) unsigned NOT NULL,
    `record_count` int(10) unsigned NOT NULL,
    `group_serial` int(3) unsigned NOT NULL,
    `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
    `create_time` datetime NOT NULL,
    `modify_time` datetime NOT NULL,
    `delete_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `shopping_cluster` ADD PRIMARY KEY (`id`);
ALTER TABLE `shopping_cluster` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;