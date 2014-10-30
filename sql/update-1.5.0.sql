ALTER TABLE `glpi_plugin_backups_work_history` RENAME `glpi_plugin_backups_histories`;
ALTER TABLE `glpi_plugin_backups_library_tape` RENAME `glpi_plugin_backups_libraries_tapes`;
ALTER TABLE `glpi_plugin_backups_work_library` RENAME `glpi_plugin_backups_works_libraries`;
ALTER TABLE `glpi_plugin_backups_work_script` RENAME `glpi_plugin_backups_works_scripts`;
ALTER TABLE `glpi_plugin_backups_work_tape` RENAME `glpi_plugin_backups_works_tapes`;
ALTER TABLE `glpi_plugin_backups_work_device` RENAME `glpi_plugin_backups_works_items`;
ALTER TABLE `glpi_plugin_backups_work_computer` RENAME `glpi_plugin_backups_works_computers`;
ALTER TABLE `glpi_dropdown_plugin_backups_tapes_type` RENAME `glpi_plugin_backups_tapetypes`;
ALTER TABLE `glpi_dropdown_plugin_backups_works_type` RENAME `glpi_plugin_backups_worktypes`;
ALTER TABLE `glpi_dropdown_plugin_backups_works_periodicity` RENAME `glpi_plugin_backups_workperiodicities`;
ALTER TABLE `glpi_dropdown_plugin_backups_scripts_type` RENAME `glpi_plugin_backups_scripttypes`;
ALTER TABLE `glpi_dropdown_plugin_backups_history_status` RENAME `glpi_plugin_backups_historystates`;

ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `location` `locations_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_locations (id)';
ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `type` `plugin_backups_tapetypes_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_tapetypes (id)';
ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `capacity` `capacity` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `service_date` `date_service` date default NULL;
ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `FK_glpi_enterprise` `manufacturers_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_manufacturers (id)';
ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `notes` `notepad` longtext collate utf8_unicode_ci;
ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `comments` `comment` text collate utf8_unicode_ci;
ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `is_template` `is_template` tinyint(1) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `tplname` `template_name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_tapes` ADD `date_mod` datetime default NULL after `notepad`;

ALTER TABLE `glpi_plugin_backups_tapes` ADD INDEX (`name`);
ALTER TABLE `glpi_plugin_backups_tapes` ADD INDEX (`entities_id`);
ALTER TABLE `glpi_plugin_backups_tapes` ADD INDEX (`locations_id`);
ALTER TABLE `glpi_plugin_backups_tapes` ADD INDEX (`plugin_backups_tapetypes_id`);
ALTER TABLE `glpi_plugin_backups_tapes` ADD INDEX (`manufacturers_id`);
ALTER TABLE `glpi_plugin_backups_tapes` ADD INDEX (`is_deleted`);
ALTER TABLE `glpi_plugin_backups_tapes` ADD INDEX (`is_template`);

ALTER TABLE `glpi_plugin_backups_libraries` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_libraries` CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_libraries` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_libraries` CHANGE `notes` `notepad` longtext collate utf8_unicode_ci;
ALTER TABLE `glpi_plugin_backups_libraries` CHANGE `comments` `comment` text collate utf8_unicode_ci;
ALTER TABLE `glpi_plugin_backups_libraries` CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0';

ALTER TABLE `glpi_plugin_backups_libraries` ADD INDEX (`name`);
ALTER TABLE `glpi_plugin_backups_libraries` ADD INDEX (`entities_id`);
ALTER TABLE `glpi_plugin_backups_libraries` ADD INDEX (`is_deleted`);

