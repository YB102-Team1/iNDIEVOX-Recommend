CREATE TABLE IF NOT EXISTS `???` (
    `id` int(11) unsigned NOT NULL,
    ...
    `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
    `create_time` datetime NOT NULL,
    `modify_time` datetime NOT NULL,
    `delete_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `???` ADD PRIMARY KEY (`id`);
ALTER TABLE `???` MODIFY `id` int(11) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=1;