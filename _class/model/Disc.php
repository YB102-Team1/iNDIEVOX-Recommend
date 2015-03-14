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

}// end class Disc
?>