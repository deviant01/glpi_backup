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

class PluginBackupsMenu extends CommonDBTM {

   static function showSummary(){
      global $DB,$LANG,$CFG_GLPI;
      
      ksort($_SESSION["glpiactiveentities"]);

      foreach ($_SESSION["glpiactiveentities"] as $key => $val){
      
         $query = "SELECT count(`id`) 
                  FROM `glpi_plugin_backups_works` 
                  WHERE `is_deleted` = '0' 
                  AND `is_template` = '0' 
                  AND `entities_id` ='".$key."'";
         $result = $DB->query($query);
         $number_of_works = $DB->result($result,0,0);
      
         $query = "SELECT count(`id`) 
                  FROM `glpi_plugin_backups_libraries` 
                  WHERE `is_deleted` = '0' 
                  AND `entities_id` ='".$key."'";
         $result = $DB->query($query);
         $number_of_libraries = $DB->result($result,0,0);
      
         $query = "SELECT count(`id`) 
                  FROM `glpi_plugin_backups_tapes` 
                  WHERE `is_deleted` = '0' 
                  AND `is_template` = '0' 
                  AND `entities_id` = '".$key."'";
         $result = $DB->query($query);
         $number_of_tapes = $DB->result($result,0,0);
      
         $query = "SELECT count(`id`) 
                  FROM `glpi_plugin_backups_scripts` 
                  WHERE `is_deleted` = '0' 
                  AND `entities_id` = '".$key."'";
         $result = $DB->query($query);
         $number_of_scripts = $DB->result($result,0,0);
         
         $query = "SELECT count(`id`) 
                  FROM `glpi_plugin_backups_histories` 
                  WHERE `is_deleted` = '0' 
                  AND `entities_id` = '".$key."'";
         $result = $DB->query($query);
         $number_of_history = $DB->result($result,0,0);
      
         echo "<div align='center'><table class='tab_cadre' align='center' width='90%'>";
         echo "<tr><th colspan='5'>".$LANG['plugin_backups']['title'][4]." - ".Dropdown::getdropdownname("glpi_entities",$key)."</th></tr>";
      
         echo "<tr><th width='20%'><a href='".$CFG_GLPI["root_doc"]."/plugins/backups/front/tape.php'>".$LANG['plugin_backups']['title'][2]."</a></th>";
         echo "<th width='20%'><a href='".$CFG_GLPI["root_doc"]."/plugins/backups/front/library.php'>".$LANG['plugin_backups']['title'][5]."</a></th>";
         echo "<th width='20%'><a href='".$CFG_GLPI["root_doc"]."/plugins/backups/front/work.php'>".$LANG['plugin_backups']['title'][1]."</a></th>";
         echo "<th width='20%'><a href='".$CFG_GLPI["root_doc"]."/plugins/backups/front/script.php'>".$LANG['plugin_backups']['title'][8]."</a></th>";
         echo "<th width='20%'><a href='".$CFG_GLPI["root_doc"]."/plugins/backups/front/history.php'>".$LANG['plugin_backups']['title'][9]."</a></th></tr>";
      
      
         echo "<tr>";
         echo "<td class='tab_bg_2'>".$LANG['common'][33]." : $number_of_tapes</td>";
         echo "<td class='tab_bg_2'>".$LANG['common'][33]." : $number_of_libraries</td>";
         echo "<td class='tab_bg_2'>".$LANG['common'][33]." : $number_of_works</td>";
         echo "<td class='tab_bg_2'>".$LANG['common'][33]." : $number_of_scripts</td>";
         echo "<td class='tab_bg_2'>".$LANG['common'][33]." : $number_of_history</td>";
         echo "</tr>";
      
         echo "</table></div><br>";

      }
   }
}
?>