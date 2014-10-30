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

$work          = new PluginBackupsWork();
$work_item     = new PluginBackupsWork_Item();
$worktape      = new PluginBackupsWork_Tape();
$worklib       = new PluginBackupsWork_Library();
$workscript    = new PluginBackupsWork_Script();
$workcomputer  = new PluginBackupsWork_Computer();

if (isset($_POST["add"]))
{
   $work->check(-1,'w',$_POST);
   $newID=$work->add($_POST);
   Html::back();
}
else if (isset($_POST["update"]))
{
   $work->check($_POST['id'],'w');
   $work->update($_POST);
   Html::back();
}
else if (isset($_POST["delete"]))
{
   $work->check($_POST['id'],'w');
   if (!empty($_POST["withtemplate"])) {
      $work->delete($_POST,1);
   }else {
      $work->delete($_POST);
   }
   if(!empty($_POST["withtemplate"])) {
      Html::redirect($CFG_GLPI["root_doc"]."/plugins/backups/front/work.template.php?add=0");
   } else {
      $work->redirectToList();
   }
}
else if (isset($_POST["restore"]))
{
   $work->check($_POST['id'],'w');
   $work->delete($_POST,1);
   $work->redirectToList();
}
else if (isset($_POST["purge"]))
{
   $work->check($_POST['id'],'w');
   $work->delete($_POST,1);
   if(!empty($_POST["withtemplate"])) {
      Html::redirect($CFG_GLPI["root_doc"]."/plugins/backups/front/work.template.php?add=0");
   } else {
      $work->redirectToList();
   }
}
else if (isset($_POST["addworkscript"])){

   $template=0;
   if (isset($_POST["is_template"]) && !empty($_POST["is_template"])) $template=1;
   if (plugin_backups_haveRight("works","w")) {
      $workscript->add($_POST);
   }
   if(!empty($_POST["is_template"])) {
      Html::redirect("./work.form.php?id=".$_POST["works_id"]."&withtemplate=".$_POST["is_template"]);
   } else {
      Html::back();
   }
}
else if (isset($_POST["deleteworkscript"])){

    foreach ($_POST["check"] as $ID => $value) {
      $workscript->delete(array("id"=>$ID), 1);
   }
   Html::back();
}
//libraries

else if (isset($_POST["addworklibrary"])){

   $template=0;
   if (isset($_POST["is_template"]) && !empty($_POST["is_template"])) $template=1;

   if (plugin_backups_haveRight("works","w")) {
      $worklib->add($_POST);
   }
   if(!empty($_POST["is_template"])) {
      Html::redirect("./work.form.php?id=".$_POST["works_id"]."&withtemplate=".$_POST["is_template"]);
   } else {
      Html::back();
   }
}
else if (isset($_POST["deleteworklibrary"])){

   foreach ($_POST["check"] as $ID => $value) {
      $worklib->delete(array("id"=>$ID), 1);
   }
   Html::back();
}
//computers
else if (isset($_POST["addworkcomputer"])){

   $template=0;
   if (isset($_POST["is_template"]) && !empty($_POST["is_template"])) $template=1;

   if (plugin_backups_haveRight("tapes","w")) {
      $workcomputer->add($_POST);
   }
   if(!empty($_POST["is_template"])) {
      Html::redirect("./work.form.php?id=".$_POST["works_id"]."&withtemplate=".$_POST["is_template"]);
   } else {
      Html::back();
   }
}
else if (isset($_POST["deleteworkcomputer"])){

   foreach ($_POST["check"] as $ID => $value) {
      $workcomputer->delete(array("id"=>$ID), 1);
   }
   Html::back();
}
//peripheral - software - nas
else if (isset($_POST["additem"])){

   if (!empty($_POST['itemtype'])&&$_POST['items_id']>0) {
      $work_item->check(-1,'w',$_POST);
      $work_item->add($_POST);
   }
   Html::back();
}
else if (isset($_POST["deleteitem"])){
   foreach ($_POST["item"] as $key => $val) {
      $input = array('id' => $key);
      if ($val==1) {
         $work_item->check($key,'w');
         $work_item->delete($input);
      }
   }
   Html::back();

}
else
{
   $work->checkGlobal("r");
   
   $plugin = new Plugin();
   if ($plugin->isActivated("environment"))
      Html::header($LANG['plugin_backups']['title'][0],'',"plugins","environment","works");
   else
      Html::header($LANG['plugin_backups']['title'][0],'',"plugins","backups","works");

   $work->showForm($_GET["id"], array('withtemplate' => $_GET["withtemplate"]));

   Html::footer();
}

?>