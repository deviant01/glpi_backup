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

if (!defined('GLPI_ROOT')){
   die("Sorry. You can't access directly to this file");
}

class PluginBackupsScript extends CommonDBTM {
   
   public $dohistory=true;
   
   static function getTypeName($nb=0) {
      global $LANG;

      if ($nb>1) {
         return $LANG['plugin_backups']['title'][8];
      }
      return $LANG['plugin_backups']['title'][14];
   }
   
   static function canCreate() {
      return plugin_backups_haveRight('tapes', 'w');
   }

   static function canView() {
      return plugin_backups_haveRight('tapes', 'r');
   }
   

   function getSearchOptions() {
      global $LANG;

      $tab = array();
      
      $tab['common']             = $LANG['plugin_backups']['title'][8];

      $tab[1]['table']           = $this->getTable();
      $tab[1]['field']           = 'name';
      $tab[1]['name']            = $LANG['common'][16];
      $tab[1]['datatype']        = 'itemlink';
      $tab[1]['itemlink_type']   = $this->getType();

      $tab[2]['table']           = 'glpi_plugin_backups_scripttypes';
      $tab[2]['field']           = 'name';
      $tab[2]['name']            = $LANG['common'][17];

      $tab[3]['table']           = $this->getTable();
      $tab[3]['field']           = 'comment';
      $tab[3]['name']            = $LANG['common'][25];
      $tab[3]['datatype']        = 'text';
      
      $tab[4]['table']           = $this->getTable();
      $tab[4]['field']           = 'location_server';
      $tab[4]['name']            = $LANG['plugin_backups']['scripts'][3];

      $tab[30]['table']          = $this->getTable();
      $tab[30]['field']          = 'id';
      $tab[30]['name']           = $LANG['common'][2];

      $tab[80]['table']          = 'glpi_entities';
      $tab[80]['field']          = 'completename';
      $tab[80]['name']           = $LANG['entity'][0];
      
       return $tab;
   }


   function defineTabs($options=array()) {
      global $LANG;

      $ong = array();
      $this->addStandardTab('PluginBackupsWork_Script', $ong, $options);
      $this->addStandardTab('Note', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);

      return $ong;
   }
   
   
   function cleanDBonPurge() {

      $temp = new PluginBackupsWork_Script();
      $temp->deleteByCriteria(array('scripts_id' => $this->fields['id']));

   }


   function showForm($ID, $options=array()) {
      global $CFG_GLPI, $LANG;

      if (!PluginBackupsScript::canView()) return false;
      
      if ($ID > 0) {
         $this->check($ID,'r');
      } else {
         // Create item
         $this->check(-1,'w');
         $this->getEmpty();
      }
      $options['colspan'] = 1;
      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'><td>".$LANG['common'][16]."</td>";
      echo "<td>";
      Html::autocompletionTextField($this,"name");
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'><td>".$LANG['common'][17]."</td>";
      echo "<td>";
      Dropdown::show('PluginBackupsScriptType',
                  array('value'  => $this->fields["plugin_backups_scripttypes_id"]));
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'><td>".$LANG['plugin_backups']['scripts'][3]."</td>";
      echo "<td>";
      Html::autocompletionTextField($this,"location_server");
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td>";
      echo $LANG['common'][25]."</td>";
      echo "<td class='left'><textarea cols='65' rows='4' name='comment' >".
               $this->fields["comment"]."</textarea>";

      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }
   
   
   static function dropdown($options=array()) {
      global $DB,$LANG,$CFG_GLPI;


      $p['name']   = 'plugin_backups_scripttypes_id';
      $p['entity'] = '';
      $p['used']   = array();

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $p[$key] = $val;
         }
      }

      $rand = mt_rand();

      $where = " WHERE `glpi_plugin_backups_scripts`.`is_deleted` = '0' ".
                       getEntitiesRestrictRequest("AND", "glpi_plugin_backups_scripts", '', $p['entity'], false);

      if (count($p['used'])) {
         $where .= " AND `id` NOT IN ('0','".implode("','",$p['used'])."')";
      }

      $query = "SELECT *
                FROM `glpi_plugin_backups_scripttypes`
                WHERE `id` IN (SELECT DISTINCT `plugin_backups_scripttypes_id`
                               FROM `glpi_plugin_backups_scripts`
                             $where)
                ORDER BY `name`";
      $result = $DB->query($query);

      echo "<select name='_plugin_backups_scripttypes_id' id='plugin_backups_scripttypes_id$rand'>";
      echo "<option value='0'>".Dropdown::EMPTY_VALUE."</option>";

      while ($data=$DB->fetch_assoc($result)) {
         echo "<option value='".$data['id']."'>".$data['name']."</option>";
      }
      echo "</select>";

      $params = array('plugin_backups_scripttypes_id' => '__VALUE__',
                      'entity' => $p['entity'],
                      'rand'   => $rand,
                      'myname' => $p['name'],
                      'used'   => $p['used']);

      Ajax::updateItemOnSelectEvent("plugin_backups_scripttypes_id$rand","show_".$p['name']."$rand",
                                    $CFG_GLPI["root_doc"]."/plugins/backups/ajax/dropdownScripts.php", $params);

      echo "<span id='show_".$p['name']."$rand'>";
      $_POST["entity"] = $p['entity'];
      $_POST["plugin_backups_scripttypes_id"] = 0;
      $_POST["myname"] = $p['name'];
      $_POST["rand"]   = $rand;
      $_POST["used"]   = $p['used'];
      include (GLPI_ROOT."/plugins/backups/ajax/dropdownScripts.php");
      echo "</span>\n";

      return $rand;
   }
}
?>
