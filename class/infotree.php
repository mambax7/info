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

defined('XOOPS_ROOT_PATH') || die('Restricted access');

if (!class_exists('InfoTree')) {

    /**
     * Abstract base class for forms
     *
     * @author     Kazumi Ono <onokazu@xoops.org>
     * @author     John Neill <catzwolf@xoops.org>
     * @copyright  copyright (c) XOOPS.org
     * @package    kernel
     * @subpackage XoopsTree
     * @access     public
     */
    class InfoTree
    {
        public $table; //table with parent-child structure
        public $id; //name of unique id for records in table $table
        public $pid; // name of parent id used in table $table
        public $order; //specifies the order of query results
        public $title; // name of a field in table $table which will be used when  selection box and paths are generated
        public $db;

        //constructor of class XoopsTree
        //sets the names of table, unique id, and parend id
        /**
         * InfoTree constructor.
         * @param $table_name
         * @param $id_name
         * @param $pid_name
         */
        public function __construct($table_name, $id_name, $pid_name)
        {
            $this->db    = \XoopsDatabaseFactory::getDatabaseConnection();
            $this->table = $table_name;
            $this->id    = $id_name;
            $this->pid   = $pid_name;
        }

        // returns an array of first child objects for a given id($sel_id)

        /**
         * @param        $sel_id
         * @param string $order
         * @return array
         */
        public function getFirstChild($sel_id, $order = '')
        {
            $sel_id = (int)$sel_id;
            $arr    = [];
            $sql    = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';
            if ('' != $order) {
                $sql .= " ORDER BY $order";
            }
            $result = $this->db->query($sql);
            $count  = $this->db->getRowsNum($result);
            if (0 == $count) {
                return $arr;
            }
            while (false !== ($myrow = $this->db->fetchArray($result))) {
                array_push($arr, $myrow);
            }

            return $arr;
        }

        /**
         * @param $sel_id
         * @return int
         */
        public function getFirstId($sel_id)
        {
            $sel_id = (int)$sel_id;
            $r_id   = 0;
            $result = $this->db->query('SELECT ' . $this->pid . ' FROM ' . $this->table . ' WHERE ' . $this->id . '=' . $sel_id . '');
            $count  = $this->db->getRowsNum($result);
            list($r_id) = $this->db->fetchRow($result);
            if (0 == $count || 0 == $r_id) {
                return $sel_id;
            }
            $r_id = $this->getFirstId($r_id);

            return $r_id;
        }

        // returns an array of all FIRST child ids of a given id($sel_id)

        /**
         * @param $sel_id
         * @return array
         */
        public function getFirstChildId($sel_id)
        {
            $sel_id  = (int)$sel_id;
            $idarray = [];
            $result  = $this->db->query('SELECT ' . $this->id . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '');
            $count   = $this->db->getRowsNum($result);
            if (0 == $count) {
                return $idarray;
            }
            while (false !== (list($id) = $this->db->fetchRow($result))) {
                array_push($idarray, $id);
            }

            return $idarray;
        }

        //returns an array of ALL child ids for a given id($sel_id)

        /**
         * @param        $sel_id
         * @param string $order
         * @param array  $idarray
         * @return array
         */
        public function getAllChildId($sel_id, $order = '', $idarray = [])
        {
            $sel_id = (int)$sel_id;
            $sql    = 'SELECT ' . $this->id . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '';
            if ('' != $order) {
                $sql .= " ORDER BY $order";
            }
            $result = $this->db->query($sql);
            $count  = $this->db->getRowsNum($result);
            if (0 == $count) {
                return $idarray;
            }
            while (false !== (list($r_id) = $this->db->fetchRow($result))) {
                array_push($idarray, $r_id);
                $idarray = $this->getAllChildId($r_id, $order, $idarray);
            }

            return $idarray;
        }

        //returns an array of ALL parent ids for a given id($sel_id)

        /**
         * @param        $sel_id
         * @param string $order
         * @param array  $idarray
         * @return array
         */
        public function getAllParentId($sel_id, $order = '', $idarray = [])
        {
            $sel_id = (int)$sel_id;
            $sql    = 'SELECT ' . $this->pid . ' FROM ' . $this->table . ' WHERE ' . $this->id . '=' . $sel_id . '';
            if ('' != $order) {
                $sql .= " ORDER BY $order";
            }
            $result = $this->db->query($sql);
            list($r_id) = $this->db->fetchRow($result);
            if (0 == $r_id) {
                return $idarray;
            }
            array_push($idarray, $r_id);
            $idarray = $this->getAllParentId($r_id, $order, $idarray);

            return $idarray;
        }

        //returns an array of ALL parent title for a given id($sel_id)

        /**
         * @param        $sel_id
         * @param string $order
         * @param array  $idarray
         * @return array
         */
        public function getAllParentTitle(
            $sel_id,
            $order = '',
            $idarray = []
        ) {
            $sel_id = (int)$sel_id;
            $sql    = 'SELECT ' . $this->pid . ', title, info_id FROM ' . $this->table . ' WHERE ' . $this->id . '=' . $sel_id . '';
            if ('' != $order) {
                $sql .= " ORDER BY $order";
            }
            $result = $this->db->query($sql);
            list($r_id, $r_title, $r_storyid) = $this->db->fetchRow($result);
            if (0 == $r_id) {
                return $idarray;
            }
            $idarray[$r_storyid] = $r_title;
            $idarray             = $this->getAllParentTitle($r_id, $order, $idarray);

            return $idarray;
        }

        //generates path from the root id to a given id($sel_id)
        // the path is delimetered with "/"
        /**
         * @param        $sel_id
         * @param        $title
         * @param string $path
         * @return string
         */
        public function getPathFromId($sel_id, $title, $path = '')
        {
            $sel_id = (int)$sel_id;
            $result = $this->db->query('SELECT ' . $this->pid . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$sel_id");
            if (0 == $this->db->getRowsNum($result)) {
                return $path;
            }
            list($parentid, $name) = $this->db->fetchRow($result);
            $myts = \MyTextSanitizer::getInstance();
            $name = $myts->htmlspecialchars($name);
            $path = '/' . $name . $path . '';
            if (0 == $parentid) {
                return $path;
            }
            $path = $this->getPathFromId($parentid, $title, $path);

            return $path;
        }

        //makes a nicely ordered selection box
        //$preset_id is used to specify a preselected item
        //set $none to 1 to add a option with value 0
        /**
         * @param        $title
         * @param string $order
         * @param int    $preset_id
         * @param int    $none
         * @param string $sel_name
         * @param string $onchange
         * @param null   $extra
         */
        public function makeMySelBox(
            $title,
            $order = '',
            $preset_id = 0,
            $none = 0,
            $sel_name = '',
            $onchange = '',
            $extra = null
        ) {
            if ('' == $sel_name) {
                $sel_name = $this->id;
            }
            $myts = \MyTextSanitizer::getInstance();
            echo "<select name='" . $sel_name . "'";
            if ('' != $onchange) {
                echo " onchange='" . $onchange . "'";
            }
            echo ">\n";
            $sql = 'SELECT ' . $this->id . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=0 ' . $extra;
            if ('' != $order) {
                $sql .= " ORDER BY $order";
            }
            $result = $this->db->query($sql);
            if ($none) {
                echo "<option value='0'>----</option>\n";
            }
            while (false !== (list($cat_id, $name) = $this->db->fetchRow($result))) {
                $sel = '';
                if ($cat_id == $preset_id) {
                    $sel = " selected='selected'";
                }
                echo "<option value='$cat_id'$sel>$name</option>\n";
                $sel = '';
                $arr = $this->getChildTreeArray($cat_id, $order, [], '', $extra);
                foreach ($arr as $option) {
                    $option['prefix'] = str_replace('.', '--', $option['prefix']);
                    $catpath          = $option['prefix'] . '&nbsp;' . $myts->htmlspecialchars($option[$title]);
                    if ($option[$this->id] == $preset_id) {
                        $sel = " selected='selected'";
                    }
                    echo "<option value='" . $option[$this->id] . "'$sel>$catpath</option>\n";
                    $sel = '';
                }
            }
            echo "</select>\n";
        }

        /**
         * @param        $title
         * @param string $order
         * @param int    $preset_id
         * @param int    $none
         * @param null   $extra
         * @return array
         */
        public function makeMySelArray(
            $title,
            $order = '',
            $preset_id = 0,
            $none = 0,
            $extra = null
        ) {
            $ret  = [];
            $myts = \MyTextSanitizer::getInstance();
            $sql  = 'SELECT ' . $this->id . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->pid . '=0 ' . $extra;
            if ('' != $order) {
                $sql .= " ORDER BY $order";
            }
            $result = $this->db->query($sql);
            if ($none) {
                $ret[0] = '----';
            }
            while (false !== (list($cat_id, $name) = $this->db->fetchRow($result))) {
                $arr          = $this->getChildTreeArray($cat_id, $order, [], '', $extra);
                $ret[$cat_id] = $name;
                foreach ($arr as $option) {
                    $option['prefix']        = str_replace('.', '--', $option['prefix']);
                    $catpath                 = $option['prefix'] . '&nbsp;' . $myts->htmlspecialchars($option['title']);
                    $ret[$option[$this->id]] = $catpath;
                    unset($option);
                }
            }

            return $ret;
        }

        //generates nicely formatted linked path from the root id to a given id

        /**
         * @param        $sel_id
         * @param        $title
         * @param        $funcURL
         * @param string $path
         * @return string
         */
        public function getNicePathFromId($sel_id, $title, $funcURL, $path = '')
        {
            $path   = !empty($path) ? '&nbsp;:&nbsp;' . $path : $path;
            $sel_id = (int)$sel_id;
            $sql    = 'SELECT ' . $this->pid . ', ' . $title . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$sel_id";
            $result = $this->db->query($sql);
            if (0 == $this->db->getRowsNum($result)) {
                return $path;
            }
            list($parentid, $name) = $this->db->fetchRow($result);
            $myts = \MyTextSanitizer::getInstance();
            $name = $myts->htmlspecialchars($name);
            $path = "<a href='" . $funcURL . '&amp;' . $this->id . '=' . $sel_id . "'>" . $name . '</a>' . $path . '';
            if (0 == $parentid) {
                return $path;
            }
            $path = $this->getNicePathFromId($parentid, $title, $funcURL, $path);

            return $path;
        }

        //generates id path from the root id to a given id
        // the path is delimetered with "/"
        /**
         * @param        $sel_id
         * @param string $path
         * @return string
         */
        public function getIdPathFromId($sel_id, $path = '')
        {
            $sel_id = (int)$sel_id;
            $result = $this->db->query('SELECT ' . $this->pid . ' FROM ' . $this->table . ' WHERE ' . $this->id . "=$sel_id");
            if (0 == $this->db->getRowsNum($result)) {
                return $path;
            }
            list($parentid) = $this->db->fetchRow($result);
            $path = '/' . $sel_id . $path . '';
            if (0 == $parentid) {
                return $path;
            }
            $path = $this->getIdPathFromId($parentid, $path);

            return $path;
        }

        /**
         * Enter description here...
         *
         * @param int    $sel_id
         * @param string $order
         * @param array  $parray
         * @param null   $extra
         * @return array
         */
        public function getAllChild(
            $sel_id = 0,
            $order = '',
            $parray = [],
            $extra = null
        ) {
            $sel_id = (int)$sel_id;
            $sql    = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '' . $extra;
            if ('' != $order) {
                $sql .= " ORDER BY $order";
            }
            $result = $this->db->query($sql);
            $count  = $this->db->getRowsNum($result);
            if (0 == $count) {
                return $parray;
            }
            while (false !== ($row = $this->db->fetchArray($result))) {
                array_push($parray, $row);
                $parray = $this->getAllChild($row[$this->id], $order, $parray);
            }

            return $parray;
        }

        /**
         * Enter description here...
         *
         * @param int    $sel_id
         * @param string $order
         * @param array  $parray
         * @param string $r_prefix
         * @param null   $extra
         * @return array
         */
        public function getChildTreeArray(
            $sel_id = 0,
            $order = '',
            $parray = [],
            $r_prefix = '',
            $extra = null
        ) {
            $sel_id = (int)$sel_id;
            $sql    = 'SELECT * FROM ' . $this->table . ' WHERE ' . $this->pid . '=' . $sel_id . '' . $extra;
            if ('' != $order) {
                $sql .= " ORDER BY $order";
            }

            $result = $this->db->query($sql);
            $count  = $this->db->getRowsNum($result);
            if (0 == $count) {
                return $parray;
            }
            while (false !== ($row = $this->db->fetchArray($result))) {
                if (0 != $sel_id) {
                    $row['prefix'] = $r_prefix . '&nbsp;';
                }
                array_push($parray, $row);
                if (0 == $sel_id) {
                    $row['prefix'] = $r_prefix . '&nbsp;';
                }
                $parray = $this->getChildTreeArray($row[$this->id], $order, $parray, $row['prefix'], $extra);
            }

            return $parray;
        }

        /**
         * @param array $visiblegroups
         * @param array $usergroups
         * @return bool
         */
        public function checkperm(
            $visiblegroups = [],
            $usergroups = []
        ) {
            if (count($usergroups) > 0 && count($visiblegroups) > 0) {
                $vsgroup = explode(',', $visiblegroups);
                $vscount = count($vsgroup) - 1;
                while ($vscount > -1) {
                    if (in_array($vsgroup[$vscount], $usergroups)) {
                        return true;
                    }
                    $vscount--;
                }
            }

            return false;
        }
    }
}
