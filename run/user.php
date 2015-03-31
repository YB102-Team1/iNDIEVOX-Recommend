<pre>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();
$sql = "INSERT INTO user (id) SELECT DISTINCT artist_id FROM disc ORDER BY artist_id";
$db_obj->query($sql);
unset($db_obj);
?>
</pre>