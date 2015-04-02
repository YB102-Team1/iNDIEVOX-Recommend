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

    public function getClusterD3Code($code_type, $item_type) 
    {

        $result = "";

        switch ($code_type) {

        case 'cluster_info':

            $sql = "SELECT item_group, COUNT(DISTINCT on_thing_id) item_count, COUNT(id) record_count, SUM(price) amount ".
                   "FROM `train_model` ".
                   "WHERE type='disc' ".
                   "AND item_group != 0 ".
                   "GROUP BY item_group";
            $param = array();
            $query_instance = $this->db_obj->select($sql, $param);

            foreach ($query_instance as $instance_data) {
                $result .= "item_count.push({".
                    "key: 'Level ".$instance_data['item_group']."',".
                    "y: ".$instance_data['item_count'].
                "});\n";
                $result .= "record_count.push({".
                    "key: 'Level ".$instance_data['item_group']."',".
                    "y: ".$instance_data['record_count'].
                "});\n";
                $result .= "amount_count.push({".
                    "key: 'Level ".$instance_data['item_group']."',".
                    "y: ".$instance_data['amount'].
                "});\n";
            }

            break;

        case 'cluster_detail':

            $array_note = array();
            for ($i = 1; $i <= 3; $i++) {
                $first = true;
                $sql = "SELECT t.on_thing_id, d.title, u.title artist, t.price, COUNT(t.id) times, SUM(t.price) amount ".
                       "FROM train_model t ".
                       "LEFT JOIN disc d ON t.on_thing_id = d.id ".
                       "LEFT JOIN user u ON d.artist_id = u.id ".
                       "WHERE t.type = :type AND t.price != 0 AND t.item_group = :item_group ".
                       "GROUP BY t.on_thing_id ".
                       "ORDER BY t.item_group";
                $param = array(
                    ":type" => $item_type,
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
                    if (!in_array($instance_data['times'], $array_note)) {
                        $result .= "item_map[".$instance_data['times']."] = [];\n";
                        $array_note[] = $instance_data['times'];
                    }
                    $result .= "item_map[".$instance_data['times']."][".$instance_data['amount']."] = {".
                               "id: ".$instance_data['on_thing_id'].",".
                               "title: '".addslashes($instance_data['title'])."',".
                               "artist: '".addslashes($instance_data['artist'])."',".
                               "price: '".$instance_data['price']."',".
                               "times: ".$instance_data['times'].",".
                               "amount: ".$instance_data['amount'].
                               "};\n";
                }
            }

            break;

        }

        return $result;

    }// end function getClusterD3Code

    public function getClusterData($cluster_type, $item_type) 
    {

        $sql = "SELECT group_serial, x, y ".
               "FROM shopping_cluster ".
               "WHERE cluster_type = :cluster_type AND item_type = :item_type";
        $param = array(
            ":cluster_type" => $cluster_type,
            ":item_type" => $item_type
        );

        return $this->db_obj->select($sql, $param);

    }// end function getClusterData

}// end class TrainModelGod
?>