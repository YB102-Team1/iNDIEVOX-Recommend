<?php
class TypeAction
{

    public function post($segments)
    {

        $action_id = $segments[0];

        switch ($action_id) {

        default:
            echo 'Undefined post action';
            break;

        }// end switch ($action_id)

    }// end function post

    public function get($segments)
    {

        $action_id = $segments[0];

        switch ($action_id) {

        default:
            echo 'Undefined get action';
            break;

        }// end switch ($action_id)

    }// end function get

}// end class TypeAction
?>