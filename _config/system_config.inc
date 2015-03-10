<?php
// time zone setting
date_default_timezone_set('Asia/Taipei');

//exceute time
set_time_limit(0);

// error handle
ini_set('display_errors', 1);
error_reporting(E_ERROR | E_WARNING | E_PARSE);

// Exception handler
function exception_handler($exception) {

    echo "Uncaught exception: $exception->getMessage()<br>";
    echo "<pre>";
    print_r(debug_backtrace());
    echo "</pre>";

}
set_exception_handler('exception_handler');

// root path
define(SITE_ROOT, $_SERVER['DOCUMENT_ROOT']);
define(ACTION_ROOT, SITE_ROOT.'/_action');
define(ASSET_ROOT, SITE_ROOT.'/_asset');
define(DATA_SQL_ROOT, SITE_ROOT.'/_asset/sql/data');
define(TABLE_SQL_ROOT, SITE_ROOT.'/_asset/sql/table');
define(TEMPLATE_ROOT, SITE_ROOT.'/_asset/template');
define(CLASS_ROOT, SITE_ROOT.'/_class');
define(COMPONENT_ROOT, SITE_ROOT.'/_component');
define(CONFIG_ROOT, SITE_ROOT.'/_config');
define(LAYOUT_ROOT, SITE_ROOT.'/_layout');
define(VIEW_ROOT, SITE_ROOT.'/_view');

// config files path
define(DB_CONFIG_FILE, CONFIG_ROOT.'/database_config.inc');
define(CLASS_TEMPLATE_FILE, TEMPLATE_ROOT.'/ClassTemplate.php');
define(CLASS_GOD_TEMPLATE_FILE, TEMPLATE_ROOT.'/ClassGodTemplate.php');
define(ACTION_TEMPLATE_FILE, TEMPLATE_ROOT.'/ActionTemplate.php');
define(TABLE_SQL_TEMPLATE_FILE, TEMPLATE_ROOT.'/TableSQLTemplate.sql');
define(GLOBAL_VARIABLES_FILE, CONFIG_ROOT.'/global_variables.inc');
define(CLASS_LOADER_FILE, CONFIG_ROOT.'/class_loader.inc');

// include config files
require_once GLOBAL_VARIABLES_FILE;
require_once CLASS_LOADER_FILE;

// define some global function
function in_array_r($needle, $haystack, $strict = false)
{

    foreach ($haystack as $item) {

        if (   ($strict ? $item === $needle : $item == $needle)
            || (is_array($item) && in_array_r($needle, $item, $strict))
        ) {

            return true;

        }

    }

    return false;
}

?>