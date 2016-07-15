CREATE TABLE IF NOT EXISTS `mail` (
  `seid` int(8) NOT NULL,
  `mail` varchar(255) NOT NULL,
  `password` varchar(255) CHARACTER SET utf8 NOT NULL,
  `date` datetime NOT NULL,
  `comment` varchar(255) CHARACTER SET utf8 NOT NULL
) ENGINE=MyISAM DEFAULT CHARSET=utf32;