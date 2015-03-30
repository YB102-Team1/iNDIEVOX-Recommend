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

        default:
        case 'site':
            if (SiteHelper::isLogin()) {
                if (SiteHelper::accessCheck('backyard')) {
                    $nav_array = array(
                        "排行榜" => "/",
                        "後台" => "/b/",
                        "登出" => "/logout.php?prev=$url"
                    );
                } else {
                    $nav_array = array(
                        "排行榜" => "/",
                        "登出" => "/logout.php?prev=$url"
                    );
                }
            } else {
                $nav_array = array(
                    "排行榜" => "/",
                    "登入" => "/login.php?prev=$url"
                );
            }
            break;

        }// end switch ($type)

        include COMPONENT_ROOT.'/navbar.php';

    }// end function getNavBar

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

            $auth = sha1($user_id.'LoginAuth'.'0Ool1I');
            setcookie('user_id', $user_id, false, "/", false);
            setcookie('user_auth', $auth, false, "/", false);

        }

        return true;

        // $json_data = array (
        //     "code"=>$code,
        //     "parameter"=>$parameter
        // );

        // header('Content-type: application/json');

        // echo json_encode($json_data);

    }// end function login

    public static function logout()
    {

        $code = 0;
        $parameter = '';

        if (self::isLogin()) {

            setcookie('user_id', '', time()-60000 );
            setcookie('user_auth', '', time()-60000 );

        }

        return true;

        // $json_data = array (
        //     "code"=>$code,
        //     "parameter"=>$parameter
        // );

        // header('Content-type: application/json');

        // echo json_encode($json_data);

    }// end function logout

}// end class SiteHelper
?>