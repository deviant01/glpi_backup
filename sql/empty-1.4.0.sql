DROP TABLE IF EXISTS `glpi_plugin_backups_tapes`;
CREATE TABLE `glpi_plugin_backups_tapes` (
	`ID` int(11) NOT NULL auto_increment,
	`FK_entities` int(11) NOT NULL default '0',
	`name` varchar(50) collate utf8_unicode_ci default NULL,
	`location` tinyint(4) NOT NULL default '0',
	`type` tinyint(4) NOT NULL default '0',
	`capacity` varchar(50) collate utf8_unicode_ci NOT NULL default '',
	`service_date` DATE NULL default NULL,
	`FK_glpi_enterprise` int(11) NOT NULL default '0',
	`comments` text,
	`notes` longtext,
	`is_template` smallint(6) NOT NULL default '0',
	`tplname` varchar(200) collate utf8_unicode_ci NOT NULL default '',
	`deleted` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`ID`),
	KEY `FK_glpi_enterprise` (`FK_glpi_enterprise`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_libraries`;
CREATE TABLE `glpi_plugin_backups_libraries` (
	`ID` int(11) NOT NULL auto_increment,
	`FK_entities` int(11) NOT NULL default '0',
	`name` varchar(50) collate utf8_unicode_ci default NULL,
	`comments` text,
	`notes` longtext,
	`deleted` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_works`;
CREATE TABLE `glpi_plugin_backups_works` (
	`ID` int(11) NOT NULL auto_increment,
	`FK_entities` int(11) NOT NULL default '0',
	`name` varchar(50)  collate utf8_unicode_ci default NULL,
	`type` TINYINT( 4 ) DEFAULT '0',
	`periodicity` TINYINT( 4 ) DEFAULT '0',
	`comments` text,
	`notes` longtext,
	`is_template` smallint(6) NOT NULL default '0',
	`tplname` varchar(200) collate utf8_unicode_ci NOT NULL default '',
	`deleted` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_scripts`;
CREATE TABLE `glpi_plugin_backups_scripts` (
	`ID` int(11) NOT NULL auto_increment,
	`FK_entities` int(11) NOT NULL default '0',
	`name` varchar(50) collate utf8_unicode_ci default NULL,
	`type` TINYINT( 4 ) DEFAULT '0',
	`location_server` VARCHAR( 255 ) collate utf8_unicode_ci NOT NULL DEFAULT '',
	`comments` text,
	`notes` longtext,
	`deleted` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`ID`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_work_history`;
CREATE TABLE `glpi_plugin_backups_work_history` (
	`ID` int(11) NOT NULL auto_increment,
	`FK_entities` int(11) NOT NULL default '0',
	`name` varchar(50) collate utf8_unicode_ci default NULL,
	`FK_work` int(11) NOT NULL default '0',
	`date` DATE NULL default NULL,
	`status` varchar(50) collate utf8_unicode_ci default NULL,
	`comments` text,
	`deleted` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`ID`),
	KEY `FK_work` (`FK_work`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_library_tape`;
CREATE TABLE `glpi_plugin_backups_library_tape` (
	`ID` int(11) NOT NULL auto_increment,
	`FK_tape` int(11) NOT NULL default '0',
	`FK_library` int(11) NOT NULL default '0',
	`is_template` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`ID`),
	UNIQUE KEY `FK_tape` (`FK_tape`,`FK_library`),
	KEY `FK_tape_2` (`FK_tape`),
	KEY `FK_library` (`FK_library`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_work_library`;
	CREATE TABLE `glpi_plugin_backups_work_library` (
	`ID` int(11) NOT NULL auto_increment,
	`FK_library` int(11) NOT NULL default '0',
	`FK_work` int(11) NOT NULL default '0',
	`is_template` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`ID`),
	UNIQUE KEY `FK_library` (`FK_library`,`FK_work`),
	KEY `FK_library_2` (`FK_library`),
	KEY `FK_work` (`FK_work`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_work_script`;
CREATE TABLE `glpi_plugin_backups_work_script` (
	`ID` int(11) NOT NULL auto_increment,
	`FK_script` int(11) NOT NULL default '0',
	`FK_work` int(11) NOT NULL default '0',
	`is_template` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`ID`),
	UNIQUE KEY `FK_script` (`FK_script`,`FK_work`),
	KEY `FK_script_2` (`FK_script`),
	KEY `FK_work` (`FK_work`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_work_tape`;
CREATE TABLE `glpi_plugin_backups_work_tape` (
	`ID` int(11) NOT NULL auto_increment,
	`FK_tape` int(11) NOT NULL default '0',
	`FK_work` int(11) NOT NULL default '0',
	`is_template` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`ID`),
	UNIQUE KEY `FK_tape` (`FK_tape`,`FK_work`),
	KEY `FK_tape_2` (`FK_tape`),
	KEY `FK_work` (`FK_work`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_work_device`;
CREATE TABLE `glpi_plugin_backups_work_device` (
	`ID` int(11) NOT NULL auto_increment,
	`FK_work` int(11) NOT NULL default '0',
	`FK_device` int(11) NOT NULL default '0',
	`device_type` int(11) NOT NULL default '0',
	`is_template` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`ID`),
	UNIQUE KEY `FK_work` (`FK_work`,`FK_device`,`device_type`),
	KEY `FK_work_2` (`FK_work`),
	KEY `FK_device` (`FK_device`,`device_type`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_work_computer`;
CREATE TABLE `glpi_plugin_backups_work_computer` (
	`ID` int(11) NOT NULL auto_increment,
	`FK_computer` int(11) NOT NULL default '0',
	`FK_work` int(11) NOT NULL default '0',
	`selection_list` longtext NOT NULL,
	`is_template` smallint(6) NOT NULL default '0',
	PRIMARY KEY  (`ID`),
	UNIQUE KEY `FK_computer` (`FK_computer`,`FK_work`),
	KEY `FK_computer_2` (`FK_computer`),
	KEY `FK_work` (`FK_work`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_dropdown_plugin_backups_tapes_type`;
CREATE TABLE `glpi_dropdown_plugin_backups_tapes_type` (
	`ID` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci NOT NULL default '',
	`comments` text,
	PRIMARY KEY  (`ID`),
	KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_dropdown_plugin_backups_works_type`;
CREATE TABLE `glpi_dropdown_plugin_backups_works_type` (
	`ID` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci NOT NULL default '',
	`comments` text,
	PRIMARY KEY  (`ID`),
KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_dropdown_plugin_backups_works_periodicity`;
CREATE TABLE `glpi_dropdown_plugin_backups_works_periodicity` (
	`ID` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci NOT NULL default '',
	`comments` text,
	PRIMARY KEY  (`ID`),
	KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_dropdown_plugin_backups_scripts_type`;
CREATE TABLE `glpi_dropdown_plugin_backups_scripts_type` (
	`ID` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci NOT NULL default '',
	`comments` text,
	PRIMARY KEY  (`ID`),
	KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_dropdown_plugin_backups_history_status`;
CREATE TABLE `glpi_dropdown_plugin_backups_history_status` (
	`ID` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci NOT NULL default '',
	`comments` text,
	PRIMARY KEY  (`ID`),
	KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_profiles`;
CREATE TABLE `glpi_plugin_backups_profiles` (
	`ID` int(11) NOT NULL auto_increment,
	`name` varchar(255) collate utf8_unicode_ci default NULL,
	`backups` char(1) default NULL,
	`libraries` char(1) default NULL,
	`scripts` char(1) default NULL,
	`tapes` char(1) default NULL,
	`works` char(1) default NULL,
	PRIMARY KEY  (`ID`),
	KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1500','3','2','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1500','4','3','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1500','5','6','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1500','6','5','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1500','7','4','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1501','2','1','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1502','2','2','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1502','3','3','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1502','4','4','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1503','2','2','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1503','3','5','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1503','4','4','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1504','2','2','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1504','3','3','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1504','4','4','0');
INSERT INTO `glpi_display` ( `ID` , `type` , `num` , `rank` , `FK_users` )  VALUES (NULL,'1504','5','5','0');