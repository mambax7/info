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
//  @package info_freiblock.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: info_freiblock.php 73 2013-03-19 20:14:02Z alfred $


if (! defined('XOOPS_ROOT_PATH')) {
    die('XOOPS_ROOT_PATH not defined!');
}

if (!function_exists('info_freiblock_show')) {
    function info_freiblock_show($options)
    {
        global $xoopsDB,$xoopsUser;
        $myts = MyTextSanitizer::getInstance();
        include_once XOOPS_ROOT_PATH . '/modules/'
                     . $options[0] . '/include/constants.php';
        $block = array();
        $result = $xoopsDB->query('SELECT * FROM '
                                  . $xoopsDB->prefix($options[0]) . ' WHERE info_id='
                                  . $options[1]);
        $row = $xoopsDB->fetchArray($result);
        $text = trim($row['text']);
        if ((int)$row['info_id'] != 0) {
            $GLOBALS['xoopsOption']['template_main'] = $options[0].'_startblock.html';
            if ($row['link'] == 6) {
                ob_start();
                echo eval($text);
                $text = ob_get_contents();
                ob_end_clean();
                $row['nohtml'] = 0;
            }
            $html = ((int)$row['nohtml'] == 1) ? 0 : 1;
            $smiley = ((int)$row['nosmiley'] == 1) ? 0 : 1;
            $breaks = ($html == 1) ? 0 : 1;
            if ((int)$row['link'] == 4) {
                if (substr($row['address'], '/', 0, 1) || substr($row['address'], "\\", 0, 1)) {
                    $row['address'] =substr($address, 1);
                }
                $file = XOOPS_ROOT_PATH . '/' . $row['address'];
                if (file_exists($file)) {
                    ob_start();
                    include $file;
                    $file = ob_get_contents();
                    ob_end_clean();
                    $text=$file;
                }
            } elseif ((int)$row['link'] == 5) {
                $iframe=unserialize($row['frame']);
                if (!isset($iframe['width']) || $iframe['width']<1 || $iframe['width']>100) {
                    $iframe['width']=100;
                }
                $text.= "<iframe width='".$iframe['width']."%' height='".$iframe['height']."px' align='".$iframe['align']."' name='".$row['title']."' scrolling='auto' frameborder='".$iframe['border']."' src='".$row['address']."'></iframe>";
                $html = 1;
                $breaks = 0;
            }
            $text = str_replace('{X_XOOPSURL}', XOOPS_URL.'/', $text);
            $text = str_replace('{X_SITEURL}', XOOPS_URL . '/', $text);
            if (is_object($xoopsUser)) {
                $text = str_replace('{X_XOOPSUSER}', $xoopsUser->getVar('uname'), $text);
                $text = str_replace('{X_XOOPSUSERID}', $xoopsUser->getVar('uid'), $text);
            } else {
                $text = str_replace('{X_XOOPSUSER}', _GUESTS, $text);
                $text = str_replace('{X_XOOPSUSERID}', '0', $text);
            }
            if (trim($text) != '') {
                $text = str_replace('<div style="page-break-after: always;"><span style="display: none;"> </span></div>', '[pagebreak]', $text);
                $infotext = explode('[pagebreak]', $text);
                $info_pages = count($infotext);
                if ($info_pages > 1) {
                    $text = $infotext[0];
                }
            }
            $text = $myts->displayTarea($text, $html, $smiley, 1, 1, $breaks);
            $block['content'] = $text;
            
            $block['id'] = $options[1];
        }
        return $block;
    }
}

if (!function_exists('info_freiblock_edit')) {
    function info_freiblock_edit($options)
    {
        global $xoopsDB;
        $module_name = $options[0];
        $result=$xoopsDB->queryF('SELECT info_id,title FROM '
                                 . $xoopsDB->prefix($module_name) . ' WHERE link !=1 && link !=2 && link !=3 && link !=4');
        if ($result) {
            $form = '' . _INFO_BL_OPTION . '&nbsp;&nbsp;';
            $form .= "<input type='hidden' name='options[0]' value='".$module_name."'>";
            $form .= "<select name='options[1]' size='1'>";
            while ($row=$xoopsDB->fetcharray($result)) {
                $form .= "<option value='".$row['info_id']."'";
                if ($options[1] == $row['info_id']) {
                    $form .= ' selected';
                }
                $form .= '> ' . $row['title'] . ' </option>';
            }
            $form .= '</select>';
            return $form;
        }
    }
}
