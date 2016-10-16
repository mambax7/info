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

defined('XOOPS_ROOT_PATH') || exit('XOOPS_ROOT_PATH not defined!');

$module_name = basename(dirname(__DIR__));

if (!function_exists('Info_Load_CSS')) {
    function Info_Load_CSS()
    {
        global $xoopsConfig, $xoTheme;
        $module_name = basename(dirname(__DIR__));
        if (!defined(strtoupper($module_name) . '_CSS_LOADED')) {
            $theme_path   = '/' . $xoopsConfig['theme_set'] . '/modules/' . $module_name;
            $default_path = '/modules/' . $module_name . '/templates';

            //Themepfad
            $rel_path = '';
            if (file_exists($GLOBALS['xoops']->path($theme_path . '/style.css'))) {
                $rel_path = XOOPS_URL . $theme_path . '/style.css';
                //default
            } else {
                $rel_path = XOOPS_URL . $default_path . '/style.css';
            }
            if ($rel_path != '') {
                $xoTheme->addStylesheet($rel_path);
            }
            define(strtoupper($module_name) . '_CSS_LOADED', 1);
        }
    }
}

if (!function_exists('InfoTableExists')) {
    /**
     * @param $tablename
     * @return bool
     */
    function InfoTableExists($tablename)
    {
        global $xoopsDB;
        $result = $xoopsDB->queryF("SHOW TABLES LIKE '$tablename'");
        $ret    = ($xoopsDB->getRowsNum($result) > 0) ? true : false;

        return $ret;
    }
}

