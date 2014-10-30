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

class PluginBackupsWork_Item extends CommonDBTM {

   // From CommonDBRelation
   public $itemtype_1 = "PluginBackupsWork";
   public $items_id_1 = 'plugin_backups_works_id';

   public $itemtype_2 = 'itemtype';
   public $items_id_2 = 'items_id';
   
   static function canCreate() {
      return plugin_backups_haveRight('works', 'w');
   }

   static function canView() {
      return plugin_backups_haveRight('works', 'r');
   }
   
   /**
    * Hook called After an item is uninstall or purge
    */
   static function cleanForItem(CommonDBTM $item) {

      $temp = new self();
      $temp->deleteByCriteria(
         array('itemtype' => $item->getType(),
               'items_id' => $item->getField('id'))
      );
      
      if ($item->getType() == "Computer") {
         $temp = new PluginBackupsWork_Computer();
         $temp->deleteByCriteria(array('computers_id' => $item->getField('id')));
      }
   }
   
   function getTabNameForItem(CommonGLPI $item, $withtemplate=0) {
      global $LANG;

      if ($item->getType()=='PluginBackupsWork'
          && count(PluginBackupsWork::getTypes(false))) {
         if ($_SESSION['glpishow_count_on_tabs']) {
            return self::createTabEntry($LANG['document'][19], self::countForWork($item));
         }
         return $LANG['document'][19];

      } else if ((in_array($item->getType(), PluginBackupsWork::getTypes(true)) && !$withtemplate)
                 && plugin_backups_haveRight('works', 'r')) {
         if ($_SESSION['glpishow_count_on_tabs']) {
            return self::createTabEntry(PluginBackupsWork::getTypeName(2), self::countForItem($item));
         }
         return PluginBackupsWork::getTypeName(2);
      }
      return '';
   }
   
    static function displayTabContentForItem(CommonGLPI $item, $tabnum=1, $withtemplate=0) {
   
      $self = new self();
      
      if ($item->getType()=='PluginBackupsWork') {
         
         $self->showItemFromPlugin($item->getID(), $withtemplate);

      } else if (in_array($item->getType(), PluginBackupsWork::getTypes(true))) {
         
            $self->showPluginFromItems(get_class($item),$item->getField('id'), $withtemplate);
      }
      return true;
   }
   
