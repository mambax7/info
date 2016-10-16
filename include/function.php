<?php
//  ------------------------------------------------------------------------ //
//                XOOPS - PHP Content Management System                      //
//                    Copyright (c) 2000 xoops.org                           //
//                       <http://www.xoops.org/>                             //
//  ------------------------------------------------------------------------ //
//  This program is free software; you can redistribute it and/or modify     //
//  it under the terms of the GNU General Public License as published by     //
//  the Free Software Foundation; either version 2 of the License, or        //
//  (at your option) any later version.                                      //
//                                                                           //
//  You may not change or alter any portion of this comment or credits       //
//  of supporting developers from this source code or any supporting         //
//  source code which is considered copyrighted (c) material of the          //
//  original comment or credit authors.                                      //
//                                                                           //
//  This program is distributed in the hope that it will be useful,          //
//  but WITHOUT ANY WARRANTY; without even the implied warranty of           //
//  MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the            //
//  GNU General Public License for more details.                             //
//                                                                           //
//  You should have received a copy of the GNU General Public License        //
//  along with this program; if not, write to the Free Software              //
//  Foundation, Inc., 59 Temple Place, Suite 330, Boston, MA  02111-1307 USA //
//  ------------------------------------------------------------------------ //
//  @package function.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id $

if( ! defined( 'XOOPS_ROOT_PATH' ) )  die("XOOPS_ROOT_PATH not defined!");

$module_name = basename(dirname(dirname(__FILE__))) ;

if (!function_exists("Info_Load_CSS")) {
  function Info_Load_CSS() { 
    global $xoopsConfig, $xoTheme;
    $module_name = basename(dirname(dirname(__FILE__))) ;
    if( ! defined( strtoupper($module_name) . '_CSS_LOADED' ) ) {
            
      $theme_path 	= "/" . $xoopsConfig['theme_set'] . "/modules/" . $module_name;
      $default_path 	= "/modules/" . $module_name . "/templates";

      //Themepfad
      $rel_path = "";
      if (file_exists( $GLOBALS['xoops']->path( $theme_path . '/style.css'))) {
        $rel_path = XOOPS_URL . $theme_path . '/style.css';
      //default
      } else {
        $rel_path = XOOPS_URL . $default_path . '/style.css';
      }
      if ($rel_path != '') {
        $xoTheme->addStylesheet($rel_path); 
      }
      define( strtoupper($module_name) . '_CSS_LOADED' , 1);
    }
  }
}

if (!function_exists("InfoTableExists")) {
    function InfoTableExists($tablename) {
      global $xoopsDB;
      $result=$xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");
      $ret = ($xoopsDB->getRowsNum($result) > 0) ? true : false;
      return $ret;
    }
}

if (!function_exists("Info_checkModuleAdmin")) {
  function Info_checkModuleAdmin()
  {
    if ( file_exists($GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php'))){
      include_once $GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php');
      return true;
    }else{
      echo xoops_error("Error: You don't use the Frameworks \"admin module\". Please install this Frameworks");
      return false;
    }
  }
}

if (!function_exists("Info_checkXoopsVersion")) {
  function Info_checkXoopsVersion($version = null)
  {
    $ret = false;
    if ($version != "") {
      $o_version = explode(" ", XOOPS_VERSION, 2);
      $o_version = $o_version[1];
      $o_version = explode(".",$o_version, 3);
      $s_version = explode(".",$version, 3);
      if (intval(@$o_version[0]) > intval(@$s_version[0])) {
        $ret = true;
      } elseif (intval(@$o_version[0]) == intval(@$s_version[0])) {
        if (intval(@$o_version[1]) > intval(@$s_version[1])) {
          $ret = true;
        } elseif (intval(@$o_version[1]) == intval(@$s_version[1])) {
          if (intval(@$o_version[2]) > intval(@$s_version[2])) {
            $ret = true;
          } elseif (intval(@$o_version[2]) == intval(@$s_version[2])) {
            $ret = true;
          }         
        }
      }
    }
    return $ret;
  }
}

if (!function_exists("InfoColumnExists")) {
    function InfoColumnExists($tablename,$spalte) {
      global $xoopsDB;
      if ($tablename=="" || $spalte=="") return true; // Fehler!!
      $result=$xoopsDB->queryF("SHOW COLUMNS FROM ". $tablename ." LIKE '".$spalte."'");
		  $ret = ($xoopsDB->getRowsNum($result) > 0) ? true : false;
      return $ret;
    }
}

if (!function_exists("setPost")) {
	function setPost($content,$sets) {
		if (!is_object($content)) return false;
		if (isset($sets)) {
			$content->setVar("cat",intval(@$sets['cat']));
			$GLOBALS['cat'] = intval(@$sets['cat']);
			if (isset($sets['title']))  $content->setVar("title",$sets['title']);
			if (isset($sets['ttip']))   $content->setVar("tooltip",$sets['ttip']);
			$content->setVar("title_sicht",intval(@$sets['title_sicht']));
      $content->setVar("footer_sicht",intval(@$sets['footer_sicht']));
			$content->setVar("parent_id",intval(@$sets['parent_id']));
			if (isset($sets['blockid'])) $content->setVar("blockid",intval($sets['blockid']));
			$content->setVar("link",intval(@$sets['link']));
			if (isset($sets['address'])) $content->setVar("address",$sets['address']);
			$height = intval(@$sets['height']);
			$border = intval(@$sets['border']);
			$width =  intval(@$sets['width']);
      $align =  trim(@$sets['align']);	
			$fr = array('height'=>$height, 'border'=>$border, 'width'=>$width, 'align'=>$align);
			$content->setVar("frame",serialize($fr));
			$content->setVar("self",intval(@$sets['self']));
			$content->setVar("click",intval(@$sets['click']));
			$content->setVar("visible",intval(@$sets['visible']));
			$content->setVar("submenu",intval(@$sets['submenu']));
			if (isset($sets['visible_group'])) $content->setVar("visible_group",implode(',',$sets['visible_group']));
			$content->setVar("bl_left",intval(@$sets['bl_left']));
			$content->setVar("bl_right",intval(@$sets['bl_right']));
			if (isset($sets['message'])) $content->setVar("text",trim($sets['message']));
			$content->setVar("nohtml",intval(@$sets['nohtml']));
			$content->setVar("nosmiley",intval(@$sets['nosmiley']));
			$content->setVar("nocomments",intval(@$sets['nocomments']));
			$content->setVar("owner",intval(@$sets['owner']));
			$content->setVar("st",intval(@$sets['st']));
			if (isset($sets['tags'])) $content->setVar("tags",$sets['tags']);
		}
		return $content;
	}
}

if (!function_exists("info_cleanVars")) {
function info_cleanVars( &$global, $key, $default = '', $type = 'int', $notset=false ) {
    switch ( $type ) {
      case 'string':
        $ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_MAGIC_QUOTES ) : $default;
        if ($notset) {
          if ( trim($ret) == '') $ret = $default;
        }
      break;

		case 'date':
			$ret = ( isset( $global[$key] ) ) ? strtotime($global[$key]) : $default;
			break;

		case 'email':
			$ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_EMAIL ) : $default;
			$ret = checkEmail($ret);
			break;

		case 'int': 
		default:
            $ret = ( isset( $global[$key] ) ) ? filter_var( $global[$key], FILTER_SANITIZE_NUMBER_INT ) : $default;
            break;

    }
    if ( $ret === false ) {
        return $default;
    }
    return $ret;
}
}

