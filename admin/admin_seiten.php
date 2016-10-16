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
//  @package admin_seiten.php
//  @author Dirk Herrmann <alfred@simple-xoops.de>
//  @version $Id: admin_seiten.php 91 2014-04-19 20:09:50Z alfred $

include_once __DIR__ . '/admin_header.php';
include_once __DIR__ . '/../include/function.php';

global $xoopsUser, $indexAdmin;
$op          = info_cleanVars($_REQUEST, 'op', 'show', 'string');
$id          = info_cleanVars($_REQUEST, 'id', 0, 'int');
$cat         = info_cleanVars($_REQUEST, 'cat', 1, 'int');
$groupid     = info_cleanVars($_REQUEST, 'groupid', 0, 'int');
$mod_isAdmin = ($xoopsUser && $xoopsUser->isAdmin()) ? true : false;

$infothisgroups  = is_object($xoopsUser) ? $xoopsUser->getGroups() : array(XOOPS_GROUP_ANONYMOUS);
$infopermHandler = xoops_getHandler('groupperm');
$show_info_perm  = $infopermHandler->getItemIds('InfoPerm', $infothisgroups, $xoopsModule->getVar('mid'));

xoops_load('XoopsCache');

switch ($op) {
    case 'appdel':
        if ($id > 0) {
            $content = $infowaitHandler->get($id);
            xoops_cp_header();
            echo $indexAdmin->addNavigation('admin_seiten.php');
            $msg     = sprintf(_INFO_INFODELETE_AENDERUNG, $content->getVar('title'));
            $hiddens = array('op' => 'appdelok', 'cat' => $cat, 'id' => $id);
            xoops_confirm($hiddens, 'admin_seiten.php', $msg);
            xoops_cp_footer();
        }
        break;
    case 'appdelok':
        if ($id > 0) {
            $content = $infowaitHandler->get($id);
            if ($infowaitHandler->delete($content)) {
                $key = $key = $xoopsModule->getVar('dirname') . '_' . '*';
                clearInfoCache($key);
                redirect_header('admin_seiten.php?op=approved', 1, _INFO_DBUPDATED);
            } else {
                redirect_header('admin_seiten.php?op=approved', 3, _INFO_ERRORINSERT);
            }
            exit();
        }
        break;
    case 'approved':
        xoops_cp_header();
        echo $indexAdmin->addNavigation('admin_seiten.php');
        $infowait =& $infowaitHandler->getAll(null, array(
            'info_id',
            'title',
            'edited_time',
            'edited_user'
        ), false, false);
        $form     = new XoopsThemeForm('', $xoopsModule->getVar('dirname') . '_form_wait', XOOPS_URL
                                                                                           . '/modules/'
                                                                                           . $xoopsModule->getVar('dirname')
                                                                                           . '/admin/admin_seiten.php?op=approved');
        $form->setExtra('enctype="multipart/form-data"');
        xoops_load('XoopsUserUtility');
        foreach ($infowait as $t => $tc) {
            $dellink  = "<a href='admin_seiten.php?op=appdel&cat="
                        . $cat
                        . '&id='
                        . $tc['info_id']
                        . "'><img src='"
                        . $pathIcon16
                        . "/delete.png' title='"
                        . _DELETE
                        . "' alt='"
                        . _DELETE
                        . "'></a>";
            $editlink = "<a href='admin_seiten.php?op=appedit&cat="
                        . $cat
                        . '&id='
                        . $tc['info_id']
                        . "'><img src='"
                        . $pathIcon16
                        . "/edit.png' title='"
                        . _EDIT
                        . "' alt='"
                        . _EDIT
                        . "'></a>";
            $edittime = formatTimestamp($tc['edited_time'], 'l');
            $form->addElement(new XoopsFormLabel($editlink . ' | ' . $dellink . ' ' . $tc['title'],
                                                 _INFO_LAST_EDITED . ': ' . sprintf(_INFO_LAST_EDITEDTEXT,
                                                                                    XoopsUserUtility::getUnameFromId($tc['edited_user'],
                                                                                                                     0,
                                                                                                                     false),
                                                                                    $edittime)));
        }
        $form->display();
        xoops_cp_footer();
        break;
    case 'appedit':
        $content = $infowaitHandler->get($id);
        if (!empty($_POST['post'])) {
            $content    = setPost($content, $_POST);
            $oldstoryid = $content->getVar('info_id');
            $content->setVar('info_id', $content->getVar('old_id'));
            if ($content->getVar('info_id') == 0) {
                $content->setNew();
            }
            $content->setVar('edited_time', time());
            $content->setVar('edited_user', $xoopsUser->uid());
            if ($infoHandler->insert($content)) {
                $content->setVar('info_id', $oldstoryid);
                if ($infowaitHandler->delete($content)) {
                    $key = $key = $xoopsModule->getVar('dirname') . '_' . '*';
                    clearInfoCache($key);
                    redirect_header('admin_seiten.php?op=approved', 1, _INFO_DBUPDATED);
                } else {
                    redirect_header('admin_seiten.php?op=approved', 3, _INFO_ERRORINSERT);
                }
            } else {
                redirect_header('admin_seiten.php?op=approved', 3, _INFO_ERRORINSERT);
            }
            exit();
        } else {
            xoops_cp_header();
            echo $indexAdmin->addNavigation('admin_seiten.php');
            $op = 'appedit';
            include_once __DIR__ . '/../include/form.php';
            xoops_cp_footer();
        }
        break;
    case 'delete':
        if ($id > 0) {
            $content = $infoHandler->get($id);
            xoops_cp_header();
            echo $indexAdmin->addNavigation('admin_seiten.php');
            $msg     = _INFO_SETDELETE . '<br><br>' . sprintf(_INFO_INFODELETE_FRAGE, $content->getVar('title'));
            $hiddens = array('op' => 'info_delete', 'cat' => $cat, 'id' => $id);
            xoops_confirm($hiddens, 'admin_seiten.php', $msg);
            xoops_cp_footer();
        }
        break;
    case 'info_delete':
        if ($id > 0) {
            $content = $infoHandler->get($id);
            if ($infoHandler->delete($content)) {
                $key = $key = $xoopsModule->getVar('dirname') . '_' . '*';
                clearInfoCache($key);
                redirect_header('admin_seiten.php?cat=' . $cat, 1, _INFO_DBUPDATED);
            } else {
                redirect_header('admin_seiten.php?cat=' . $cat, 3, _INFO_ERRORINSERT);
            }
            exit();
        }
        break;
    case 'delhp':
        if ($id > 0) {
            $content = $infoHandler->get($id);
            xoops_cp_header();
            echo $indexAdmin->addNavigation('admin_seiten.php');
            $msg     = sprintf(_AM_INFO_SITEDEL_HP, $content->getVar('title'));
            $hiddens = array('op' => 'info_delhp', 'cat' => $cat, 'id' => $id);
            xoops_confirm($hiddens, 'admin_seiten.php', $msg);
            xoops_cp_footer();
        }
        break;
    case 'info_delhp':
        if ($id > 0) {
            if ($infoHandler->del_startpage($id)) {
                $key = $key = $xoopsModule->getVar('dirname') . '_' . '*';
                clearInfoCache($key);
                redirect_header('admin_seiten.php?cat=' . $cat, 1, _INFO_DBUPDATED);
            } else {
                redirect_header('admin_seiten.php?cat=' . $cat, 3, _INFO_ERRORINSERT);
            }
            exit();
        }
        break;
    case 'edit':
        $content = $infoHandler->get($id);
        if (isset($_POST['post'])) {
            $content->setVar('edited_time', time());
            if (is_object($xoopsUser)) {
                $content->setVar('edited_user', $xoopsUser->uid());
            } else {
                $content->setVar('edited_user', '0');
            }

            // Upload
            if (isset($_FILES[$_POST['xoops_upload_file'][0]]['name'])
                && $_FILES[$_POST['xoops_upload_file'][0]]['name'] != ''
            ) {
                include_once XOOPS_ROOT_PATH . '/class/uploader.php';
                $allowed_mimetypes = include_once XOOPS_ROOT_PATH . '/include/mimetypes.inc.php';
                $maxfilesize       = ((int)ini_get('post_max_size') < 1) ? 204800 : (int)ini_get('post_max_size')
                                                                                    * 1024
                                                                                    * 1024;
                // $maxfilewidth = 120;
                // $maxfileheight = 120;
                $upload_dir = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/files';
                $uploader   = new XoopsMediaUploader($upload_dir, $allowed_mimetypes,
                                                     $maxfilesize/*, $maxfilewidth, $maxfileheight */);

                if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                    if ($uploader->mediaSize < 1) {
                        $uploader->setErrors(_ER_UP_INVALIDFILESIZE);
                    }
                    if (file_exists($upload_dir . '/' . $uploader->mediaName)) {
                        $uploader->setErrors(_ER_UP_INVALIDFILENAME);
                    }

                    if (count($uploader->errors) > 0) {
                        xoops_cp_header();
                        echo $indexAdmin->addNavigation('admin_seiten.php');
                        $indexAdmin->addItemButton(_MI_INFO_VIEWSITE, 'admin_seiten.php?cat=' . $cat, $icon = 'index');
                        echo $indexAdmin->renderButton();
                        $ret    = 0;
                        $errors = $uploader->getErrors();
                        include_once __DIR__ . '/../include/form.php';
                        xoops_cp_footer();
                        exit();
                    }

                    if (!$uploader->upload()) {
                        if (count($uploader->errors) > 0) {
                            xoops_cp_header();
                            echo $indexAdmin->addNavigation('admin_seiten.php');
                            $indexAdmin->addItemButton(_MI_INFO_VIEWSITE, 'admin_seiten.php?cat=' . $cat,
                                                       $icon = 'index');
                            echo $indexAdmin->renderButton();
                            $ret    = 0;
                            $errors = $uploader->getErrors();
                            include_once __DIR__ . '/../include/form.php';
                            xoops_cp_footer();
                            exit();
                        }
                    }
                } else {
                    if (count($uploader->errors) > 0) {
                        xoops_cp_header();
                        echo $indexAdmin->addNavigation('admin_seiten.php');
                        $indexAdmin->addItemButton(_MI_INFO_VIEWSITE, 'admin_seiten.php?cat=' . $cat, $icon = 'index');
                        echo $indexAdmin->renderButton();
                        $ret    = 0;
                        $errors = $uploader->getErrors();
                        include_once __DIR__ . '/../include/form.php';
                        xoops_cp_footer();
                        exit();
                    }
                }
            }
            $content = setPost($content, $_POST);

            if ($infoHandler->insert($content)) {
                $key = $key = $xoopsModule->getVar('dirname') . '_' . '*';
                clearInfoCache($key);
                redirect_header('admin_seiten.php?cat=' . $cat, 1, _INFO_DBUPDATED);
            } else {
                redirect_header('admin_seiten.php?cat=' . $cat, 3, _INFO_ERRORINSERT);
            }
            exit();
        } else {
            xoops_cp_header();
            echo $indexAdmin->addNavigation('admin_seiten.php');
            $indexAdmin->addItemButton(_MI_INFO_VIEWSITE, 'admin_seiten.php?cat=' . $cat, $icon = 'index');
            echo $indexAdmin->renderButton();
            $ret = 0;
            include_once __DIR__ . '/../include/form.php';
            xoops_cp_footer();
        }
        break;
    case 'update':
        if (isset($_POST['id'])) {
            $id         = $_POST['id'];
            $parent_id  = isset($_POST['parent_id']) ? $_POST['parent_id'] : array();
            $blockid    = $_POST['blockid'];
            $visible    = $_POST['visible'];
            $title      = $_POST['title'];
            $hp         = isset($_POST['hp'][0]) ? (int)$_POST['hp'] : 0;
            $fp         = isset($_POST['fp'][0]) ? (int)$_POST['fp'][0] : 0;
            $nocomments = $_POST['nocomments'];
            $submenu    = $_POST['submenu'];
            foreach ($id as $storyid) {
                if ((int)$storyid > 0) {
                    $fpp = ($storyid == $fp) ? 1 : 0;
                    if ($fpp == 1) {
                        $sql    = 'UPDATE '
                                  . $xoopsDB->prefix($xoopsModule->getVar('dirname'))
                                  . ' SET frontpage=0 WHERE frontpage>0';
                        $result = $xoopsDB->query($sql);
                        $key    = $xoopsModule->getVar('dirname') . '_' . 'startpage';
                        $data   = array(
                            $storyid,
                            $cat,
                            $parent_id[$storyid],
                            $title[$storyid]
                        );
                        XoopsCache::write($key, $data);
                    }
                    if (!isset($parent_id[$storyid])) {
                        $parent_id[$storyid] = 0;
                    }
                    $sql = 'UPDATE ' . $xoopsDB->prefix($xoopsModule->getVar('dirname')) . ' SET ';
                    $sql .= 'parent_id=' . (int)$parent_id[$storyid] . ',';
                    $sql .= 'blockid=' . (int)$blockid[$storyid] . ',';
                    $sql .= 'visible=' . (int)$visible[$storyid] . ',';
                    $sql .= 'cat=' . $cat . ',';
                    $sql .= 'nocomments=' . (int)$nocomments[$storyid] . ',';
                    $sql .= 'submenu=' . (int)$submenu[$storyid] . ',';
                    $sql .= 'frontpage=' . $fpp . '';
                    $sql .= " WHERE info_id='" . (int)$storyid . "'";
                    if (!$result = $xoopsDB->queryF($sql)) {
                        echo _INFO_ERRORINSERT . '<br>[ ' . $sql . ' ]<hr>';
                    }
                }
            }
            $key = $key = $xoopsModule->getVar('dirname') . '_' . '*';
            clearInfoCache($key);
            redirect_header("admin_seiten.php?op=show&amp;cat=$cat", 1, _INFO_DBUPDATED);
            exit();
        } else {
            redirect_header("admin_seiten.php?cat=op=show&amp;$cat", 2, _TAKINGBACK);
            exit();
        }
        break;
    default:
    case 'show':
        xoops_cp_header();
        $content = $infoHandler->get($id);
        echo $indexAdmin->addNavigation('admin_seiten.php?op=show');
        $indexAdmin->addItemButton(_INFO_ADDCONTENT, 'admin_seiten.php?op=edit&amp;cat=' . $cat, $icon = 'add');
        echo $indexAdmin->renderButton();
        $sseite    = _AM_HP_SEITE . ' ';
        $startpage = $infoHandler->read_startpage();
        if (is_array($startpage)) {
            $sseite .= "<a href=\"admin_seiten.php?op=delhp&amp;cat="
                       . $cat
                       . '&amp;id='
                       . $startpage['0']
                       . "\">"
                       . $startpage['1']
                       . '</a>';
        } else {
            $sseite .= _AM_HP_SEITE_NODEF;
        }
        echo $sseite;
        $form = new XoopsThemeForm('', $xoopsModule->getVar('dirname') . '_form_groupcat', XOOPS_URL
                                                                                           . '/modules/'
                                                                                           . $xoopsModule->getVar('dirname')
                                                                                           . '/admin/admin_seiten.php?op=show');
        $form->setExtra('enctype="multipart/form-data"');
        $option_tray = new XoopsFormElementTray('', '');
        $sql         = 'SELECT cat_id,title FROM '
                       . $xoopsDB->prefix($xoopsModule->getVar('dirname') . '_cat')
                       . ' ORDER BY title ASC';
        $result      = $xoopsDB->query($sql);
        $blist       = array();
        if ($result) {
            while ($myrow = $xoopsDB->fetcharray($result)) {
                $blist[$myrow['cat_id']] = $myrow['title'];
            }
        }
        $block_select = new XoopsFormSelect(_INFO_HOMEPAGE, 'cat', $cat);
        $block_select->addOptionArray($blist);
        $block_select->setextra('onchange="document.forms.'
                                . $xoopsModule->getVar('dirname')
                                . '_form_groupcat'
                                . '.submit()"');
        $option_tray->addElement($block_select);
        $group_select = new XoopsFormSelectGroup(_INFO_AM_GROUP, 'groupid', true, $groupid, 1, false);
        $group_select->addOptionArray(array(0 => _ALL));
        $group_select->setExtra('onchange="document.forms.'
                                . $xoopsModule->getVar('dirname')
                                . '_form_groupcat'
                                . '.submit()"');
        $option_tray->addElement($group_select);
        $submit = new XoopsFormButton('', 'post', _SUBMIT, 'submit');
        $option_tray->addElement($submit);
        $form->addElement($option_tray);
        $form->display();
        echo "<form action='admin_seiten.php' method='post'>";
        echo "<input type='hidden' name='op' value='update'>";

        echo "<table border='1' cellpadding='0' cellspacing='1' width='100%' class='outer'>";
        echo "<tr class='odd'>";
        echo "<td width=\"1%\" nowrap><b>" . _INFO_FRONTPAGE . '</b></td>';
        echo "<td width=\"1%\" nowrap><b>" . _INFO_POSITION . '</b></td>';
        echo "<td width=\"93%\" nowrap><b>" . _INFO_LINKNAME . '</b></td>';
        echo "<td width=\"1%\" nowrap><b>" . _INFO_LINKID . '</b></td>';
        echo "<td width=\"1%\" nowrap><b>" . _INFO_VISIBLE . '</b></td>';
        echo "<td width=\"1%\" nowrap><b>" . _INFO_SUBMENU . '</b></td>';
        echo "<td width=\"1%\" nowrap><b>" . _COMMENTS . '</b></td>';
        echo "<td width=\"1%\" nowrap><b>" . _INFO_ACTION . '</b></td></tr>';
        echo '</tr>';
        $info = show_list(0, $groupid, $cat, $id);
        foreach ($info as $z => $tcontent) {
            echo "<tr class='odd'>";
            echo '<td>';
            if (in_array($tcontent['link'], array(0, 1, 4, 5))) {
                $check = ($tcontent['frontpage'] == 1) ? 'checked' : '';
                echo "<input type='radio' name='fp[]' value='" . $tcontent['info_id'] . "' " . $check . ' />';
            } else {
                echo '&nbsp;';
            }

            echo '</td><td>';
            $info_tree->makeMySelBox('title', 'blockid', $tcontent['parent_id'], 1,
                                     'parent_id[' . $tcontent['info_id'] . ']', '',
                                     ' AND cat=' . $cat . ' AND info_id<>' . $tcontent['info_id']);
            echo '</td><td>';
            $title =& $myts->displayTarea($tcontent['title'], 0, 0, 0);
            echo "<input type='hidden' name='title[" . $tcontent['info_id'] . "]' value='" . $title . "' />";
            if ($tcontent['st'] == 2 || $tcontent['st'] == 0) {
                echo '<font color="red">' . _MI_INFO_GESPERRT . '</font>&nbsp;';
            }
            if ($tcontent['visible'] == 0 && $tcontent['submenu'] == 0) {
                echo '<font color="red">' . _AM_INFO_INAKTIVE . '</font>&nbsp;';
            }
            if ($tcontent['link'] == 3) { //kategorie
                echo '<b>' . $title . '</b>';
            } else {
                echo "<a href='"
                     . XOOPS_URL
                     . '/modules/'
                     . $xoopsModule->dirname()
                     . '/index.php?content='
                     . $tcontent['info_id']
                     . "'>"
                     . $title
                     . '</a>';
            }
            echo '</td><td>';
            echo "<input type='hidden' name='id["
                 . $tcontent['info_id']
                 . "]' value='"
                 . $tcontent['info_id']
                 . "' /><input type='text' name='blockid["
                 . $tcontent['info_id']
                 . "]' size='5' maxlength='5' value='"
                 . $tcontent['blockid']
                 . "'/>";
            echo '</td>';
            $check1 = "selected='selected'";
            $check2 = '';
            if ($tcontent['visible'] == '1') {
                $check1 = '';
                $check2 = "selected='selected'";
            }
            if ($tcontent['nocomments'] == '1') {
                $check4 = "selected='selected'";
                $check5 = '';
            } else {
                $check4 = '';
                $check5 = "selected='selected'";
            }
            if ($tcontent['submenu'] == '0') {
                $check6 = "selected='selected'";
                $check7 = '';
            } else {
                $check6 = '';
                $check7 = "selected='selected'";
            }
            echo "<td width=\"1%\" nowrap><select name='visible["
                 . $tcontent['info_id']
                 . "]'><option value='0' "
                 . $check1
                 . ' />'
                 . _NO
                 . "</option><option value='1' "
                 . $check2
                 . ' />'
                 . _YES
                 . '</option></select></td>';
            echo "<td width=\"1%\" nowrap>&nbsp;";
            if ($tcontent['link'] != 3) {
                echo "<select name='submenu["
                     . $tcontent['info_id']
                     . "]'><option value='0' "
                     . $check5
                     . ' />'
                     . _NO
                     . "</option><option value='1' "
                     . $check7
                     . ' />'
                     . _YES
                     . '</option></select>';
            } else {
                echo "<input type=\"hidden\" name=\"submenu["
                     . $tcontent['info_id']
                     . "]\" value=\""
                     . $tcontent['submenu']
                     . "\">";
            }
            echo '</td>';
            echo "<td width=\"1%\" nowrap>&nbsp;";
            if ($tcontent['link'] == 0
                || $tcontent['link'] == 4
                || $tcontent['link'] == 5
            ) {
                echo "<select name='nocomments["
                     . $tcontent['info_id']
                     . "]'><option value='1' "
                     . $check4
                     . ' />'
                     . _NO
                     . "</option><option value='0' "
                     . $check5
                     . ' />'
                     . _YES
                     . '</option></select>';
            } else {
                echo "<input type='hidden' name='nocomments["
                     . $tcontent['info_id']
                     . "]' value='"
                     . $tcontent['nocomments']
                     . "'>";
            }
            echo '</td>';
            echo "<td width=\"1%\" nowrap><a href='admin_seiten.php?op=edit&cat=$cat&id="
                 . $tcontent['info_id']
                 . "'><img src='"
                 . $pathIcon16
                 . "/edit.png' title='"
                 . _EDIT
                 . "' alt='"
                 . _EDIT
                 . "'></a>";
            echo " | <a href='admin_seiten.php?op=delete&cat=$cat&id="
                 . $tcontent['info_id']
                 . "'><img src='"
                 . $pathIcon16
                 . "/delete.png' title='"
                 . _DELETE
                 . "' alt='"
                 . _DELETE
                 . "'></a></td></tr>";
            unset($tcontent);
            echo '</tr>';
        }
        echo '</table>';
        echo "	<input type='hidden' name='op' value='update' />
				<input type='hidden' name='cat' value='" . $cat . "' />
				<input type='submit' name='start' value=" . _SUBMIT . ' />
			 ';

        echo '</form>';
        xoops_cp_footer();
        break;
}

/**
 * @param int $cat0
 * @param int $groupid
 * @param int $cat
 * @param int $aktuell
 * @return array
 */
function show_list($cat0 = 0, $groupid = 0, $cat = 0, $aktuell = 0)
{
    global $info_tree;
    $infolist = $info_tree->getAllChild(0, 'blockid', array(), ' AND cat=' . $cat . ' AND info_id<>' . $aktuell);

    $info = array();
    foreach ($infolist as $s => $t) {
        if ($t['cat'] != $cat) {
            continue;
        }
        $groups = explode(',', $t['visible_group']);
        if ($groupid == 0) {
            $info[$s] = $t;
        } elseif (in_array($groupid, $groups)) {
            $info[$s] = $t;
        }
    }

    return $info;
}
