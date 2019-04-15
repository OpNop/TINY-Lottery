ALTER TABLE `lottery_log`
	ADD COLUMN `lottery_id` INT(11) NOT NULL AUTO_INCREMENT FIRST,
	CHANGE COLUMN `id` `log_id` INT(11) NOT NULL DEFAULT '0' AFTER `lottery_id`,
	ADD COLUMN `guild` VARCHAR(36) NOT NULL DEFAULT '##original_guild_id##' AFTER `coins`,
	DROP PRIMARY KEY,
	ADD PRIMARY KEY (`lottery_id`);