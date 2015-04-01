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

    public function getClusterD3Code($type) 
    {

        $result = "";
        for ($i = 1; $i <= 3; $i++) {
            $first = true;
            $sql = "SELECT t.on_thing_id, d.title, t.price, COUNT(t.id) times, SUM(t.price) amount ".
                   "FROM train_model t ".
                   "LEFT JOIN disc d ON t.on_thing_id = d.id ".
                   "WHERE t.type = :type AND t.price != 0 AND t.item_group = :item_group ".
                   "GROUP BY t.on_thing_id ".
                   "ORDER BY t.item_group";
            $param = array(
                ":type" => $type,
                ":item_group" => $i
            );
            $query_instance = $this->db_obj->select($sql, $param);
            foreach ($query_instance as $instance_data) {
                $result .= "data[".($i - 1)."].values.push({".
                    "x: ".$instance_data['times'].",".
                    "y: ".$instance_data['amount'].",".
                    "size: ".$instance_data['price'].",".
                    "shape: shapes[".($i - 1)."]".
                "});\n";
                $result .= "if (item_map[".$instance_data['times']."] == undefined) item_map[".$instance_data['times']."] = [];\n";
                $result .= "item_map[".$instance_data['times']."][".$instance_data['amount']."] = '".$instance_data['on_thing_id']." ".addslashes($instance_data['title'])."';\n";
            }
        }

        return $result;

    }// end function getClusterD3Code

}// end class TrainModelGod
?>