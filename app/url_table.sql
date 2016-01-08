--
-- Table structure for table `url`
--

CREATE TABLE `url` (
  `id` bigint(20) NOT NULL,
  `code` char(32) CHARACTER SET latin1 COLLATE latin1_general_cs NOT NULL,
  `md5` char(32) COLLATE utf8_bin DEFAULT NULL,
  `target` text COLLATE utf8_bin,
  `updated` timestamp NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
  UNIQUE KEY `code` (`code`),
  UNIQUE KEY `md5` (`md5`),
  KEY `id` (`id`),
  KEY `code_2` (`code`,`target`(255))
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_bin;
