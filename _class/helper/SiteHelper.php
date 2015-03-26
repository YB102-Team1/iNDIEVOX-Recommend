<?php
class SiteHelper
{

    public static function getToolNavBar($type, $url)
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

        include COMPONENT_ROOT.'/tool_navbar.php';

    }// end function getToolNavBar

    public static function isLogin()
    {

        if (   isset($_COOKIE['user_id'])
            && !empty($_COOKIE['user_id'])
            && isset($_COOKIE['user_auth'])
            && !empty($_COOKIE['user_auth'])
            && sha1($_COOKIE['user_id'].'LoginAuth'.'0Ool1I') == $_COOKIE['user_auth']
        ) {
            return true;
        } else {
            return false;
        }

    }// end function isLogin

    public static function accessCheck($section)
    {

        switch ($section) {

        case 'backyard':

            if ($_COOKIE['user_id'] == '523') {

                return true;

            }

            break;

        }

        return false;

    }// end function accessCheck

    public static function login($user_id)
    {

        $code = 0;
        $parameter = '';

        if (!self::isLogin()) {

            $auth = sha1($account.'ManageAuth'.'0Ool1I');
            setcookie('user_id', $account, 0 );
            setcookie('user_auth', $auth, 0 );

        }

        $json_data = array (
            "code"=>$code,
            "parameter"=>$parameter
        );

        header('Content-type: application/json');

        echo json_encode($json_data);

    }// end function login

    public static function logout($user_id)
    {

        $code = 0;
        $parameter = '';

        if (self::isLogin()) {

            setcookie('user_id', '', time()-60000 );
            setcookie('user_auth', '', time()-60000 );

        }

        $json_data = array (
            "code"=>$code,
            "parameter"=>$parameter
        );

        header('Content-type: application/json');

        echo json_encode($json_data);

    }// end function logout

}// end class SiteHelper
?>