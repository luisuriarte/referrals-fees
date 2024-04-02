CREATE TABLE `fees` (
	`id` INT(11) NOT NULL AUTO_INCREMENT,
	`pid` INT(11) NOT NULL,
	`fee_date` DATE NULL DEFAULT NULL,
	`fee` DECIMAL(20,2) NULL DEFAULT NULL,
	`discount` DECIMAL(20,2) NULL DEFAULT NULL,
	`method` VARCHAR(50) NULL DEFAULT NULL COLLATE 'utf8mb4_general_ci',
	`billing_period` DATE NULL DEFAULT NULL,
	`month_type` INT(11) NULL DEFAULT NULL,
	`user_auth` INT(11) NULL DEFAULT NULL,
	`inserted_at` DATETIME NULL DEFAULT NULL,
	`deleted` INT(11) NULL DEFAULT NULL,
	UNIQUE INDEX `id` (`id`) USING BTREE
)
COLLATE='utf8mb4_general_ci'
ENGINE=InnoDB
AUTO_INCREMENT=3
;