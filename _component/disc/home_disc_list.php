<div id="disc-list" style="width: 950px;">
    <?php
    $disc_god_obj = new DiscGod();
    $disc_query = $disc_god_obj->getDiscList($type, $genre, 0, 10);
    $serial = 1;

    foreach ($disc_query as $data) {

        $disc_id = $data['id'];
        $disc_url = "/disc/".$disc_id;
        $disc_obj = new Disc($disc_id);
        $artist_obj = new User($disc_obj->artist_id);
    ?>
    <div class="box" style="width: 190px; height: 350px;">
        <h4><strong style="color: #993333; "><?php echo "No. $serial"; ?></strong></h4>
        <a href="<?php echo $disc_url; ?>" class="main-pjax">
            <img src="<?php echo $disc_obj->getIcon(180); ?>" style="width: 170px; height: 170px;" class="img-polaroid" />
        </a>
        <h4 class="text-center" style="height: 40px; overflow: hidden;"><strong><a class="main-pjax" href="<?php echo $disc_url; ?>"><?php echo $disc_obj->title; ?></a><strong></h4>
        <h5 class="text-center" style="margin: 2px 0;"><?php echo $artist_obj->title; ?></h5>
        <span class="pull-right" style="margin-right: 15px;"><?php echo StringHelper::dateFormat($disc_obj->release_time, 0, 10); ?></span>
    </div>
    <?php
        unset($artist_obj);
        unset($disc_obj);
        $serial++;

    }

    unset($disc_god_obj);
    ?>
</div>
<script>
$(document).ready(function() {
    $('#disc-list').masonry({
        itemSelector: '.box',
        columnWidth: 190
    });
});
</script>