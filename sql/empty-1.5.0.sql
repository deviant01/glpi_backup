DROP TABLE IF EXISTS `glpi_plugin_backups_tapes`;
CREATE TABLE `glpi_plugin_backups_tapes` (
   `id` int(11) NOT NULL auto_increment,
   `entities_id` int(11) NOT NULL default '0',
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `locations_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_locations (id)',
   `plugin_backups_tapetypes_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_tapestypes (id)',
   `capacity` varchar(255) collate utf8_unicode_ci default NULL,
   `date_service` date default NULL,
   `manufacturers_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_manufacturers (id)',
   `comment` text collate utf8_unicode_ci,
   `notepad` longtext collate utf8_unicode_ci,
   `date_mod` datetime default NULL,
   `is_template` tinyint(1) NOT NULL default '0',
   `template_name` varchar(255) collate utf8_unicode_ci default NULL,
   `is_deleted` tinyint(1) NOT NULL default '0',
   PRIMARY KEY  (`id`),
   KEY `name` (`name`),
   KEY `entities_id` (`entities_id`),
   KEY `locations_id` (`locations_id`),
   KEY `plugin_backups_tapetypes_id` (`plugin_backups_tapetypes_id`),
   KEY `manufacturers_id` (`manufacturers_id`),
   KEY `is_deleted` (`is_deleted`),
   KEY `is_template` (`is_template`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_libraries`;
CREATE TABLE `glpi_plugin_backups_libraries` (
   `id` int(11) NOT NULL auto_increment,
   `entities_id` int(11) NOT NULL default '0',
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
   `notepad` longtext collate utf8_unicode_ci,
   `is_deleted` tinyint(1) NOT NULL default '0',
   PRIMARY KEY  (`id`),
   KEY `name` (`name`),
   KEY `entities_id` (`entities_id`),
   KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_works`;
CREATE TABLE `glpi_plugin_backups_works` (
   `id` int(11) NOT NULL auto_increment,
   `entities_id` int(11) NOT NULL default '0',
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `plugin_backups_worktypes_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_workstypes (id)',
   `plugin_backups_workperiodicities_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_worksperiodicities (id)',
   `comment` text collate utf8_unicode_ci,
   `notepad` longtext collate utf8_unicode_ci,
   `date_mod` datetime default NULL,
   `is_template` tinyint(1) NOT NULL default '0',
   `template_name` varchar(255) collate utf8_unicode_ci default NULL,
   `is_deleted` tinyint(1) NOT NULL default '0',
   PRIMARY KEY  (`id`),
   KEY `name` (`name`),
   KEY `entities_id` (`entities_id`),
   KEY `plugin_backups_worktypes_id` (`plugin_backups_worktypes_id`),
   KEY `plugin_backups_workperiodicities_id` (`plugin_backups_workperiodicities_id`),
   KEY `is_template` (`is_template`),
   KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_scripts`;
CREATE TABLE `glpi_plugin_backups_scripts` (
   `id` int(11) NOT NULL auto_increment,
   `entities_id` int(11) NOT NULL default '0',
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `plugin_backups_scripttypes_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_scriptstypes (id)',
   `location_server` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
   `notepad` longtext collate utf8_unicode_ci,
   `is_deleted` tinyint(1) NOT NULL default '0',
   PRIMARY KEY  (`id`),
   KEY `name` (`name`),
   KEY `entities_id` (`entities_id`),
   KEY `plugin_backups_scripttypes_id` (`plugin_backups_scripttypes_id`),
   KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_histories`;
CREATE TABLE `glpi_plugin_backups_histories` (
   `id` int(11) NOT NULL auto_increment,
   `entities_id` int(11) NOT NULL default '0',
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `plugin_backups_works_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_works (id)',
   `date` date default NULL,
   `plugin_backups_historystates_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_historiesstates (id)',
   `comment` text collate utf8_unicode_ci,
   `is_deleted` tinyint(1) NOT NULL default '0',
   PRIMARY KEY  (`id`),
   KEY `name` (`name`),
   KEY `entities_id` (`entities_id`),
   KEY `plugin_backups_works_id` (`plugin_backups_works_id`),
   KEY `plugin_backups_historystates_id` (`plugin_backups_historystates_id`),
   KEY `is_deleted` (`is_deleted`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Relations tables

DROP TABLE IF EXISTS `glpi_plugin_backups_libraries_tapes`;
CREATE TABLE `glpi_plugin_backups_libraries_tapes` (
   `id` int(11) NOT NULL auto_increment,
   `tapes_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_tapes (id)',
   `libraries_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_libraries (id)',
   PRIMARY KEY  (`id`),
   UNIQUE KEY `unicity` (`tapes_id`,`libraries_id`),
   KEY `tapes_id` (`tapes_id`),
   KEY `libraries_id` (`libraries_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_works_libraries`;
   CREATE TABLE `glpi_plugin_backups_works_libraries` (
   `id` int(11) NOT NULL auto_increment,
   `libraries_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_libraries (id)',
   `works_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_works (id)',
   PRIMARY KEY  (`id`),
   UNIQUE KEY `unicity` (`works_id`,`libraries_id`),
   KEY `works_id` (`works_id`),
   KEY `libraries_id` (`libraries_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_works_scripts`;
CREATE TABLE `glpi_plugin_backups_works_scripts` (
   `id` int(11) NOT NULL auto_increment,
   `scripts_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_scripts (id)',
   `works_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_works (id)',
   PRIMARY KEY  (`id`),
   UNIQUE KEY `unicity` (`works_id`,`scripts_id`),
   KEY `works_id` (`works_id`),
   KEY `scripts_id` (`scripts_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_works_tapes`;
CREATE TABLE `glpi_plugin_backups_works_tapes` (
   `id` int(11) NOT NULL auto_increment,
   `tapes_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_tapes (id)',
   `works_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_works (id)',
   PRIMARY KEY  (`id`),
   UNIQUE KEY `unicity` (`works_id`,`tapes_id`),
   KEY `works_id` (`works_id`),
   KEY `tapes_id` (`tapes_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_works_items`;
CREATE TABLE `glpi_plugin_backups_works_items` (
   `id` int(11) NOT NULL auto_increment,
   `plugin_backups_works_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_works (id)',
   `items_id` int(11) NOT NULL default '0' COMMENT 'RELATION to various tables, according to itemtype (id)',
   `itemtype` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'see .class.php file',
   PRIMARY KEY  (`id`),
   UNIQUE KEY `unicity` (`plugin_backups_works_id`,`items_id`,`itemtype`),
   KEY `FK_device` (`items_id`,`itemtype`),
   KEY `item` (`itemtype`,`items_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_works_computers`;
CREATE TABLE `glpi_plugin_backups_works_computers` (
   `id` int(11) NOT NULL auto_increment,
   `computers_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_computers (id)',
   `works_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_works (id)',
   `list_selection` longtext collate utf8_unicode_ci,
   PRIMARY KEY  (`id`),
   UNIQUE KEY `unicity` (`works_id`,`computers_id`),
   KEY `works_id` (`works_id`),
   KEY `computers_id` (`computers_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- Dropdowns tables

DROP TABLE IF EXISTS `glpi_plugin_backups_tapetypes`;
CREATE TABLE `glpi_plugin_backups_tapetypes` (
   `id` int(11) NOT NULL auto_increment,
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
   PRIMARY KEY  (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_worktypes`;
CREATE TABLE `glpi_plugin_backups_worktypes` (
   `id` int(11) NOT NULL auto_increment,
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
   PRIMARY KEY  (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_workperiodicities`;
CREATE TABLE `glpi_plugin_backups_workperiodicities` (
   `id` int(11) NOT NULL auto_increment,
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
   PRIMARY KEY  (`id`),
   KEY `name` (`name`)
   ) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_scripttypes`;
CREATE TABLE `glpi_plugin_backups_scripttypes` (
   `id` int(11) NOT NULL auto_increment,
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
   PRIMARY KEY  (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_historystates`;
CREATE TABLE `glpi_plugin_backups_historystates` (
   `id` int(11) NOT NULL auto_increment,
   `name` varchar(255) collate utf8_unicode_ci default NULL,
   `comment` text collate utf8_unicode_ci,
   PRIMARY KEY  (`id`),
   KEY `name` (`name`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

DROP TABLE IF EXISTS `glpi_plugin_backups_profiles`;
CREATE TABLE `glpi_plugin_backups_profiles` (
   `id` int(11) NOT NULL auto_increment,
   `profiles_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)',
   `backups` char(1) collate utf8_unicode_ci default NULL,
   `libraries` char(1) collate utf8_unicode_ci default NULL,
   `scripts` char(1) collate utf8_unicode_ci default NULL,
   `tapes` char(1) collate utf8_unicode_ci default NULL,
   `works` char(1) collate utf8_unicode_ci default NULL,
   PRIMARY KEY  (`id`),
   KEY `profiles_id` (`profiles_id`)
) ENGINE=MyISAM  DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsTape','3','2','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsTape','4','3','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsTape','5','6','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsTape','6','5','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsTape','7','4','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsLibrary','2','1','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsWork','2','2','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsWork','3','3','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsWork','4','4','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsScript','2','2','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsScript','3','5','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsScript','4','4','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsHistory','2','2','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsHistory','3','3','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsHistory','4','4','0');
INSERT INTO `glpi_displaypreferences` VALUES (NULL,'PluginBackupsHistory','5','5','0');