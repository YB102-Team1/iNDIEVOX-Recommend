<?php
abstract class DataModelGod
{

    protected $db_obj;
    protected $table_name;

    public function __construct()
    {

        // intialize basic variables
        $this->db_obj = new DatabasePDO();
        $this->table_name = strtolower(
            preg_replace('/([^\s])([A-Z])/',
            '\1_\2',
            str_replace('God', '', get_class($this)))
        );

    }// end function __construct

    public function getAll()
    {

        $sql = "SELECT * FROM $this->table_name";

        return $this->db_obj->select($sql);

    }// end function getAll

    public function getData($start=0, $length=1)
    {

        $sql = "SELECT * FROM $this->table_name LIMIT $start, $length";

        return $this->db_obj->select($sql);

    }// end function getData

    public function getDataCount()
    {

        $id_count = 0;
        $sql = "SELECT COUNT(id) id_count FROM $this->table_name WHERE is_deleted = 0";

        $result = $this->db_obj->select($sql);

        foreach ($result as $data) {

            $id_count = $data['id_count'];

        }// end foreach ($result as $data)

        return $id_count;

    }// end function getDataCount

    public function getMaxId()
    {

        $max_id = 0;
        $sql = "SELECT MAX(id) max_id FROM $this->table_name";

        $result = $this->db_obj->select($sql);

        foreach ($result as $data) {

            $max_id = $data['max_id'];

        }// end foreach ($result as $data)

        return $max_id;

    }// end function getMaxId

    public function check($instance_property, $undeleted_only = false)
    {

        $instance_id = 0;
        $param = array();

        if (empty($instance_property)) {

            return $instance_id;

        } else if (!is_array($instance_property)) {

            if (is_numeric($instance_property)) {

                $sql = "SELECT id FROM $this->table_name WHERE id = :id ";
                $param[':id'] = $instance_property;

            }// end if (is_numeric($instance_property))

        } else {// end if (empty($instance_property)) else if (!is_array($instance_property)

            $sql = "SELECT id FROM $this->table_name WHERE 1 ";

            foreach ($property_array as $property_key => $property_value) {

                $sql .= "AND $property_key = :property_key ";
                $param[':'.$property_key] = $property_value;

            }// end foreach ($property_array as $property_key => $property_value)

        }// end if (empty($instance_property)) else if (!is_array($instance_property) else

        if ($undeleted_only) {

            $sql .= "AND is_deleted = :is_deleted ";
            $param[':is_deleted'] = 0;

        }// end if ($undeleted_only)

        $sql .= "LIMIT 1";

        $query_instance = $this->db_obj->select($sql, $param);

        foreach ($query_instance as $instance_data) {

            $instance_id = $instance_data['id'];

        }// end foreach ($query_instance as $instance_data)

        return $instance_id;

    }// end function check

    public function create($class_property_array)
    {

        $now = date('Y-m-d H:i:s');

        $sql = "INSERT INTO $this->table_name (";
        $value_list = "";
        $param = array();

        foreach ($class_property_array as $property_key => $property_value) {

            switch ($property_key) {

            case 'id':
            case 'is_deleted':
            case 'create_time':
            case 'modify_time':
            case 'delete_time':
                break;

            default:
                $sql .= "$property_key, ";
                $value_list .= ":$property_key, ";
                $param[':'.$property_key] = $property_value;
                break;

            }

        }// end foreach ($class_property_array as $property_key => $property_value)

        $sql .= " create_time, modify_time) VALUES ($value_list :create_time, :modify_time)";
        $param[':create_time'] = $now;
        $param[':modify_time'] = $now;

        return $this->db_obj->insert($sql, $param);

    }// end function create

    public function __destruct()
    {

        unset($this->table_name);

    }// end function __destruct

}// end class DataModelGod
?>