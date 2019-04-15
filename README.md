# TINY Lottery
A WIP simple Guild Wars 2 lottery/raffle system used by Pewpews TINY Army

## Requirements
 - PHP >= 7.0

## Install
```sh
composer install
```

## Setup
### Config
Copy `config.example.php` to `config.php` and fill in with your data

### Database
Create the table with `Create_table.sql` or use the following
```sql
CREATE TABLE `lottery_log` (
	`id` INT(11) NOT NULL DEFAULT '0',
	`time` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
	`user` VARCHAR(50) NOT NULL DEFAULT '0',
	`coins` INT(11) NOT NULL DEFAULT '0',
	PRIMARY KEY (`id`),
	INDEX `user` (`user`)
)
COLLATE='latin1_swedish_ci'
ENGINE=InnoDB;
```

### Cron
Add `/cron/log-cron.php` to you crontab with any time you wish (1 hour is recommended)