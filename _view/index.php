<?php
SiteHelper::getNavBar($url);
?>
<section class="pull-left">
    <?php
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
    <h4>&nbsp;</h4>
    <h3><strong>音樂類型</strong></h3>
    <hr>
    <ul class="nav nav-list fwb">
        <?php 
        foreach ($genre_array as $index => $title) {

            if ($index == 0) {
        ?>
        <li class="active">
            <a class="disc-genre-link" data-genre="<?php echo $index; ?>"><?php echo $title; ?></a>
        </li>
        <?php
            } else {
        ?>
        <li>
            <a class="disc-genre-link" data-genre="<?php echo $index; ?>"><?php echo $title; ?></a>
        </li>
        <?php
            }
        }
        ?>
    </ul>
</section>
<section class="pull-right">
    <h4 style="margin-top: -5px;">&nbsp;</h4>
    <div class="row">
        <h3 class="pull-left">排行榜</h3>
        <select id="disc-list-type" class="pull-right input-small" style="margin-top: 10px;">
            <option value="download">熱賣</option>
            <option value="favorite">人氣</option>
            <option value="release">新鮮度</option>
        </select>
        <span class="pull-right" style="margin-top: 15px;"><strong>依據：</strong></span>
    </div>
    <div id="disc-list-block">
        <?php
        $type = 'download';
        $genre = 0;
        include COMPONENT_ROOT.'/disc/home_disc_list.php';
        ?>
    </div>
</section>
<script>
$(document).ready(function() {

    function update_disc_block() {

        var type = $('#disc-list-type').val();
        var genre = $('.nav-list > li.active > a').attr('data-genre');
        $.ajax({
            url: '/action/site/update-home-disc-list',
            data: {
                type: type,
                genre: genre
            },
            type: 'post',
            dataType: "html",
            beforeSend: function () {
                $('#system-message').html('載入中');
                $('#system-message').show();
            },
            success: function(html_block) {
                $('#disc-list-block').html(html_block);
                $('#system-message').html('完成');
                $('#system-message').fadeOut();
            }
        });

    }

    $(document.body).off('click', '.nav-list > li:not(.active) > a');
    $(document.body).on('click', '.nav-list > li:not(.active) > a', function() {

        $('.nav-list > li.active').removeClass('active');
        $(this).parent().addClass('active');
        update_disc_block();

    });

    $(document.body).off('change', '#disc-list-type');
    $(document.body).on('change', '#disc-list-type', function() {

        update_disc_block();

    });

});
</script>