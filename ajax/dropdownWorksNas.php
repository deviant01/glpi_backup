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

// Direct access to file
if(strpos($_SERVER['PHP_SELF'],"dropdownWorksNas.php")){
   //$AJAX_INCLUDE = 1;
   define('GLPI_ROOT', '../../..');
   include (GLPI_ROOT."/inc/includes.php");
   header("Content-Type: text/html; charset=UTF-8");
   Html::header_nocache();
}

Session::checkCentralAccess();

if (!is_array($_POST['used'])) {
      $_POST['used'] = unserialize(stripslashes($_POST['used']));
   }
   $used = array();

   // Clean used array
   if (is_array($_POST['used']) && count($_POST['used'])>0) {
      $query = "SELECT `id`
                FROM `glpi_plugin_backups_works`
                WHERE `id` IN (".implode(',',$_POST['used']).")
                      AND `plugin_backups_worktypes_id` = '".$_POST["nastypes_id"]."'";
      
      foreach ($DB->request($query) AS $data) {
         $used[$data['id']] = $data['id'];
      }
   }

   Dropdown::show('PluginBackupsWork',
                  array('name'      => $_POST['myname'],
                        'used'      => $used,
                        'entity'    => $_POST['entity'],
                        'rand'      => $_POST['rand'],
                        'condition' => "glpi_plugin_backups_works.plugin_backups_worktypes_id='".$_POST["nastypes_id"]."'"));

?>