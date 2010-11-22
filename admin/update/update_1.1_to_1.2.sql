ALTER TABLE `brands` ADD `description` TEXT NOT NULL;

INSERT INTO `settings` (
`setting_id` ,
`name` ,
`value` 
)
VALUES (
NULL , 'meta_autofill', '1'
);