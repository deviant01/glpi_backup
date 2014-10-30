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