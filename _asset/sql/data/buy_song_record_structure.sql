CREATE TABLE IF NOT EXISTS `buy_song_record` (
  `id` int(11) unsigned NOT NULL AUTO_INCREMENT,
  `buyer_id` int(11) unsigned NOT NULL,
  `song_id` int(11) unsigned NOT NULL,
  `disc_id` int(11) unsigned NOT NULL,
  `price` float NOT NULL,
  `buy_type` enum('whole_disc','single_song','redeem') NOT NULL,
  `buy_time` datetime NOT NULL,
  `is_deleted` tinyint(1) unsigned NOT NULL DEFAULT '0',
  `create_time` datetime NOT NULL,
  `modify_time` datetime NOT NULL,
  `delete_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00',
  PRIMARY KEY (`id`),
  KEY `buyer_id` (`buyer_id`),
  KEY `song_id` (`song_id`),
  KEY `disc_id` (`disc_id`),
  KEY `buy_type` (`buy_type`),
  KEY `create_time` (`create_time`)
) ENGINE=InnoDB  DEFAULT CHARSET=utf8 AUTO_INCREMENT=573076 ;