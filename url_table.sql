--
-- Table structure for table `url`
--

CREATE TABLE IF NOT EXISTS `url` (
  `id` bigint(20) NOT NULL AUTO_INCREMENT,
  `code` char(32) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `md5` char(32) COLLATE utf8_bin DEFAULT NULL,
  `target` text COLLATE utf8_bin DEFAULT NULL,
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `md5` (`md5`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;