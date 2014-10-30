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

class PluginBackupsWork extends CommonDBTM {
   
   public $dohistory=true;
   
   #static $types = array('Computer','Peripheral','Software');
   static $types = array('Peripheral','Software');

   static function getTypeName($nb=0) {
      global $LANG;

      if ($nb>1) {
         return $LANG['plugin_backups']['title'][1];
      }
      return $LANG['plugin_backups']['title'][10];
   }
   
   static function canCreate() {
      return plugin_backups_haveRight('works', 'w');
   }
   

   static function canView() {
      return plugin_backups_haveRight('works', 'r');
   }
   
   
   function prepareInputForAdd($input) {

      if (isset($input["id"]) && $input["id"]>0) {
         $input["_oldID"]=$input["id"];
      }
      
      unset($input['id']);
      
      return $input;
   }
   
   
   function post_addItem() {
      
      // Manage add from template
      if (isset($this->input["_oldID"])) {
         // ADD libraries
         $work_lib = new PluginBackupsWork_Library();
         $restrict = "`works_id` = '".$this->input["_oldID"]."'";
         $libs = getAllDatasFromTable("glpi_plugin_backups_works_libraries",$restrict);
         if (!empty($libs)) {
            foreach ($libs as $lib) {
               $work_lib->add(array('libraries_id' => $lib["libraries_id"],
                                     'works_id'     => $this->fields['id']));
            }
         }

         // ADD tapes
         $work_tape = new PluginBackupsWork_Tape();
         $restrict = "`works_id` = '".$this->input["_oldID"]."'";
         $tapes = getAllDatasFromTable("glpi_plugin_backups_works_tapes",$restrict);
         if (!empty($tapes)) {
            foreach ($tapes as $tape) {
               $work_tape->add(array('tapes_id' => $tape["tapes_id"],
                                      'works_id' => $this->fields['id']));
            }
         }
         
         // ADD script
         $work_script = new PluginBackupsWork_Script();
         $restrict = "`works_id` = '".$this->input["_oldID"]."'";
         $scripts = getAllDatasFromTable("glpi_plugin_backups_works_scripts",$restrict);
         if (!empty($scripts)) {
            foreach ($scripts as $script) {
               $work_script->add(array('scripts_id'   => $script["scripts_id"],
                                       'works_id'     => $this->fields['id']));
            }
         }
         
         // ADD computer
         $work_computer = new PluginBackupsWork_Computer();
         $restrict = "`works_id` = '".$this->input["_oldID"]."'";
         $comps = getAllDatasFromTable("glpi_plugin_backups_works_computers",$restrict);
         if (!empty($comps)) {
            foreach ($comps as $comp) {
               $work_computer->add(array('computers_id'     => $comp["computers_id"],
                                          'list_selection'  => $comp["list_selection"],
                                          'works_id'        => $this->fields['id']));
            }
         }
         
         //ADD item
         $work_item = new PluginBackupsWork_Item();
         $restrict = "`works_id` = '".$this->input["_oldID"]."'";
         $items = getAllDatasFromTable("glpi_plugin_backups_works_items",$restrict);
         if (!empty($items)) {
            foreach ($items as $item) {
               $work_item->add(array('works_id' => $this->fields['id'],
                                    'itemtype' => $item["itemtype"],
                                    'items_id' => $item["items_id"]));
            }
         }
      }
   }


   function getSearchOptions() {
      global $LANG;

      $tab = array();
      
      $tab['common']             = $LANG['plugin_backups']['title'][1];

      $tab[1]['table']           = $this->getTable();
      $tab[1]['field']           = 'name';
      $tab[1]['name']            = $LANG['common'][16];
      $tab[1]['datatype']        = 'itemlink';
      $tab[1]['itemlink_type']   = $this->getType();
      
      $tab[2]['table']           = 'glpi_plugin_backups_worktypes';
      $tab[2]['field']           = 'name';
      $tab[2]['name']            = $LANG['common'][17];
      
      $tab[3]['table']           = 'glpi_plugin_backups_workperiodicities';
      $tab[3]['field']           = 'name';
      $tab[3]['name']            = $LANG['plugin_backups']['works'][9];

      $tab[4]['table']           = $this->getTable();
      $tab[4]['field']           = 'comment';
      $tab[4]['name']            = $LANG['common'][25];
      $tab[4]['datatype']        = 'text';
   
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
      $this->addStandardTab('PluginBackupsWork_Item', $ong, $options);
      $this->addStandardTab('PluginBackupsWork_Library', $ong, $options);
      $this->addStandardTab('PluginBackupsWork_Computer', $ong, $options);
      $this->addStandardTab('PluginBackupsWork_Script', $ong, $options);
      if (!isset($options['withtemplate']) 
            || empty($options['withtemplate'])) {
         $this->addStandardTab('PluginBackupsHistory', $ong, $options);
      }
      $this->addStandardTab('Note', $ong, $options);
      $this->addStandardTab('Log', $ong, $options);

      return $ong;
   }
   
   
   function cleanDBonPurge() {

      $temp = new PluginBackupsWork_Library();
      $temp->deleteByCriteria(array('works_id' => $this->fields['id']));
      
      $temp = new PluginBackupsWork_Script();
      $temp->deleteByCriteria(array('works_id' => $this->fields['id']));
      
      $temp = new PluginBackupsWork_Tape();
      $temp->deleteByCriteria(array('works_id' => $this->fields['id']));
      
      $temp = new PluginBackupsWork_Computer();
      $temp->deleteByCriteria(array('works_id' => $this->fields['id']));
      
      $temp = new PluginBackupsHistory();
      $temp->deleteByCriteria(array('plugin_backups_works_id' => $this->fields['id']));
      
      $temp = new PluginBackupsWork_Item();
      $temp->deleteByCriteria(array('works_id' => $this->fields['id']));
   }


