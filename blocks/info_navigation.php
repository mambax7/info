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
//  @package info_navigation.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: info_navigation.php 89 2014-04-12 19:28:07Z alfred $

if (! defined('XOOPS_ROOT_PATH')) {
    die();
}

include_once dirname(__DIR__) . '/include/function.php';
Info_Load_CSS();

if (!function_exists('info_navblock_edit')) {
    function info_navblock_edit($options)
    {
        global $xoopsDB;
        $module_name = basename(dirname(__DIR__)) ;
        $sql= 'SELECT cat_id,title FROM '
            . $xoopsDB->prefix($module_name . '_cat') . ' ORDER BY title';
        $result=$xoopsDB->query($sql);
        if ($result && $xoopsDB->getRowsNum($result)>0) {
            $form = '' . _INFO_BL_OPTION . '&nbsp;&nbsp;';
            $form .= "<input type='hidden' name='options[0]' value='".$module_name."'>";
            $form .= "<select name='options[1]' size='1'>";
            while ($row=$xoopsDB->fetcharray($result)) {
                $form .= "<option value='".$row['cat_id']."'";
                if ($options[1] == $row['cat_id']) {
                    $form .= ' selected';
                }
                $form .= '> ' . $row['title'] . ' </option>';
            }
            $form .= '</select>';
            $form .= '<br />' . _INFO_BL_OPTION1 . '&nbsp;&nbsp;';
            $form .= "<select name='options[2]' size='1'>";
            $form .= "<option value='dynamisch'";
            if (isset($options[2]) && $options[2] == 'dynamisch') {
                $form .= ' selected';
            }
            $form .= '> ' . _INFO_BL_OPTION2 . ' </option>';
            $form .= "<option value='fest'";
            if (isset($options[2]) && $options[2] == 'fest') {
                $form .= ' selected';
            }
            $form .= '> ' . _INFO_BL_OPTION3 . ' </option>';
            $form .= '</select>';
            return $form;
        }
    }
}


