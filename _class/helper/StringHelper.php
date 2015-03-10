<?php
class StringHelper
{

    public static function dateFormat($value, $offset = 0, $length = 16)
    {

        return str_replace('-', '/', substr($value, $offset, $length));

    }// end function dateFormat

}// end class StringHelper
?>