if (!function_exists("clearInfoCache")) {
	function clearInfoCache($name = "", $dirname = null, $root_path = XOOPS_CACHE_PATH)
	{
		if (empty($dirname)) {
			$pattern = ($dirname) ? "{$dirname}_{$name}.*\.php" : "[^_]+_{$name}.*\.php";
			if ($handle = opendir($root_path)) {
				while (false !== ($file = readdir($handle))) {
					if (is_file($root_path . '/' . $file) && preg_match("/{$pattern}$/", $file)) {
						@unlink($root_path . '/' . $file);
					}
				}
				closedir($handle);
			}
		} else {
			$files = (array) glob($root_path . "/*{$dirname}_{$name}*.php");
			foreach ($files as $file) {
				@unlink($file);
			}
		}
		return true;
	}
}

if (!function_exists("makeSeoUrl")) {
	function makeSeoUrl($mod = null)
	{
		$search = array ("ä","Ä","ö","Ö","ü","Ü","ß"," ");
    $replace = array("ae","Ae","oe","Oe","ue","Ue","ss","_");
    $mod["title"] = str_replace ($search, $replace, utf8_decode($mod["title"]));
	
    if ($mod["seo"] == 1)
      $content = XOOPS_URL . "/modules/" . $mod["dir"] . "/" . $mod["cat"] . ":" . $mod["id"] . "-" . urlencode($mod["title"]) . ".html";
    elseif ($mod["seo"] == 2)
      $content = XOOPS_URL . "/modules/" . $mod["dir"] . "/" . "?" . $mod["cat"] . ":" . $mod["id"] . "-" . urlencode($mod["title"]) . ".html";
    elseif ($mod["seo"] == 3)
      $content = XOOPS_URL . "/" . $mod["dir"] . "-" . $mod["cat"] . ":" . $mod["id"] . "-" . urlencode($mod["title"]) . ".html";
    else {
      if (substr($mod["cat"],0,1) == "p") {
        $content = XOOPS_URL . "/modules/" . $mod["dir"] . "/index.php?pid=" . $mod["id"];
      } else {
        $content = XOOPS_URL . "/modules/" . $mod["dir"]. "/index.php?content=" . $mod["cat"] . ":" . $mod["id"];
      }
    }
    return $content;
	}
}

if (!function_exists("readSeoUrl")) {
	function readSeoUrl($get, $seo = 0)
	{
		$para=array("id"=>0,"cid"=>0,"pid"=>0);
    
    if ($seo == 2) {
      if ($_SERVER["QUERY_STRING"] != "") {
        $query = explode("-", $_SERVER["QUERY_STRING"], 2);
        if (substr($query[0],0,1) == "p") {
          $query  = substr($query[0],1);
          $query = explode(":",$query);
          $para["pid"] = intval($query[1]);		   
        } elseif (substr($query[0],0,8)=="content=") {
          $id = explode(":",$get["content"]);
          if (count($id) == 2) {
            $para["id"] = intval($id[1]);
            $para["cid"] = intval($id[0]);
          } else {
            $para["id"] = intval($id[0]);
          }
        } else {
          $id = explode(":",$query[0]);
          if (count($id)==2) {
            $para["id"] = intval($id[1]);
            $para["cid"] = intval($id[0]);
          }
        }
      } 
    } else {
      if (!empty($get["content"])) {
        $id = explode(":", $get["content"]);
        if (count($id) == 2) {
          $para["id"] = intval($id[1]);
          $para["cid"] = intval($id[0]);
        } else {
          $para["id"] = intval($id[0]);
        }
      } elseif (!empty($get["pid"])) {
        $para["pid"] = intval($get["pid"]);
      } 
    }
    return $para;
	}
}

?>