ALTER TABLE `glpi_plugin_backups_works` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_works` CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_works` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_works` CHANGE `type` `plugin_backups_worktypes_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_worktypes (id)';
ALTER TABLE `glpi_plugin_backups_works` CHANGE `periodicity` `plugin_backups_workperiodicities_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_workperiodicities (id)';
ALTER TABLE `glpi_plugin_backups_works` CHANGE `notes` `notepad` longtext collate utf8_unicode_ci;
ALTER TABLE `glpi_plugin_backups_works` CHANGE `comments` `comment` text collate utf8_unicode_ci;
ALTER TABLE `glpi_plugin_backups_works` CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_works` CHANGE `is_template` `is_template` tinyint(1) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_works` CHANGE `tplname` `template_name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_works` ADD `date_mod` datetime default NULL after `notepad`;

ALTER TABLE `glpi_plugin_backups_works` ADD INDEX (`name`);
ALTER TABLE `glpi_plugin_backups_works` ADD INDEX (`entities_id`);
ALTER TABLE `glpi_plugin_backups_works` ADD INDEX (`plugin_backups_worktypes_id`);
ALTER TABLE `glpi_plugin_backups_works` ADD INDEX (`plugin_backups_workperiodicities_id`);
ALTER TABLE `glpi_plugin_backups_works` ADD INDEX (`is_deleted`);
ALTER TABLE `glpi_plugin_backups_works` ADD INDEX (`is_template`);

ALTER TABLE `glpi_plugin_backups_scripts` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_scripts` CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_scripts` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_scripts` CHANGE `type` `plugin_backups_scripttypes_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_scripttypes (id)';
ALTER TABLE `glpi_plugin_backups_scripts` CHANGE `location_server` `location_server` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_scripts` CHANGE `notes` `notepad` longtext collate utf8_unicode_ci;
ALTER TABLE `glpi_plugin_backups_scripts` CHANGE `comments` `comment` text collate utf8_unicode_ci;
ALTER TABLE `glpi_plugin_backups_scripts` CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0';

ALTER TABLE `glpi_plugin_backups_scripts` ADD INDEX (`name`);
ALTER TABLE `glpi_plugin_backups_scripts` ADD INDEX (`plugin_backups_scripttypes_id`);
ALTER TABLE `glpi_plugin_backups_scripts` ADD INDEX (`entities_id`);
ALTER TABLE `glpi_plugin_backups_scripts` ADD INDEX (`is_deleted`);

ALTER TABLE `glpi_plugin_backups_histories` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_histories` CHANGE `FK_entities` `entities_id` int(11) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_histories` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_histories` CHANGE `FK_work` `plugin_backups_works_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_works (id)';
ALTER TABLE `glpi_plugin_backups_histories` CHANGE `date` `date` date default NULL;
ALTER TABLE `glpi_plugin_backups_histories` CHANGE `comments` `comment` text collate utf8_unicode_ci;
ALTER TABLE `glpi_plugin_backups_histories` CHANGE `deleted` `is_deleted` tinyint(1) NOT NULL default '0';
ALTER TABLE `glpi_plugin_backups_histories` CHANGE `status` `plugin_backups_historystates_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_historystates (id)';

ALTER TABLE `glpi_plugin_backups_histories` ADD INDEX (`name`);
ALTER TABLE `glpi_plugin_backups_histories` ADD INDEX (`entities_id`);
ALTER TABLE `glpi_plugin_backups_histories` ADD INDEX (`plugin_backups_`);
ALTER TABLE `glpi_plugin_backups_histories` ADD INDEX (`plugin_backups_historystates_id`);
ALTER TABLE `glpi_plugin_backups_histories` ADD INDEX (`is_deleted`);

ALTER TABLE `glpi_plugin_backups_libraries_tapes` DROP INDEX `FK_tape`;
ALTER TABLE `glpi_plugin_backups_libraries_tapes` DROP INDEX `FK_tape_2`;
ALTER TABLE `glpi_plugin_backups_libraries_tapes` DROP INDEX `FK_library`;

ALTER TABLE `glpi_plugin_backups_libraries_tapes` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_libraries_tapes` CHANGE `FK_tape` `tapes_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_tapes (id)';
ALTER TABLE `glpi_plugin_backups_libraries_tapes` CHANGE `FK_library` `libraries_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_libraries (id)';
ALTER TABLE `glpi_plugin_backups_libraries_tapes` DROP `is_template`;