if (!function_exists('info_block_nav')) {
    function info_block_nav($options)
    {
        global $xoopsDB, $xoopsModule, $xoopsTpl, $xoopsUser,$xoopsConfig;
        global $xoopsRequestUri,$moduleHandler,$configHandler;
        global $id,$pid,$cat;
        if (!is_object($moduleHandler)) {
            $moduleHandler = xoops_getHandler('module');
        }
        require_once XOOPS_ROOT_PATH . '/modules/'
                     . $options[0] . '/class/infotree.php';
        //Variablen erstellen
        $block = array();
        if (empty($options)) {
            return $block;
        }
        $groups =  $xoopsUser ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
        $myts = MyTextSanitizer::getInstance();
        $InfoModule = $moduleHandler->getByDirname($options[0]);
        $InfoModuleConfig = $configHandler->getConfigsByCat(0, $InfoModule->getVar('mid'));
        $seo = (!empty($InfoModuleConfig[$options[0].'_seourl']) && $InfoModuleConfig[$options[0].'_seourl']>0) ? (int)$InfoModuleConfig[$options[0] . '_seourl'] : 0;
        $info_tree = new InfoTree($xoopsDB->prefix($options[0]), 'info_id',
                                  'parent_id');
        
        $key = $InfoModule->getVar('dirname') . '_' . 'block_' . $options[1];
        if (!$arr = XoopsCache::read($key)) {
            $arr = $info_tree->getChildTreeArray(0, 'blockid', array(), $InfoModuleConfig[$options[0] . '_trenner'], ' AND cat=' . $options[1]);
            XoopsCache::write($key, $arr);
        }
         
        $infopermHandler = xoops_getHandler('groupperm');
        $show_info_perm = $infopermHandler->getItemIds('InfoPerm', $groups, $options[0]);
        $mod_isAdmin    = ($xoopsUser && $xoopsUser->isAdmin()) ? true : false;
        if (in_array(_CON_INFO_CANCREATE, $show_info_perm) || $mod_isAdmin) {
            $link['title'] = _MI_INFO_CREATESITE;
            $link['parent'] = 1;
            $link['aktiv'] = 1;
            $link['address'] = XOOPS_URL . '/modules/'
                         . $options[0] . '/submit.php?cat='
                         . $options[1];
            $block['links'][] = $link;
            unset($link);
        }
        foreach ($arr as $i => $tc) {
            $link = array();
            $visible = $info_tree->checkperm($tc['visible_group'], $groups);
            if ($tc['st'] != 1 || $tc['visible'] == 0) {
                $visible = false;
            }
            if ($visible == true) {
                $sub = array();
                if ($id > 0) {
                    $key = $InfoModule->getVar('dirname') . '_' . 'firstblock_'
                           . $id;
                    if (!$first = XoopsCache::read($key)) {
                        $first = $info_tree->getFirstId($id);
                        XoopsCache::write($key, $first);
                    }
                    if ($first > 0) {
                        $key = $InfoModule->getVar('dirname') . '_'
                               . 'subblock_'
                               . $first;
                        if (!$sub = XoopsCache::read($key)) {
                            $sub = $info_tree->getAllChildId($first);
                            XoopsCache::write($key, $sub);
                        }
                    }
                }
        
                $xuid = $xoopsUser ? $xoopsUser->getVar('uid') : 0;
                $tc['address'] = str_replace('{xuid}', $xuid, $tc['address']);  //automatisch generierte uid
                $link['id'] = $tc['info_id'];
                $prefix = (!empty($tc['prefix'])) ? $tc['prefix'] : '';
                $link['title'] = $prefix . $tc['title'];
                $link['parent'] = $tc['parent_id'];
                $mode=array(
                    'seo' =>$seo, 'id' => $tc['info_id'], 'title' => $tc['title'], 'dir' => $options[0],
                    'cat' => $tc['cat']);
                $ctURL = makeSeoUrl($mode);
                if ($tc['link'] == 1) { //int.Link
                    if (substr($tc['address'], -1) == '/' || substr($tc['address'], -1) == "\\") {
                        $tc['address'] .= 'index.php';
                    }
                    $link['target'] = ((int)$tc['self'] == 1) ? '_blank' : '_self';
                } elseif ($tc['link'] == 2) { // ext.Link
                    $ok = (substr($tc['address'], 0, 4) == 'http'
                           || substr($tc['address'], 0, 3) == 'ftp') ? 1:0;
                    if ($ok==1) {
                        $contentURL = $tc['address'];
                    }
                    $link['target'] = ((int)$tc['self'] == 1) ? '_blank' : '_self';
                } elseif ($tc['link'] == 3) {
                    $mode=array(
                        'seo' =>$seo, 'id' => $tc['info_id'], 'title' => $tc['title'], 'dir' => $options[0],
                        'cat' => 'p' . $tc['cat']);
          //eval ('$ctURL = seo_plugin_'.$options[0].'_make($mode);');
                    $link['kategorie'] = '1';
                    $link['click'] = $tc['click'];
                }
        
                $link['address'] = trim($ctURL);
                if ($tc['tooltip'] != '') {
                    $tooltext = strip_tags($tc['tooltip']);
                    $link['tooltip'] = $tooltext;
                } else {
                    $link['tooltip'] = $link['title'];
                }

                if ($options[2] == 'fest') {
                    $link['aktiv'] = 1;
                } else {
                    $link['aktiv'] = 0;
                    if ($tc['parent_id'] == $id || $tc['parent_id'] == 0) {
                        $link['aktiv'] = 1;
                    }
                    if (in_array((int)$tc['info_id'], $sub)) {
                        $link['aktiv'] = 1;
                    }
                }
                $block['links'][] = $link;
                unset($link);
            }
        }
        print_r($block);
        return $block;
    }
}
