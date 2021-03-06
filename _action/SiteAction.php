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
            if ((int)$user_id == $user_id && (int)$user_id) {
                SiteHelper::login($user_id);
            } else {
                $status = 'fail';
            }

            ResponseMessenger::json($status, $message, $parameter);

            break;

        case 'update-home-disc-list':
            $type = $_POST['type'];
            $genre = $_POST['genre'];
            include COMPONENT_ROOT.'/disc/home_disc_list.php';
            break;

        case 'buy-disc':
            $disc_id = $_POST['disc_id'];
            $user_id = $_POST['user_id'];
            include COMPONENT_ROOT.'/disc/promote_disc_list.php';
            break;

        case 'update-disc-chart-block':
            $type = $_POST['type'];
            $genre = $_POST['genre'];
            include COMPONENT_ROOT.'/b/disc_chart_block.php';
            break;

        default:
            echo 'Undefined post action';
            break;

        }// end switch ($action_id)

    }// end function post

}// end class SiteAction
?>