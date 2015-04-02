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

    public function getSimilarArtistSourceArray()
    {

        $sql = "SELECT s.source user_id, u.title, COUNT(s.id) edges ".
               "FROM similar_artist s ".
               "LEFT JOIN user u ".
               "ON s.source = u.id ".
               "WHERE s.occurrence > 150 ".
               "GROUP BY s.source ".
               "ORDER BY edges DESC ";
        $param = array();

        $query_instance = $this->db_obj->select($sql, $param);
        $similar_artist_source_array = array();
        foreach ($query_instance as $instance_data) {
            $similar_artist_source_array[$instance_data['user_id']] = array(
                "title" => $instance_data['title'],
                "edges" => $instance_data['edges']
            );
        }

        return $similar_artist_source_array;

    }// end function getSimilarArtistSourceArray

    public function getSimilarArtistArborCode()
    {

        $sql = "SELECT s.source user_id, u1.title source, u2.title target ".
               "FROM similar_artist s ".
               "LEFT JOIN user u1 ".
               "ON s.source = u1.id ".
               "LEFT JOIN user u2 ".
               "ON s.target = u2.id ".
               "WHERE s.occurrence > 150 ";
        $param = array();

        $query_instance = $this->db_obj->select($sql, $param);
        $similar_artist_arbor_code = "{color:#222299}\n-- {color:#e9eff0, weight:3}\n";
        $artist_array = array();
        foreach ($query_instance as $instance_data) {
            $user_id = $instance_data['user_id'];
            $source = $instance_data['source'];
            $target = $instance_data['target'];

            $similar_artist_arbor_code .= ";user-$user_id-\n;$source -- $target\n";
            $artist_array[$user_id] = $source;
        }

        $similar_artist_arbor_code .= "\n";

        foreach ($artist_array as $artist_id => $title) {
            $similar_artist_arbor_code .= ";user-$artist_id-\n;$title{color:#c6531e}\n";
        }

        return $similar_artist_arbor_code;

    }// end function getSimilarArtistArborCode

}// end class SimilarArtistGod
?>