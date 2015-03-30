<?php
class SiteAction
{

    public function get($segments)
    {

        $action_id = $segments[0];

        switch ($action_id) {

        default:
            echo 'Undefined get action';
            break;

        }// end switch ($action_id)

    }// end function get

    public function post($segments)
    {

        $action_id = $segments[0];
        $status = 'success';
        $message = '';
        $parameter = array();

        switch ($action_id) {

        case 'login':
            $user_id = $_POST['user_id'];
            $password = $_POST['password'];
            $prev = $_POST['prev'];
            if ((int)$user_id == $user_id && (int)$user_id) {
                SiteHelper::login($user_id);
                $parameter['url'] = $prev;
            } else {
                $status = 'fail';
            }

            ResponseMessenger::json($status, $message, $parameter);

            break;

        default:
            echo 'Undefined post action';
            break;

        }// end switch ($action_id)

    }// end function post

}// end class SiteAction
?>