ALTER TABLE `orders_products` DROP PRIMARY KEY;
ALTER TABLE `orders_products` ADD PRIMARY KEY ( `order_id`, `product_id`, `variant_id` );

