<?php
class SimilarArtistGod extends DataModelGod
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
    public function getSimilarArtistArray($artist_id, $length = 500)
    {

        $sql = "SELECT target ".
               "FROM similar_artist ".
               "WHERE source = :source ".
               "ORDER BY occurrence DESC ".
               "LIMIT $length";
        $param = array(
            ':source' => $artist_id
        );

        $query_instance = $this->db_obj->select($sql, $param);
        $similar_artist_array = array();
        foreach ($query_instance as $instance_data) {
            array_push($similar_artist_array, $instance_data['target']);
        }

        return $similar_artist_array;

    }// end function getSimilarArtistArray

}// end class SimilarArtistGod
?>