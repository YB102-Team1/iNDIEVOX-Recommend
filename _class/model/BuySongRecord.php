<?php
class BuySongRecord extends DataModel
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
    protected $buyer_id;
    protected $song_id;
    protected $disc_id;
    protected $price;
    protected $buy_type;
    protected $buy_time;
    
    /**
     * inherited functions:
     *
     * public function save() {}
     * public function destroy($type = ['mark' | 'delete']) {}
     * public function recover() {}
     */

}// end class BuySongRecord
?>