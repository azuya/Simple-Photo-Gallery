CREATE TABLE IF NOT EXISTS `sigal_galleries` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `slug` varchar(50) NOT NULL,
  `order` tinyint(3) unsigned NOT NULL,
  `thumbnail` int(11) NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `slug` (`slug`),
  UNIQUE KEY `order` (`order`)
) ;

CREATE TABLE IF NOT EXISTS `sigal_images` (
  `id` int(10) unsigned NOT NULL AUTO_INCREMENT,
  `name` varchar(50) NOT NULL,
  `description` text NOT NULL,
  `order` tinyint(3) unsigned NOT NULL,
  `filename` varchar(255) NOT NULL,
  `gallery_id` int(10) unsigned NOT NULL,
  `date` int(11) unsigned NOT NULL,
  PRIMARY KEY (`id`),
  UNIQUE KEY `order-gallery` (`gallery_id`,`order`)
);
