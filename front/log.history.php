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

if(isset($_GET)) $tab = $_GET;
if(empty($tab) && isset($_POST)) $tab = $_POST;
if(empty($tab["id"])) $tab["id"] = "";
if(empty($_GET["log"])) $_GET["log"] = "";

if (isset($_POST["update_display"])) {

   Html::redirect($CFG_GLPI["root_doc"]."/plugins/backups/front/log.history.php?log=".$_POST["log"]);

} else {
   
   $plugin = new Plugin();
   if ($plugin->isActivated("environment"))
      Html::header($LANG['plugin_backups']['title'][9],'',"plugins","environment","history");
   else
      Html::header($LANG['plugin_backups']['title'][9],'',"plugins","backups","history");

   $dir    = "../log/";
   $file_log = $dir.$_GET["log"];
   $array_dir  = array();
   $array_file = array();
   
   if (is_dir($dir)) {
      if ($dh = opendir($dir)) {
         while (($file = readdir($dh)) !== false)
         {
         $filename = $file;
         $filetype = filetype($dir . $file);
         $filedate = Html::convdate(date ("Y-m-d", filemtime($dir . $file)));
         $basename=explode('.', basename($filename));
         $extension = array_pop($basename);
         if ($filename == ".." OR $filename == ".")
         {
         echo "";
         }
         else
            {
            if ($filetype == 'file' && $extension =='log')
               {
               $array_file[] = array($filename,$filedate,$extension);
            }
            elseif ($filetype == "dir")
               {
               $array_dir[] = $filename;
               }
            }
         }
         closedir($dh);
      }
   }

   rsort($array_file);
   if (!empty($array_file)) {
   
      echo "<div align='center'><form method='post' action=\"./log.history.php\">";
      echo "<table class='tab_cadre'>";
      echo "<tr><th colspan='2'>".$LANG['plugin_backups']['parser'][0]." Log</th></tr>";
      echo "<tr class='tab_bg_3'><td align='left' valign='top'>";
      echo "<select name='log'>";
      foreach ($array_file as $item)
         echo "<option value='".$item[0]."' ".($item[0]==$_GET["log"]?" selected ":"").">".$item[0]." - ".$item[1]."</option>";
         
      echo "</select></td>";
      echo "<td><input type='submit' name='update_display' value='".$LANG['plugin_backups']['parser'][1]."' class='submit' ></td>";
      echo "</tr>";
      echo "</table>";
      Html::closeForm();
      echo "</div>";
   } else {
      echo "<div align='center'><img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt=\"warning\"><br><br>";
      echo "<b>".$LANG['plugin_backups']['parser'][3]." ".$dir."</b></div>";
   }
   
   if (isset($_GET["log"]) && !empty($_GET["log"])){
      if (PluginBackupsHistory::url_exists($file_log)) {
   
         if (!($fp = fopen($file_log, "r"))) {
            echo "<div align='center'>".$LANG['plugin_backups']['parser'][2]."</div>";
            Html::footer();
            die();
         }

         echo "<div align='center'>";
         echo "<table class='tab_cadre' width='80%'>";
         echo "<tr><th>".$LANG['plugin_backups']['parser'][4]." : ".$_GET["log"]."</th></tr>";
         echo "<tr class='tab_bg_3'><td align='left' valign='top'>";
      
         //if (Toolbox::seems_utf8($str = fgets($fp))) {
         //   for ($i = 0;$str = fgets($fp); $i++){
         //      echo $str;
         //      echo "<br>";
         //   }
         //} else {
            for ($i = 0;$str = fgets($fp); $i++) {
               $str=Toolbox::encodeInUtf8($str, "UTF-8");
               echo $str;
               echo "<br>";
            }
         //}

         echo "</td></tr>";
         echo "</table></div>";
      
      }
   }
   Html::footer();

}
?>