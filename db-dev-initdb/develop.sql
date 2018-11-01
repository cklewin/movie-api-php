CREATE TABLE IF NOT EXISTS `movies` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `owner` varchar(50) NOT NULL,
  `title` varchar(50) NOT NULL,
  `format` enum('VHS','DVD','Streaming') NOT NULL,
  `length` smallint(5) unsigned NOT NULL,
  `release_year` smallint(5) unsigned NOT NULL,
  `rating` tinyint(3) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  KEY `IDX_title` (`title`),
  KEY `IDX_owner` (`owner`),
  KEY `IDX_format` (`format`),
  KEY `IDX_length` (`length`),
  KEY `IDX_release_year` (`release_year`),
  KEY `IDX_rating` (`rating`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
