<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';

class ActionProcessor
{

    private $_controller = false;
    private $_segments = false;

    function __construct()
    {

        if (!isset($_SERVER['PATH_INFO']) or $_SERVER['PATH_INFO'] == '/') {

            return 'Incorrect action request';

        }

        $this->_segments = explode('/', $_SERVER['PATH_INFO']);
        array_shift($this->_segments);// first element always is an empty string.
        $type = array_shift($this->_segments);

        $controller_name = ucfirst($type).'Action';

        if (!class_exists($controller_name)) {

            $controller_file_path = $controller_name.'.php';

            if (file_exists($controller_file_path)) {

                include $controller_file_path;

            } else {

                header("HTTP/1.0 {503} {'Service Unavailable'}");
                echo "{503} {'Service Unavailable'}";
                exit;

            }

        }// end if (!class_exists($controller_name))

        $this->_controller = new $controller_name;

    }// end function __construct

    function run()
    {

        if ($this->_controller === false) {

            return 'Empty controller';

        }// end if ($this->_controller === false)

        if (empty($this->_segments)) {

            return 'Missing action parameter';

        }// end if (empty($this->_segments))

        $method = strtolower($_SERVER['REQUEST_METHOD']);

        if (!method_exists($this->_controller, $method)) {

            header("HTTP/1.0 {405} {'Method not Allowed'}");
            echo "{405} {'Method not Allowed'}";
            exit;

        }// end if (!method_exists($this->_controller, $method))

        $arguments = $this->_segments;
        $this->_controller->$method($arguments);

    }// end function run

}// end class ActionProcessor

$processor = new ActionProcessor();
$processor->run();
?>