   function showForm($ID, $options=array()) {
      global $CFG_GLPI, $LANG;

      if (!$this->canView()) return false;
      
      if ($ID > 0) {
         $this->check($ID,'r');
      } else {
         // Create item
         $this->check(-1,'w');
         $this->getEmpty();
      }

      if (isset($options['withtemplate']) && $options['withtemplate'] == 2) {
         $template = "newcomp";
         $datestring = $LANG['computers'][14]." : ";
         $date = Html::convDateTime($_SESSION["glpi_currenttime"]);
      } else if (isset($options['withtemplate']) && $options['withtemplate'] == 1) {
         $template = "newtemplate";
         $datestring = $LANG['computers'][14]." : ";
         $date = Html::convDateTime($_SESSION["glpi_currenttime"]);
      } else {
         $datestring = $LANG['common'][26].": ";
         $date = Html::convDateTime($this->fields["date_mod"]);
         $template = false;
      }
    
      $this->showTabs($options);
      $this->showFormHeader($options);

      echo "<tr class='tab_bg_1'><td>".$LANG['common'][16]."</td>";
      echo "<td>";
      Html::autocompletionTextField($this,"name");
      echo "</td>";

      echo "<td>".$LANG['common'][17]."</td><td>";
      Dropdown::show('PluginBackupsWorkType',
                  array('value'  => $this->fields["plugin_backups_worktypes_id"]));
      echo "</td></tr>";

      echo "<tr class='tab_bg_1'><td>".$LANG['plugin_backups']['works'][9]."</td>";
      echo "<td>";
      Dropdown::show('PluginBackupsWorkPeriodicity',
                  array('value'  => $this->fields["plugin_backups_workperiodicities_id"]));
      echo "</td>";
      echo "<td colspan='2'>";
      $datestring = $LANG['common'][26].": ";
      $date = Html::convDateTime($this->fields["date_mod"]);
      echo $datestring.$date."</td>";
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
   
   /**
    * For other plugins, add a type to the linkable types
    *
    * @since version 1.5.0
    *
    * @param $type string class name
   **/
   static function registerType($type) {
      if (!in_array($type, self::$types)) {
         self::$types[] = $type;
      }
   }


   /**
    * Type than could be linked to a work
    *
    * @param $all boolean, all type, or only allowed ones
    *
    * @return array of types
   **/
   static function getTypes($all=false) {

      if ($all) {
         return self::$types;
      }

      // Only allowed types
      $types = self::$types;

      foreach ($types as $key => $type) {
         if (!class_exists($type)) {
            continue;
         }

         $item = new $type();
         if (!$item->canView()) {
            unset($types[$key]);
         }
      }
      return $types;
   }
   
   
   function listOfTemplates($target,$add=0) {
      global $LANG;
      
      $restrict = "`is_template` = '1'";
      $restrict.=getEntitiesRestrictRequest(" AND ",$this->getTable(),'','',$this->maybeRecursive());
      $restrict.=" ORDER BY `name`";
      $templates = getAllDatasFromTable($this->getTable(),$restrict);
      
      if (Session::isMultiEntitiesMode()) {
         $colsup=1;
      } else {
         $colsup=0;
      }
         
      echo "<div align='center'><table class='tab_cadre' width='50%'>";
      if ($add) {
         echo "<tr><th colspan='".(2+$colsup)."'>".$LANG['common'][7]." - ".$LANG['plugin_backups']['title'][1].":</th>";
      } else {
         echo "<tr><th colspan='".(2+$colsup)."'>".$LANG['common'][14]." - ".$LANG['plugin_backups']['title'][1]." :</th>";
      }
      
      echo "</tr>";
      if ($add) {

         echo "<tr>";
         echo "<td colspan='".(2+$colsup)."' class='center tab_bg_1'>";
         echo "<a href=\"$target?id=-1&amp;withtemplate=2\">&nbsp;&nbsp;&nbsp;" . $LANG['common'][31] . "&nbsp;&nbsp;&nbsp;</a></td>";
         echo "</tr>";
      }
      
      foreach ($templates as $template) {

         $templname = $template["template_name"];
         if ($_SESSION["glpiis_ids_visible"]||empty($template["template_name"]))
         $templname.= "(".$template["id"].")";

         echo "<tr>";
         echo "<td class='center tab_bg_1'>";
         if (!$add) {
            echo "<a href=\"$target?id=".$template["id"]."&amp;withtemplate=1\">&nbsp;&nbsp;&nbsp;$templname&nbsp;&nbsp;&nbsp;</a></td>";
            
            if (Session::isMultiEntitiesMode()) {
               echo "<td class='center tab_bg_2'>";
               echo Dropdown::getDropdownName("glpi_entities",$template['entities_id']);
               echo "</td>";
            }
            echo "<td class='center tab_bg_2'>";
            Html::showSimpleForm($target,
                                    'purge',
                                    $LANG['buttons'][6],
                                    array('id' => $template["id"],'withtemplate'=>1));

            echo "</td>";
            
         } else {
            echo "<a href=\"$target?id=".$template["id"]."&amp;withtemplate=2\">&nbsp;&nbsp;&nbsp;$templname&nbsp;&nbsp;&nbsp;</a></td>";
            
            if (Session::isMultiEntitiesMode()) {
               echo "<td class='center tab_bg_2'>";
               echo Dropdown::getDropdownName("glpi_entities",$template['entities_id']);
               echo "</td>";
            }
         }
         echo "</tr>";
      }
      if (!$add) {
         echo "<tr>";
         echo "<td colspan='".(2+$colsup)."' class='tab_bg_2 center'>";
         echo "<b><a href=\"$target?withtemplate=1\">".$LANG['common'][9]."</a></b>";
         echo "</td>";
         echo "</tr>";
      }
      echo "</table></div>";
   }

   
   static function dropdown($options=array()) {
      global $DB,$LANG,$CFG_GLPI;


      $p['name']   = 'plugin_backups_worktypes_id';
      $p['entity'] = '';
      $p['used']   = array();

      if (is_array($options) && count($options)) {
         foreach ($options as $key => $val) {
            $p[$key] = $val;
         }
      }

      $rand = mt_rand();

      $where = " WHERE `glpi_plugin_backups_works`.`is_deleted` = '0' ".
                       getEntitiesRestrictRequest("AND", "glpi_plugin_backups_works", '', $p['entity'], false);

      if (count($p['used'])) {
         $where .= " AND `id` NOT IN ('0','".implode("','",$p['used'])."')";
      }

      $query = "SELECT *
                FROM `glpi_plugin_backups_worktypes`
                WHERE `id` IN (SELECT DISTINCT `plugin_backups_worktypes_id`
                               FROM `glpi_plugin_backups_works`
                             $where)
                ORDER BY `name`";
      $result = $DB->query($query);

      echo "<select name='_plugin_backups_worktypes_id' id='plugin_backups_worktypes_id$rand'>";
      echo "<option value='0'>".Dropdown::EMPTY_VALUE."</option>";

      while ($data=$DB->fetch_assoc($result)) {
         echo "<option value='".$data['id']."'>".$data['name']."</option>";
      }
      echo "</select>";

      $params = array('plugin_backups_worktypes_id' => '__VALUE__',
                      'entity' => $p['entity'],
                      'rand'   => $rand,
                      'myname' => $p['name'],
                      'used'   => $p['used']);

      Ajax::updateItemOnSelectEvent("plugin_backups_worktypes_id$rand","show_".$p['name']."$rand",
                                    $CFG_GLPI["root_doc"]."/plugins/backups/ajax/dropdownWorks.php", $params);

      echo "<span id='show_".$p['name']."$rand'>";
      $_POST["entity"] = $p['entity'];
      $_POST["plugin_backups_worktypes_id"] = 0;
      $_POST["myname"] = $p['name'];
      $_POST["rand"]   = $rand;
      $_POST["used"]   = $p['used'];
      include (GLPI_ROOT."/plugins/backups/ajax/dropdownWorks.php");
      echo "</span>\n";

      return $rand;
   }
}
?>
