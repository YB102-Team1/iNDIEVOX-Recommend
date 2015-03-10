<?php
class SiteHelper
{

    public static function getNavBar($type, $url)
    {

        switch ($type) {

        case 'tool':
            $nav_array = array(
                "PHP Test" => "/tool/php-test.php",
                "Table & Data" => array(
                    "Create Table" => "/tool/create-table.php",
                    "Table" => array(
                        "Export Table" => "/tool/export-table.php",
                        "Import Table" => "/tool/import-table.php"
                    ),
                    "Data" => array(
                        "Export Data" => "/tool/export-data.php",
                        "Sync Data" => "/tool/sync-data.php"
                    ),
                    "divider",
                    "Arrnge Database" => "/tool/arrange-database.php"
                ),
                "PHP Info" => "/tool/phpinfo.php"
            );
            break;

        }// end switch ($type)

        include COMPONENT_ROOT.'/navbar.php';

    }// end function getNavBar

}// end class SiteHelper
?>