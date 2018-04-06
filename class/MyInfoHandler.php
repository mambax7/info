<?php namespace XoopsModules\Info;

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

use XoopsModules\Info;

/**
 * Class MyInfoHandler
 */
class MyInfoHandler extends \XoopsPersistableObjectHandler
{
    /**
     * MyInfoHandler constructor.
     * @param null|\XoopsDatabase $db
     * @param string              $mname
     */
    public function __construct($db, $mname)
    {
        parent::__construct($db, $mname, MyInfo::class, 'info_id', 'parent_id');
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
            $frontpage = [$res['info_id'], $res['title']];
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
    public function insert(\XoopsObject $object, $force = true)
    {
        if (parent::insert($object, $force)) {
            if ('' != $object->getVar('tags', 'n')) {
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
