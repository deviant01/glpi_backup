ALTER TABLE `glpi_plugin_backups_tapes` CHANGE `service_date` `service_date` DATE NULL default NULL;
UPDATE `glpi_plugin_backups_tapes` SET `service_date` = NULL WHERE `service_date` ='0000-00-00';
ALTER TABLE `glpi_plugin_backups_work_history` CHANGE `date` `date` DATE NULL default NULL;
UPDATE `glpi_plugin_backups_work_history` SET `date` = NULL WHERE `date` ='0000-00-00';

ALTER TABLE `glpi_plugin_backups_profiles` DROP COLUMN `interface` , DROP COLUMN `is_default`;