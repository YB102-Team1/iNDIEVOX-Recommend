<?php
class Disc extends DataModel
{

    /**
     * inherited variables:
     *
     * protected $db_obj;
     * protected $table_name;
     * protected $id;
     * protected $is_deleted;
     * protected $create_time;
     * protected $modify_time;
     * protected $delete_time;
     */
    protected $artist_id;
    protected $title;
    protected $description;
    protected $release_time;
    protected $force_price;
    protected $disc_type_id;
    protected $genre;
    protected $producer;
    protected $label;
    protected $sub_label_id;
    protected $upc_code;
    
    /**
     * inherited functions:
     *
     * public function save() {}
     * public function destroy($type = ['mark' | 'delete']) {}
     * public function recover() {}
     */
    public function getRecommendDiscs($instance_user)
    {

        $similar_artist_god_obj = new SimilarArtistGod();
        $similar_artist = $similar_artist_god_obj->getSimilarArtistArray($this->artist_id);
        $similar_artist_list = $this->artist_id.','.implode(',', $similar_artist);
        unset($similar_artist_god_obj);

        if (count($similar_artist)) {
            $sql = "SELECT * ".
                   "FROM train_model ".
                   "WHERE type = 'disc' ".
                   "AND artist_id IN ($similar_artist_list) ".
                   "AND price != 0";
        } else {
            $sql = "SELECT * ".
                   "FROM train_model ".
                   "WHERE type = 'disc' ".
                   "AND price != 0";
        }
        $param = array();
        $query_instance = $this->db_obj->select($sql, $param);
        
        // cf init
        $user_array = array();
        $item_array = array();
        $temp_pref_array = array();
        $train_model = array();

        // read tarin model
        foreach ($query_instance as $instance_data) {
            $user = $instance_data['user_id'];
            $item = $instance_data['on_thing_id'];
            $pref = 0;
            if ($instance_data['is_purchased'] == 1) {
                $pref += 3;
            }
            if ($instance_data['is_liked'] == 1) {
                $pref += 2;
            }

            $data = array(
                "user"=>$user,
                "item"=>$item,
                "pref"=>$pref
            );
            array_push($user_array, $user);
            array_push($item_array, $item);
            if ($user == $instance_user) {
                $temp_pref_array[$item] = $pref;
            }
            array_push($train_model, $data);
        }

        // make this item pref higher
        $temp_pref_array[$this->id] += 10;

        // purify user array and item array
        $user_array = array_unique($user_array);
        sort($user_array);
        $item_array = array_unique($item_array);
        sort($item_array);
        $item_index_array = array_flip($item_array);
        $item_array_quantity = count($item_array);

        // complete the prefs array
        $pref_array = array();
        foreach ($item_array as $item_index => $item_value) {
            if (isset($temp_pref_array[$item_value])) {
                $pref_array[$item_index] = (float)$temp_pref_array[$item_value];
            } else {
                $pref_array[$item_index] = 0.0;
            }
        }

        // co-occurrence matrix
        $co_occurrence = array();
        for ($i = 0; $i < $item_array_quantity; $i++) {
            for ($j = 0; $j < $item_array_quantity; $j++) {
                $co_occurrence[$i][$j] = 0;
            }
        }
        $transactions = array();
        foreach ($train_model as $instance_data) {
            if (is_array($transactions[$instance_data['user']])) {
                $transactions[$instance_data['user']][] = $instance_data['item'];
            } else {
                $transactions[$instance_data['user']] = array($instance_data['item']);
            }
        }
        foreach ($transactions as $tid => $t) {
            foreach ($t as $t1) {
                foreach ($t as $t2) {
                    $i = $item_index_array[$t1];
                    $j = $item_index_array[$t2];
                    $co_occurrence[$i][$j] += 0.5;
                    $co_occurrence[$j][$i] += 0.5;
                }
            }
        }

        // get score
        $score = array();
        for ($i = 0; $i < $item_array_quantity; $i++){
            $score[$i] = 0;
            for($k = 0; $k < $item_array_quantity; $k++){
                $score[$i] += $co_occurrence[$i][$k] * $pref_array[$k];
            }
            if ($pref_array[$i] != 0 || $item[$i] == $this->id) {
                $score[$i] = -1;
            }
        }

        // combine item and score
        $temp_result = array_combine($item_array, $score);
        arsort($temp_result);

        $result = array();
        foreach ($temp_result as $disc_id => $score) {
            if ($score < 0) {
                break;
            } else {
                $result[$disc_id] = $score;
            }
        }

        return array_slice($result, 0, 10, true);

    }// end function getRecommendDiscs

