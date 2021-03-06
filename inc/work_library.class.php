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

class PluginBackupsWork_Library extends CommonDBTM {
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      if ($item->getType()=='PluginBackupsWork') {
         if ($_SESSION['glpishow_count_on_tabs']) {

            $libs = self::countForLibrary($item->getID(), $withtemplate);
            $tapes = PluginBackupsWork_Tape::countForTape($item->getID(), $withtemplate);
            $count = $libs + $tapes;
            return self::createTabEntry($LANG['plugin_backups']['works'][19], $count);
         }
         return $LANG['plugin_backups']['works'][19];

      } elseif ($item->getType()=='PluginBackupsLibrary' && !$withtemplate) {
         if ($_SESSION['glpishow_count_on_tabs']) {
            return self::createTabEntry($LANG['plugin_backups']['title'][1], self::countForWork($item->getID(), $withtemplate));
         }
         return $LANG['plugin_backups']['title'][1];

      }
      return '';
   }
   
   
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
   
      $self = new self();
      $work_tape = new PluginBackupsWork_Tape();
      if ($item->getType()=='PluginBackupsWork') {
         
         $self->showWorksLibraries($item->getField('id'),$withtemplate);
         $work_tape->showWorksTapes($item->getField('id'),$withtemplate, "work");
         
      } elseif ($item->getType()=='PluginBackupsLibrary') {
         
         $self->showLibrariesWorks($item->getField('id'),$withtemplate);

      }
      
      
      return true;
   }
   

   static function countForWork($id, $withtemplate) {
      global $DB;

      $query = "SELECT COUNT(`glpi_plugin_backups_works_libraries`.`id`)
                FROM `glpi_plugin_backups_works_libraries`
                INNER JOIN `glpi_plugin_backups_works`
                      ON (`glpi_plugin_backups_works_libraries`.`works_id` = `glpi_plugin_backups_works`.`id`)
                WHERE `glpi_plugin_backups_works_libraries`.`libraries_id` = '$id'
                      AND `glpi_plugin_backups_works`.`is_deleted` = '0' " .
                      getEntitiesRestrictRequest('AND', 'glpi_plugin_backups_works');
      $query.= " AND `glpi_plugin_backups_works`.`is_template` = '0' ";
      $result = $DB->query($query);

      if ($DB->numrows($result) != 0) {
         return $DB->result($result, 0, 0);
      }
      return 0;
   }


   static function countForLibrary($id, $withtemplate) {
      global $DB;

      $query = "SELECT COUNT(`glpi_plugin_backups_works_libraries`.`id`)
                FROM `glpi_plugin_backups_works_libraries`
                INNER JOIN `glpi_plugin_backups_works`
                      ON (`glpi_plugin_backups_works_libraries`.`works_id` = `glpi_plugin_backups_works`.`id`)
                WHERE `glpi_plugin_backups_works_libraries`.`works_id` = '$id'
                      AND `glpi_plugin_backups_works`.`is_deleted` = '0' " .
                      getEntitiesRestrictRequest('AND', 'glpi_plugin_backups_works');

      if ($withtemplate == 0) {
         $query.= " AND `glpi_plugin_backups_works`.`is_template` = '0' ";
      }
      $result = $DB->query($query);

      if ($DB->numrows($result) != 0) {
         return $DB->result($result, 0, 0);
      }
      return 0;
   }
   
   
   function showWorksLibraries($ID,$withtemplate='') {
      global $DB,$CFG_GLPI, $LANG;
    
      $rand=mt_rand();
      $work = new PluginBackupsWork();
      $work->getFromDB($ID);
      $canedit = $work->can($ID,'w') && $withtemplate<2;
      
      Session::initNavigateListItems($this->getType(),$LANG['plugin_backups']['title'][10] ." = ". $work->fields["name"]);
      
      $query = "SELECT `glpi_plugin_backups_works_libraries`.`id` AS `linkID`, `glpi_plugin_backups_libraries`.*";
      $query.= " FROM `glpi_plugin_backups_libraries`, `glpi_plugin_backups_works_libraries` 
               WHERE `glpi_plugin_backups_works_libraries`.`works_id` = '$ID' ";
      $query.= " AND `glpi_plugin_backups_works_libraries`.`libraries_id` = `glpi_plugin_backups_libraries`.`id` 
               AND `glpi_plugin_backups_libraries`.`is_deleted` = '0' 
               ORDER BY `glpi_plugin_backups_libraries`.`name` ";

      $result = $DB->query($query);
      $number = $DB->numrows($result);
      
      if (Session::isMultiEntitiesMode()) {
         $colsup=1;
      }else {
         $colsup=0;
      }
      
      $i = 0;
      $row_num=1;
      
      if ($canedit) {
         echo "<form method='post' name='form_lib$rand' id='form_lib$rand' action=\"./work.form.php\">";
      }
      echo "<div align='center'><table class='tab_cadre_fixe'>";
      
      echo "<tr><th colspan='".(4+$colsup)."'>".$LANG['plugin_backups']['works'][10]."</th></tr>";
      
      echo "<tr>";
      if ($canedit) {
        echo "<th>&nbsp;</th>";
      }
      echo "<th>".$LANG['common'][16]."</th>";
      if (Session::isMultiEntitiesMode()) {
         echo "<th>".$LANG['entity'][0]."</th>";
      }
      echo "<th>".$LANG['common'][25]."</th>";

      echo "</tr>";
      
      $used = array();
      if ($number !="0") {
         while ($data=$DB->fetch_array($result)) {
            
            Session::addToNavigateListItems($this->getType(),$data['id']);
            
            $i++;
            $row_num++;
            
            $used[]=$data["id"];
            echo "<tr class='tab_bg_1 center'>";
            
            if ($canedit) {
               echo "<td width='10'>";
               echo "<input type='checkbox' name='check[" . $data["linkID"] . "]'";
               if (isset($_POST['check']) && $_POST['check'] == 'all')
                  echo " checked ";
               echo ">";
               echo "</td>";
            }

            echo "<td class='center'>";
            echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/backups/front/library.form.php?id=".$data["id"]."'>".$data["name"];
            if ($_SESSION["glpiis_ids_visible"] || empty($data["name"])) echo " (".$data["id"].")";
            echo "</a></td>";
            if (Session::isMultiEntitiesMode()) {
               echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",$data['entities_id'])."</td>";
            }
            echo "<td class='center'>".nl2br($data["comment"])."</td>";
            echo "</tr>";

         }
      }   
      $q="SELECT * 
      FROM `glpi_plugin_backups_libraries` 
      WHERE `is_deleted` = '0'";
      $result = $DB->query($q);
      $nb = $DB->numrows($result);

      if ($withtemplate<2 && $nb>count($used) && $canedit) {

            echo "<tr class='tab_bg_1'><td colspan='".(2+$colsup)."' align='center'>";
            echo "<input type='hidden' name='works_id' value='$ID'>";
            echo "<input type='hidden' name='is_template' value='$withtemplate'>";
            Dropdown::show('PluginBackupsLibrary', array('name'   => "libraries_id",
                                                         'entity' => $work->fields["entities_id"],
                                                         'used'   => $used));
            echo "</td><td align='center'>";
            echo "<input type='submit' name='addworklibrary' value=\"".$LANG['buttons'][8]."\" class='submit'>";
            echo "</td>";
            echo "</tr>";

      }
      echo "</table></div>";
   
      if ($number && $canedit  && $withtemplate<2) {
         Html::openArrowMassives("form_lib$rand", true);
         Html::closeArrowMassives(array('deleteworklibrary' => $LANG['buttons'][6]));
      }
      if ($canedit) {
         Html::closeForm();
      }
   }
   
   
   function showLibrariesWorks($ID,$withtemplate='') {
      global $DB,$CFG_GLPI, $LANG;
    
      $rand=mt_rand();
      $lib = new PluginBackupsLibrary();
      $lib->getFromDB($ID);
      $canedit = $lib->can($ID,'w');
      
      Session::initNavigateListItems($this->getType(),$LANG['plugin_backups']['title'][12] ." = ". $lib->fields["name"]);
      
      $query = "SELECT `glpi_plugin_backups_works_libraries`.`id` AS `linkID`, `glpi_plugin_backups_works`.*";
      $query.= " FROM `glpi_plugin_backups_works`, `glpi_plugin_backups_works_libraries` 
               WHERE `glpi_plugin_backups_works_libraries`.`libraries_id` = '$ID'";
      $query.= " AND `glpi_plugin_backups_works_libraries`.`works_id` = `glpi_plugin_backups_works`.`id` 
               AND `glpi_plugin_backups_works`.`is_template` = '0' 
               ORDER BY `glpi_plugin_backups_works`.`name`";

      $result = $DB->query($query);
      $number = $DB->numrows($result);
      
      if (Session::isMultiEntitiesMode()) {
         $colsup=1;
      }else {
         $colsup=0;
      }
      
      $i = 0;
      $row_num=1;
      
      if ($canedit) {
         echo "<form method='post' name='form$rand' id='form$rand' action=\"./work.form.php\">";
      }
      echo "<div align='center'><table class='tab_cadre_fixe'>";
      
      echo "<tr><th colspan='".(5+$colsup)."'>".$LANG['plugin_backups']['tapes'][3]."</th></tr>";
      
      echo "<tr>";
      if ($canedit) {
        echo "<th>&nbsp;</th>";
      }
      echo "<th>".$LANG['common'][16]."</th>";
      if (Session::isMultiEntitiesMode()) {
         echo "<th>".$LANG['entity'][0]."</th>";
      }
      echo "<th>".$LANG['common'][17]."</th>";
      echo "<th>".$LANG['plugin_backups']['works'][9]."</th>";
      echo "<th>".$LANG['common'][25]."</th>";

      echo "</tr>";
      
      $used = array();
      if ($number !="0") {
         while ($data=$DB->fetch_array($result)) {
            
            Session::addToNavigateListItems($this->getType(),$data['id']);
            
            $i++;
            $row_num++;
            
            $used[]=$data["id"];
            echo "<tr class='tab_bg_1 center'>";
            
            if ($canedit) {
               echo "<td width='10'>";
               echo "<input type='checkbox' name='check[" . $data["linkID"] . "]'";
               if (isset($_POST['check']) && $_POST['check'] == 'all')
                  echo " checked ";
               echo ">";
               echo "</td>";
            }

            echo "<td class='center'>";
            echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/backups/front/work.form.php?id=".$data["id"]."'>".$data["name"];
            if ($_SESSION["glpiis_ids_visible"] || empty($data["name"])) echo " (".$data["id"].")";
            echo "</a></td>";
            if (Session::isMultiEntitiesMode()) {
               echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",$data['entities_id'])."</td>";
            }
            echo "<td>".Dropdown::getDropdownName("glpi_plugin_backups_worktypes",$data["plugin_backups_worktypes_id"])."</td>";
         echo "<td>".Dropdown::getDropdownName("glpi_plugin_backups_workperiodicities",$data["plugin_backups_workperiodicities_id"])."</td>";
         echo "<td>".nl2br($data["comment"])."</td>";
            echo "</tr>";

         }
      }   
      $q="SELECT * 
         FROM `glpi_plugin_backups_works` 
         WHERE `is_deleted` = '0' 
         AND `is_template` = '0'";
      $result = $DB->query($q);
      $nb = $DB->numrows($result);

      if ($withtemplate<2 && $nb>count($used) && $canedit) {

            echo "<tr class='tab_bg_1'><td colspan='".(4+$colsup)."' align='center'>";
            echo "<input type='hidden' name='libraries_id' value='$ID'>";
            echo "<input type='hidden' name='is_template' value='$withtemplate'>";
            $options = array('name' => "works_id",
                              'entity' => $lib->fields["entities_id"],
                              'used'   => $used);
            PluginBackupsWork::dropdown($options);
            echo "</td><td align='center'>";
            echo "<input type='submit' name='addworklibrary' value=\"".$LANG['buttons'][8]."\" class='submit'>";
            echo "</td>";
            echo "</tr>";

      }
      echo "</table></div>";
   
      if ($number && $canedit  && $withtemplate<2) {
         Html::openArrowMassives("form$rand", true);
         Html::closeArrowMassives(array('deleteworklibrary' => $LANG['buttons'][6]));
      }
      if ($canedit) {
         Html::closeForm();
      }
   }
}
?>