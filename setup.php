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

// Init the hooks of the plugins -Needed
function plugin_init_backups() {
   global $PLUGIN_HOOKS,$CFG_GLPI,$LANG;
   
   $PLUGIN_HOOKS['csrf_compliant']['backups'] = true;
   $PLUGIN_HOOKS['change_profile']['backups'] = array('PluginBackupsProfile','changeProfile');

   // Params : plugin name - string type - number - attributes
   Plugin::registerClass('PluginBackupsHistory', array(
         'document_types' => true
      ));
      
   Plugin::registerClass('PluginBackupsProfile',
                         array('addtabon' => 'Profile'));
   
   Plugin::registerClass('PluginBackupsWork_Computer',
                         array('addtabon' => 'Computer'));
                         
   Plugin::registerClass('PluginBackupsHistory',
                         array('addtabon' => 'Central'));
                         
   if (Session::getLoginUserID()) {
      
      if (isset($_SESSION["glpi_plugin_environment_installed"]) && $_SESSION["glpi_plugin_environment_installed"]==1) {

         $_SESSION["glpi_plugin_environment_backups"]=1;
            
         if (plugin_backups_haveRight("tapes","r")) {
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['tapes']['title'] = $LANG['plugin_backups']['title'][2];
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['tapes']['page'] = '/plugins/backups/front/tape.php';
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['tapes']['links']['search'] = '/plugins/backups/front/tape.php';
         }

         if (plugin_backups_haveRight("tapes","w")) {
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['tapes']['links']['add'] = '/plugins/backups/front/tape.template.php?add=1';
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['tapes']['links']['template'] = '/plugins/backups/front/tape.template.php?add=0';

         }
         
         if (plugin_backups_haveRight("libraries","r")) {
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['libraries']['title'] = $LANG['plugin_backups']['title'][5];
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['libraries']['page'] = '/plugins/backups/front/library.php';
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['libraries']['links']['search'] = '/plugins/backups/front/library.php';
         }

         if (plugin_backups_haveRight("libraries","w")) {
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['libraries']['links']['add'] = '/plugins/backups/front/library.form.php';

         }
         
         if (plugin_backups_haveRight("works","r")) {
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['works']['title'] = $LANG['plugin_backups']['title'][1];
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['works']['page'] = '/plugins/backups/front/work.php';
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['works']['links']['search'] = '/plugins/backups/front/work.php';
            
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['history']['title'] = $LANG['plugin_backups']['title'][9];
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['history']['page'] = '/plugins/backups/front/history.php';
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['history']['links']['search'] = '/plugins/backups/front/history.php';
         }

         if (plugin_backups_haveRight("works","w")) {
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['works']['links']['add'] = '/plugins/backups/front/work.template.php?add=1';
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['works']['links']['template'] = '/plugins/backups/front/work.template.php?add=0';
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['history']['links']['add'] = '/plugins/backups/front/history.form.php';

         }
         
         if (plugin_backups_haveRight("scripts","r")) {
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['scripts']['title'] = $LANG['plugin_backups']['title'][8];
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['scripts']['page'] = '/plugins/backups/front/script.php';
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['scripts']['links']['search'] = '/plugins/backups/front/script.php';
         }

         if (plugin_backups_haveRight("scripts","w")) {
            $PLUGIN_HOOKS['submenu_entry']['environment']['options']['scripts']['links']['add'] = '/plugins/backups/front/script.form.php';
         }

      } else {
         
         if (plugin_backups_haveRight("backups","r")) {
            $PLUGIN_HOOKS['menu_entry']['backups'] = 'front/menu.php';
         }
         
         // Display a menu entry ?
         if (plugin_backups_haveRight("tapes","r")) {
            $PLUGIN_HOOKS['submenu_entry']['backups']['search']['tapes'] = 'front/tape.php';
         }

         if (plugin_backups_haveRight("tapes","w")) {
            $PLUGIN_HOOKS['submenu_entry']['backups']['add']['tapes'] = 'front/tape.template.php?add=1';
            $PLUGIN_HOOKS['submenu_entry']['backups']['template']['tapes'] = 'front/tape.template.php?add=0';
         }
         
         if (plugin_backups_haveRight("libraries","r")) {
            $PLUGIN_HOOKS['submenu_entry']['backups']['search']['libraries'] = 'front/library.php';
         }

         if (plugin_backups_haveRight("libraries","w")) {
            $PLUGIN_HOOKS['submenu_entry']['backups']['add']['libraries'] = 'front/library.form.php?new=1';
         }
         
         if (plugin_backups_haveRight("works","r")) {
            $PLUGIN_HOOKS['submenu_entry']['backups']['search']['works'] = 'front/work.php';
            $PLUGIN_HOOKS['submenu_entry']['backups']['search']['history'] = 'front/history.php';
         }

         if (plugin_backups_haveRight("works","w")) {
            $PLUGIN_HOOKS['submenu_entry']['backups']['add']['works'] = 'front/work.template.php?add=1';
            $PLUGIN_HOOKS['submenu_entry']['backups']['template']['works'] = 'front/work.template.php?add=0';
            $PLUGIN_HOOKS['submenu_entry']['backups']['add']['history'] = 'front/history.form.php?new=1';
         }
         
         if (plugin_backups_haveRight("scripts","r")) {
            $PLUGIN_HOOKS['submenu_entry']['backups']['search']['scripts'] = 'front/script.php';
         }

         if (plugin_backups_haveRight("scripts","w")) {
            $PLUGIN_HOOKS['submenu_entry']['backups']['add']['scripts'] = 'front/script.form.php?new=1';
         }
      }
      
      if (plugin_backups_haveRight("backups","w")) {
         $PLUGIN_HOOKS['use_massive_action']['backups']=1;
      }
      
      if (class_exists('PluginBackupsWork_Item')) { // only if plugin activated
         $PLUGIN_HOOKS['pre_item_purge']['backups'] 
                        = array('Profile'=>array('PluginBackupsProfile', 'purgeProfiles'));
         $PLUGIN_HOOKS['plugin_datainjection_populate']['backups'] = 'plugin_datainjection_populate_backups';
         
      }
   }   
   // End init, when all types are registered
   $PLUGIN_HOOKS['post_init']['backups'] = 'plugin_backups_postinit';
   // Import from Data_Injection plugin
   $PLUGIN_HOOKS['migratetypes']['backups'] = 'plugin_datainjection_migratetypes_backups';
   $PLUGIN_HOOKS['add_css']['backups']="backups.css";
}

