<?php
SiteHelper::getNavBar($url);
SiteHelper::getBackyardBreadcrumbs($url);
?>
<svg id="disc-cluster" style="height: 800px;"></svg>
<script>

    var shapes = ['circle', 'diamond', 'square'];
    var data = [];
    var item_map = [];
    for (i = 1; i <= 3; i++) {
        data.push({
            key: 'Level ' + i,
            values: []
        });
    }
    <?php
    $train_model_god_obj = new TrainModelGod();
    echo $train_model_god_obj->getClusterD3Code('cluster_detail', 'disc');
    unset($train_model_god_obj);
    ?>

    var chart;
    nv.addGraph(function() {
        chart = nv.models.scatterChart()
            .showDistX(true)
            .showDistY(true)
            .duration(300)
            .color(d3.scale.category10().range());

        chart.xAxis.tickFormat(d3.format('.0f'));
        chart.yAxis.tickFormat(d3.format('.0f'));
        chart.tooltipContent(function(key, x, y) {
            var item = item_map[parseInt(x)][parseInt(y)];
            return '<h4>' + item.title + '</h4>' +
                   '唱片編號：' + item.id + '<br>' +
                   '唱片藝人：' + item.artist + '<br>' +
                   '目前定價：' + item.price + '<br>' +
                   '銷售次數：' + item.times + '<br>' +
                   '銷售金額：' + item.amount + '<br>'
                   ;
        });

        d3.select('#disc-cluster')
            .datum(data)
            .call(chart);

        nv.utils.windowResize(chart.update);
        chart.dispatch.on('stateChange', function(e) { nv.log('New State:', JSON.stringify(e)); });
        return chart;
    });

</script>