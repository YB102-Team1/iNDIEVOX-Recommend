<pre>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();
$sql = "INSERT INTO user (id) SELECT DISTINCT source FROM similar_artist ORDER BY source";
$db_obj->query($sql);
unset($db_obj);
?>
</pre>