if (!function_exists('Info_checkModuleAdmin')) {
    /**
     * @return bool
     */
    function Info_checkModuleAdmin()
    {
        if (file_exists($GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php'))) {
            include_once $GLOBALS['xoops']->path('/Frameworks/moduleclasses/moduleadmin/moduleadmin.php');

            return true;
        } else {
            echo xoops_error("Error: You don't use the Frameworks \"admin module\". Please install this Frameworks");

            return false;
        }
    }
}

if (!function_exists('Info_checkXoopsVersion')) {
    /**
     * @param null $version
     * @return bool
     */
    function Info_checkXoopsVersion($version = null)
    {
        $ret = false;
        if ($version != '') {
            $o_version = explode(' ', XOOPS_VERSION, 2);
            $o_version = $o_version[1];
            $o_version = explode('.', $o_version, 3);
            $s_version = explode('.', $version, 3);
            if ((int)(@$o_version[0]) > (int)(@$s_version[0])) {
                $ret = true;
            } elseif ((int)(@$o_version[0]) == (int)(@$s_version[0])) {
                if ((int)(@$o_version[1]) > (int)(@$s_version[1])) {
                    $ret = true;
                } elseif ((int)(@$o_version[1]) == (int)(@$s_version[1])) {
                    if ((int)(@$o_version[2]) > (int)(@$s_version[2])) {
                        $ret = true;
                    } elseif ((int)(@$o_version[2]) == (int)(@$s_version[2])) {
                        $ret = true;
                    }
                }
            }
        }

        return $ret;
    }
}

if (!function_exists('InfoColumnExists')) {
    /**
     * @param $tablename
     * @param $spalte
     * @return bool
     */
    function InfoColumnExists($tablename, $spalte)
    {
        global $xoopsDB;
        if ($tablename == '' || $spalte == '') {
            return true;
        } // Fehler!!
        $result = $xoopsDB->queryF('SHOW COLUMNS FROM ' . $tablename . " LIKE '" . $spalte . "'");
        $ret    = ($xoopsDB->getRowsNum($result) > 0) ? true : false;

        return $ret;
    }
}

if (!function_exists('setPost')) {
    /**
     * @param $content
     * @param $sets
     * @return bool
     */
    function setPost($content, $sets)
    {
        if (!is_object($content)) {
            return false;
        }
        if (isset($sets)) {
            $content->setVar('cat', (int)(@$sets['cat']));
            $GLOBALS['cat'] = (int)(@$sets['cat']);
            if (isset($sets['title'])) {
                $content->setVar('title', $sets['title']);
            }
            if (isset($sets['ttip'])) {
                $content->setVar('tooltip', $sets['ttip']);
            }
            $content->setVar('title_sicht', (int)(@$sets['title_sicht']));
            $content->setVar('footer_sicht', (int)(@$sets['footer_sicht']));
            $content->setVar('parent_id', (int)(@$sets['parent_id']));
            if (isset($sets['blockid'])) {
                $content->setVar('blockid', (int)$sets['blockid']);
            }
            $content->setVar('link', (int)(@$sets['link']));
            if (isset($sets['address'])) {
                $content->setVar('address', $sets['address']);
            }
            $height = (int)(@$sets['height']);
            $border = (int)(@$sets['border']);
            $width  = (int)(@$sets['width']);
            $align  = trim(@$sets['align']);
            $fr     = array(
                'height' => $height,
                'border' => $border,
                'width'  => $width,
                'align'  => $align
            );
            $content->setVar('frame', serialize($fr));
            $content->setVar('self', (int)(@$sets['self']));
            $content->setVar('click', (int)(@$sets['click']));
            $content->setVar('visible', (int)(@$sets['visible']));
            $content->setVar('submenu', (int)(@$sets['submenu']));
            if (isset($sets['visible_group'])) {
                $content->setVar('visible_group', implode(',', $sets['visible_group']));
            }
            $content->setVar('bl_left', (int)(@$sets['bl_left']));
            $content->setVar('bl_right', (int)(@$sets['bl_right']));
            if (isset($sets['message'])) {
                $content->setVar('text', trim($sets['message']));
            }
            $content->setVar('nohtml', (int)(@$sets['nohtml']));
            $content->setVar('nosmiley', (int)(@$sets['nosmiley']));
            $content->setVar('nocomments', (int)(@$sets['nocomments']));
            $content->setVar('owner', (int)(@$sets['owner']));
            $content->setVar('st', (int)(@$sets['st']));
            if (isset($sets['tags'])) {
                $content->setVar('tags', $sets['tags']);
            }
        }

        return $content;
    }
}

if (!function_exists('info_cleanVars')) {
    /**
     * @param        $global
     * @param        $key
     * @param string $default
     * @param string $type
     * @param bool   $notset
     * @return bool|false|int|mixed|string
     */
    function info_cleanVars(
        &$global,
        $key,
        $default = '',
        $type = 'int',
        $notset = false
    ) {
        switch ($type) {
            case 'string':
                $ret = isset($global[$key]) ? filter_var($global[$key], FILTER_SANITIZE_MAGIC_QUOTES) : $default;
                if ($notset) {
                    if (trim($ret) == '') {
                        $ret = $default;
                    }
                }
                break;

            case 'date':
                $ret = isset($global[$key]) ? strtotime($global[$key]) : $default;
                break;

            case 'email':
                $ret = isset($global[$key]) ? filter_var($global[$key], FILTER_SANITIZE_EMAIL) : $default;
                $ret = checkEmail($ret);
                break;

            case 'int':
            default:
                $ret = isset($global[$key]) ? filter_var($global[$key], FILTER_SANITIZE_NUMBER_INT) : $default;
                break;

        }
        if ($ret === false) {
            return $default;
        }

        return $ret;
    }
}

if (!function_exists('clearInfoCache')) {
    /**
     * @param string $name
     * @param null   $dirname
     * @param string $root_path
     * @return bool
     */
    function clearInfoCache(
        $name = '',
        $dirname = null,
        $root_path = XOOPS_CACHE_PATH
    ) {
        if (empty($dirname)) {
            $pattern = $dirname ? "{$dirname}_{$name}.*\.php" : "[^_]+_{$name}.*\.php";
            if ($handle = opendir($root_path)) {
                while (false !== ($file = readdir($handle))) {
                    if (is_file($root_path . '/' . $file)
                        && preg_match("/{$pattern}$/", $file)
                    ) {
                        @unlink($root_path . '/' . $file);
                    }
                }
                closedir($handle);
            }
        } else {
            $files = (array)glob($root_path . "/*{$dirname}_{$name}*.php");
            foreach ($files as $file) {
                @unlink($file);
            }
        }

        return true;
    }
}

if (!function_exists('makeSeoUrl')) {
    /**
     * @param null $mod
     * @return string
     */
    function makeSeoUrl($mod = null)
    {
        $search       = array('ä', 'Ä', 'ö', 'Ö', 'ü', 'Ü', 'ß', ' ');
        $replace      = array('ae', 'Ae', 'oe', 'Oe', 'ue', 'Ue', 'ss', '_');
        $mod['title'] = str_replace($search, $replace, utf8_decode($mod['title']));

        if ($mod['seo'] == 1) {
            $content = XOOPS_URL
                       . '/modules/'
                       . $mod['dir']
                       . '/'
                       . $mod['cat']
                       . ':'
                       . $mod['id']
                       . '-'
                       . urlencode($mod['title'])
                       . '.html';
        } elseif ($mod['seo'] == 2) {
            $content = XOOPS_URL
                       . '/modules/'
                       . $mod['dir']
                       . '/'
                       . '?'
                       . $mod['cat']
                       . ':'
                       . $mod['id']
                       . '-'
                       . urlencode($mod['title'])
                       . '.html';
        } elseif ($mod['seo'] == 3) {
            $content = XOOPS_URL
                       . '/'
                       . $mod['dir']
                       . '-'
                       . $mod['cat']
                       . ':'
                       . $mod['id']
                       . '-'
                       . urlencode($mod['title'])
                       . '.html';
        } else {
            if (0 === strpos($mod['cat'], 'p')) {
                $content = XOOPS_URL . '/modules/' . $mod['dir'] . '/index.php?pid=' . $mod['id'];
            } else {
                $content = XOOPS_URL
                           . '/modules/'
                           . $mod['dir']
                           . '/index.php?content='
                           . $mod['cat']
                           . ':'
                           . $mod['id'];
            }
        }

        return $content;
    }
}

if (!function_exists('readSeoUrl')) {
    /**
     * @param     $get
     * @param int $seo
     * @return array
     */
    function readSeoUrl($get, $seo = 0)
    {
        $para = array('id' => 0, 'cid' => 0, 'pid' => 0);

        if ($seo == 2) {
            if ($_SERVER['QUERY_STRING'] != '') {
                $query = explode('-', $_SERVER['QUERY_STRING'], 2);
                if (0 === strpos($query[0], 'p')) {
                    $query       = substr($query[0], 1);
                    $query       = explode(':', $query);
                    $para['pid'] = (int)$query[1];
                } elseif (0 === strpos($query[0], 'content=')) {
                    $id = explode(':', $get['content']);
                    if (count($id) == 2) {
                        $para['id']  = (int)$id[1];
                        $para['cid'] = (int)$id[0];
                    } else {
                        $para['id'] = (int)$id[0];
                    }
                } else {
                    $id = explode(':', $query[0]);
                    if (count($id) == 2) {
                        $para['id']  = (int)$id[1];
                        $para['cid'] = (int)$id[0];
                    }
                }
            }
        } else {
            if (!empty($get['content'])) {
                $id = explode(':', $get['content']);
                if (count($id) == 2) {
                    $para['id']  = (int)$id[1];
                    $para['cid'] = (int)$id[0];
                } else {
                    $para['id'] = (int)$id[0];
                }
            } elseif (!empty($get['pid'])) {
                $para['pid'] = (int)$get['pid'];
            }
        }

        return $para;
    }
}
