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

include __DIR__ . '/../../mainfile.php';
$module_name = basename(__DIR__);

require_once __DIR__ . '/include/function.php';
require_once __DIR__ . '/include/constants.php';
require_once XOOPS_ROOT_PATH . '/class/xoopsformloader.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $module_name . '/class/infotree.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $module_name . '/class/info.php';
require_once XOOPS_ROOT_PATH . '/modules/' . $module_name . '/class/category.php';
xoops_loadLanguage('admin', $module_name);
xoops_loadLanguage('modinfo', $module_name);

$seo  = (!empty($xoopsModuleConfig[$module_name . '_seourl'])
         && $xoopsModuleConfig[$module_name . '_seourl'] > 0) ? (int)$xoopsModuleConfig[$module_name . '_seourl'] : 0;
$myts = MyTextSanitizer::getInstance();

$infoHandler     = new InfoInfoHandler($xoopsDB, $module_name);
$infowaitHandler = new InfoInfoHandler($xoopsDB, $module_name . '_bak');
$catHandler      = new InfoCategoryHandler($xoopsDB, $module_name);
$infoTree        = new InfoTree($xoopsDB->prefix($module_name), 'info_id', 'parent_id');

$op = info_cleanVars($_REQUEST, 'op', '', 'string');
if (!in_array($op, ['edit', 'delete'])) {
    $op = '';
}
$id          = info_cleanVars($_REQUEST, 'id', 0, 'int');
$cat         = info_cleanVars($_REQUEST, 'cat', 1, 'int');
$groupid     = info_cleanVars($_REQUEST, 'groupid', 0, 'int');
$mod_isAdmin = ($xoopsUser && $xoopsUser->isAdmin()) ? true : false;

//Permission
$infothisgroups  = is_object($xoopsUser) ? $xoopsUser->getGroups() : [XOOPS_GROUP_ANONYMOUS];
$infopermHandler = xoops_getHandler('groupperm');
$show_info_perm  = $infopermHandler->getItemIds('InfoPerm', $infothisgroups, $xoopsModule->getVar('mid'));

$content = $infoHandler->get($id);
if (!empty($_POST)) {
    $content = setPost($content, $_POST);
}

$approve = 0;
if (in_array(_CON_INFO_CANUPDATEALL, $show_info_perm) || $mod_isAdmin) {
    $approve = 1;
} elseif (in_array(_CON_INFO_CANCREATE, $show_info_perm) && $id == 0) {
    $approve = 1;
} elseif ($xoopsUser
          && ($xoopsUser->uid() == $content->getVar('owner'))) { // eigene Seite
    if (in_array(_CON_INFO_CANUPDATE, $show_info_perm)) {
        $approve = 1;
    }
}

if ($approve == 0) {
    $mode = [
        'seo'   => $seo,
        'id'    => $content->getVar('info_id'),
        'title' => $content->getVar('title'),
        'dir'   => $module_name,
        'cat'   => $content->getVar('cat')
    ];
    redirect_header(makeSeoUrl($mode), 3, _INFO_MA_NOEDITRIGHT);
}

