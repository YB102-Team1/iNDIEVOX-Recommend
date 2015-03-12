CREATE TABLE IF NOT EXISTS `favorite` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `adder_id` int(11) unsigned NOT NULL,
  `on_thing_id` int(11) unsigned NOT NULL,
  `owner_id` int(11) unsigned NOT NULL,
  `type` enum('artist','disc','song','playlist') NOT NULL,
  `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` datetime NOT NULL,
  `modify_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `adder_id` (`adder_id`),
  KEY `on_thing_id` (`on_thing_id`),
  KEY `owner_id` (`owner_id`),
  KEY `type` (`type`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=370158 ;