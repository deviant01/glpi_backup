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

class PluginBackupsLibrary extends CommonDBTM {

   public $dohistory=true;
   
   static function getTypeName($nb=0) {
      global $LANG;

      if ($nb>1) {
         return $LANG['plugin_backups']['title'][5];
      }
      return $LANG['plugin_backups']['title'][12];
   }
   
   static function canCreate() {
      return plugin_backups_haveRight('libraries', 'w');
   }

   static function canView() {
      return plugin_backups_haveRight('libraries', 'r');
   }

   function getSearchOptions() {
      global $LANG;

      $tab = array();
      
      $tab['common']             = $LANG['plugin_backups']['title'][5];

      $tab[1]['table']           = $this->getTable();
      $tab[1]['field']           = 'name';
      $tab[1]['name']            = $LANG['common'][16];
      $tab[1]['datatype']        = 'itemlink';
      $tab[1]['itemlink_type']   = $this->getType();

      $tab[5]['table']           = $this->getTable();
      $tab[5]['field']           = 'comment';
      $tab[5]['name']            = $LANG['common'][25];
      $tab[5]['datatype']        = 'text';

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
      $this->addStandardTab('PluginBackupsLibrary_Tape', $ong, $options);
      $this->addStandardTab('PluginBackupsWork_Library', $ong, $options);
      $this->addStandardTab('Note', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);

      return $ong;
   }
   
   
   function cleanDBonPurge() {

      $temp = new PluginBackupsWork_Library();
      $temp->deleteByCriteria(array('libraries_id' => $this->fields['id']));

   }


   function showForm($ID, $options=array()) {
      global $CFG_GLPI, $LANG;

      if (!PluginBackupsLibrary::canView()) return false;
      
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

      echo "<tr class='tab_bg_1'><td>";
      echo $LANG['common'][25]."</td>";
      echo "<td align='left'><textarea cols='75' rows='4' name='comment' >".$this->fields["comment"]."</textarea>";
      echo "</td>";
      echo "</tr>";
      
      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }
}
?>