   static function countForWork(PluginBackupsWork $item) {

      $types = implode("','", $item->getTypes());
      if (empty($types)) {
         return 0;
      }
      return countElementsInTable('glpi_plugin_backups_works_items',
                                  "`itemtype` IN ('$types')
                                   AND `plugin_backups_works_id` = '".$item->getID()."'");
   }


   static function countForItem(CommonDBTM $item) {

      return countElementsInTable('glpi_plugin_backups_works_items',
                                  "`itemtype`='".$item->getType()."'
                                   AND `items_id` = '".$item->getID()."'");
   }
   
   
   function showItemFromPlugin($instID, $withtemplate) {
      global $DB,$CFG_GLPI, $LANG;

      if (!PluginBackupsWork_Item::canView())  return false;
      
      $rand=mt_rand();
      
      $work=new PluginBackupsWork();
      if ($work->getFromDB($instID)) {
      
         $canedit=$work->can($instID,'w') && $withtemplate<2;
         
         $query = "SELECT DISTINCT `itemtype` 
             FROM `".$this->getTable()."` 
             WHERE `plugin_backups_works_id` = '$instID' 
             ORDER BY `itemtype`";
         $result = $DB->query($query);
         $number = $DB->numrows($result);
      
         if (Session::isMultiEntitiesMode()) {
            $colsup=1;
         } else {
            $colsup=0;
         }
      
         echo "<form method='post' name='work_form$rand' id='work_form$rand'  action=\"".$CFG_GLPI["root_doc"]."/plugins/backups/front/work.form.php\">";
    
         echo "<div class='center'><table class='tab_cadre_fixe'>";
         echo "<tr><th colspan='".($canedit?(5+$colsup):(4+$colsup))."'>".$LANG['plugin_backups']['works'][17].":</th></tr><tr>";
         if ($canedit) {
            echo "<th>&nbsp;</th>";
         }
         echo "<th>".$LANG['common'][17]."</th>";
         echo "<th>".$LANG['common'][16]."</th>";
         if (Session::isMultiEntitiesMode()) {
            echo "<th>".$LANG['entity'][0]."</th>";
         }
         echo "<th>".$LANG['common'][19]."</th>";
         echo "<th>".$LANG['common'][20]."</th>";
         echo "</tr>";
      
         for ($i=0 ; $i < $number ; $i++) {
            $type=$DB->result($result, $i, "itemtype");
            if (!class_exists($type)) {
               continue;
            }           
            $item = new $type();
            if ($item->canView()) {
               $column="name";
               $table = getTableForItemType($type);

               $query = "SELECT `".$table."`.*, `".$this->getTable()."`.`id` AS items_id, `glpi_entities`.`id` AS entity "
                ." FROM `".$this->getTable()."`, `".$table
                ."` LEFT JOIN `glpi_entities` ON (`glpi_entities`.`id` = `".$table."`.`entities_id`) "
                ." WHERE `".$table."`.`id` = `".$this->getTable()."`.`items_id` 
                AND `".$this->getTable()."`.`itemtype` = '$type' 
                AND `".$this->getTable()."`.`plugin_backups_works_id` = '$instID' "
                . getEntitiesRestrictRequest(" AND ",$table,'','',$item->maybeRecursive()); 

               if ($item->maybeTemplate()) {
                  $query.=" AND `".$table."`.`is_template` = '0'";
               }
               $query.=" ORDER BY `glpi_entities`.`completename`, `".$table."`.`$column`";

               if ($result_linked=$DB->query($query))
                  if ($DB->numrows($result_linked)) {
                     Session::initNavigateListItems($type,$LANG['plugin_backups']['title'][10]." = ".$work->fields['name']);

                     while ($data=$DB->fetch_assoc($result_linked)) {
                        $item->getFromDB($data["id"]);
                        
                        Session::addToNavigateListItems($type,$data["id"]);
                        $ID="";
                        if ($_SESSION["glpiis_ids_visible"]||empty($data["name"])) $ID= " (".$data["id"].")";
                        $link=Toolbox::getItemTypeFormURL($type);
                        $name= "<a href=\"".$link."?id=".$data["id"]."\">"
                        .$data["name"]."$ID</a>";
                
                        echo "<tr class='tab_bg_1'>";

                        if ($canedit) {
                           echo "<td width='10'>";
                           $sel="";
                           if (isset($_GET["select"])&&$_GET["select"]=="all") $sel="checked";
                           echo "<input type='checkbox' name='item[".$data["items_id"]."]' value='1' $sel>";
                           echo "</td>";
                        }
                        echo "<td class='center'>".$item->getTypeName()."</td>";
                
                        echo "<td class='center' ".(isset($data['is_deleted'])&&$data['is_deleted']?"class='tab_bg_2_2'":"").">".$name."</td>";
                
                        if (Session::isMultiEntitiesMode())
                           echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",$data['entity'])."</td>";
                           
                        echo "<td class='center'>".(isset($data["serial"])? "".$data["serial"]."" :"-")."</td>";
                        echo "<td class='center'>".(isset($data["otherserial"])? "".$data["otherserial"]."" :"-")."</td>";
                
                        echo "</tr>";
                     }
                  }
            }
         }
    
         if ($canedit) {
            echo "<tr class='tab_bg_1'><td colspan='".(3+$colsup)."' class='center'>";
            echo "<input type='hidden' name='plugin_backups_works_id' value='$instID'>";
            Dropdown::showAllItems("items_id",0,0,$work->fields['entities_id'],PluginBackupsWork::getTypes());
            echo "</td>";
            echo "<td colspan='2' class='tab_bg_2'>";
            echo "<input type='submit' name='additem' value=\"".$LANG['buttons'][8]."\" class='submit'>";
            echo "</td></tr>";
            echo "</table></div>" ;
            
            Html::openArrowMassives("work_form$rand",true);
            Html::closeArrowMassives(array('deleteitem'=> $LANG['buttons'][6]));

         } else {
    
            echo "</table></div>";
         }
         Html::closeForm();
      }
   }
   
   
   //items
   function showPluginFromItems($itemtype,$ID,$withtemplate='') {
      global $DB,$CFG_GLPI,$LANG;
      
      $rand=mt_rand();
      $item = new $itemtype();
      $canread = $item->can($ID,'r');
      $canedit = $item->can($ID,'w');

      $work = new PluginBackupsWork();
      
      $query = "SELECT `".$this->getTable()."`.`id` AS items_id,`glpi_plugin_backups_works`.* "
      ."FROM `".$this->getTable()."`,`glpi_plugin_backups_works` "
      ." LEFT JOIN `glpi_entities` ON (`glpi_entities`.`id` = `glpi_plugin_backups_works`.`entities_id`) "
      ." WHERE `".$this->getTable()."`.`items_id` = '".$ID."' 
      AND `".$this->getTable()."`.`itemtype` = '".$itemtype."' 
      AND `".$this->getTable()."`.`plugin_backups_works_id` = `glpi_plugin_backups_works`.`id` "
      . getEntitiesRestrictRequest(" AND ","glpi_plugin_backups_works",'','',false);
    
      $query.= " ORDER BY `glpi_plugin_backups_works`.`name` ";
    
      $result = $DB->query($query);
      $number = $DB->numrows($result);
    
      if (Session::isMultiEntitiesMode()) {
         $colsup=1;
      } else {
         $colsup=0;
      }

      if (strcmp($itemtype,'Computer') == 0) return;
      
      if ($withtemplate!=2) echo "<form method='post' name='form_item$rand' id='form_item$rand' action=\"".$CFG_GLPI["root_doc"]."/plugins/backups/front/work.form.php\">";

      echo "<div align='center'><table class='tab_cadre_fixe'>";
      echo "<tr><th colspan='".(5+$colsup)."'>".$LANG['plugin_backups']['works'][31]."</th></tr>";
      if ($canedit) {
        echo "<th>&nbsp;</th>";
      }
      echo "<th>".$LANG['common'][16]."</th>";
      if (Session::isMultiEntitiesMode())
         echo "<th>".$LANG['entity'][0]."</th>";
      echo "<th>".$LANG['common'][17]."</th>";
      echo "<th>".$LANG['plugin_backups']['works'][9]."</th>";
      echo "<th>".$LANG['common'][25]."</th>";
      $used=array();
      while ($data=$DB->fetch_array($result)) {
         $workID=$data["id"];
         $used[]=$workID;
         echo "<tr class='tab_bg_1".($data["is_deleted"]=='1'?"_2":"")."'>";

         if ($canedit) {
            echo "<td width='10'>";
            $sel="";
            if (isset($_GET["select"])&&$_GET["select"]=="all") $sel="checked";
            echo "<input type='checkbox' name='item[".$data["items_id"]."]' value='1' $sel>";
            echo "</td>";
         }

         if ($withtemplate!=3 && $canread && (in_array($data['entities_id'],$_SESSION['glpiactiveentities']) || $data["is_recursive"])) {
            echo "<td class='center'><a href='".$CFG_GLPI["root_doc"]."/plugins/backups/front/work.form.php?id=".$data["id"]."'>".$data["name"];
         if ($_SESSION["glpiis_ids_visible"]) echo " (".$data["id"].")";
            echo "</a></td>";
         } else {
            echo "<td class='center'>".$data["name"];
            if ($_SESSION["glpiis_ids_visible"]) echo " (".$data["id"].")";
            echo "</td>";
         }
         if (Session::isMultiEntitiesMode())
            echo "<td class='center'>".Dropdown::getDropdownName("glpi_entities",$data['entities_id'])."</td>";

         echo "<td>".Dropdown::getDropdownName("glpi_plugin_backups_worktypes",$data["plugin_backups_worktypes_id"])."</td>";
         echo "<td>".Dropdown::getDropdownName("glpi_plugin_backups_workperiodicities",$data["plugin_backups_workperiodicities_id"])."</td>";
         echo "<td>".nl2br($data["comment"])."</td>";
         
         echo "</tr>";
      }

      if ($canedit) {
      
         $entities=""; 
         if ($item->isRecursive()) {
            $entities = getSonsOf('glpi_entities',$item->getEntityID());
         } else {
            $entities = $item->getEntityID();
         }   
         $limit = getEntitiesRestrictRequest(" AND ","glpi_plugin_backups_works",'',$entities,false);

         $q="SELECT COUNT(*) 
           FROM `glpi_plugin_backups_works` 
           WHERE `is_deleted` = '0' 
                  AND `is_template` = '0'";
         $q.=" $limit";
         $result = $DB->query($q);
         $nb = $DB->result($result,0,0);
         
         if ($nb>count($used)) {
            if (PluginBackupsWork_Item::canCreate()) {
               
               echo "<tr class='tab_bg_1'><td colspan='".(4+$colsup)."' class='right'>";
               echo "<input type='hidden' name='items_id' value='$ID'><input type='hidden' name='itemtype' value='$itemtype'>";
               $options = array('name' => "plugin_backups_works_id",
                              'entity' => $item->fields["entities_id"],
                              'used'   => $used);
               if ($itemtype != "Computer") {
                  PluginBackupsWork::dropdown($options);
               } else {
                  self::dropdown($options);
               }
               echo "</td><td class='center'>";
               echo "<input type='submit' name='additem' value=\"".$LANG['buttons'][8]."\" class='submit'>";
               echo "</td>";
               echo "</tr>";
            }
         }
         
         Html::openArrowMassives("form_item$rand",true);
         Html::closeArrowMassives(array('deleteitem'=> $LANG['buttons'][6]));
      }
      echo "</table></div>";
      Html::closeForm();
   }
  
  
   static function dropdown($options=array()) {
      global $DB,$LANG,$CFG_GLPI;


      $p['name']   = 'nastypes_id';
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

      echo "<select name='_nastypes_id' id='nastypes_id$rand'>";
      echo "<option value='0'>".Dropdown::EMPTY_VALUE."</option>";

      while ($data=$DB->fetch_assoc($result)) {
         echo "<option value='".$data['id']."'>".$data['name']."</option>";
      }
      echo "</select>";

      $params = array('nastypes_id' => '__VALUE__',
                      'entity' => $p['entity'],
                      'rand'   => $rand,
                      'myname' => $p['name'],
                      'used'   => $p['used']);

      Ajax::updateItemOnSelectEvent("nastypes_id$rand","show_".$p['name']."$rand",
                                    $CFG_GLPI["root_doc"]."/plugins/backups/ajax/dropdownWorksNas.php", $params);

      echo "<span id='show_".$p['name']."$rand'>";
      $_POST["entity"] = $p['entity'];
      $_POST["nastypes_id"] = 0;
      $_POST["myname"] = $p['name'];
      $_POST["rand"]   = $rand;
      $_POST["used"]   = $p['used'];
      include (GLPI_ROOT."/plugins/backups/ajax/dropdownWorksNas.php");
      echo "</span>\n";

      return $rand;
   }
}
?>
