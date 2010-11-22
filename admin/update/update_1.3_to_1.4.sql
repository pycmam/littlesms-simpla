ALTER TABLE `orders_products` ADD `variant_id` INT( 11 ) NOT NULL AFTER `product_id`;
ALTER TABLE `orders_products` ADD `variant_name` VARCHAR( 255 ) NOT NULL AFTER `product_name`;

CREATE TABLE IF NOT EXISTS `products_variants` (
  `variant_id` bigint(20) NOT NULL auto_increment,
  `product_id` int(11) NOT NULL,
  `sku` varchar(255) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` float(8,2) NOT NULL,
  `stock` int(11) NOT NULL,
  `position` int(11) NOT NULL,
  PRIMARY KEY  (`variant_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8;

INSERT INTO `products_variants` (`product_id`, `sku`, `name`, `price`, `stock`)
SELECT `product_id`, `sku`, '', `price`, `quantity` FROM `products`;

UPDATE `products_variants` SET `position`=`variant_id`;

ALTER TABLE `products`
  DROP `sku`,
  DROP `price`,
  DROP `old_price`,
  DROP `guarantee`,
  DROP `quantity`;
  
  
INSERT INTO `modules` (
`module_id` ,
`class` ,
`name` ,
`valuable` 
)
VALUES (
NULL , 'Compare', 'Сравнение товаров', '0'
);

ALTER TABLE `orders` CHANGE `comment` `comment` VARCHAR( 1024 ) CHARACTER SET utf8 COLLATE utf8_general_ci NOT NULL;