<?php
class ShoppingCluster extends DataModel
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
    protected $cluster_type;
    protected $item_type;
    protected $x;
    protected $y;
    protected $item_count;
    protected $record_count;
    protected $group_serial;

    /**
     * inherited functions:
     *
     * public function save() {}
     * public function destroy($type = ['mark' | 'delete']) {}
     * public function recover() {}
     */

}// end class ShoppingCluster
?>