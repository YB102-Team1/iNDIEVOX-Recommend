<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';

PJAXHelper::run('後台首頁', $_SERVER['PHP_SELF']);
if (!SiteHelper::accessCheck('backyard')) {
    header("Refresh: 0; url=/index.php");
}
?>