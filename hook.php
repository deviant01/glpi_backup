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

function plugin_backups_install() {
   global $DB, $LANG;

   include_once (GLPI_ROOT."/plugins/backups/inc/profile.class.php");
   
   $install=false;
   $update=false;
   
   if(!TableExists("glpi_plugin_backups_works") 
         && !TableExists("glpi_plugin_backups_histories")) {

      $install=true;
      $DB->runFile(GLPI_ROOT ."/plugins/backups/sql/empty-1.5.0.sql");
      
      $query = "INSERT INTO `glpi_plugin_backups_worktypes` ( `id` , `name` , `comment` )
               VALUES (NULL , '".$LANG['plugin_backups']['works'][12]."', NULL);";
      $DB->query($query) or die($DB->error());


      $query = "INSERT INTO `glpi_plugin_backups_worktypes` ( `id` , `name` , `comment` )
               VALUES (NULL , '".$LANG['plugin_backups']['works'][13]."', NULL);";
      $DB->query($query) or die($DB->error());

      $query = "INSERT INTO `glpi_plugin_backups_worktypes` ( `id` , `name` , `comment` )
               VALUES (NULL , '".$LANG['plugin_backups']['works'][14]."', NULL);";
      $DB->query($query) or die($DB->error());


      $query = "INSERT INTO `glpi_plugin_backups_historystates` ( `id` , `name` , `comment` )
               VALUES (NULL , '".$LANG['plugin_backups']['history'][10]."', NULL);";
      $DB->query($query) or die($DB->error());

      $query = "INSERT INTO `glpi_plugin_backups_historystates` ( `id` , `name` , `comment` )
               VALUES (NULL , '".$LANG['plugin_backups']['history'][11]."', NULL);";
      $DB->query($query) or die($DB->error());


   } else if(TableExists("glpi_plugin_backups_work_peripheral") 
            && !TableExists("glpi_plugin_backups_work_nas")) {
      
      $update=true;
      
      $DB->runFile(GLPI_ROOT ."/plugins/backups/sql/update-1.2.0.sql");
      /*Update 1.2.0*/
      $query = "INSERT INTO `glpi_plugin_backups_historiesstates` ( `id` , `name` , `comment` )
               VALUES (NULL , '".$LANG['plugin_backups']['history'][10]."', NULL);";
      $DB->query($query) or die($DB->error());


      $query = "INSERT INTO `glpi_plugin_backups_historiesstates` ( `id` , `name` , `comment` )
               VALUES (NULL , '".$LANG['plugin_backups']['history'][11]."', NULL);";
      $DB->query($query) or die($DB->error());

      $query = "INSERT INTO `glpi_plugin_backups_workstypes` ( `id` , `name` , `comment` )
               VALUES (NULL , '".$LANG['plugin_backups']['works'][12]."', NULL);";
      $DB->query($query) or die($DB->error());


      $query = "INSERT INTO `glpi_plugin_backups_workstypes` ( `id` , `name` , `comment` )
               VALUES (NULL , '".$LANG['plugin_backups']['works'][13]."', NULL);";
      $DB->query($query) or die($DB->error());

      $query = "INSERT INTO `glpi_plugin_backups_workstypes` ( `id` , `name` , `comment` )
               VALUES (NULL , '".$LANG['plugin_backups']['works'][14]."', NULL);";
      $DB->query($query) or die($DB->error());
      /*End Update 1.2.0*/
      
      $DB->runFile(GLPI_ROOT ."/plugins/backups/sql/update-1.3.0.sql");
      
      /*Update 1.3.0*/
      $query="INSERT INTO `glpi_plugin_backups_work_device` (FK_work,FK_device,device_type,is_template) 
            SELECT `FK_work`, `FK_peripheral`, 5,`is_template` FROM `glpi_plugin_backups_work_peripheral`;";
      $DB->query($query);

      $query="INSERT INTO `glpi_plugin_backups_work_device` (FK_work,FK_device,device_type,is_template) 
            SELECT `FK_work`, `FK_software`, 6,`is_template` FROM `glpi_plugin_backups_work_software`;";
      $DB->query($query);

      $query="INSERT INTO `glpi_plugin_backups_work_device` (FK_work,FK_device,device_type,is_template) 
            SELECT `FK_work`, `FK_computer`, 1,`is_template` FROM `glpi_plugin_backups_work_nas`;";
      $DB->query($query);

      $query = "DROP TABLE `glpi_plugin_backups_work_peripheral`;";
      $DB->query($query) or die($DB->error());

      $query = "DROP TABLE `glpi_plugin_backups_work_software`;";
      $DB->query($query) or die($DB->error());

      $query = "DROP TABLE `glpi_plugin_backups_work_nas`;";
      $DB->query($query) or die($DB->error());
      /*End Update 1.3.0*/
      $DB->runFile(GLPI_ROOT ."/plugins/backups/sql/update-1.4.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/backups/sql/update-1.5.0.sql");

   } else if(TableExists("glpi_plugin_backups_work_computer") 
               && !TableExists("glpi_plugin_backups_work_device")) {
      
      $update=true;
      $DB->runFile(GLPI_ROOT ."/plugins/backups/sql/update-1.3.0.sql");
      /*Update 1.3.0*/
      $query="INSERT INTO `glpi_plugin_backups_work_device` (FK_work,FK_device,device_type,is_template) 
            SELECT `FK_work`, `FK_peripheral`, 5,`is_template` FROM `glpi_plugin_backups_work_peripheral`;";
      $DB->query($query);

      $query="INSERT INTO `glpi_plugin_backups_work_device` (FK_work,FK_device,device_type,is_template) 
            SELECT `FK_work`, `FK_software`, 6,`is_template` FROM `glpi_plugin_backups_work_software`;";
      $DB->query($query);

      $query="INSERT INTO `glpi_plugin_backups_work_device` (FK_work,FK_device,device_type,is_template) 
            SELECT `FK_work`, `FK_computer`, 1,`is_template` FROM `glpi_plugin_backups_work_nas`;";
      $DB->query($query);

      $query = "DROP TABLE `glpi_plugin_backups_work_peripheral`;";
      $DB->query($query) or die($DB->error());

      $query = "DROP TABLE `glpi_plugin_backups_work_software`;";
      $DB->query($query) or die($DB->error());

      $query = "DROP TABLE `glpi_plugin_backups_work_nas`;";
      $DB->query($query) or die($DB->error());
      /*End Update 1.3.0*/
      $DB->runFile(GLPI_ROOT ."/plugins/backups/sql/update-1.4.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/backups/sql/update-1.5.0.sql");
      
   } else if(TableExists("glpi_plugin_backups_profiles") 
            && FieldExists("glpi_plugin_backups_profiles","interface")) {
      
      $update=true;
      $DB->runFile(GLPI_ROOT ."/plugins/backups/sql/update-1.4.0.sql");
      $DB->runFile(GLPI_ROOT ."/plugins/backups/sql/update-1.5.0.sql");

   } else if(!TableExists("glpi_plugin_backups_histories")) {
      $update=true;
      $DB->runFile(GLPI_ROOT ."/plugins/backups/sql/update-1.5.0.sql");

   }
   
   if ($update) {
      $query_="SELECT *
            FROM `glpi_plugin_backups_profiles` ";
      $result_=$DB->query($query_);
      if ($DB->numrows($result_)>0) {

         while ($data=$DB->fetch_array($result_)) {
            $query="UPDATE `glpi_plugin_backups_profiles`
                  SET `profiles_id` = '".$data["id"]."'
                  WHERE `id` = '".$data["id"]."';";
            $result=$DB->query($query);

         }
      }
      
      $query="ALTER TABLE `glpi_plugin_backups_profiles`
               DROP `name` ;";
      $result=$DB->query($query);

      Plugin::migrateItemType(
         array(1500=>'PluginBackupsTape',
               1501=>'PluginBackupsLibrary',
               1502=>'PluginBackupsWork',
               1503=>'PluginBackupsScript',
               1504=>'PluginBackupsHistory'),
         array("glpi_bookmarks", "glpi_bookmarks_users", "glpi_displaypreferences",
               "glpi_documents_items", "glpi_logs"));
   }
              
   PluginBackupsProfile::createFirstAccess($_SESSION['glpiactiveprofile']['id']);

   return true;
}

