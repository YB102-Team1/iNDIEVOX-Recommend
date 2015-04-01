CREATE TABLE IF NOT EXISTS `user` (
    `id` int(11) unsigned NOT NULL,
    `title` varchar(255) NOT NULL,
    `url` varchar(255) NOT NULL,
    `icon` varchar(255) NOT NULL,
    `fans` int(10) unsigned NOT NULL,
    `description` text NOT NULL,
    PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8;