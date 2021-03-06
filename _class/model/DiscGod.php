<?php
class DiscGod extends DataModelGod
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
    public function getDiscList($type, $genre = 0, $offset = 0, $length = 10)
    {

        switch ($type) {

        case 'download':

            if ($genre==0) {

                $select_sql = "SELECT ".
                              "d.id, ".
                              "SUM(bd.price) price_sum ".
                              "FROM disc d ".
                              "INNER JOIN buy_disc_record bd ".
                              "ON (d.id=bd.disc_id) ".
                              "WHERE d.is_released='1' ".
                              "AND d.is_deleted='0' ".
                              "GROUP BY d.id ".
                              "ORDER BY price_sum DESC";
                $param = array();

            } else {

                $select_sql = "SELECT ".
                              "d.id, ".
                              "SUM(bd.price) price_sum ".
                              "FROM disc d ".
                              "INNER JOIN buy_disc_record bd ".
                              "ON (d.id=bd.disc_id) ".
                              "WHERE d.is_released='1' ".
                              "AND d.is_deleted='0' ".
                              "AND d.genre=:genre ".
                              "GROUP BY d.id ".
                              "ORDER BY price_sum DESC";
                $param = array(
                    ":genre" => $genre
                );

            }

            break;

        case 'favorite':

            if ($genre==0) {

                $select_sql = "SELECT ".
                              "d.id, ".
                              "COUNT(d.id) fav_num ".
                              "FROM disc d ".
                              "INNER JOIN favorite f ".
                              "ON (".
                                  "d.id=f.on_thing_id ".
                                  "AND f.type='disc'".
                               ") ".
                               "WHERE ".
                               "d.is_released='1' ".
                               "AND d.is_deleted='0' ".
                               "GROUP BY d.id ".
                               "ORDER BY fav_num DESC";
                $param = array();

            } else {

                $select_sql = "SELECT ".
                              "d.id, ".
                              "COUNT(d.id) fav_num ".
                              "FROM disc d ".
                              "INNER JOIN favorite f ".
                              "ON (".
                                  "d.id=f.on_thing_id ".
                                  "AND f.type='disc'".
                               ") ".
                               "WHERE ".
                               "d.is_released='1' ".
                               "AND d.is_deleted='0' ".
                               "AND d.genre=:genre ".
                               "GROUP BY d.id ".
                               "ORDER BY fav_num DESC";
                $param = array(
                    ":genre" => $genre
                );

            }

            break;

        case 'release':

            if ($genre==0) {

                $select_sql = "SELECT id ".
                              "FROM disc ".
                              "WHERE is_released='1' ".
                              "AND is_deleted='0' ".
                              "ORDER BY release_time DESC";
                $param = array();

            } else {

                $select_sql = "SELECT id ".
                              "FROM disc ".
                              "WHERE is_released='1' ".
                              "AND is_deleted='0' ".
                              "AND genre=:genre ".
                              "ORDER BY release_time DESC";
                $param = array(
                    ":genre" => $genre
                );

            }

            break;
            
        }

        if (!empty($length)) {
            $select_sql = $select_sql." LIMIT :offset, :length";
            $param[':offset'] = $offset;
            $param[':length'] = $length;
        }

        return $this->db_obj->select($select_sql, $param);

    }// end function getDiscList
    
    public function getChartInfo($type, $genre = 0, $offset = 0, $length = 20)
    {

        switch ($type) {

        case 'download':

            if ($genre==0) {

                $select_sql = "SELECT d.id disc_id, d.title, u.title artist, COUNT(bd.id) score ".
                              "FROM disc d INNER JOIN buy_disc_record bd ON (d.id=bd.disc_id) ".
                              "LEFT JOIN user u ON d.artist_id = u.id ".
                              "WHERE d.is_released='1' AND d.is_deleted='0' ".
                              "GROUP BY d.id ".
                              "ORDER BY score DESC";
                $param = array();

            } else {

                $select_sql = "SELECT d.id disc_id, d.title, u.title artist, COUNT(bd.id) score ".
                              "FROM disc d INNER JOIN buy_disc_record bd ON (d.id=bd.disc_id) ".
                              "LEFT JOIN user u ON d.artist_id = u.id ".
                              "WHERE d.is_released='1' AND d.is_deleted='0' AND genre = :genre ".
                              "GROUP BY d.id ".
                              "ORDER BY score DESC";
                $param = array(
                    ":genre" => $genre
                );

            }

            break;

        case 'amount':

            if ($genre==0) {

                $select_sql = "SELECT d.id disc_id, d.title, u.title artist, SUM(bd.price) score ".
                              "FROM disc d INNER JOIN buy_disc_record bd ON (d.id=bd.disc_id) ".
                              "LEFT JOIN user u ON d.artist_id = u.id ".
                              "WHERE d.is_released='1' AND d.is_deleted='0' ".
                              "GROUP BY d.id ".
                              "ORDER BY score DESC";
                $param = array();

            } else {

                $select_sql = "SELECT d.id disc_id, d.title, u.title artist, SUM(bd.price) score ".
                              "FROM disc d INNER JOIN buy_disc_record bd ON (d.id=bd.disc_id) ".
                              "LEFT JOIN user u ON d.artist_id = u.id ".
                              "WHERE d.is_released='1' AND d.is_deleted='0' AND genre = :genre ".
                              "GROUP BY d.id ".
                              "ORDER BY score DESC";
                $param = array(
                    ":genre" => $genre
                );

            }

            break;

        case 'favorite':

            if ($genre==0) {

                $select_sql = "SELECT d.id disc_id, d.title, u.title artist, COUNT(d.id) score ".
                              "FROM disc d INNER JOIN favorite f ON (d.id=f.on_thing_id AND f.type='disc') ".
                              "LEFT JOIN user u ON d.artist_id = u.id ".
                              "WHERE d.is_released='1' AND d.is_deleted='0' ".
                              "GROUP BY d.id ".
                              "ORDER BY score DESC";
                $param = array();

            } else {

                $select_sql = "SELECT d.id disc_id, d.title, u.title artist, COUNT(d.id) score ".
                              "FROM disc d INNER JOIN favorite f ON (d.id=f.on_thing_id AND f.type='disc') ".
                              "LEFT JOIN user u ON d.artist_id = u.id ".
                              "WHERE d.is_released='1' AND d.is_deleted='0' AND genre = :genre ".
                              "GROUP BY d.id ".
                              "ORDER BY score DESC";
                $param = array(
                    ":genre" => $genre
                );

            }

            break;
            
        }

        if (!empty($length)) {
            $select_sql = $select_sql." LIMIT :offset, :length";
            $param[':offset'] = $offset;
            $param[':length'] = $length;
        }

        return $this->db_obj->select($select_sql, $param);

    }// end function getChartInfo

}// end class DiscGod
?>