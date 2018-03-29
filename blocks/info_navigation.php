<?php
/*
 * You may not change or alter any portion of this comment or credits
 * of supporting developers from this source code or any supporting source code
 * which is considered copyrighted (c) material of the original comment or credit authors.
 *
 * This program is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.
 */

/**
 * @copyright    {@link https://xoops.org/ XOOPS Project}
 * @license      {@link http://www.gnu.org/licenses/gpl-2.0.html GNU GPL 2 or later}
 * @package      info module
 * @since
 * @author       XOOPS Development Team
 * @author       Dirk Herrmann <alfred@simple-xoops.de>
 */

defined('XOOPS_ROOT_PATH') || die('XOOPS_ROOT_PATH not defined!');

require_once dirname(__DIR__) . '/include/function.php';
Info_Load_CSS();

if (!function_exists('info_navblock_edit')) {
    /**
     * @param $options
     * @return string
     */
    function info_navblock_edit($options)
    {
        global $xoopsDB;
        $module_name = basename(dirname(__DIR__));
        $sql         = 'SELECT cat_id,title FROM ' . $xoopsDB->prefix($module_name . '_cat') . ' ORDER BY title';
        $result      = $xoopsDB->query($sql);
        if ($result && $xoopsDB->getRowsNum($result) > 0) {
            $form = '' . _INFO_BL_OPTION . '&nbsp;&nbsp;';
            $form .= "<input type='hidden' name='options[0]' value='" . $module_name . "'>";
            $form .= "<select name='options[1]' size='1'>";
            while (false !== ($row = $xoopsDB->fetchArray($result))) {
                $form .= "<option value='" . $row['cat_id'] . "'";
                if ($options[1] == $row['cat_id']) {
                    $form .= ' selected';
                }
                $form .= '> ' . $row['title'] . ' </option>';
            }
            $form .= '</select>';
            $form .= '<br>' . _INFO_BL_OPTION1 . '&nbsp;&nbsp;';
            $form .= "<select name='options[2]' size='1'>";
            $form .= "<option value='dynamisch'";
            if (isset($options[2]) && 'dynamisch' === $options[2]) {
                $form .= ' selected';
            }
            $form .= '> ' . _INFO_BL_OPTION2 . ' </option>';
            $form .= "<option value='fest'";
            if (isset($options[2]) && 'fest' === $options[2]) {
                $form .= ' selected';
            }
            $form .= '> ' . _INFO_BL_OPTION3 . ' </option>';
            $form .= '</select>';

            return $form;
        }
    }
}

