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

defined('XOOPS_ROOT_PATH') || exit('XOOPS_ROOT_PATH not defined!');

if (!function_exists('info_freiblock_show')) {
    /**
     * @param $options
     * @return array
     */
    function info_freiblock_show($options)
    {
        global $xoopsDB, $xoopsUser;
        $myts = MyTextSanitizer::getInstance();
        require_once XOOPS_ROOT_PATH . '/modules/' . $options[0] . '/include/constants.php';
        $block  = [];
        $result = $xoopsDB->query('SELECT * FROM ' . $xoopsDB->prefix($options[0]) . ' WHERE info_id=' . $options[1]);
        $row    = $xoopsDB->fetchArray($result);
        $text   = trim($row['text']);
        if ((int)$row['info_id'] != 0) {
            $GLOBALS['xoopsOption']['template_main'] = $options[0] . '_startblock.html';
            if ($row['link'] == 6) {
                ob_start();
                echo eval($text);
                $text = ob_get_contents();
                ob_end_clean();
                $row['nohtml'] = 0;
            }
            $html   = ((int)$row['nohtml'] == 1) ? 0 : 1;
            $smiley = ((int)$row['nosmiley'] == 1) ? 0 : 1;
            $breaks = ($html == 1) ? 0 : 1;
            if ((int)$row['link'] == 4) {
                if (substr($row['address'], '/', 0, 1)
                    || substr($row['address'], "\\", 0, 1)) {
                    $row['address'] = substr($address, 1);
                }
                $file = XOOPS_ROOT_PATH . '/' . $row['address'];
                if (file_exists($file)) {
                    ob_start();
                    include $file;
                    $file = ob_get_contents();
                    ob_end_clean();
                    $text = $file;
                }
            } elseif ((int)$row['link'] == 5) {
                $iframe = unserialize($row['frame']);
                if (!isset($iframe['width'])
                    || $iframe['width'] < 1
                    || $iframe['width'] > 100) {
                    $iframe['width'] = 100;
                }
                $text   .= "<iframe width='" . $iframe['width'] . "%' height='" . $iframe['height'] . "px' align='" . $iframe['align'] . "' name='" . $row['title'] . "' scrolling='auto' frameborder='" . $iframe['border'] . "' src='" . $row['address'] . "'></iframe>";
                $html   = 1;
                $breaks = 0;
            }
            $text = str_replace('{X_XOOPSURL}', XOOPS_URL . '/', $text);
            $text = str_replace('{X_SITEURL}', XOOPS_URL . '/', $text);
            if (is_object($xoopsUser)) {
                $text = str_replace('{X_XOOPSUSER}', $xoopsUser->getVar('uname'), $text);
                $text = str_replace('{X_XOOPSUSERID}', $xoopsUser->getVar('uid'), $text);
            } else {
                $text = str_replace('{X_XOOPSUSER}', _GUESTS, $text);
                $text = str_replace('{X_XOOPSUSERID}', '0', $text);
            }
            if (trim($text) != '') {
                $text       = str_replace('<div style="page-break-after: always;"><span style="display: none;"> </span></div>', '[pagebreak]', $text);
                $infotext   = explode('[pagebreak]', $text);
                $info_pages = count($infotext);
                if ($info_pages > 1) {
                    $text = $infotext[0];
                }
            }
            $text             = $myts->displayTarea($text, $html, $smiley, 1, 1, $breaks);
            $block['content'] = $text;

            $block['id'] = $options[1];
        }

        return $block;
    }
}

if (!function_exists('info_freiblock_edit')) {
    /**
     * @param $options
     * @return string
     */
    function info_freiblock_edit($options)
    {
        global $xoopsDB;
        $module_name = $options[0];
        $result      = $xoopsDB->queryF('SELECT info_id,title FROM ' . $xoopsDB->prefix($module_name) . ' WHERE link !=1 && link !=2 && link !=3 && link !=4');
        if ($result) {
            $form = '' . _INFO_BL_OPTION . '&nbsp;&nbsp;';
            $form .= "<input type='hidden' name='options[0]' value='" . $module_name . "'>";
            $form .= "<select name='options[1]' size='1'>";
            while ($row = $xoopsDB->fetcharray($result)) {
                $form .= "<option value='" . $row['info_id'] . "'";
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
