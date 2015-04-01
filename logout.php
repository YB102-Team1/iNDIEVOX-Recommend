<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';

if ($_GET['target']) {
	$target = "/disc/".$_GET['target'];
} else {
	$target = "/";
}

if (SiteHelper::isLogin()) {
	SiteHelper::logout();
}
?>
<script>
window.location = '<?php echo $target; ?>';
</script>