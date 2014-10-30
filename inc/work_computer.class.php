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

class PluginBackupsWork_Computer extends CommonDBTM {
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      if ($item->getType()=='PluginBackupsWork') {
         if ($_SESSION['glpishow_count_on_tabs']) {
            return self::createTabEntry($LANG['Menu'][0], self::countForItem($item, "works_id"));
         }
         return $LANG['Menu'][0];

      } elseif ($item->getType()=='Computer' && !$withtemplate) {
         if ($_SESSION['glpishow_count_on_tabs']) {
            return self::createTabEntry($LANG['plugin_backups']['works'][30], self::countForItem($item, "computers_id"));
         }
         return $LANG['plugin_backups']['works'][30];

      }
      return '';
   }
   
   
   static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
   
      $self = new self();
      $work_item = new PluginBackupsWork_Item();
      if ($item->getType()=='PluginBackupsWork') {
         
         $self->showWorksComputers($item->getField('id'),$withtemplate);

      } elseif ($item->getType()=='Computer') {
         
         $self->showComputersWorks($item->getField('id'),$withtemplate);
         $work_item->showPluginFromItems(get_class($item),$item->getField('id'));
      }
      
      
      return true;
   }

   static function countForItem(CommonDBTM $item, $target) {

      return countElementsInTable('glpi_plugin_backups_works_computers',
                                  "`".$target."` = '".$item->getID()."'");
   }

   
   function showWorksComputers($ID,$withtemplate='') {
      global $DB,$CFG_GLPI, $LANG;
    
      $rand=mt_rand();
      $work = new PluginBackupsWork();
      $work->getFromDB($ID);
      $canedit = $work->can($ID,'w') && $withtemplate<2;
      
      Session::initNavigateListItems($this->getType(),$LANG['plugin_backups']['title'][10] ." = ". $work->fields["name"]);
      
      $query = "SELECT `glpi_plugin_backups_works_computers`.`id` AS `linkID`,`glpi_plugin_backups_works_computers`.`list_selection`, `glpi_computers`.*";
      $query.= " FROM `glpi_computers`, `glpi_plugin_backups_works_computers` 
               WHERE `glpi_plugin_backups_works_computers`.`works_id` = '$ID'";
      $query.= " AND `glpi_plugin_backups_works_computers`.`computers_id` = `glpi_computers`.`id` 
               ORDER BY `glpi_computers`.`name`";

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
      
      echo "<tr><th colspan='".(4+$colsup)."'>".$LANG['plugin_backups']['works'][18]."</th></tr>";
      
      echo "<tr>";
      if ($canedit) {
        echo "<th>&nbsp;</th>";
      }
      echo "<th>".$LANG['common'][16]."</th>";
      if (Session::isMultiEntitiesMode()) {
         echo "<th>".$LANG['entity'][0]."</th>";
      }
      echo "<th>".$LANG['plugin_backups']['works'][5]."</th>";

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
            echo "<a href='".$CFG_GLPI["root_doc"]."/front/computer.form.php?id=".$data["id"]."'>".$data["name"];
            if ($_SESSION["glpiis_ids_visible"] || empty($data["name"])) echo " (".$data["id"].")";
            echo "</a></td>";
            if (Session::isMultiEntitiesMode()) {
               echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",$data['entities_id'])."</td>";
            }
            echo "<td>".nl2br($data["list_selection"])."</td>";
            echo "</tr>";

         }
      }   
      $q="SELECT * 
         FROM `glpi_computers` 
         WHERE `is_deleted` = '0' 
         AND `is_template` = '0'";
      $result = $DB->query($q);
      $nb = $DB->numrows($result);

      if ($withtemplate<2 && $nb>count($used) && $canedit) {

            echo "<tr class='tab_bg_1'><td colspan='".(3+$colsup)."' align='center'>";
            echo "<input type='hidden' name='works_id' value='$ID'>";
            echo "<input type='hidden' name='is_template' value='$withtemplate'>";
            Dropdown::show('Computer', array('name'   => "computers_id",
                                                         'entity' => $work->fields["entities_id"],
                                                         'used'   => $used));
            echo "</td></tr>";
            echo "<tr class='tab_bg_1'><td valign='top'>".$LANG['plugin_backups']['works'][5]."</td>";
            echo "<td align='left' colspan='3'><textarea cols='45' rows='4' name='list_selection' ></textarea></td>";
            echo "</tr>";
            echo "<tr class='tab_bg_2'><td colspan='4' align='center'><input type='submit' name='addworkcomputer' value=\"".$LANG['buttons'][8]."\" class='submit'>";
            echo "</td>";
            echo "</tr>";

      }
      echo "</table></div>";
   
      if ($number && $canedit  && $withtemplate<2) {
         Html::openArrowMassives("form$rand", true);
         Html::closeArrowMassives(array('deleteworkcomputer' => $LANG['buttons'][6]));
      }
      if ($canedit) {
         Html::closeForm();
      }
   }
   
   
   function showComputersWorks($ID,$withtemplate='') {
      global $DB,$CFG_GLPI, $LANG;
    
      $rand=mt_rand();
      $comp = new Computer();
      $comp->getFromDB($ID);
      $canedit = $comp->can($ID,'w');
      
      Session::initNavigateListItems($this->getType(),$LANG['help'][25] ." = ". $comp->fields["name"]);
      
      $query = "SELECT `glpi_plugin_backups_works_computers`.`id` AS `linkID`, `glpi_plugin_backups_works`.*";
      $query.= " FROM `glpi_plugin_backups_works`, `glpi_plugin_backups_works_computers` 
               WHERE `glpi_plugin_backups_works_computers`.`computers_id` = '$ID'";
      $query.= " AND `glpi_plugin_backups_works_computers`.`works_id` = `glpi_plugin_backups_works`.`id` 
               AND `glpi_plugin_backups_works`.`is_template` = '0' 
               ORDER BY `glpi_plugin_backups_works`.`name` ";

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
         echo "<form method='post' name='form$rand' id='form$rand' action='".$CFG_GLPI["root_doc"]."/plugins/backups/front/work.form.php'>";
      }
      echo "<div align='center'><table class='tab_cadre_fixe'>";
      
      echo "<tr><th colspan='".(5+$colsup)."'>".$LANG['plugin_backups']['works'][30]."</th></tr>";
      
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
            echo "<input type='hidden' name='computers_id' value='$ID'>";
            echo "<input type='hidden' name='is_template' value='$withtemplate'>";
            $options = array('name' => "works_id",
                              'entity' => $comp->fields["entities_id"],
                              'used'   => $used);
            PluginBackupsWork::dropdown($options);
            echo "</td><td align='center'>";
            echo "<input type='submit' name='addworkcomputer' value=\"".$LANG['buttons'][8]."\" class='submit'>";
            echo "</td>";
            echo "</tr>";

      }
      echo "</table></div>";
   
      if ($number && $canedit  && $withtemplate<2) {
         Html::openArrowMassives("form$rand", true);
         Html::closeArrowMassives(array('deleteworkcomputer' => $LANG['buttons'][6]));
      }
      if ($canedit) {
         Html::closeForm();
      }
   }
}
?>