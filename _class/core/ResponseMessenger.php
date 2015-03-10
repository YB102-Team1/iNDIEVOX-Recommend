<?php
class ResponseMessenger
{

    public static function json($status, $message = "", $parameter = array())
    {

        if ($status == 'success') {

            $code = 0;
            $status_text = 'success';

        } else {// end if ($status == 'success')

            $code = 1;
            $status_text = 'fail';

        }// end if ($status == 'success') else

        if (empty($message)) {

            $message = $status_text;

        }// end if (empty($message))

        $return_array = array(
            "status"=>array(
                "code"=>$code,
                "text"=>$status_text
            ),
            "message"=>$message,
            "parameter"=>$parameter
        );

        print_r(json_encode($return_array));

    }// end function json

}// end class ResponseMessenger
?>