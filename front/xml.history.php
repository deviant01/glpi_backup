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
if(empty($_GET["xml"])) $_GET["xml"] = "";

if (isset($_POST["update_display"])) {

   Html::redirect($CFG_GLPI["root_doc"]."/plugins/backups/front/xml.history.php?xml=".$_POST["xml"]);

}else {

   $plugin = new Plugin();
   if ($plugin->isActivated("environment"))
      Html::header($LANG['plugin_backups']['title'][9],'',"plugins","environment","history");
   else
      Html::header($LANG['plugin_backups']['title'][9],'',"plugins","backups","history");

   $dir    = "../xml/";
   $file_xml = $dir.$_GET["xml"];
   $array_dir  = array();
   $array_file = array();

   if (is_dir($dir)) {
      if ($dh = opendir($dir)) {
         while (($file = readdir($dh)) !== false)
         {
         $filename = $file;
         $filetype = filetype($dir . $file);
         $filedate = Html::convdate(date ("Y-m-d", filemtime($dir . $file)));
         $basename=basename($filename);
         $explode_basename=explode('.', $basename);
         $extension = array_pop($explode_basename);
         if ($filename == ".." OR $filename == ".")
         {
         echo "";
         }
         else
            {
            if ($filetype == 'file' && $extension =='xml')
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
   if (!empty($array_file)){

      echo "<div align='center'><form method='post' action=\"./xml.history.php\">";
      echo "<table class='tab_cadre'>";
      echo "<tr><th colspan='2'>".$LANG['plugin_backups']['parser'][0]." XML</th></tr>";
      echo "<tr class='tab_bg_3'><td align='left' valign='top'>";
      echo "<select name='xml'>";
      foreach ($array_file as $item)
         echo "<option value='".$item[0]."' ".($item[0]==$_GET["xml"]?" selected ":"").">".$item[0]." - ".$item[1]."</option>";
         
      echo "</select></td>";
      echo "<td><input type='submit' name='update_display' value='".$LANG['plugin_backups']['parser'][1]."' class='submit' ></td>";
      echo "</tr>";
      echo "</table>";
      Html::closeForm();
      echo "</div>";
   }else{
      echo "<div align='center'><img src=\"".$CFG_GLPI["root_doc"]."/pics/warning.png\" alt=\"warning\"><br><br>";
      echo "<b>".$LANG['plugin_backups']['parser'][3]." ".$dir."</b></div>";
   }

   $map_array = array(
      "BOLD"    => "B",
      "EMPHASIS" => "I",
      "LITERAL"  => "TT"
   );

   function start_Element($parser, $name, $attrs){
      global $map_array; 
     // if (isset($map_array[$name]))
      if ($name!="ERRORDESCRIPTION" && $name!="TITLE")
         echo "";
      elseif ($name=="ERRORDESCRIPTION")
         echo "<span class='plugin_backups_color_ko'>";
      elseif ($name=="TITLE")
         echo "<span class='plugin_backups_color_ok'>";
   }

   function end_Element($parser, $name){
      global $map_array;
     if ($name!="ERRORDESCRIPTION" && $name!="TITLE")
         echo "<BR>";
       else
         echo "</span><BR>";
   }

   function character_Data($parser, $data){
      echo $data;
   }
   
   if (isset($_GET["xml"]) && !empty($_GET["xml"])){
      if (PluginBackupsHistory::url_exists($file_xml)){
         $xml_parser = xml_parser_create();
         // Utilisons la gestion de casse, de manière à être sûrs de trouver la balise dans $map_array
         xml_parser_set_option($xml_parser, XML_OPTION_CASE_FOLDING, true);
         xml_set_element_handler($xml_parser, "start_Element", "end_Element");
         xml_set_character_data_handler($xml_parser, "character_Data");
         if (!($fp = fopen($file_xml, "r"))) {
            echo "<div align='center'>".$LANG['plugin_backups']['parser'][2]."</div>";
            Html::footer();
            die();
         }

         echo "<div align='center'>";
         echo "<table class='tab_cadre' width='80%'>";
         echo "<tr><th>".$LANG['plugin_backups']['parser'][4]." : ".$_GET["xml"]."</th></tr>";
         echo "<tr class='tab_bg_3'><td align='left' valign='top'>";
         
         while ($data = fread($fp, 4096)) {
            if (!xml_parse($xml_parser, $data, feof($fp))) {
               die(sprintf("erreur XML : %s à la ligne %d",
               xml_error_string(xml_get_error_code($xml_parser)),
               xml_get_current_line_number($xml_parser)));
            }
         }

         echo "</td></tr>";
         echo "</table></div>";
         
         xml_parser_free($xml_parser);
      }
   }
   Html::footer();

}
?>