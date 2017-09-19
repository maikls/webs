DROP TABLE IF EXISTS `en_platforms`;

CREATE TABLE en_platforms (
    `id` int(11) unsigned NOT NULL auto_increment,
    `name` varchar(255) NOT NULL,
  `descr` varchar(1024) COLLATE utf8mb4_unicode_ci NOT NULL,
  `links` varchar(255) COLLATE utf8mb4_unicode_ci,
  `type` int(3),
  `price` int(10),
    PRIMARY KEY  (id),
    UNIQUE `name` (`name`(191))
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
