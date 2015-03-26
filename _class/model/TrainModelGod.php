<?php
class TrainModelGod extends DataModelGod
{

    /**
     * inherited variables:
     *
     * protected $db_obj;
     * protected $table_name;
     */

    /**
     * inherited functions:
     *
     * public function getAll() {}
     * public function getDataCount() {}
     * public function getMaxId() {}
     * public function check($instance_property, $undeleted_only = false) {}
     * public function create($class_property_array) {}
     */
    public function getUserGroup($user_id, $type)
    {

        $sql = "SELECT user_group ".
               "FROM train_model ".
               "WHERE user_id = :user_id ".
               "AND type = :type ".
               "LIMIT 1";
        $param = array(
            ':user_id' => $user_id,
            ':type' => $type
        );
        $query_instance = $this->db_obj->select($sql, $param);

        $user_group = 1;
        foreach ($query_instance as $instance_data) {
            $user_group = $instance_data['user_group'];
        }

        return $user_group;

    }// end function getUserGroup

}// end class TrainModelGod
?>