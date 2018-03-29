<?php namespace XoopsModules\Info;

use Xmf\Request;
use XoopsModules\Info;
use XoopsModules\Info\Common;

/**
 * Class Utility
 */
class Utility
{
    use Common\VersionChecks; //checkVerXoops, checkVerPhp Traits

    use Common\ServerStats; // getServerStats Trait

    use Common\FilesManagement; // Files Management Trait

    //--------------- Custom module methods -----------------------------
}
