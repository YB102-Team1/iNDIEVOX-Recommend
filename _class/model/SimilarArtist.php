<?php
class SimilarArtist extends DataModel
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
    protected $source;
    protected $target;
    protected $type;
    protected $support;
    protected $occurrence;

    /**
     * inherited functions:
     *
     * public function save() {}
     * public function destroy($type = ['mark' | 'delete']) {}
     * public function recover() {}
     */

}// end class SimilarArtist
?>