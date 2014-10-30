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

$lib     = new PluginBackupsLibrary();
$libtape = new PluginBackupsLibrary_Tape();
$worklib = new PluginBackupsWork_Library();

if (isset($_POST["add"]))
{
   $lib->check(-1,'w',$_POST);
   $newID=$lib->add($_POST);
   Html::back();
}
else if (isset($_POST["delete"]))
{
   $lib->check($_POST['id'],'w');
   $lib->delete($_POST);
   $lib->redirectToList();
}
else if (isset($_POST["restore"]))
{
   $lib->check($_POST['id'],'w');
   $lib->delete($_POST,1);
   $lib->redirectToList();
}
else if (isset($_POST["purge"]))
{
   $lib->check($_POST['id'],'w');
   $lib->delete($_POST,1);
   $lib->redirectToList();
}
else if (isset($_POST["update"]))
{
   $lib->check($_POST['id'],'w');
   $lib->update($_POST);
   Html::back();
}
else
{
   $lib->checkGlobal("r");
   
   $plugin = new Plugin();
   if ($plugin->isActivated("environment"))
      Html::header($LANG['plugin_backups']['title'][0],'',"plugins","environment","libraries");
   else
      Html::header($LANG['plugin_backups']['title'][0],'',"plugins","backups","libraries");

   $lib->showForm($_GET["id"]);

   Html::footer();
}

?>