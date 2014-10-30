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

class PluginBackupsLibrary_Tape extends CommonDBTM {
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      if ($item->getType()=='PluginBackupsTape') {
         if ($_SESSION['glpishow_count_on_tabs']) {
            return self::createTabEntry($LANG['plugin_backups']['title'][5], self::countForItem($item));
         }
         return $LANG['plugin_backups']['title'][5];

      } elseif ($item->getType()=='PluginBackupsLibrary' && !$withtemplate) {
         if ($_SESSION['glpishow_count_on_tabs']) {
            return self::createTabEntry($LANG['plugin_backups']['title'][2], self::countForTape($item->getID()));
         }
         return $LANG['plugin_backups']['tapes'][2];

      }
      return '';
   }


   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
   
      $self = new self();
      
      if ($item->getType()=='PluginBackupsTape') {
         
         $self->showTapesLibraries($item->getField('id'),$withtemplate);

      } elseif ($item->getType()=='PluginBackupsLibrary') {
         
         $self->showLibrariesTapes($item->getField('id'),$withtemplate);

      }
      
      
      return true;
   }


   static function countForItem(CommonDBTM $item) {

      return countElementsInTable('glpi_plugin_backups_libraries_tapes',
                                  "`tapes_id` = '".$item->getID()."'");
   }
   
   static function countForTape($id) {
      global $DB;

      $query = "SELECT COUNT(`glpi_plugin_backups_libraries_tapes`.`id`)
                FROM `glpi_plugin_backups_libraries_tapes`
                INNER JOIN `glpi_plugin_backups_tapes`
                      ON (`glpi_plugin_backups_libraries_tapes`.`tapes_id` = `glpi_plugin_backups_tapes`.`id`)
                WHERE `glpi_plugin_backups_libraries_tapes`.`libraries_id` = '$id'
                      AND `glpi_plugin_backups_tapes`.`is_deleted` = '0'
                      AND `glpi_plugin_backups_tapes`.`is_template` = '0' " .
                      getEntitiesRestrictRequest('AND', 'glpi_plugin_backups_tapes');

      $result = $DB->query($query);

      if ($DB->numrows($result) != 0) {
         return $DB->result($result, 0, 0);
      }
      return 0;
   }
   
   
   function showTapesLibraries($ID,$withtemplate='') {
      global $DB,$CFG_GLPI, $LANG;
    
      $rand=mt_rand();
      $tape = new PluginBackupsTape();
      $tape->getFromDB($ID);
      $canedit = $tape->can($ID,'w') && $withtemplate<2;
      
      Session::initNavigateListItems($this->getType(),$LANG['plugin_backups']['title'][11] ." = ". $tape->fields["name"]);
      
      $query = "SELECT `glpi_plugin_backups_libraries_tapes`.`id` AS `linkID`, `glpi_plugin_backups_libraries`.*";
      $query.= " FROM `glpi_plugin_backups_libraries`, `glpi_plugin_backups_libraries_tapes` 
               WHERE `glpi_plugin_backups_libraries_tapes`.`tapes_id` = '$ID'";
      $query.= " AND `glpi_plugin_backups_libraries_tapes`.`libraries_id` = `glpi_plugin_backups_libraries`.`id` 
               ORDER BY `glpi_plugin_backups_libraries`.`name`";

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
         echo "<form method='post' name='form$rand' id='form$rand' action=\"./tape.form.php\">";
      }
      echo "<div align='center'><table class='tab_cadre_fixe'>";
      
      echo "<tr><th colspan='".(4+$colsup)."'>".$LANG['plugin_backups']['libraries'][3]."</th></tr>";
      
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
            echo "<input type='hidden' name='tapes_id' value='$ID'>";
            echo "<input type='hidden' name='is_template' value='$withtemplate'>";
            Dropdown::show('PluginBackupsLibrary', array('name'   => "libraries_id",
                                                         'entity' => $tape->fields["entities_id"],
                                                         'used'   => $used));
            echo "</td><td align='center'>";
            echo "<input type='submit' name='addlibrarytape' value=\"".$LANG['buttons'][8]."\" class='submit'>";
            echo "</td>";
            echo "</tr>";

      }
      echo "</table></div>";
   
      if ($number && $canedit  && $withtemplate<2) {
         Html::openArrowMassives("form$rand", true);
         Html::closeArrowMassives(array('deletelibrarytape' => $LANG['buttons'][6]));
      }
      if ($canedit) {
         Html::closeForm();
      }
   }
   
 
   
   function showLibrariesTapes($ID,$withtemplate='') {
      global $DB,$CFG_GLPI, $LANG;
    
      $rand=mt_rand();
      $lib = new PluginBackupsLibrary();
      $lib->getFromDB($ID);
      $canedit = $lib->can($ID,'w');
      
      Session::initNavigateListItems($this->getType(),$LANG['plugin_backups']['title'][12] ." = ". $lib->fields["name"]);
      
      $query = "SELECT `glpi_plugin_backups_libraries_tapes`.`id` AS linkID, `glpi_plugin_backups_tapes`.*";
      $query.= " FROM `glpi_plugin_backups_tapes`, `glpi_plugin_backups_libraries_tapes` 
               WHERE `glpi_plugin_backups_libraries_tapes`.`libraries_id` = '$ID'";
      $query.= " AND `glpi_plugin_backups_libraries_tapes`.`tapes_id` = `glpi_plugin_backups_tapes`.`id` 
               AND `glpi_plugin_backups_tapes`.`is_template` = '0' 
               ORDER BY `glpi_plugin_backups_tapes`.`name` ";

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
         echo "<form method='post' name='form$rand' id='form$rand' action=\"./tape.form.php\">";
      }
      echo "<div align='center'><table class='tab_cadre_fixe'>";
      
      echo "<tr><th colspan='".(8+$colsup)."'>".$LANG['plugin_backups']['libraries'][13]."</th></tr>";
      
      echo "<tr>";
      if ($canedit) {
        echo "<th>&nbsp;</th>";
      }
      echo "<th>".$LANG['common'][16]."</th>";
      if (Session::isMultiEntitiesMode()) {
         echo "<th>".$LANG['entity'][0]."</th>";
      }
      echo "<th>".$LANG['common'][17]."</th>";
      echo "<th>".$LANG['plugin_backups']['tapes'][7]."</th>";
      echo "<th>".$LANG['plugin_backups']['tapes'][9]."</th>";
      echo "<th>".$LANG['plugin_backups']['tapes'][10]."</th>";
      echo "<th>".$LANG['plugin_backups']['tapes'][12]."</th>";
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
            echo "<a href='".$CFG_GLPI["root_doc"]."/plugins/backups/front/tape.form.php?id=".$data["id"]."'>".$data["name"];
            if ($_SESSION["glpiis_ids_visible"] || empty($data["name"])) echo " (".$data["id"].")";
            echo "</a></td>";
            if (Session::isMultiEntitiesMode()) {
               echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",$data['entities_id'])."</td>";
            }
            echo "<td>".Dropdown::getDropdownName("glpi_plugin_backups_tapetypes",$data["plugin_backups_tapetypes_id"])."</td>";
            echo "<td align='center'>".$data["capacity"]."</td>";
            echo "<td align='center'>".HTml::convdate($data["date_service"])."</td>";
            echo "<td>".Dropdown::getDropdownName("glpi_locations",$data["locations_id"])."</td>";
            echo "<td>".Dropdown::getDropdownName("glpi_manufacturers",$data["manufacturers_id"])."</td>";
            echo "<td>".nl2br($data["comment"])."</td>";
            echo "</tr>";

         }
      }   
      $q="SELECT * 
         FROM `glpi_plugin_backups_tapes` 
         WHERE `is_deleted` = '0' 
         AND `is_template` = '0'";
      $result = $DB->query($q);
      $nb = $DB->numrows($result);

      if ($withtemplate<2 && $nb>count($used) && $canedit) {

            echo "<tr class='tab_bg_1'><td colspan='".(7+$colsup)."' align='center'>";
            echo "<input type='hidden' name='libraries_id' value='$ID'>";
            echo "<input type='hidden' name='is_template' value='$withtemplate'>";
            $options = array('name' => "tapes_id",
                              'entity' => $lib->fields["entities_id"],
                              'used'   => $used);
            PluginBackupsTape::dropdown($options);
            echo "</td><td align='center'>";
            echo "<input type='submit' name='addlibrarytape' value=\"".$LANG['buttons'][8]."\" class='submit'>";
            echo "</td>";
            echo "</tr>";

      }
      echo "</table></div>";
   
      if ($number && $canedit  && $withtemplate<2) {
         Html::openArrowMassives("form$rand", true);
         Html::closeArrowMassives(array('deletelibrarytape' => $LANG['buttons'][6]));
      }
      if ($canedit) {
         Html::closeForm();
      }
   }
}
?>