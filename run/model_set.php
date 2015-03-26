<pre>
<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';
$db_obj = new DatabaseAccess();

$sql = "TRUNCATE `test_set_0`;
TRUNCATE `test_set_1`;
TRUNCATE `test_set_2`;
TRUNCATE `test_set_3`;
TRUNCATE `test_set_4`;
TRUNCATE `train_set_0`;
TRUNCATE `train_set_1`;
TRUNCATE `train_set_2`;
TRUNCATE `train_set_3`;
TRUNCATE `train_set_4`;";
$db_obj->query($sql);

for ($i = 0; $i <= 4; $i++) {
    $sql = "INSERT INTO test_set_$i SELECT * FROM train_model WHERE id % 5 = $i";
    $db_obj->query($sql);
    $sql = "INSERT INTO train_set_$i SELECT * FROM train_model WHERE id % 5 != $i";
    $db_obj->query($sql);
}

unset($db_obj);
?>
</pre>