<?php
class PJAXHelper
{

    public static function isPJAX()
    {

        if (   array_key_exists('HTTP_X_PJAX', $_SERVER)
            && $_SERVER['HTTP_X_PJAX'] === 'true'
        ) {

            return true;

        } else {// end if(...)

            return false;

        }// end if(...) else

    }// end function isPJAX

    public static function getPJAXContainer()
    {

        return $_SERVER['HTTP_X_PJAX_CONTAINER'];

    }// end function getPJAXContainer

    public static function run($page_title, $url, $http_param = array())
    {

        if (self::isPJAX() && self::getPJAXContainer() == '#main-section') {

            echo "<title>$page_title</title>";
            include VIEW_ROOT.$url;

        } else {// end if (self::isPJAX() && self::getPJAXContainer() == '#main-section')

            $view_path = VIEW_ROOT.$url;
            if (!file_exists($view_path)) {

                echo 'View file missing';

            } else {// end if (file_exists($view_path))

                include LAYOUT_ROOT.'/main-layout.php';

            }// end if (file_exists($view_path)) else

        }// end if (self::isPJAX() && self::getPJAXContainer() == '#main-section') else

    }// end function run

}// end class PJAXHelper
?>