<div id="disc-list" style="width: 950px;">
    <?php
    $disc_god_obj = new DiscGod();
    $disc_query = $disc_god_obj->getDiscList($type, $genre, 0, 15);
    $serial = 1;

    foreach ($disc_query as $data) {

        $disc_id = $data['id'];
        $disc_obj = new Disc($disc_id);
    ?>
    <div class="box" style="width: 190px; height: 300px;">
        <strong><?php echo "No. $serial"; ?></strong><br>
        <a>
            <img src="" style="width: 180px; height: 180px;" />
        </a>
        <?php 
        echo $disc_obj->title."<br>";
        echo "●".$disc_obj->artist_id."<br>"; 
        ?>
        <span class="pull-right" style="margin-right: 15px;"><?php echo StringHelper::dateFormat($disc_obj->release_time, 0, 10); ?></span>
    </div>
    <?php
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