CREATE TABLE IF NOT EXISTS `similar_artist` (
    `id` int(11) unsigned NOT NULL,
    `source` int(11) unsigned NOT NULL,
    `target` int(11) unsigned NOT NULL,
    `support` double unsigned NOT NULL,
    `occurrence` int(11) NOT NULL,
    `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
    `create_time` datetime NOT NULL,
    `modify_time` datetime NOT NULL,
    `delete_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `similar_artist` ADD PRIMARY KEY (`id`);
ALTER TABLE `similar_artist` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;