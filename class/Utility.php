<?php namespace XoopsModules\Info;

use Xmf\Request;
use XoopsModules\Info;
use XoopsModules\Info\Common;

/**
 * Class Utility
 */
class Utility
{
    use common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use common\ServerStats; // getServerStats Trait

    use common\FilesManagement; // Files Management Trait

    //--------------- Custom module methods -----------------------------

}
