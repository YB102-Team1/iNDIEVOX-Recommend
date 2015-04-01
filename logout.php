<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';

if (SiteHelper::isLogin()) {
	SiteHelper::logout();
}
?>
<script>
window.location = '/';
</script>