// Get the name and the version of the plugin - Needed
function plugin_version_backups(){
   global $LANG;

   return array (
      'name'      => $LANG['plugin_backups']['title'][0],
      'version'   => '1.5.0',
      'license' => 'GPLv2+',
      'author'    => 'Xavier Caillaud',
      'homepage'  => 'https://forge.indepnet.net/projects/show/backups',
      'minGlpiVersion' => '0.84.7',
      #'minGlpiVersion' => '0.83.3',
   );
}

// Optional : check prerequisites before install : may print errors or add to message after redirect
function plugin_backups_check_prerequisites() {
//   if (version_compare(GLPI_VERSION,'0.83.3','lt') || version_compare(GLPI_VERSION,'0.84','ge')) {
//     echo "This plugin requires GLPI >= 0.83.3 and GLPI < 0.84";
//      return false;
//   }
   return true;
}

// Uninstall process for plugin : need to return true if succeeded : may display messages or add to message after redirect
function plugin_backups_check_config(){
   return true;
}

function plugin_backups_haveRight($module,$right) {
   $matches=array(
         ""  => array("","r","w"), // ne doit pas arriver normalement
         "r" => array("r","w"),
         "w" => array("w"),
         "1" => array("1"),
         "0" => array("0","1"), // ne doit pas arriver non plus
            );
   if (isset($_SESSION["glpi_plugin_backups_profile"][$module])
   && in_array($_SESSION["glpi_plugin_backups_profile"][$module],$matches[$right]))
      return true;
   else return false;
}

function plugin_datainjection_migratetypes_backups($types) {

   $types[1500] = 'PluginBackupsTape';
   $types[1501] = 'PluginBackupsLibrary';
   $types[1502] = 'PluginBackupsWork';
   $types[1503] = 'PluginBackupsScript';
   $types[1504] = 'PluginBackupsHistory';
   return $types;
}
?>