if ($op === 'edit') {
    if (isset($_POST['post'])) {
        if (!$GLOBALS['xoopsSecurity']->check()) {
            //redirect_header("index.php",3,implode('<br>', $GLOBALS['xoopsSecurity']->getErrors()));
            //exit();
        }

        $content->setVar('edited_time', time());
        if (is_object($xoopsUser)) {
            $content->setVar('edited_user', $xoopsUser->uid());
        } else {
            $content->setVar('edited_user', '0');
        }

        if (in_array(_CON_INFO_ALLCANUPLOAD, $show_info_perm) || $mod_isAdmin) {
            if (isset($_FILES[$_POST['xoops_upload_file'][0]]['name'])
                && $_FILES[$_POST['xoops_upload_file'][0]]['name'] != '') {
                require_once XOOPS_ROOT_PATH . '/class/uploader.php';
                $allowed_mimetypes = require_once XOOPS_ROOT_PATH . '/include/mimetypes.inc.php';
                $maxfilesize       = ((int)ini_get('post_max_size') < 1) ? 204800 : (int)ini_get('post_max_size') * 1024 * 1024;
                // $maxfilewidth = 120;
                // $maxfileheight = 120;
                $upload_dir = XOOPS_ROOT_PATH . '/modules/' . $xoopsModule->getVar('dirname') . '/files';
                $uploader   = new XoopsMediaUploader($upload_dir, $allowed_mimetypes, $maxfilesize/*, $maxfilewidth, $maxfileheight */);

                if ($uploader->fetchMedia($_POST['xoops_upload_file'][0])) {
                    if ($uploader->mediaSize < 1) {
                        $uploader->setErrors(_ER_UP_INVALIDFILESIZE);
                    }
                    if (file_exists($upload_dir . '/' . $uploader->mediaName)) {
                        $uploader->setErrors(_ER_UP_INVALIDFILENAME);
                    }

                    if (count($uploader->errors) > 0) {
                        require_once XOOPS_ROOT_PATH . '/header.php';
                        $sbl = (int)$xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_showrblock'];
                        if ($sbl == 0) {
                            // no blocks
                        } elseif ($sbl == 1) {
                            $GLOBALS['xoopsTpl']->assign('xoops_showrblock', 0);
                        } elseif ($sbl == 2) {
                            $GLOBALS['xoopsTpl']->assign('xoops_showlblock', 0);
                        } elseif ($sbl == 3) {
                            $GLOBALS['xoopsTpl']->assign('xoops_showrblock', 0);
                            $GLOBALS['xoopsTpl']->assign('xoops_showlblock', 0);
                        }
                        $op     = 'edit';
                        $ret    = 1;
                        $errors = $uploader->getErrors();
                        require_once __DIR__ . '/include/form.php';
                        require_once XOOPS_ROOT_PATH . '/footer.php';
                        exit();
                    }
                    if (!$uploader->upload()) {
                        if (count($uploader->errors) > 0) {
                            require_once XOOPS_ROOT_PATH . '/header.php';
                            $sbl = (int)$xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_showrblock'];
                            if ($sbl == 0) {
                                // no blocks
                            } elseif ($sbl == 1) {
                                $GLOBALS['xoopsTpl']->assign('xoops_showrblock', 0);
                            } elseif ($sbl == 2) {
                                $GLOBALS['xoopsTpl']->assign('xoops_showlblock', 0);
                            } elseif ($sbl == 3) {
                                $GLOBALS['xoopsTpl']->assign('xoops_showrblock', 0);
                                $GLOBALS['xoopsTpl']->assign('xoops_showlblock', 0);
                            }
                            $op     = 'edit';
                            $ret    = 1;
                            $errors = $uploader->getErrors();
                            require_once __DIR__ . '/include/form.php';
                            require_once XOOPS_ROOT_PATH . '/footer.php';
                            exit();
                        }
                    }
                } else {
                    if (count($uploader->errors) > 0) {
                        require_once XOOPS_ROOT_PATH . '/header.php';
                        $sbl = (int)$xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_showrblock'];
                        if ($sbl == 0) {
                            // no blocks
                        } elseif ($sbl == 1) {
                            $GLOBALS['xoopsTpl']->assign('xoops_showrblock', 0);
                        } elseif ($sbl == 2) {
                            $GLOBALS['xoopsTpl']->assign('xoops_showlblock', 0);
                        } elseif ($sbl == 3) {
                            $GLOBALS['xoopsTpl']->assign('xoops_showrblock', 0);
                            $GLOBALS['xoopsTpl']->assign('xoops_showlblock', 0);
                        }
                        $op     = 'edit';
                        $ret    = 1;
                        $errors = $uploader->getErrors();
                        require_once __DIR__ . '/include/form.php';
                        require_once XOOPS_ROOT_PATH . '/footer.php';
                        exit();
                    }
                }
            }
        }

        if ((in_array(_CON_INFO_ALLCANUPDATE_SITEFULL, $show_info_perm)
             && $id == 0)
            || (in_array(_CON_INFO_CANUPDATE_SITEFULL, $show_info_perm)
                && $id > 0)
            || $mod_isAdmin) {
            $res     = $infoHandler->insert($content);
            $eintrag = true;
        } else {
            $content->setVar('old_id', $id);
            $content->setVar('info_id', 0);
            $content->setNew();
            $eintrag = false;
            $res     = $infowaitHandler->insert($content);
        }

        if ((int)$_POST['ret'] == 1) {
            $mode = [
                'seo'   => $seo,
                'id'    => 0,
                'title' => '',
                'dir'   => $module_name,
                'cat'   => 0
            ];
        } else {
            $mode = [
                'seo'   => $seo,
                'id'    => $id,
                'title' => $content->getVar('title'),
                'dir'   => $module_name,
                'cat'   => $content->getVar('cat')
            ];
        }

        $rurl = makeSeoUrl($mode);
        if ($res) {
            $key = $xoopsModule->getVar('dirname') . '_' . '*';
            clearInfoCache($key);
            if ($eintrag) {
                redirect_header($rurl, 1, _INFO_DBUPDATED);
            } else {
                redirect_header($rurl, 1, _MA_INFO_WAITTOEDIT);
            }
        } else {
            redirect_header($rurl, 3, _INFO_ERRORINSERT);
        }
    } else {
        if (!$infowaitHandler->readbakid($id)) {
            $ret = 0;
            require_once XOOPS_ROOT_PATH . '/header.php';
            if ((int)$xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_showrblock'] == 1) {
                $GLOBALS['xoopsTpl']->assign('xoops_showrblock', 0);
            }
            require_once __DIR__ . '/include/form.php';
            require_once XOOPS_ROOT_PATH . '/footer.php';
        } else {
            $mode = [
                'seo'   => $seo,
                'id'    => $content->getVar('info_id'),
                'title' => $content->getVar('title'),
                'dir'   => $xoopsModule->dirname(),
                'cat'   => $content->getVar('cat')
            ];
            redirect_header(makeSeoUrl($mode), 3, _MA_INFO_WAITTOFREE);
        }
    }
} elseif ($op === 'delete') {
    if (!in_array(_CON_INFO_CANUPDATE_DELETE, $show_info_perm)) {
        $mode = [
            'seo'   => $seo,
            'id'    => $content->getVar('info_id'),
            'title' => $content->getVar('title'),
            'dir'   => $module_name,
            'cat'   => $content->getVar('cat')
        ];
        redirect_header(makeSeoUrl($mode), 3, _NOPERM);
    } elseif (!empty($_POST['delok']) && (int)$_POST['delok'] == 1) {
        if ($GLOBALS['xoopsSecurity']->check()) {
            if ($infoHandler->delete($content)) {
                $key = $xoopsModule->getVar('dirname') . '_' . '*';
                clearInfoCache($key);
                redirect_header(XOOPS_URL, 1, _INFO_DBUPDATED);
            } else {
                redirect_header(XOOPS_URL, 1, _MA_INFO_WAITTOEDIT);
            }
        } else {
            $mode = [
                'seo'   => $seo,
                'id'    => $content->getVar('info_id'),
                'title' => $content->getVar('title'),
                'dir'   => $module_name,
                'cat'   => $content->getVar('cat')
            ];
            redirect_header(makeSeoUrl($mode), 3, _AM_INFO_TOCKEN_MISSING);
        }
    } else {
        require_once XOOPS_ROOT_PATH . '/header.php';
        $msg     = sprintf(_INFO_INFODELETE_FRAGE, $content->getVar('title'));
        $hiddens = ['op' => 'delete', 'delok' => 1, 'id' => $id];
        xoops_confirm($hiddens, 'submit.php', $msg, _DELETE, true);
        require_once XOOPS_ROOT_PATH . '/footer.php';
    }
} else {
    require_once XOOPS_ROOT_PATH . '/header.php';
    $sbl = (int)$xoopsModuleConfig[$xoopsModule->getVar('dirname') . '_showrblock'];
    if ($sbl == 0) {
        // no blocks
    } elseif ($sbl == 1) {
        $GLOBALS['xoopsTpl']->assign('xoops_showrblock', 0);
    } elseif ($sbl == 2) {
        $GLOBALS['xoopsTpl']->assign('xoops_showlblock', 0);
    } elseif ($sbl == 3) {
        $GLOBALS['xoopsTpl']->assign('xoops_showrblock', 0);
        $GLOBALS['xoopsTpl']->assign('xoops_showlblock', 0);
    }
    $op  = 'edit';
    $ret = 1;
    require_once __DIR__ . '/include/form.php';
    require_once XOOPS_ROOT_PATH . '/footer.php';
}
