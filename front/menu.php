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

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

$plugin = new Plugin();
if ($plugin->isActivated("environment"))
   Html::header($LANG['plugin_backups']['title'][0],$_SERVER['PHP_SELF'],"plugins","environment","backups");
else
   Html::header($LANG['plugin_backups']['title'][0],$_SERVER["PHP_SELF"],"plugins","backups","summary");

if (plugin_backups_haveRight("backups","r") || Session::haveRight("config","w")){
   
   echo "<div align='center'>";
   echo "<table class='tab_cadre_central'>";
   echo "<tr><td align='center' valign='top'>";
   PluginBackupsMenu::showSummary();
   echo "<br>";
   PluginBackupsHistory::showCentral($_SERVER["PHP_SELF"]);
   echo "<br></td></tr>";
   echo "</table></div>";

} else {
   Html::displayRightError();
}

Html::footer();

?>