function plugin_backups_uninstall(){
   global $DB;

   $tables = array("glpi_plugin_backups_tapes",
               "glpi_plugin_backups_libraries",
               "glpi_plugin_backups_works",
               "glpi_plugin_backups_scripts",
               "glpi_plugin_backups_histories",
               "glpi_plugin_backups_libraries_tapes",
               "glpi_plugin_backups_works_libraries",
               "glpi_plugin_backups_works_scripts",
               "glpi_plugin_backups_works_tapes",
               "glpi_plugin_backups_works_items",
               "glpi_plugin_backups_works_computers",
               "glpi_plugin_backups_tapetypes",
               "glpi_plugin_backups_worktypes",
               "glpi_plugin_backups_workperiodicities",
               "glpi_plugin_backups_scripttypes",
               "glpi_plugin_backups_historystates",
               "glpi_plugin_backups_profiles");
   
   foreach($tables as $table)
      $DB->query("DROP TABLE `$table`;");
   
      $tables_glpi = array("glpi_displaypreferences",
               "glpi_documents_items",
               "glpi_bookmarks",
               "glpi_logs");

   foreach($tables_glpi as $table_glpi)
      $DB->query("DELETE FROM `$table_glpi` WHERE `itemtype` = 'PluginBackupsTape'
                                                   OR `itemtype` = 'PluginBackupsLibrary'
                                                   OR `itemtype` = 'PluginBackupsWork'
                                                   OR `itemtype` = 'PluginBackupsScript'
                                                   OR `itemtype` = 'PluginBackupsHistory';");


  $tables_glpi = array("glpi_displayprefs",
                        "glpi_documents_items",
                        "glpi_bookmarks",
                        "glpi_logs");

   if (class_exists('PluginDatainjectionModel')) {
      PluginDatainjectionModel::clean(array('itemtype'=>'PluginBackupsTape'));
      PluginDatainjectionModel::clean(array('itemtype'=>'PluginBackupsLibrary'));
      PluginDatainjectionModel::clean(array('itemtype'=>'PluginBackupsWork'));
      PluginDatainjectionModel::clean(array('itemtype'=>'PluginBackupsScript'));
      PluginDatainjectionModel::clean(array('itemtype'=>'PluginBackupsHistory'));
   }

   return true;
}

function plugin_datainjection_populate_backups() {
   global $INJECTABLE_TYPES;
   $INJECTABLE_TYPES['PluginBackupsTapeInjection']    = 'backups';
   $INJECTABLE_TYPES['PluginBackupsLibraryInjection'] = 'backups';
   $INJECTABLE_TYPES['PluginBackupsWorkInjection']    = 'backups';
   $INJECTABLE_TYPES['PluginBackupsScriptInjection']  = 'backups';
   $INJECTABLE_TYPES['PluginBackupsHistoryInjection'] = 'backups';
}

// Define dropdown relations
function plugin_backups_getDatabaseRelations(){

   $plugin = new Plugin();
   if ($plugin->isActivated("backups"))
      return array("glpi_plugin_backups_tapestypes"=>array("glpi_plugin_backups_tapes"=>"plugin_backups_tapetypes_id"),
               "glpi_plugin_backups_worktypes"=>array("glpi_plugin_backups_works"=>"plugin_backups_worktypes_id"),
               "glpi_plugin_backups_workperiodicities"=>array("glpi_plugin_backups_works"=>"plugin_backups_workperiodicities_id"),
               "glpi_plugin_backups_scripttypes"=>array("glpi_plugin_backups_scripts"=>"plugin_backups_scripttypes_id"),
               "glpi_plugin_backups_historystates"=>array("glpi_plugin_backups_histories"=>"plugin_backups_historystates_id"),
               "glpi_entities"=>array("glpi_plugin_backups_tapes"=>"entities_id",
                                    "glpi_plugin_backups_libraries"=>"entities_id",
                                    "glpi_plugin_backups_works"=>"entities_id",
                                    "glpi_plugin_backups_scripts"=>"entities_id",
                                    "glpi_plugin_backups_histories"=>"entities_id"));
   else
      return array();
}

// Define Dropdown tables to be manage in GLPI :
function plugin_backups_getDropdown(){
   // Table => Name
   global $LANG;

   $plugin = new Plugin();
   if ($plugin->isActivated("backups"))
      return array("PluginBackupsTapeType"           => $LANG['plugin_backups']['setup'][6],
                     "PluginBackupsWorkType"          => $LANG['plugin_backups']['setup'][9],
                     "PluginBackupsWorkPeriodicity"   => $LANG['plugin_backups']['setup'][5],
                     "PluginBackupsScriptType"        => $LANG['plugin_backups']['setup'][4],
                     "PluginBackupsHistoryState"      => $LANG['plugin_backups']['setup'][7]);
   else
      return array();
}

////// SEARCH FUNCTIONS ///////()

function plugin_backups_getAddSearchOptions($itemtype) {
   global $LANG;

    $sopt=array();

   if (in_array($itemtype, PluginBackupsWork::getTypes(true))) {
      if (plugin_backups_haveRight("backups","r")) {
         $sopt[1510]['table']='glpi_plugin_backups_works';
         $sopt[1510]['field']='name';
         $sopt[1510]['name']=$LANG['plugin_backups']['title'][0]." - ".$LANG['plugin_backups']['title'][1]." - ".$LANG['common'][16];
         $sopt[1510]['forcegroupby']=true;
         $sopt[1510]['datatype']='itemlink';
         $sopt[1510]['itemlink_type']='PluginBackupsWork';
         $sopt[1510]['joinparams']     = array('beforejoin'
                                             => array('table'      => 'glpi_plugin_backups_works_items',
                                                      'joinparams' => array('jointype' => 'itemtype_item')));

         $sopt[1511]['table']='glpi_plugin_backups_worktypes';
         $sopt[1511]['field']='name';
         $sopt[1511]['name']=$LANG['plugin_backups']['title'][0]." - ".$LANG['plugin_backups']['title'][1]." - ".$LANG['common'][17];
         $sopt[1511]['forcegroupby']=true;
         $sopt[1511]['joinparams']     = array('beforejoin' => array(
                                                   array('table'      => 'glpi_plugin_backups_works',
                                                         'joinparams' => $sopt[1510]['joinparams'])));
      }
   }
   return $sopt;
}


////// SPECIFIC MODIF MASSIVE FUNCTIONS ///////

function plugin_backups_MassiveActions($type) {
   global $LANG;
   
   switch ($type){
      case "PluginBackupsTape":
         return array(
            "plugin_backups_associatelibrary"         => $LANG['plugin_backups']['buttons'][5],
            "plugin_backups_dissociatelibrary"        => $LANG['plugin_backups']['buttons'][8],
            "plugin_backups_associatework"            => $LANG['plugin_backups']['buttons'][9],
            "plugin_backups_dissociatework"           => $LANG['plugin_backups']['buttons'][10],
            "plugin_backups_transfert" => $LANG['buttons'][48],
            );

      break;

      case "PluginBackupsLibrary":
         return array(
            "plugin_backups_associatetape"            => $LANG['plugin_backups']['buttons'][11],
            "plugin_backups_dissociatetape"           => $LANG['plugin_backups']['buttons'][12],
            "plugin_backups_associatework"            => $LANG['plugin_backups']['buttons'][9],
            "plugin_backups_dissociatework"           => $LANG['plugin_backups']['buttons'][10],
            "plugin_backups_transfert" => $LANG['buttons'][48],
            );

      break;

      case "PluginBackupsWork":
         return array(
            "plugin_backups_associatesoftware"        => $LANG['plugin_backups']['buttons'][13],
            "plugin_backups_dissociatesoftware"       => $LANG['plugin_backups']['buttons'][14],
            "plugin_backups_associateperipheral"      => $LANG['plugin_backups']['buttons'][15],
            "plugin_backups_dissociateperipheral"     => $LANG['plugin_backups']['buttons'][16],
            "plugin_backups_associatetape"            => $LANG['plugin_backups']['buttons'][11],
            "plugin_backups_dissociatetape"           => $LANG['plugin_backups']['buttons'][12],
            "plugin_backups_associatelibrary"         => $LANG['plugin_backups']['buttons'][5],
            "plugin_backups_dissociatelibrary"        => $LANG['plugin_backups']['buttons'][8],
            "plugin_backups_associatecomputer"        => $LANG['plugin_backups']['buttons'][17],
            "plugin_backups_dissociatecomputer"       => $LANG['plugin_backups']['buttons'][18],
            "plugin_backups_associatescript"          => $LANG['plugin_backups']['buttons'][19],
            "plugin_backups_dissociatescript"         => $LANG['plugin_backups']['buttons'][20],
            "plugin_backups_associatenas"             => $LANG['plugin_backups']['buttons'][21],
            "plugin_backups_dissociatenas"            => $LANG['plugin_backups']['buttons'][22],
            "plugin_backups_transfert" => $LANG['buttons'][48],
            );

      break;

      case "PluginBackupsScript":
         return array(
            "plugin_backups_associatework"            => $LANG['plugin_backups']['buttons'][9],
            "plugin_backups_dissociatework"           => $LANG['plugin_backups']['buttons'][10],
            "plugin_backups_transfert" => $LANG['buttons'][48],
            );

      break;

      case "PluginBackupsHistory":
         return array(
            "plugin_backups_transfert" => $LANG['buttons'][48],
            );

      break;
   }
   return array();
}

function plugin_backups_MassiveActionsDisplay ($options=array()) {
   global $LANG,$CFG_GLPI;
   
   switch ($options['itemtype']) {
      case "PluginBackupsTape":
         switch ($options['action']) {
            case "plugin_backups_associatelibrary":
               Dropdown::show('PluginBackupsLibrary', array('name'   => "libraries_id"));
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
            break;
            case "plugin_backups_dissociatelibrary":
               Dropdown::show('PluginBackupsLibrary', array('name'   => "libraries_id"));
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_associatework":
               $options = array('name' => "works_id",
                                 'entity' => $_SESSION['glpiactive_entity']);
               PluginBackupsWork::dropdown($options);
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_dissociatework":
               $options = array('name' => "works_id",
                                 'entity' => $_SESSION['glpiactive_entity']);
               PluginBackupsWork::dropdown($options);
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_transfert":
               Dropdown::show('Entity');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
         }
      break;

      case "PluginBackupsLibrary":
         switch ($options['action']) {
            // No case for add_document : use GLPI core one
            case "plugin_backups_associatetape":
               $options = array('name' => "tapes_id",
                                 'entity' => $_SESSION['glpiactive_entity']);
               PluginBackupsTape::dropdown($options);
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_dissociatetape":
               $options = array('name' => "tapes_id",
                                 'entity' => $_SESSION['glpiactive_entity']);
               PluginBackupsTape::dropdown($options);
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_associatework":
               $options = array('name' => "works_id",
                                 'entity' => $_SESSION['glpiactive_entity']);
               PluginBackupsWork::dropdown($options);
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_dissociatework":
               $options = array('name' => "works_id",
                                 'entity' => $_SESSION['glpiactive_entity']);
               PluginBackupsWork::dropdown($options);
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_transfert":
               Dropdown::show('Entity');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
         }
      break;

      case "PluginBackupsWork":
         switch ($options['action']) {
            // No case for add_document : use GLPI core one
            case "plugin_backups_associatesoftware":
               Dropdown::show('Software');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_dissociatesoftware":
               Dropdown::show('Software');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_associateperipheral":
               Dropdown::show('Peripheral');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_dissociateperipheral":
               Dropdown::show('Peripheral');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_associatetape":
               $options = array('name' => "tapes_id",
                                 'entity' => $_SESSION['glpiactive_entity']);
               PluginBackupsTape::dropdown($options);
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_dissociatetape":
               $options = array('name' => "tapes_id",
                                 'entity' => $_SESSION['glpiactive_entity']);
               PluginBackupsTape::dropdown($options);
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_associatelibrary":
               Dropdown::show('PluginBackupsLibrary', array('name'   => "libraries_id"));
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_dissociatelibrary":
               Dropdown::show('PluginBackupsLibrary', array('name'   => "libraries_id"));
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_associatecomputer":
               Dropdown::show('Computer');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_dissociatecomputer":
               Dropdown::show('Computer');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_associatescript":
               $options = array('name' => "scripts_id",
                                 'entity' => $_SESSION['glpiactive_entity']);
               PluginBackupsScript::dropdown($options);
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_dissociatescript":
               $options = array('name' => "scripts_id",
                                 'entity' => $_SESSION['glpiactive_entity']);
               PluginBackupsScript::dropdown($options);
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_associatenas":
               Dropdown::show('Computer');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_dissociatenas":
               Dropdown::show('Computer');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
            case "plugin_backups_transfert":
               Dropdown::show('Entity');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
               break;
         }

      break;

      case "PluginBackupsScript":
         switch ($options['action']) {
            // No case for add_document : use GLPI core one
            case "plugin_backups_associatework":
               $options = array('name' => "works_id",
                                 'entity' => $_SESSION['glpiactive_entity']);
               PluginBackupsWork::dropdown($options);
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
            break;
            case "plugin_backups_dissociatework":
               $options = array('name' => "works_id",
                                 'entity' => $_SESSION['glpiactive_entity']);
               PluginBackupsWork::dropdown($options);
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
            break;
            case "plugin_backups_transfert":
               Dropdown::show('Entity');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
            break;
         }
      break;

      case "PluginBackupsHistory":
         switch ($options['action']) {
            // No case for add_document : use GLPI core one

            case "plugin_backups_transfert":
               Dropdown::show('Entity');
               echo "&nbsp;<input type=\"submit\" name=\"massiveaction\" class=\"submit\" value=\"".$LANG['buttons'][2]."\" >";
            break;
         }
      break;
   }
   return "";
}

function plugin_backups_MassiveActionsProcess($data){
   global $LANG,$DB;
   
   $tape          = new PluginBackupsTape();
   $lib           = new PluginBackupsLibrary();
   $script        = new PluginBackupsScript();
   $work          = new PluginBackupsWork();
   $history       = new PluginBackupsHistory();
   $lib_tape      = new PluginBackupsLibrary_Tape();
   $work_tape     = new PluginBackupsWork_Tape();
   $work_script   = new PluginBackupsWork_Script();
   $work_lib      = new PluginBackupsWork_Library();
   $work_item     = new PluginBackupsWork_Item();
   $work_computer = new PluginBackupsWork_Computer();
   
   switch ($data['action']){
      case "plugin_backups_associatelibrary":
         if ($data['itemtype']=="PluginBackupsTape"){
            
            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  // Items exists ?
                  if ($tape->getFromDB($key)){
                     // Entity security
                     if ($lib->getFromDB($data['libraries_id'])){
                        if ($lib->fields["entities_id"]==$tape->fields["entities_id"]) {
                           $values['libraries_id'] = $data["libraries_id"];
                           $values['tapes_id'] = $key;
                           $lib_tape->add($values);
                        }
                     }
                  }
               }
            }
         } else if ($data['itemtype']=="PluginBackupsWork"){
            
            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  // Items exists ?
                  if ($work->getFromDB($key)){
                     // Entity security
                     if ($lib->getFromDB($data['libraries_id'])){
                        if ($lib->fields["entities_id"]==$work->fields["entities_id"]){
                           $values['libraries_id'] = $data["libraries_id"];
                           $values['works_id'] = $key;
                           $work_lib->add($values);
                        }
                     }
                  }
               }
            }
         }
         break;
      case "plugin_backups_dissociatelibrary":
         if ($data['itemtype']=="PluginBackupsTape"){
            
            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  $lib_tape->deleteByCriteria(array('libraries_id' => $data["libraries_id"]));
               }
            }
         } else if ($data['itemtype']=="PluginBackupsWork"){

            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  $work_lib->deleteByCriteria(array('libraries_id' => $data["libraries_id"]));
               }
            }
         }
         break;
      case "plugin_backups_associatework":
         if ($data['itemtype']=="PluginBackupsTape"){
            
            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  // Items exists ?
                  if ($tape->getFromDB($key)){
                     // Entity security
                     if ($work->getFromDB($data['works_id'])){
                        if ($work->fields["entities_id"]==$tape->fields["entities_id"]){
                           $values['tapes_id'] = $key;
                           $values['works_id'] = $data["works_id"];
                           $work_tape->add($values);
                        }
                     }
                  }
               }
            }
         } else if ($data['itemtype']=="PluginBackupsLibrary"){
            
            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  // Items exists ?
                  if ($lib->getFromDB($key)){
                     // Entity security
                     if ($work->getFromDB($data['works_id'])){
                        if ($work->fields["entities_id"]==$lib->fields["entities_id"]){
                           $values['libraries_id'] = $key;
                           $values['works_id'] = $data["works_id"];
                           $work_lib->add($values);
                        }
                     }
                  }
               }
            }
         } else if ($data['itemtype']=="PluginBackupsScript"){
            
            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  // Items exists ?
                  if ($script->getFromDB($key)){
                     // Entity security
                     if ($work->getFromDB($data['works_id'])){
                        if ($work->fields["entities_id"]==$script->fields["entities_id"]){
                           $values['scripts_id'] = $key;
                           $values['works_id'] = $data["works_id"];
                           $work_script->add($values);
                        }
                     }
                  }
               }
            }
         }
         break;
      case "plugin_backups_dissociatework":
         if ($data['itemtype']=="PluginBackupsTape"){

            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  $work_tape->deleteByCriteria(array('works_id' => $data["works_id"]));
               }
            }
         } else if ($data['itemtype']=="PluginBackupsLibrary"){

            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  $work_lib->deleteByCriteria(array('works_id' => $data["works_id"]));
               }
            }
         } else if ($data['itemtype']=="PluginBackupsScript"){

            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  $work_script->deleteByCriteria(array('works_id' => $data["works_id"]));
               }
            }
         }
         break;
      case "plugin_backups_associatetape":
         if ($data['itemtype']=="PluginBackupsLibrary"){

            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  // Items exists ?
                  if ($lib->getFromDB($key)){
                     // Entity security
                     if ($tape->getFromDB($data['tapes_id'])){
                        if ($tape->fields["entities_id"]==$lib->fields["entities_id"]){
                           $values['libraries_id'] = $key;
                           $values['tapes_id'] = $data["tapes_id"];
                           $lib_tape->add($values);
                        }
                     }
                  }
               }
            }
         } else if ($data['itemtype']=="PluginBackupsWork"){

            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  // Items exists ?
                  if ($work->getFromDB($key)){
                     // Entity security
                     if ($tape->getFromDB($data['tapes_id'])){
                        if ($tape->fields["entities_id"]==$work->fields["entities_id"]){
                           $values['works_id'] = $key;
                           $values['tapes_id'] = $data["tapes_id"];
                           $work_tape->add($values);
                        }
                     }
                  }
               }
            }
         }
         break;
      case "plugin_backups_dissociatetape":
         if ($data['itemtype']=="PluginBackupsLibrary"){

            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  $lib_tape->deleteByCriteria(array('tapes_id' => $data["tapes_id"]));
               }
            }
         } else if ($data['itemtype']=="PluginBackupsWork"){

            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  $work_tape->deleteByCriteria(array('tapes_id' => $data["tapes_id"]));
               }
            }
         }
      break;
      case "plugin_backups_associatescript":
         if ($data['itemtype']=="PluginBackupsWork"){

            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  // Items exists ?
                  if ($work->getFromDB($key)){
                     // Entity security
                     if ($script->getFromDB($data['scripts_id'])){
                        if ($script->fields["entities_id"]==$work->fields["entities_id"]){
                           $values['works_id'] = $key;
                           $values['scripts_id'] = $data["scripts_id"];
                           $work_script->add($values);
                        }
                     }
                  }
               }
            }
         }
         break;
      case "plugin_backups_dissociatescript":
         if ($data['itemtype']=="PluginBackupsWork"){

            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  $work_script->deleteByCriteria(array('scripts_id' => $data["scripts_id"]));
               }
            }
         }
         break;
      case "plugin_backups_associatesoftware":
         if ($data['itemtype']=="PluginBackupsWork"){
         
            $soft       = new Software();
            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  // Items exists ?
                  if ($work->getFromDB($key)){
                     // Entity security
                     if ($soft->getFromDB($data['softwares_id'])){
                        if ($soft->fields["entities_id"]==$work->fields["entities_id"]){
                           $values['plugin_backups_works_id'] = $key;
                           $values['itemtype'] = 'Software';
                           $values['items_id'] = $data["softwares_id"];
                           $work_item->add($values);
                        }
                     }
                  }
               }
            }
         }
         break;
      case "plugin_backups_dissociatesoftware":
         if ($data['itemtype']=="PluginBackupsWork"){

            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  $work_item->deleteByCriteria(array('items_id' => $data["softwares_id"],
                                                        'itemtype' => 'Software'));
               }
            }
         }
         break;
      case "plugin_backups_associateperipheral":
         if ($data['itemtype']=="PluginBackupsWork"){

            $periph     = new Peripheral();
            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  // Items exists ?
                  if ($work->getFromDB($key)){
                     // Entity security
                     if ($periph->getFromDB($data['peripherals_id'])){
                        if ($periph->fields["entities_id"]==$work->fields["entities_id"]){
                           $values['plugin_backups_works_id'] = $key;
                           $values['itemtype'] = 'Peripheral';
                           $values['items_id'] = $data["peripherals_id"];
                           $work_item->add($values);
                        }
                     }
                  }
               }
            }
         }
         break;
      case "plugin_backups_dissociateperipheral":
         if ($data['itemtype']=="PluginBackupsWork"){

            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  $work_item->deleteByCriteria(array('items_id' => $data["peripherals_id"],
                                                        'itemtype' => 'Peripheral'));
               }
            }
         }
         break;
      case "plugin_backups_associatenas":
         if ($data['itemtype']=="PluginBackupsWork"){

            $comp       = new Computer();
            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  // Items exists ?
                  if ($work->getFromDB($key)){
                     // Entity security
                     if ($comp->getFromDB($data['computers_id'])){
                        if ($comp->fields["entities_id"]==$work->fields["entities_id"]){
                           $values['plugin_backups_works_id'] = $key;
                           $values['itemtype'] = 'Computer';
                           $values['items_id'] = $data["computers_id"];
                           $work_item->add($values);
                        }
                     }
                  }
               }
            }
         }
         break;
      case "plugin_backups_dissociatenas":
         if ($data['itemtype']=="PluginBackupsWork"){
            $work_item  = new PluginBackupsWork_Item();
            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  $work_item->deleteByCriteria(array('items_id' => $data["computers_id"],
                                                        'itemtype' => 'Computer'));
               }
            }
         }
      break;
      case "plugin_backups_associatecomputer":
         if ($data['itemtype']=="PluginBackupsWork"){

            $comp          = new Computer();
            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  // Items exists ?
                  if ($work->getFromDB($key)){
                     // Entity security
                     if ($comp->getFromDB($data['computers_id'])){
                        if ($comp->fields["entities_id"]==$work->fields["entities_id"]){
                           $values['works_id'] = $key;
                           $values['computers_id'] = $data["computers_id"];
                           $work_computer->add($values);
                        }
                     }
                  }
               }
            }
         }
         break;
      case "plugin_backups_dissociatecomputer":
         if ($data['itemtype']=="PluginBackupsWork"){

            foreach ($data["item"] as $key => $val){
               if ($val==1) {
                  $work_computer->deleteByCriteria(array('computers_id' => $data["computers_id"]));
               }
            }
         }
         break;
      case "plugin_backups_transfert":
         if ($data['itemtype']=="PluginBackupsTape"){
            foreach ($data["item"] as $key => $val) {
               if ($val == 1) {
                  $values["id"] = $key;
                  $values["entities_id"] = $data['entities_id'];
                  $tape->update($values);
               }
            }
         } else if ($data['itemtype']=="PluginBackupsLibrary"){
            foreach ($data["item"] as $key => $val) {
               if ($val == 1) {
                  $values["id"] = $key;
                  $values["entities_id"] = $data['entities_id'];
                  $lib->update($values);
               }
            }
         } else if ($data['itemtype']=="PluginBackupsWork"){
            foreach ($data["item"] as $key => $val) {
               if ($val == 1) {
                  $values["id"] = $key;
                  $values["entities_id"] = $data['entities_id'];
                  $works->update($values);
               }
            }
         } else if ($data['itemtype']=="PluginBackupsScript"){
            foreach ($data["item"] as $key => $val) {
               if ($val == 1) {
                  $values["id"] = $key;
                  $values["entities_id"] = $data['entities_id'];
                  $script->update($values);
               }
            }
         } else if ($data['itemtype']=="PluginBackupsHistory"){
            foreach ($data["item"] as $key => $val) {
               if ($val == 1) {
                  $values["id"] = $key;
                  $values["entities_id"] = $data['entities_id'];
                  $history->update($values);
               }
            }
         }
         break;
   }
}

//////////////////////////////
function plugin_backups_postinit() {
   global $CFG_GLPI, $PLUGIN_HOOKS;

   $PLUGIN_HOOKS['item_purge']['backups'] = array();

   foreach (PluginBackupsWork::getTypes(true) as $type) {

      $PLUGIN_HOOKS['item_purge']['backups'][$type]
         = array('PluginBackupsWork_Item','cleanForItem');

      CommonGLPI::registerStandardTab($type, 'PluginBackupsWork_Item');
   }
}

?>