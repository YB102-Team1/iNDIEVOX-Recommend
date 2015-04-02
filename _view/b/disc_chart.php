<?php
SiteHelper::getNavBar($url);
SiteHelper::getBackyardBreadcrumbs($url);
?>
<div class="row">
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
    <h3 class="text-center">
        <input type="radio" name="chart_type" value="download" checked="checked" /> 銷售次數
        <input type="radio" name="chart_type" value="amount" /> 銷售金額
        <input type="radio" name="chart_type" value="favorite" /> 人氣
        &nbsp;&nbsp;&nbsp;
        <select id="disc-genre">
            <?php
            foreach ($genre_array as $index => $title) {
            ?>
            <option value="<?php echo $index; ?>"><?php echo $title; ?></option>
            <?php
            }
            ?>
        </select>
        <hr>
    </h3>
    <div id="disc-chart-block" class="row">
        <?php
        $type = 'download';
        $genre = 0;
        include COMPONENT_ROOT.'/b/disc_chart_block.php';
        ?>
    </div>
</div>
<script>
function update_chart_color() {

    var type = $('input[name="chart_type"]:checked').val();
    var chart_color = '#d67777';
    if (type == 'amount') {
        chart_color = '#77d677';
    } else if (type == 'favorite') {
        chart_color = '#7777d6';
    }

    $('.nv-series-0').css('fill', chart_color);
    $('.nv-legend-symbol').css('fill', chart_color);

}

$(document).ready(function () {

    function update_chart_block() {

        var type = $('input[name="chart_type"]:checked').val();
        var genre = $('#disc-genre').val();
        $.ajax({
            url: '/action/site/update-disc-chart-block',
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
                $('#disc-chart-block').html(html_block);
                $('#system-message').html('完成');
                $('#system-message').fadeOut();

                setTimeout('update_chart_color()', 100);
            }
        });

    }

    $(document.body).off('change', 'input[name="chart_type"]');
    $(document.body).on('change', 'input[name="chart_type"]', function() {

        update_chart_block();

    });

    $(document.body).off('change', '#disc-genre');
    $(document.body).on('change', '#disc-genre', function() {

        update_chart_block();

    });

    setTimeout('update_chart_color()', 100);

});
</script>