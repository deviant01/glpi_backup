ALTER TABLE `glpi_plugin_backups_profiles` DROP `create_libraries`;
ALTER TABLE `glpi_plugin_backups_profiles` DROP `update_libraries`;
ALTER TABLE `glpi_plugin_backups_profiles` DROP `delete_libraries`;
ALTER TABLE `glpi_plugin_backups_profiles` DROP `create_scripts`;
ALTER TABLE `glpi_plugin_backups_profiles` DROP `update_scripts`;
ALTER TABLE `glpi_plugin_backups_profiles` DROP `delete_scripts`;
ALTER TABLE `glpi_plugin_backups_profiles` DROP `create_tapes`;
ALTER TABLE `glpi_plugin_backups_profiles` DROP `update_tapes`;
ALTER TABLE `glpi_plugin_backups_profiles` DROP `delete_tapes`;
ALTER TABLE `glpi_plugin_backups_profiles` DROP `create_works`;
ALTER TABLE `glpi_plugin_backups_profiles` DROP `update_works`;
ALTER TABLE `glpi_plugin_backups_profiles` DROP `delete_works`;

DROP TABLE IF EXISTS `glpi_plugin_backups_work_nas`;
CREATE TABLE `glpi_plugin_backups_work_nas` (
	`ID` int(11) NOT NULL auto_increment,
	`FK_computer` int(11) NOT NULL default '0',
	`FK_work` int(11) NOT NULL default '0',
	`is_template` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`ID`),
	UNIQUE KEY `FK_computer` (`FK_computer`,`FK_work`),
	KEY `FK_computer_2` (`FK_computer`),
	KEY `FK_work` (`FK_work`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_dropdown_plugin_backups_history_status`;
CREATE TABLE `glpi_dropdown_plugin_backups_history_status` (
	`ID` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci NOT NULL default '',
	`comments` text,
	PRIMARY KEY  (`ID`),
	KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

ALTER TABLE `glpi_plugin_backups_tapes` ADD `is_template` smallint(6) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_tapes` ADD `tplname` varchar(200) collate utf8_unicode_ci NOT NULL default '';
ALTER TABLE `glpi_plugin_backups_tapes` ADD `FK_entities` int(11) NOT NULL default '0' AFTER `ID`;
ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `notes` `notes` LONGTEXT ;
ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `comments` `comments` TEXT ;

ALTER TABLE `glpi_plugin_backups_works` ADD `is_template` smallint(6) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_works` ADD `tplname` varchar(200) collate utf8_unicode_ci NOT NULL default '';
ALTER TABLE `glpi_plugin_backups_works` ADD `FK_entities` int(11) NOT NULL default '0' AFTER `ID`;
ALTER TABLE `glpi_plugin_backups_works` CHANGE `notes` `notes` LONGTEXT ;
ALTER TABLE `glpi_plugin_backups_works` CHANGE `comments` `comments` TEXT ;

ALTER TABLE `glpi_plugin_backups_scripts` ADD `location_server` VARCHAR( 255 ) collate utf8_unicode_ci NOT NULL DEFAULT '';
ALTER TABLE `glpi_plugin_backups_scripts` ADD `FK_entities` int(11) NOT NULL default '0' AFTER `ID`;
ALTER TABLE `glpi_plugin_backups_scripts` CHANGE `notes` `notes` LONGTEXT ;
ALTER TABLE `glpi_plugin_backups_scripts` CHANGE `comments` `comments` TEXT ;

ALTER TABLE `glpi_plugin_backups_libraries` ADD `FK_entities` int(11) NOT NULL default '0' AFTER `ID`;
ALTER TABLE `glpi_plugin_backups_libraries` CHANGE `notes` `notes` LONGTEXT ;
ALTER TABLE `glpi_plugin_backups_libraries` CHANGE `comments` `comments` TEXT ;

ALTER TABLE `glpi_plugin_backups_work_history` ADD `FK_entities` int(11) NOT NULL default '0' AFTER `ID`;
ALTER TABLE `glpi_plugin_backups_work_history` ADD `name` VARCHAR( 50 ) collate utf8_unicode_ci NULL AFTER `ID` ;
ALTER TABLE `glpi_plugin_backups_work_history` ADD `deleted` smallint(6) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_work_history` CHANGE `comments` `comments` TEXT ;

ALTER TABLE `glpi_plugin_backups_work_peripheral` ADD `is_template` smallint(6) NOT NULL default '0';

ALTER TABLE `glpi_plugin_backups_work_computer` ADD `is_template` smallint(6) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_work_computer` ADD `selection_list` longtext NOT NULL;

ALTER TABLE `glpi_plugin_backups_work_software` ADD `is_template` smallint(6) NOT NULL default '0';

ALTER TABLE `glpi_plugin_backups_library_tape` ADD `is_template` smallint(6) NOT NULL default '0';

ALTER TABLE `glpi_plugin_backups_work_library` ADD `is_template` smallint(6) NOT NULL default '0';

ALTER TABLE `glpi_plugin_backups_work_script` ADD `is_template` smallint(6) NOT NULL default '0';

ALTER TABLE `glpi_plugin_backups_work_tape` ADD `is_template` smallint(6) NOT NULL default '0';

ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `deleted` `deleted` smallint(6) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_libraries` CHANGE `deleted` `deleted` smallint(6) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_scripts` CHANGE `deleted` `deleted` smallint(6) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_works` CHANGE `deleted` `deleted` smallint(6) NOT NULL default '0';
UPDATE `glpi_plugin_backups_tapes` SET `deleted` = '0' WHERE `deleted` = '1';
UPDATE `glpi_plugin_backups_libraries` SET `deleted` = '0' WHERE `deleted` = '1';
UPDATE `glpi_plugin_backups_scripts` SET `deleted` = '0' WHERE `deleted` = '1';
UPDATE `glpi_plugin_backups_works` SET `deleted` = '0' WHERE `deleted` = '1';
UPDATE `glpi_plugin_backups_tapes` SET `deleted` = '1' WHERE `deleted` = '2';
UPDATE `glpi_plugin_backups_libraries` SET `deleted` = '1' WHERE `deleted` = '2';
UPDATE `glpi_plugin_backups_scripts` SET `deleted` = '1' WHERE `deleted` = '2';
UPDATE `glpi_plugin_backups_works` SET `deleted` = '1' WHERE `deleted` = '2';

ALTER TABLE `glpi_plugin_backups_profiles` CHANGE `is_default` `is_default` smallint(6) NOT NULL default '0';
UPDATE `glpi_plugin_backups_profiles` SET `is_default` = '0' WHERE `is_default` = '1';
UPDATE `glpi_plugin_backups_profiles` SET `is_default` = '1' WHERE `is_default` = '2';
	
DROP TABLE IF EXISTS `glpi_dropdown_plugin_backups_works_type`;
CREATE TABLE `glpi_dropdown_plugin_backups_works_type` (
	`ID` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci NOT NULL default '',
	`comments` text,
	PRIMARY KEY  (`ID`),
	KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_displayprefs` VALUES (NULL,'1500','3','2','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1500','4','3','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1500','5','6','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1500','6','5','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1500','7','4','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1501','2','1','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1502','2','2','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1502','3','3','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1502','4','4','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1503','2','2','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1503','3','5','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1503','4','4','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1504','2','2','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1504','3','3','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1504','4','4','0');
INSERT INTO `glpi_displayprefs` VALUES (NULL,'1504','5','5','0');