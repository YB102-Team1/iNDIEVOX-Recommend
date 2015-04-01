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
    echo $train_model_god_obj->getClusterD3Code('disc');
    unset($train_model_god_obj);
    ?>

    // data = randomData(3,400);

    var chart;
    nv.addGraph(function() {
        chart = nv.models.scatterChart()
            .showDistX(true)
            .showDistY(true)
            .duration(300)
            .color(d3.scale.category10().range());

        chart.xAxis.tickFormat(d3.format('.02f'));
        chart.yAxis.tickFormat(d3.format('.02f'));
        chart.tooltipContent(function(key, x, y) {
            return '<h4>' + item_map[parseInt(x)][parseInt(y)] + '</h4>';
        });

        d3.select('#disc-cluster')
            .datum(nv.log(data))
            .call(chart);

        nv.utils.windowResize(chart.update);
        chart.dispatch.on('stateChange', function(e) { nv.log('New State:', JSON.stringify(e)); });
        return chart;
    });


    function randomData(groups, points) { //# groups,# points per group
        var data = [],
            shapes = ['circle'],
            random = d3.random.normal();

        for (i = 0; i < groups; i++) {
            data.push({
                key: 'Group ' + i,
                values: []
            });

            for (j = 0; j < points; j++) {
                data[i].values.push({
                    x: random(),
                    y: random(),
                    size: Math.random(),
                    shape: shapes[j % shapes.length]
                });
            }
        }
        return data;
    }

</script>