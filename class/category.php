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

if (!class_exists('InfoCategory')) {
    /**
     * Class InfoCategory
     */
    class InfoCategory extends XoopsObject
    {
        /**
         * InfoCategory constructor.
         */
        public function __construct()
        {
            $this->initVar('cat_id', XOBJ_DTYPE_INT, null, false);
            $this->initVar('visible', XOBJ_DTYPE_INT, 1, false);
            $this->initVar('title', XOBJ_DTYPE_TXTBOX, null, true, 255, true);
        }
    }
}

if (!class_exists('InfoCategoryHandler')) {
    /**
     * Class InfoCategoryHandler
     */
    class InfoCategoryHandler extends XoopsPersistableObjectHandler
    {
        /**
         * InfoCategoryHandler constructor.
         * @param null|\XoopsDatabase $db
         * @param string             $mname
         */
        public function __construct($db, $mname)
        {
            parent::__construct($db, $mname . '_cat', 'InfoCategory', 'cat_id', 'title');
        }
    }
}