ALTER TABLE `glpi_plugin_backups_libraries_tapes` ADD UNIQUE `unicity` (`tapes_id`,`libraries_id`);
ALTER TABLE `glpi_plugin_backups_libraries_tapes` ADD INDEX `tapes_id` (`tapes_id`);
ALTER TABLE `glpi_plugin_backups_libraries_tapes` ADD INDEX `libraries_id` (`libraries_id`);

ALTER TABLE `glpi_plugin_backups_works_libraries` DROP INDEX `FK_library`;
ALTER TABLE `glpi_plugin_backups_works_libraries` DROP INDEX `FK_library_2`;
ALTER TABLE `glpi_plugin_backups_works_libraries` DROP INDEX `FK_work`;

ALTER TABLE `glpi_plugin_backups_works_libraries` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_works_libraries` CHANGE `FK_work` `works_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_works (id)';
ALTER TABLE `glpi_plugin_backups_works_libraries` CHANGE `FK_library` `libraries_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_libraries (id)';
ALTER TABLE `glpi_plugin_backups_works_libraries` DROP `is_template`;

ALTER TABLE `glpi_plugin_backups_works_libraries` ADD UNIQUE `unicity` (`works_id`,`libraries_id`);
ALTER TABLE `glpi_plugin_backups_works_libraries` ADD INDEX `works_id` (`works_id`);
ALTER TABLE `glpi_plugin_backups_works_libraries` ADD INDEX `libraries_id` (`libraries_id`);

ALTER TABLE `glpi_plugin_backups_works_scripts` DROP INDEX `FK_script`;
ALTER TABLE `glpi_plugin_backups_works_scripts` DROP INDEX `FK_script_2`;
ALTER TABLE `glpi_plugin_backups_works_scripts` DROP INDEX `FK_work`;

ALTER TABLE `glpi_plugin_backups_works_scripts` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_works_scripts` CHANGE `FK_work` `works_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_works (id)';
ALTER TABLE `glpi_plugin_backups_works_scripts` CHANGE `FK_script` `scripts_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_scripts (id)';
ALTER TABLE `glpi_plugin_backups_works_scripts` DROP `is_template`;

ALTER TABLE `glpi_plugin_backups_works_scripts` ADD UNIQUE `unicity` (`works_id`,`scripts_id`);
ALTER TABLE `glpi_plugin_backups_works_scripts` ADD INDEX `works_id` (`works_id`);
ALTER TABLE `glpi_plugin_backups_works_scripts` ADD INDEX `scripts_id` (`scripts_id`);

ALTER TABLE `glpi_plugin_backups_works_tapes` DROP INDEX `FK_tape`;
ALTER TABLE `glpi_plugin_backups_works_tapes` DROP INDEX `FK_tape_2`;
ALTER TABLE `glpi_plugin_backups_works_tapes` DROP INDEX `FK_work`;

