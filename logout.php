<?php
include $_SERVER['DOCUMENT_ROOT'].'/_config/system_config.inc';

if (SiteHelper::isLogin()) {
	$prev = $_GET['prev'];
	if (empty($prev) || $prev = 'logout.php') {
		$prev = '/';
	}
	SiteHelper::logout();
?>
<script>
window.location = '<?php echo $prev; ?>';
</script>
<?php
}
?>