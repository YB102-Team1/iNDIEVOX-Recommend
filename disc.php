<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';

$disc_id = $_GET['disc_id'];
$disc_obj = new Disc($disc_id);

PJAXHelper::run($disc_obj->title, $_SERVER['PHP_SELF']);
?>