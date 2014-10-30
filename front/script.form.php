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

define('GLPI_ROOT', '../../..');
include (GLPI_ROOT."/inc/includes.php");

if (!isset($_GET["id"])) $_GET["id"] = "";
if (!isset($_GET["withtemplate"])) $_GET["withtemplate"] = "";

$script     = new PluginBackupsScript();
$workscript = new PluginBackupsWork_Script();

if (isset($_POST["add"]))
{
   $script->check(-1,'w',$_POST);
   $newID=$script->add($_POST);
   Html::back();
}
else if (isset($_POST["delete"]))
{
   $script->check($_POST['id'],'w');
   $script->delete($_POST);
   $script->redirectToList();
}
else if (isset($_POST["restore"]))
{
   $script->check($_POST['id'],'w');
   $script->delete($_POST,1);
   $script->redirectToList();
}
else if (isset($_POST["purge"]))
{
   $script->check($_POST['id'],'w');
   $script->delete($_POST,1);
   $script->redirectToList();
}
else if (isset($_POST["update"]))
{
   $script->check($_POST['id'],'w');
   $script->update($_POST);
   Html::back();
}
else
{
   $script->checkGlobal("r");
   
   $plugin = new Plugin();
   if ($plugin->isActivated("environment"))
      Html::header($LANG['plugin_backups']['title'][0],'',"plugins","environment","scripts");
   else
      Html::header($LANG['plugin_backups']['title'][0],'',"plugins","backups","scripts");

   $script->showForm($_GET["id"]);

   Html::footer();
}

?>