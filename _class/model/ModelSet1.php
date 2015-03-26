<?php
class ModelSet1 extends DataModel
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
    protected $user_id;
    protected $on_thing_id;
    protected $artist_id;
    protected $type;
    protected $is_purchased;
    protected $is_liked;
    protected $genre;
    protected $user_group;
    protected $item_group;

    /**
     * inherited functions:
     *
     * public function save() {}
     * public function destroy($type = ['mark' | 'delete']) {}
     * public function recover() {}
     */

}// end class ModelSet1
?>