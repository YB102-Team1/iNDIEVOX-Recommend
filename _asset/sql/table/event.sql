CREATE TABLE IF NOT EXISTS `event` (
  `id` int(11) unsigned NOT NULL,
  `title` varchar(255) NOT NULL,
  `icon` varchar(255) NOT NULL,
  `place` varchar(50) NOT NULL,
  `city` varchar(50) NOT NULL,
  `date` date NOT NULL DEFAULT '0000-00-00',
  `detail_time` datetime NOT NULL DEFAULT '0000-00-00 00:00:00'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;
ALTER TABLE `event` ADD PRIMARY KEY (`id`);