if (!function_exists('info_block_nav')) {
    /**
     * @param $options
     * @return array
     */
    function info_block_nav($options)
    {
        global $xoopsDB, $xoopsModule, $xoopsTpl, $xoopsUser, $xoopsConfig;
        global $xoopsRequestUri, $moduleHandler, $configHandler;
        global $id, $pid, $cat;
        if (!is_object($moduleHandler)) {
            $moduleHandler = xoops_getHandler('module');
        }
        if (!is_object($configHandler)) {
            $configHandler = xoops_getHandler('config');
        }
        require_once XOOPS_ROOT_PATH . '/modules/' . $options[0] . '/include/constants.php';
        require_once XOOPS_ROOT_PATH . '/modules/' . $options[0] . '/class/infotree.php';
        //Variablen erstellen
        $block = [];
        if (empty($options)) {
            return $block;
        }
        $groups = $xoopsUser ? $xoopsUser->getGroups() : [XOOPS_GROUP_ANONYMOUS];
        //        $myts             = \MyTextSanitizer::getInstance();
        $infoModule       = $moduleHandler->getByDirname($options[0]);
        $infoModuleConfig = $configHandler->getConfigsByCat(0, $infoModule->getVar('mid'));
        $seo              = (!empty($infoModuleConfig[$options[0] . '_seourl'])
                             && $infoModuleConfig[$options[0] . '_seourl'] > 0) ? (int)$infoModuleConfig[$options[0] . '_seourl'] : 0;
        $infoTree         = new InfoTree($xoopsDB->prefix($options[0]), 'info_id', 'parent_id');

        $key = $infoModule->getVar('dirname') . '_' . 'block_' . $options[1];
        if (!$arr = XoopsCache::read($key)) {
            $arr = $infoTree->getChildTreeArray(0, 'blockid', [], $infoModuleConfig[$options[0] . '_trenner'], ' AND cat=' . $options[1]);
            XoopsCache::write($key, $arr);
        }
        xoops_loadLanguage('modinfo', basename(dirname(__DIR__)));
        $infopermHandler = xoops_getHandler('groupperm');
        $show_info_perm  = $infopermHandler->getItemIds('InfoPerm', $groups, $options[0]);
        $mod_isAdmin     = ($xoopsUser && $xoopsUser->isAdmin()) ? true : false;
        if (in_array(_CON_INFO_CANCREATE, $show_info_perm) || $mod_isAdmin) {
            $link['title']    = _MI_INFO_CREATESITE;
            $link['parent']   = 1;
            $link['aktiv']    = 1;
            $link['address']  = XOOPS_URL . '/modules/' . $options[0] . '/submit.php?cat=' . $options[1];
            $block['links'][] = $link;
            unset($link);
        }
        foreach ($arr as $i => $tc) {
            $link    = [];
            $visible = $infoTree->checkperm($tc['visible_group'], $groups);
            if (1 != $tc['st'] || 0 == $tc['visible']) {
                $visible = false;
            }
            if (true === $visible) {
                $sub = [];
                if ($id > 0) {
                    $key = $infoModule->getVar('dirname') . '_' . 'firstblock_' . $id;
                    if (!$first = XoopsCache::read($key)) {
                        $first = $infoTree->getFirstId($id);
                        XoopsCache::write($key, $first);
                    }
                    if ($first > 0) {
                        $key = $infoModule->getVar('dirname') . '_' . 'subblock_' . $first;
                        if (!$sub = XoopsCache::read($key)) {
                            $sub = $infoTree->getAllChildId($first);
                            XoopsCache::write($key, $sub);
                        }
                    }
                }

                $xuid           = $xoopsUser ? $xoopsUser->getVar('uid') : 0;
                $tc['address']  = str_replace('{xuid}', $xuid, $tc['address']);  //automatisch generierte uid
                $link['id']     = $tc['info_id'];
                $prefix         = (!empty($tc['prefix'])) ? $tc['prefix'] : '';
                $link['title']  = $prefix . $tc['title'];
                $link['parent'] = $tc['parent_id'];
                $mode           = [
                    'seo'   => $seo,
                    'id'    => $tc['info_id'],
                    'title' => $tc['title'],
                    'dir'   => $options[0],
                    'cat'   => $tc['cat']
                ];
                $ctURL          = makeSeoUrl($mode);
                if (1 == $tc['link']) { //int.Link
                    if ('/' === substr($tc['address'], -1)
                        || "\\" === substr($tc['address'], -1)) {
                        $tc['address'] .= 'index.php';
                    }
                    $link['target'] = (1 == (int)$tc['self']) ? '_blank' : '_self';
                } elseif (2 == $tc['link']) { // ext.Link
                    $ok = (0 === strpos($tc['address'], 'http')
                           || 0 === strpos($tc['address'], 'ftp')) ? 1 : 0;
                    if (1 == $ok) {
                        $contentURL = $tc['address'];
                    }
                    $link['target'] = (1 == (int)$tc['self']) ? '_blank' : '_self';
                } elseif (3 == $tc['link']) {
                    $mode = [
                        'seo'   => $seo,
                        'id'    => $tc['info_id'],
                        'title' => $tc['title'],
                        'dir'   => $options[0],
                        'cat'   => 'p' . $tc['cat']
                    ];
                    //eval ('$ctURL = seo_plugin_'.$options[0].'_make($mode);');
                    $link['kategorie'] = '1';
                    $link['click']     = $tc['click'];
                }

                $link['address'] = trim($ctURL);
                if ('' != $tc['tooltip']) {
                    $tooltext        = strip_tags($tc['tooltip']);
                    $link['tooltip'] = $tooltext;
                } else {
                    $link['tooltip'] = $link['title'];
                }

                if ('fest' === $options[2]) {
                    $link['aktiv'] = 1;
                } else {
                    $link['aktiv'] = 0;
                    if ($tc['parent_id'] == $id || 0 == $tc['parent_id']) {
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

        //        print_r($block);

        return $block;
    }
}
