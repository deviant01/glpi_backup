<?php
/*
 * @version $Id: HEADER 15930 2013-09-16 09:47:55Z tsmr $
 -------------------------------------------------------------------------
 Backups plugin for GLPI
 Copyright (C) 2003-2013 by the Backups Development Team.

 https://forge.indepnet.net/projects/backups
 -------------------------------------------------------------------------

 LICENSE
		
 This file is part of Backups.

 Backups is free software; you can redistribute it and/or modify
 it under the terms of the GNU General Public License as published by
 the Free Software Foundation; either version 2 of the License, or
 (at your option) any later version.

 Backups is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
 GNU General Public License for more details.

 You should have received a copy of the GNU General Public License
 along with Backups. If not, see <http://www.gnu.org/licenses/>.
 --------------------------------------------------------------------------
 */
if (!defined('GLPI_ROOT')) {
   die("Sorry. You can't access directly to this file");
}

// Class for a Dropdown
class PluginBackupsWorkPeriodicity extends CommonDropdown {

   static function getTypeName() {
      global $LANG;

      return $LANG['plugin_backups']['setup'][5];
   }
   
   static function canCreate() {
      return plugin_backups_haveRight('backups', 'w');
   }

   static function canView() {
      return plugin_backups_haveRight('backups', 'r');
   }
}
?>
