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

class PluginBackupsHistory extends CommonDBTM {

   static function getTypeName($nb=0) {
      global $LANG;

      if ($nb>1) {
         return $LANG['plugin_backups']['title'][9];
      }
      return $LANG['plugin_backups']['title'][13];
   }
   
   static function canCreate() {
      return plugin_backups_haveRight('works', 'w');
   }

   static function canView() {
      return plugin_backups_haveRight('works', 'r');
   }
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      if (!$withtemplate) {
         if ($item->getType()=='PluginBackupsWork') {
            if ($_SESSION['glpishow_count_on_tabs']) {
               return self::createTabEntry($LANG['plugin_backups']['works'][24], self::countForItem($item));
            }
            return $LANG['plugin_backups']['works'][24];

         } else if ($item->getType()=='Central') {
            return $LANG['plugin_backups']['title'][0];

         }
      }
      return '';
   }
   
   
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
   
      $self = new self();
      $work_tape = new PluginBackupsWork_Tape();
      if ($item->getType()=='PluginBackupsWork') {
         
         $self->showWorksHistory($item->getField('id'),$withtemplate);
         
      } else if ($item->getType()=='Central') {
         
         $self->showCentral($item->getType());
         
      }
      
      
      return true;
   }


   static function countForItem(CommonDBTM $item) {

      return countElementsInTable('glpi_plugin_backups_histories',
                                  "`plugin_backups_works_id` = '".$item->getID()."'");
   }

   function getSearchOptions() {
      global $LANG;

      $tab = array();
      
      $tab['common']             = $LANG['plugin_backups']['title'][9];

      $tab[1]['table']           = $this->getTable();
      $tab[1]['field']           = 'name';
      $tab[1]['name']            = $LANG['common'][16];
      $tab[1]['datatype']        = 'itemlink';
      $tab[1]['itemlink_type']   = $this->getType();
      
      $tab[2]['table']           = 'glpi_plugin_backups_historystates';
      $tab[2]['field']           = 'name';
      $tab[2]['name']            = $LANG['state'][0];
      
      $tab[3]['table']           = 'glpi_plugin_backups_works';
      $tab[3]['field']           = 'name';
      $tab[3]['name']            = $LANG['plugin_backups']['history'][6];

      $tab[4]['table']           = $this->getTable();
      $tab[4]['field']           = 'date';
      $tab[4]['name']            = $LANG['common'][27];
      $tab[4]['datatype']        = 'date';

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
      $this->addStandardTab('Document', $ong, $options);
      return $ong;
   }
   

   static function title(){
      global $LANG;

      echo "<div align='center'><table border='0'><tr>";
      echo "<td><a class='icon_consol' href=\"./xml.history.php\">";
      echo "<b>".$LANG['plugin_backups']['title'][7]."</b></a></td>";
      echo "<td><a  class='icon_consol' href=\"./log.history.php\">";
      echo "<b>".$LANG['plugin_backups']['title'][6]."</b></a></td>";
      echo "</tr></table></div><br>";

   }
   
   function prepareInputForAdd($input) {

      if (isset($input['date']) && empty($input['date'])) 
         $input['date']='NULL';

      return $input;
   }
   

   function prepareInputForUpdate($input) {

      if (isset($input['date']) && empty($input['date'])) 
         $input['date']='NULL';

      return $input;
   }


   function showForm($ID, $options=array()) {
      global $CFG_GLPI, $LANG;

      if (!PluginBackupsHistory::canView()) return false;
      
      if ($ID > 0) {
         $this->check($ID,'r');
      } else {
         // Create item
         $this->check(-1,'w');
         $this->getEmpty();
      } 
      
      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'><td>".$LANG['common'][16]."</td>";
      echo "<td>";
      Html::autocompletionTextField($this,"name");
      echo "</td>";

      echo "<td>".$LANG['plugin_backups']['history'][6]."</td>";
      echo "<td colspan='2'>";
      if (empty($ID)) {
         $options = array('name' => "plugin_backups_works_id",
                              'entity' => $this->fields["entities_id"]);
            PluginBackupsWork::dropdown($options);
      } else {
         Dropdown::show('PluginBackupsWork',
                     array('value'  => $this->fields["plugin_backups_works_id"]));
      }
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'><td>".$LANG['state'][0]."</td>";
      echo "<td>";
      Dropdown::show('PluginBackupsHistoryState',
                  array('value'  => $this->fields["plugin_backups_historystates_id"]));
      echo "</td>";

      echo "<td>".$LANG['common'][27]."</td>";
      echo "<td>";
      Html::showDateFormItem("date",$this->fields["date"],true,true);
      echo "</td>";
      echo "</tr>";

      echo "<tr class='tab_bg_1'>";
      echo "<td colspan='3'>";
      echo $LANG['common'][25]."</td>";
      echo "<td class='left'><textarea cols='65' rows='4' name='comment' >".
               $this->fields["comment"]."</textarea>";

      echo "</td>";
      echo "</tr>";

      $this->showFormButtons($options);
      $this->addDivForTabs();

      return true;
   }
   
   static function showCentral($target) {
      global $DB,$CFG_GLPI, $LANG;

      $query = "SELECT `glpi_plugin_backups_histories`.*,`glpi_plugin_backups_works`.`id` AS `works_id`, `glpi_plugin_backups_works`.`name` AS `works_name`,`glpi_plugin_backups_works`.`entities_id` ";
      $query.= " FROM `glpi_plugin_backups_histories`, `glpi_plugin_backups_works`";
      $query.= " WHERE `glpi_plugin_backups_histories`.`plugin_backups_works_id` = `glpi_plugin_backups_works`.`id` 
               AND `glpi_plugin_backups_histories`.`is_deleted` = '0' 
               AND `glpi_plugin_backups_histories`.`entities_id` = '".$_SESSION["glpiactive_entity"]."'";
      $query.= " ORDER BY `glpi_plugin_backups_histories`.`date` DESC LIMIT 0,10";

      $result = $DB->query($query);
      $number = $DB->numrows($result);
      
      if (Session::isMultiEntitiesMode()) {
         $colsup=1;
      }else {
         $colsup=0;
      }
      
      if ($target == "Central") {
            echo "<table class='tab_cadre_central'><tr><td>";
      }      
      if ($DB->numrows($result)!=0) {
         
         echo "<div align='center'><table class='tab_cadre' width='100%'>";
         echo "<tr><th colspan='".(5+$colsup)."'>".$LANG['plugin_backups']['works'][28].":</th></tr>";
         echo "<tr>";
         if (Session::isMultiEntitiesMode())
            echo "<th>".$LANG['entity'][0]."</th>";
         echo "<th>".$LANG['common'][27]."</th>";
         echo "<th>".$LANG['state'][0]."</th>";
         echo "<th>".$LANG['common'][16]."</th>";
         echo "<th>".$LANG['plugin_backups']['title'][10]."</th>";
         echo "<th>".$LANG['common'][25]."</th>";
         echo "</tr>";

         while ($data=$DB->fetch_array($result)){

            echo "<tr class='tab_bg_1'>";
            if (Session::isMultiEntitiesMode())
               echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",$data['entities_id'])."</td>";
            echo "<td align='center'>".Html::convdate($data["date"])."</td>";
            echo "<td>".Dropdown::getDropdownName("glpi_plugin_backups_historystates",$data["plugin_backups_historystates_id"])."</td>";
            echo "<td><a href=\"".$CFG_GLPI["root_doc"]."/plugins/backups/front/history.form.php?id=".$data["id"]."\">".$data["name"]."</a></td>";
            echo "<td><a href=\"".$CFG_GLPI["root_doc"]."/plugins/backups/front/work.form.php?id=".$data["works_id"]."\">".$data["works_name"]."</a></td>";
            echo "<td>".$data["comment"]."</td>";

         }
         echo "</table></div>";
         
      }
      
      if ($target == "Central") {
         echo "</td></tr></table>";
      }
   }
   
   function showWorksHistory($ID, $withtemplate = '') {
      global $DB,$CFG_GLPI, $LANG;

      $query = "SELECT * 
            FROM `glpi_plugin_backups_histories`
            WHERE `plugin_backups_works_id` = '$ID' 
            AND `is_deleted` = '0' 
            ORDER BY `date`";
      $result = $DB->query($query);
      $number = $DB->numrows($result);

      echo "<div align='center'><table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='4'>".$LANG['plugin_backups']['works'][25].":</th></tr>";
      echo "<tr>";
      echo "<th>".$LANG['common'][16]."</th>";
      echo "<th>".$LANG['common'][27]."</th>";
      echo "<th>".$LANG['state'][0]."</th>";
      echo "<th>".$LANG['common'][25]."</th>";
      echo "</tr>";

      while ($data= $DB->fetch_array($result)) {

         echo "<tr class='tab_bg_1'>";
         echo "<td><a href='".$CFG_GLPI["root_doc"]."/plugins/backups/front/history.form.php?id=".$data["id"]."'>".$data["name"]."";
         if ($_SESSION["glpiis_ids_visible"] || empty($data["name"])) echo " (".$data["id"].")";
         echo "</a></td>";
         echo "<td align='center'>".Html::convDate($data["date"])."</td>";
         echo "<td align='center'>".Dropdown::getDropdownName("glpi_plugin_backups_historystates",$data["plugin_backups_historystates_id"])."</td>";
         echo "<td>".nl2br($data["comment"])."</td>";

      }

      echo "</table></div>";
   }


   static function url_exists($url){
      $handle = @fopen($url, 'r');
      if ($handle === false) {
         return false;
      }
      fclose($handle);
      return true;
   }
}
?>
