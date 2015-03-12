CREATE TABLE IF NOT EXISTS `buy_disc_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) unsigned NOT NULL,
  `disc_id` int(11) unsigned NOT NULL,
  `price` float unsigned NOT NULL,
  `buy_time` datetime NOT NULL,
  `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` datetime NOT NULL,
  `modify_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `disc_id` (`disc_id`),
  KEY `buy_time` (`buy_time`),
  KEY `is_deleted` (`is_deleted`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=27926 ;