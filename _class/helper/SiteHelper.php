<?php
class SiteHelper
{

    public static function getNavBar($url)
    {

        if ($_GET['disc_id']) {
            $target = "?target=".$_GET['disc_id'];
        } else {
            $target = "";
        }

        if (SiteHelper::isLogin()) {
            if (SiteHelper::accessCheck('backyard')) {
                $nav_array = array(
                    "首頁" => "/index.php",
                    "後台" => "/b/index.php",
                    "測試" => "/tool/php-test.php",
                    "資料表與資料" => array(
                        "建立資料表" => "/tool/create-table.php",
                        "資料表" => array(
                            "匯出資料表結構" => "/tool/export-table.php",
                            "匯入資料表結構" => "/tool/import-table.php"
                        ),
                        "資料" => array(
                            "匯出資料表資料" => "/tool/export-data.php",
                            "同步資料表資料" => "/tool/sync-data.php"
                        )//,
                        // "divider",
                        // "Arrnge Database" => "/tool/arrange-database.php"
                    ),
                    "PHP Info" => "/tool/phpinfo.php",
                    "登出" => "/logout.php$target"
                );
            } else {
                $nav_array = array(
                    "首頁" => "/",
                    "登出" => "/logout.php$target"
                );
            }
        } else {
            $nav_array = array(
                "首頁" => "/",
                "登入" => "/login.php$target"
            );
        }

        include COMPONENT_ROOT.'/navbar.php';

    }// end function getNavBar

    public static function getBackyardMap()
    {

        return array(
            "音樂業務相關" => array(
                "唱片銷售排行" => "/b/disc_chart.php",
                "唱片叢集概況" => "/b/disc_sales.php",
                "唱片叢集分布" => "/b/disc_cluster.php"
            ),
            "售票業務相關" => array(
                "藝人相似度" => "/b/similar_artist.php"
            )
        );

    }// end function getBackyardMap

    public static function getBackyardBreadcrumbs($url) 
    {

        $map = self::getBackyardMap();
        include COMPONENT_ROOT.'/b/breadcrumbs.php';

    }// end function getBackyardBreadcrumbs

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