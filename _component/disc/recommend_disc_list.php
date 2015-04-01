<?php
$disc_obj = new Disc($disc_id); 
$promote_disc_array = $disc_obj->getPromoteDiscs($user_id);
if (count($promote_disc_array)) {
?>
<h3 class="text-left">可加購商品</h3>
	<?php
	foreach ($promote_disc_array as $instance_disc_id => $score) {

		$instance_disc_obj = new Disc($instance_disc_id);
		$instance_user_obj = new User($instance_disc_obj->artist_id);
	?>
	<div style="width: 180px; height: 125px" class="pull-left">
		<h4 style="height: 20px; overflow-y: hidden;">
			<a href="/disc/<?php echo $instance_disc_id; ?>" target="_blank"><?php echo $instance_disc_obj->title; ?></a>
		</h4>
		<h5><?php echo $instance_user_obj->title; ?></h5>
		<h4><del>原價&nbsp;$<?php echo 20 * $instance_disc_obj->getDiscSongsNumber(); ?></del></h4>
		<button class="btn btn-danger"><i class="icon-shopping-cart icon-white"></i>&nbsp;加購&nbsp;$<?php echo 17 * $instance_disc_obj->getDiscSongsNumber(); ?></button>
	</div>
	<?php
		unset($instance_user_obj);
		unset($instance_disc_obj);

	}
	?>
<?php
} else {
?>
<button id="purchase-btn" class="btn btn-default disabled btn-large" style="margin: 50px auto; padding: 30px;"><i class="icon-shopping-cart"></i>&nbsp;已購買&nbsp;$<?php echo 20 * $disc_obj->getDiscSongsNumber(); ?></button>
<?php
}

unset($disc_obj);
?>