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

$tape       = new PluginBackupsTape();
$libtape    = new PluginBackupsLibrary_Tape();
$worktape   = new PluginBackupsWork_Tape();

if (isset($_POST["add"]))
{
   $tape->check(-1,'w',$_POST);
   $newID=$tape->add($_POST);
   Html::back();
}
else if (isset($_POST["delete"]))
{
   $tape->check($_POST['id'],'w');
   if (!empty($_POST["withtemplate"])) {
      $tape->delete($_POST,1);
   }else {
      $tape->delete($_POST);
   }
   if(!empty($_POST["withtemplate"])) {
      Html::redirect($CFG_GLPI["root_doc"]."/plugins/backups/front/tape.template.php?add=0");
   } else {
      $tape->redirectToList();
   }
}
else if (isset($_POST["restore"]))
{
   $tape->check($_POST['id'],'w');
   $tape->delete($_POST,1);
   $tape->redirectToList();
}
else if (isset($_POST["purge"]))
{
   $tape->check($_POST['id'],'w');
   $tape->delete($_POST,1);
   if(!empty($_POST["withtemplate"])) {
      Html::redirect($CFG_GLPI["root_doc"]."/plugins/backups/front/tape.template.php?add=0");
   } else {
      $tape->redirectToList();
   }

}
else if (isset($_POST["update"]))
{
   $tape->check($_POST['id'],'w');
   $tape->update($_POST);
   Html::back();
}
//librarytape
else if (isset($_POST["addlibrarytape"])){

   $template=0;
   if (isset($_POST["is_template"]) && !empty($_POST["is_template"])) $template=1;

   if (plugin_backups_haveRight("tapes","w")) {
    $libtape->add($_POST);
   } 
   if(!empty($_POST["is_template"])) {
      Html::redirect("./tape.form.php?id=".$_POST["tapes_id"]."&withtemplate=".$_POST["is_template"]);
   } else {
      Html::back();
   }
}
else if (isset($_POST["deletelibrarytape"])) {

   foreach ($_POST["check"] as $ID => $value) {
      $libtape->delete(array("id"=>$ID), 1);
   }
   Html::back();
   
}
//tapeworks
else if (isset($_POST["addtapework"])){

   $template=0;
   if (isset($_POST["is_template"]) && !empty($_POST["is_template"])) $template=1;

   if (plugin_backups_haveRight("tapes","w")) {
      $worktape->add($_POST);
   }
   if(!empty($_POST["is_template"])) {
      $ID = $_POST["tapes_id"];
      if ($_POST["target"] == "work") {
         $ID = $_POST["works_id"];
      }
      Html::redirect("./".$_POST["target"].".form.php?id=".$ID."&withtemplate=".$_POST["is_template"]);
   } else {
      Html::back();
   }
}
else if (isset($_POST["deletetapework"])){

   foreach ($_POST["check"] as $ID => $value) {
      $worktape->delete(array("id"=>$ID), 1);
   }
   Html::back();
}
else
{
   $tape->checkGlobal("r");
   
   $plugin = new Plugin();
   if ($plugin->isActivated("environment"))
      Html::header($LANG['plugin_backups']['title'][0],'',"plugins","environment","tapes");
   else
      Html::header($LANG['plugin_backups']['title'][0],'',"plugins","backups","tapes");

   $tape->showForm($_GET["id"], array('withtemplate' => $_GET["withtemplate"]));

   Html::footer();
}

?>