ALTER TABLE `glpi_plugin_backups_works_tapes` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_works_tapes` CHANGE `FK_work` `works_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_works (id)';
ALTER TABLE `glpi_plugin_backups_works_tapes` CHANGE `FK_tape` `tapes_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_tapes (id)';
ALTER TABLE `glpi_plugin_backups_works_tapes` DROP `is_template`;

ALTER TABLE `glpi_plugin_backups_works_tapes` ADD UNIQUE `unicity` (`works_id`,`tapes_id`);
ALTER TABLE `glpi_plugin_backups_works_tapes` ADD INDEX `works_id` (`works_id`);
ALTER TABLE `glpi_plugin_backups_works_tapes` ADD INDEX `tapes_id` (`tapes_id`);

ALTER TABLE `glpi_plugin_backups_works_items` DROP INDEX `FK_work`;
ALTER TABLE `glpi_plugin_backups_works_items` DROP INDEX `FK_work_2`;
ALTER TABLE `glpi_plugin_backups_works_items` DROP INDEX `FK_device`;

ALTER TABLE `glpi_plugin_backups_works_items` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_works_items` CHANGE `FK_work` `plugin_backups_works_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_works (id)';
ALTER TABLE `glpi_plugin_backups_works_items` CHANGE `FK_device` `items_id` int(11) NOT NULL default '0' COMMENT 'RELATION to various table, according to itemtype (id)';
ALTER TABLE `glpi_plugin_backups_works_items` CHANGE `device_type` `itemtype` varchar(100) collate utf8_unicode_ci NOT NULL COMMENT 'see .class.php file';

ALTER TABLE `glpi_plugin_backups_works_items` ADD UNIQUE `unicity` (`plugin_backups_works_id`,`itemtype`,`items_id`);
ALTER TABLE `glpi_plugin_backups_works_items` ADD INDEX `FK_device` (`items_id`,`itemtype`);
ALTER TABLE `glpi_plugin_backups_works_items` ADD INDEX `item` (`itemtype`,`items_id`);

ALTER TABLE `glpi_plugin_backups_works_computers` DROP INDEX `FK_computer`;
ALTER TABLE `glpi_plugin_backups_works_computers` DROP INDEX `FK_computer_2`;
ALTER TABLE `glpi_plugin_backups_works_computers` DROP INDEX `FK_work`;

ALTER TABLE `glpi_plugin_backups_works_computers` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_works_computers` CHANGE `FK_work` `works_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_plugin_backups_works (id)';
ALTER TABLE `glpi_plugin_backups_works_computers` CHANGE `FK_computer` `computers_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_computers (id)';
ALTER TABLE `glpi_plugin_backups_works_computers` CHANGE `selection_list` `list_selection` longtext collate utf8_unicode_ci;
ALTER TABLE `glpi_plugin_backups_works_computers` DROP `is_template`;

ALTER TABLE `glpi_plugin_backups_works_computers` ADD UNIQUE `unicity` (`works_id`,`computers_id`);
ALTER TABLE `glpi_plugin_backups_works_computers` ADD INDEX `works_id` (`works_id`);
ALTER TABLE `glpi_plugin_backups_works_computers` ADD INDEX `computers_id` (`computers_id`);

ALTER TABLE `glpi_plugin_backups_tapetypes` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_tapetypes` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_tapetypes` CHANGE `comments` `comment` text collate utf8_unicode_ci;

ALTER TABLE `glpi_plugin_backups_worktypes` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_worktypes` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_worktypes` CHANGE `comments` `comment` text collate utf8_unicode_ci;

ALTER TABLE `glpi_plugin_backups_workperiodicities` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_workperiodicities` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_workperiodicities` CHANGE `comments` `comment` text collate utf8_unicode_ci;

ALTER TABLE `glpi_plugin_backups_scripttypes` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_scripttypes` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_scripttypes` CHANGE `comments` `comment` text collate utf8_unicode_ci;

ALTER TABLE `glpi_plugin_backups_historystates` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_historystates` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_historystates` CHANGE `comments` `comment` text collate utf8_unicode_ci;

ALTER TABLE `glpi_plugin_backups_profiles` CHANGE `ID` `id` int(11) NOT NULL auto_increment;
ALTER TABLE `glpi_plugin_backups_profiles` CHANGE `name` `name` varchar(255) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_profiles` CHANGE `backups` `backups` char(1) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_profiles` CHANGE `libraries` `libraries` char(1) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_profiles` CHANGE `scripts` `scripts` char(1) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_profiles` CHANGE `tapes` `tapes` char(1) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_profiles` CHANGE `works` `works` char(1) collate utf8_unicode_ci default NULL;
ALTER TABLE `glpi_plugin_backups_profiles` ADD `profiles_id` int(11) NOT NULL default '0' COMMENT 'RELATION to glpi_profiles (id)';