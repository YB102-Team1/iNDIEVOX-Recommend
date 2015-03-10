<?php
abstract class DataModel
{

    protected $db_obj;
    protected $table_name;
    protected $id;
    protected $is_deleted;
    protected $create_time;
    protected $modify_time;
    protected $delete_time;

    public function __construct($id)
    {

        if (empty($id)) {

            throw new Exception('Exception: '.get_class($this).' id is empty.');

        } else {// end if (empty($id))

            // intialize basic variables
            $this->db_obj = new DatabasePDO();
            $this->id = $id;
            $this->table_name = strtolower(
                preg_replace('/([^\s])([A-Z])/',
                '\1_\2',
                get_class($this))
            );

            // get object data
            $sql = "SELECT * FROM ".$this->table_name." WHERE id = :id";
            $param = array();
            $param[':id'] = $this->id;
            $query_instance = $this->db_obj->select($sql, $param);
            $class_property_array = get_object_vars($this);

            if (count($query_instance)==0) {

                echo "<h2>".get_class($this)."</h2>";
                echo "id: ".$this->id." not exist.";
                echo "<br>";
                throw new RuntimeException();

            }// end if (count($query_instance)==0)

            foreach ($query_instance as $instance_data) {

                foreach ($class_property_array as $property_key => $property_value) {

                    switch ($property_key) {

                    case 'db_obj':
                    case 'table_name':
                    case 'id':
                        break;

                    default:
                        $this->$property_key = $instance_data[$property_key];
                        break;

                    }// end switch ($property_key)

                }// end foreach ($instance_data as $property_key => $property_value)

            }// end foreach ($query_instance as $instance_data)

        }// end if (empty($id)) else


    }// end function __construct

    public function __get($key)
    {

        $method = 'get'.str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));

        if ($key == 'db_obj') {

            return null;

        } else if (method_exists($this, $method)) {// end if ($key == 'db_obj')

            return $this->$method();

        } else {// end if ($key == 'db_obj') else if (method_exists($this, $method))

            return isset($this->$key) ? $this->$key : null;

        }// end if ($key == 'db_obj') else if (method_exists($this, $method)) else

    }// end function __get

    public function __set($key, $value)
    {

        $method = 'set'.str_replace(' ', '', ucwords(str_replace('_', ' ', $key)));

        if ($key == 'db_obj' || $key == 'table_name') {

            // do nothing

        } else if (method_exists($this, $method)) {// end if (...)

            $this->$method($value);

        } else {// end if (...) else if (method_exists($this, $method))

            $this->$key = $value;

        }// end if (...) else if (method_exists($this, $method)) else

    }// end function __set

    public function save()
    {

        $class_property_array = get_object_vars($this);
        $now = date('Y-m-d H:i:s');
        $sql = "UPDATE $this->table_name SET ";
        $param = array();

        foreach ($class_property_array as $property_key => $property_value) {

            switch ($property_key) {

            case 'db_obj':
            case 'table_name':
            case 'id':
            case 'is_deleted':
            case 'create_time':
            case 'modify_time':
            case 'delete_time':
                break;

            default:
                if (!is_null($property_value)) {

                    $sql .= $property_key.'=:'.$property_key.', ';
                    $param[':'.$property_key] = $property_value;

                }// end if (!is_null($property_value))
                break;

            }// end switch($property_key)

        }// end foreach ($class_property_array as $property_key => $property_value)

        $sql .= "modify_time = :modify_time WHERE id = :id";
        $param[':modify_time'] = $now;
        $param[':id'] = $this->id;

        return $this->db_obj->update($sql, $param);

    }// end function save

    public function destroy($type = 'mark')
    {

        $now = date('Y-m-d H:i:s');

        if ($type == 'mark') {

            $this->is_deleted = 1;
            $sql = "UPDATE $this->table_name SET ".
                   "is_deleted = :is_deleted, ".
                   "modify_time = :modify_time, ".
                   "delete_time = :delete_time ".
                   "WHERE id = :id";
            $param = array();
            $param[':is_deleted'] = $this->is_deleted;
            $param[':modify_time'] = $now;
            $param[':delete_time'] = $now;
            $param[':id'] = $this->id;

            return $this->db_obj->update($sql, $param);

        } else if ($type == 'delete') {// end if ($type == 'mark')

            $sql = "DELETE FROM $this->table_name WHERE id = :id LIMIT 1";
            $param = array();
            $param[':id'] = $this->id;

            return $this->db_obj->delete($sql, $param);

        }// end if ($type == 'mark') else if ($type == 'delete')

    }// end function destroy

    public function recover()
    {

        $now = date('Y-m-d H:i:s');

        $this->is_deleted = 0;
        $sql = "UPDATE $this->table_name SET ".
               "is_deleted = :is_deleted, ".
               "modify_time = :modify_time, ".
               "delete_time = :delete_time ".
               "WHERE id = :id";
        $param = array();
        $param[':is_deleted'] = $this->is_deleted;
        $param[':modify_time'] = $now;
        $param[':delete_time'] = '0000-00-00 00:00:00';
        $param[':id'] = $this->id;

        return $this->db_obj->update($sql, $param);

    }// end function recover

    public function __destruct()
    {

        $class_property_array = get_object_vars($this);

        foreach ($class_property_array as $property_key => $property_value) {

            switch ($property_key) {

            case 'db_obj':
                break;

            default:
                unset($this->$property_key);
                break;

            }// end switch($property_key)

        }// end foreach ($class_property_array as $property_key => $property_value)

    }// end function __destruct

}// end of class DataModel
?>