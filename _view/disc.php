<?php
SiteHelper::getNavBar($url);
$disc_id = $_GET['disc_id'];
$disc_obj = new Disc($disc_id);
$user_obj = new User($disc_obj->artist_id);
$genre_array = array(
    "全部",
    "搖滾",
    "嘻哈/饒舌",
    "電音/舞曲",
    "流行",
    "民謠",
    "唱作人",
    "另類",
    "後搖滾",
    "重金屬",
    "龐克",
    "雷鬼/放客",
    "節奏藍調/靈魂",
    "古典",
    "藍調",
    "爵士",
    "原聲帶/新世紀音樂",
    "世界音樂",
    "宗教音樂",
    "動漫音樂"
);
?>
<div class="row" style="border-bottom: 2px solid #555555; padding-bottom: 10px;">
    <div class="pull-left">
        <img src="<?php echo $user_obj->icon; ?>" class="img-circle">
    </div>
    <div class="pull-left" style="margin-left: 20px;">
        <h4 class="pull-right text-right">
            <p>&nbsp;</p>
            粉絲數：<?php echo $user_obj->fans; ?><br>
            <a href="<?php echo $user_obj->url; ?>" target="_blank"><?php echo $user_obj->title; ?> on iNDIEVOX</a>
        </h4>
        <h1>&nbsp;</h1>
        <h2><?php echo $user_obj->title; ?></h2>
        <div style="width: 1000px; height: 80px; overflow: hidden;"><?php echo $user_obj->description; ?></div>
    </div>
</div>
<h4>&nbsp;</h4>
<div class="row">
    <section class="pull-left">
        <img src="<?php echo $disc_obj->getIcon(480); ?>" style="width: 360px; height: 360px;" class="img-polaroid" />
        <div>
            <h4 style="margin-top: -10px;">&nbsp;</h4>
            <h4 class="fwb"><span style="padding-left: 120px; padding-right: 20px;">音樂分類</span><?php echo $genre_array[$disc_obj->genre]; ?></h4>
            <h4 class="fwb"><span style="padding-left: 120px; padding-right: 20px;">發行日期</span><?php echo StringHelper::dateFormat($disc_obj->release_time, 0, 10); ?></h4>
            <h4 class="fwb"><span style="padding-left: 120px; padding-right: 20px;">加入最愛</span><?php echo $disc_obj->getFavoriteNumber(); ?></h4>
            <div id="purchase-block" style="width: 370px; height: 185px; margin: 0;" class="text-center row">
                <button id="purchase-btn" class="btn btn-danger btn-large" style="margin: 50px auto; padding: 30px;"><i class="icon-shopping-cart icon-white"></i>&nbsp;購買專輯&nbsp;$<?php echo 20 * $disc_obj->getDiscSongsNumber(); ?></button>
            </div>
        </div>
    </section>
    <section class="pull-right fwb" style="width: 800px; font-size: 1.5em; line-height: 1.5em;">
        <h1 style="margin-bottom: 20px;"><?php echo $disc_obj->title; ?></h1>
        <div style="max-height: 600px; overflow-y: auto;">
            <?php echo nl2br($disc_obj->description); ?>
        </div>
    </section>
</div>
<?php
if (SiteHelper::isLogin()) {
?>
<h4>&nbsp;</h4>
<h3>我猜你會喜歡...</h3>
<div id="recommend-disc-block">
    <?php
    $recommend_disc_array = $disc_obj->getRecommendDiscs($_COOKIE['user_id']);
    foreach ($recommend_disc_array as $instance_disc_id => $score) {

        $instance_disc_obj = new Disc($instance_disc_id);
        $instance_user_obj = new User($instance_disc_obj->artist_id);
    ?>
    <div style="width: 240px; height: 300px;" class="box">
        <a href="/disc/<?php echo $instance_disc_id; ?>" class="main-pjax" title="<?php echo $instance_disc_obj->title; ?>">
            <img src="<?php echo $instance_disc_obj->getAPIIcon(180); ?>" style="width: 220px; height: 220px;" class="img-polaroid" />
        </a>
        <h4 style="width: 220px; height: 20px; overflow: hidden;">
            <a href="/disc/<?php echo $instance_disc_id; ?>" class="main-pjax" title="<?php echo $instance_disc_obj->title; ?>">
                <?php echo $instance_disc_obj->title; ?>
            </a>
        </h4>
        <h5><?php echo $instance_user_obj->title; ?></h5>
    </div>
    <?php
        unset($instance_user_obj);
        unset($instance_disc_obj);

    }
    ?>
</div>
<script>
$(document).ready(function() {

    $('#recommend-disc-block').masonry({
        itemSelector: '.box',
        columnWidth: 240
    });

    $(document.body).off('click', '#purchase-btn');
    $(document.body).on('click', '#purchase-btn', function () {
        $.ajax({
            url: '/action/site/buy-disc',
            data: {
                disc_id: <?php echo $disc_id; ?>,
                user_id: <?php echo $_COOKIE['user_id']; ?>
            },
            type: 'post',
            dataType: "html",
            beforeSend: function () {
                $('#system-message').html('購買中');
                $('#system-message').show();
            },
            success: function(html_block) {
                $('#purchase-block').html(html_block);
                $('#system-message').html('完成');
                $('#system-message').fadeOut();
            }
        });

    });

});
</script>
<?php 
} else {
?>
<script>
$(document).ready(function() {

    $(document.body).off('click', '#purchase-btn');
    $(document.body).on('click', '#purchase-btn', function () {

        window.location = '/login.php?target=<?php echo $disc_id; ?>';

    });

});
</script>
<?php
}
?>