ALTER TABLE `products` ADD `sku` VARCHAR( 255 ) NOT NULL AFTER `model` ;
ALTER TABLE `products` ADD INDEX ( `sku` );
ALTER TABLE `categories` CHANGE `order_num` `order_num` INT( 11 ) NOT NULL DEFAULT '0';
ALTER TABLE `related_products` CHANGE `related_id` `related_sku` VARCHAR( 255 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;

CREATE TABLE IF NOT EXISTS `properties` (
  `property_id` bigint(20) NOT NULL auto_increment,
  `name` varchar(255) NOT NULL,
  `in_product` int(1) NOT NULL,
  `in_filter` int(1) NOT NULL,
  `in_compare` int(1) NOT NULL,
  `order_num` int(11) NOT NULL,
  `enabled` int(1) NOT NULL default '1',
  `options` text NOT NULL,
  PRIMARY KEY  (`property_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `properties_categories` (
  `property_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  PRIMARY KEY  (`property_id`,`category_id`)
) ENGINE=MyISAM DEFAULT CHARSET=utf8;


CREATE TABLE IF NOT EXISTS `properties_values` (
  `product_id` int(11) NOT NULL,
  `property_id` int(11) NOT NULL,
  `value` varchar(512) NOT NULL,
  PRIMARY KEY  (`product_id`,`property_id`),
  KEY `value` (`value`(333))
) ENGINE=MyISAM DEFAULT CHARSET=utf8;