    public function getPromoteDiscs($instance_user)
    {

        $similar_artist_god_obj = new SimilarArtistGod();
        $similar_artist = $similar_artist_god_obj->getSimilarArtistArray($this->artist_id);
        $similar_artist_list = $this->artist_id.','.implode(',', $similar_artist);
        unset($similar_artist_god_obj);

        $tarin_model_god_obj = new TrainModelGod();
        $user_group = $tarin_model_god_obj->getUserGroup($instance_user, 'disc');
        unset($tarin_model_god_obj);

        if (count($similar_artist)) {
            $sql = "SELECT * ".
                   "FROM train_model ".
                   "WHERE type = 'disc' ".
                   "AND artist_id IN ($similar_artist_list) ".
                   "AND item_group = :item_group ".
                   "AND price != 0";
            $param = array(
                ':item_group' => (4 - $user_group)
            );
        } else {
            $sql = "SELECT * ".
                   "FROM train_model ".
                   "WHERE type = 'disc' ".
                   "AND item_group = :item_group ".
                   "AND price != 0";
            $param = array(
                ':item_group' => (4 - $user_group)
            );
        }
        $query_instance = $this->db_obj->select($sql, $param);
        
        // cf init
        $user_array = array();
        $item_array = array();
        $temp_pref_array = array();
        $train_model = array();

        // read tarin model
        foreach ($query_instance as $instance_data) {
            $user = $instance_data['user_id'];
            $item = $instance_data['on_thing_id'];
            $pref = 0;
            if ($instance_data['is_purchased'] == 1) {
                $pref += 3;
            }
            if ($instance_data['is_liked'] == 1) {
                $pref += 2;
            }

            $data = array(
                "user"=>$user,
                "item"=>$item,
                "pref"=>$pref
            );
            array_push($user_array, $user);
            array_push($item_array, $item);
            if ($user == $instance_user) {
                $temp_pref_array[$item] = $pref;
            }
            array_push($train_model, $data);
        }

        // make this item pref higher
        $temp_pref_array[$this->id] += 10;

        // purify user array and item array
        $user_array = array_unique($user_array);
        sort($user_array);
        $item_array = array_unique($item_array);
        sort($item_array);
        $item_index_array = array_flip($item_array);
        $item_array_quantity = count($item_array);

        // complete the prefs array
        $pref_array = array();
        foreach ($item_array as $item_index => $item_value) {
            if (isset($temp_pref_array[$item_value])) {
                $pref_array[$item_index] = (float)$temp_pref_array[$item_value];
            } else {
                $pref_array[$item_index] = 0.0;
            }
        }

        // co-occurrence matrix
        $co_occurrence = array();
        for ($i = 0; $i < $item_array_quantity; $i++) {
            for ($j = 0; $j < $item_array_quantity; $j++) {
                $co_occurrence[$i][$j] = 0;
            }
        }
        $transactions = array();
        foreach ($train_model as $instance_data) {
            if (is_array($transactions[$instance_data['user']])) {
                $transactions[$instance_data['user']][] = $instance_data['item'];
            } else {
                $transactions[$instance_data['user']] = array($instance_data['item']);
            }
        }
        foreach ($transactions as $tid => $t) {
            foreach ($t as $t1) {
                foreach ($t as $t2) {
                    $i = $item_index_array[$t1];
                    $j = $item_index_array[$t2];
                    $co_occurrence[$i][$j]++;
                    $co_occurrence[$j][$i]++;
                }
            }
        }

        // get score
        $score = array();
        for ($i = 0; $i < $item_array_quantity; $i++){
            $score[$i] = 0;
            for($k = 0; $k < $item_array_quantity; $k++){
                $score[$i] += $co_occurrence[$i][$k] * $pref_array[$k];
            }
            if ($pref_array[$i] > 2 || $item[$i] == $this->id) {
                $score[$i] = -1;
            }
        }

        // combine item and score
        $temp_result = array_combine($item_array, $score);
        arsort($temp_result);

        $result = array();
        foreach ($temp_result as $disc_id => $score) {
            if ($score == -1) {
                break;
            } else {
                $result[$disc_id] = $score;
            }
        }

        return array_slice($result, 0, 2, true);

    }// end function getPromoteDiscs

    public function getIcon($size)
    {

        switch ($size) {
        case '180':
            $sql = "SELECT icon_180 icon FROM disc_icon WHERE disc_id = :disc_id LIMIT 1";
            break;

        case '480':
        default:
            $sql = "SELECT icon_480 icon FROM disc_icon WHERE disc_id = :disc_id LIMIT 1";
            break;

        }
        $param = array(
            ":disc_id" => $this->id
        );

        $query_instance = $this->db_obj->select($sql, $param);
        $icon_url = '';
        foreach ($query_instance as $instance_data) {
            $icon_url = $instance_data['icon'];
        }

        return $icon_url;

    }// end function getIcon

    public function getFavoriteNumber()
    {

        $sql = "SELECT COUNT(id) count FROM favorite WHERE on_thing_id = :on_thing_id AND type = 'disc' GROUP BY on_thing_id";
        $param = array(
            ":on_thing_id" => $this->id
        );

        $query_instance = $this->db_obj->select($sql, $param);
        $count_number = 0;
        foreach ($query_instance as $instance_data) {
            $count_number = $instance_data['count'];
        }

        return $count_number;

    }// end function getFavoriteNumber

    public function getDiscSongsNumber()
    {

        $sql = "SELECT COUNT(id) count FROM song WHERE disc_id = :disc_id GROUP BY disc_id";
        $param = array(
            ":disc_id" => $this->id
        );

        $query_instance = $this->db_obj->select($sql, $param);
        $count_number = 0;
        foreach ($query_instance as $instance_data) {
            $count_number = $instance_data['count'];
        }

        return $count_number;

    }// end function getDiscSongsNumber

}// end class Disc
?>