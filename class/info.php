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

if (!class_exists('InfoInfo')) {
    /**
     * Class InfoInfo
     */
    class InfoInfo extends XoopsObject
    {
        /**
         * InfoInfo constructor.
         */
        public function __construct()
        {
            $this->initVar('info_id', XOBJ_DTYPE_INT, null, false);
            $this->initVar('old_id', XOBJ_DTYPE_INT, null, false);
            $this->initVar('parent_id', XOBJ_DTYPE_INT, null, false);
            $this->initVar('owner', XOBJ_DTYPE_INT, -1, false);
            $this->initVar('st', XOBJ_DTYPE_INT, 2, false);
            $this->initVar('frontpage', XOBJ_DTYPE_INT, 0, false);
            $fr = array(
                'height' => '250',
                'border' => '0',
                'width'  => '100',
                'align'  => 'center'
            );
            $this->initVar('frame', XOBJ_DTYPE_OTHER, serialize($fr), true);
            $this->initVar('click', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('self', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('blockid', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, true, 255, true);
            $this->initVar('text', XOBJ_DTYPE_OTHER, null, true, null, true);
            $this->initVar('visible', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('nohtml', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('nobreaks', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('nosmiley', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('dobr', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('nocomments', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('cat', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('submenu', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('link', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('address', XOBJ_DTYPE_TXTBOX, null, false, 255, true);
            $this->initVar('visible_group', XOBJ_DTYPE_OTHER, '1,2,3', true);
            $this->initVar('edited_time', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('edited_user', XOBJ_DTYPE_INT, 0, false);
            $this->initVar('title_sicht', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('footer_sicht', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('tooltip', XOBJ_DTYPE_TXTBOX, null, false, 255, true);
            $this->initVar('bl_left', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('bl_right', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('tags', XOBJ_DTYPE_TXTBOX, null, false, 255, true);
        }
    }
}

if (!class_exists('InfoInfoHandler')) {
    /**
     * Class InfoInfoHandler
     */
    class InfoInfoHandler extends XoopsPersistableObjectHandler
    {
        /**
         * InfoInfoHandler constructor.
         * @param null|XoopsDatabase $db
         * @param string             $mname
         */
        public function __construct($db, $mname)
        {
            parent::__construct($db, $mname, 'InfoInfo', 'info_id', 'parent_id');
        }

        /**
         * @return array|bool
         */
        public function readStartpage()
        {
            $frontpage = false;
            $sql       = 'SELECT info_id,title FROM ' . $this->table . ' WHERE frontpage=1';
            $res       = $this->db->fetchArray($this->db->query($sql));
            if ($res) {
                $frontpage = array($res['info_id'], $res['title']);
            }

            return $frontpage;
        }

        /**
         * @param int $id
         * @return bool
         */
        public function deleteStartpage($id = 0)
        {
            if ($id > 0) {
                $sql = 'UPDATE ' . $this->table . ' SET frontpage=0 WHERE info_id=' . $id;
                $res = $this->db->query($sql);
                if ($res) {
                    return true;
                }
            }

            return false;
        }

        /**
         * @param XoopsObject $object
         * @param bool        $force
         * @return bool
         */
        public function insert(XoopsObject $object, $force = true)
        {
            if (parent::insert($object, $force)) {
                if ($object->getVar('tags', 'n') != '') {
                    require_once XOOPS_ROOT_PATH . '/modules/tag/include/functions.php';
                    if ($tagHandler = tag_getTagHandler()) {
                        $module_name = basename(dirname(__DIR__));
                        $tagHandler->updateByItem($object->getVar('tags', 'n'), $object->getVar('info_id'), $module_name);
                    }
                }

                return true;
            }

            return false;
        }

        /**
         * @param int $id
         * @return bool
         */
        public function readbakid($id = 0)
        {
            if ((int)$id <= 0) {
                return false;
            }
            $ret = false;
            $sql = 'SELECT old_id FROM ' . $this->table . ' WHERE old_id=' . $id;
            $res = $this->db->fetchArray($this->db->query($sql));
            if ($res) {
                if ($res['old_id'] > 0 && $res['old_id'] == $id) {
                    $ret = true;
                }
            }

            return $ret;
        }
    }
}
