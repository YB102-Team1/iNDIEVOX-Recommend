<?php
class Song extends DataModel
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
    protected $disc_id;
    protected $disc_order;
    protected $title;
    protected $description;
    protected $release_time;
    protected $year;
    protected $price;
    protected $price_type;
    protected $genre;
    protected $lyric;
    protected $distribution_lyric;
    protected $total_sec;
    protected $artist_name;
    protected $song_composer;
    protected $lyricist;
    protected $producer;
    protected $label;
    protected $isrc_code;
    protected $radio_playable;
    
    /**
     * inherited functions:
     *
     * public function save() {}
     * public function destroy($type = ['mark' | 'delete']) {}
     * public function recover() {}
     */

}// end class Song
?>