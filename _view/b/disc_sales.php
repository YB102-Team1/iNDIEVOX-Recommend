<?php
SiteHelper::getNavBar($url);
SiteHelper::getBackyardBreadcrumbs($url);
?>
<div class="row">
    <div class="pull-left" style="width: 400px; height: 400px;">
        <svg id="item-count"></svg>
        <h3 class="text-center" style="margin-top: -40px;">各叢集唱片數</h3>
    </div>
    <div class="pull-left" style="width: 400px; height: 400px;">
        <svg id="record-count"></svg>
        <h3 class="text-center" style="margin-top: -40px;">各叢集銷售總次數</h3>
    </div>
    <div class="pull-left" style="width: 400px; height: 400px;">
        <svg id="amount-count"></svg>
        <h3 class="text-center" style="margin-top: -40px;">各叢集銷售總金額</h3>
    </div>
</div>
<hr>
<table class="table table-condensed table-bordered table-striped" style="width: 400px; margin: 0 auto;">
    <thead class="fwb">
        <tr style="background-color: silver;">
            <th>叢集等級</th>
            <th>平均銷售次數</th>
            <th>平均銷售金額</th>
        </tr>
    </thead>
    <tbody>
        <?php
        $train_model_god_obj = new TrainModelGod();
        $cluster_basic_data = $train_model_god_obj->getClusterData('item', 'disc');
        unset($train_model_god_obj);

        foreach ($cluster_basic_data as $data) {
        ?>
        <tr>
            <td style="text-align: right;"><?php echo $data['group_serial']; ?></td>
            <td style="text-align: right;"><?php echo sprintf('%.2f', $data['x']); ?></td>
            <td style="text-align: right;"><?php echo sprintf('%.2f', $data['y']); ?></td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<script>

    var item_count = [];
    var record_count = [];
    var amount_count = [];
    <?php
    $train_model_god_obj = new TrainModelGod();
    echo $train_model_god_obj->getClusterD3Code('cluster_info', 'disc');
    unset($train_model_god_obj);
    ?>

    var height = 600;
    var width = 600;

    nv.addGraph(function() {
        var chart = nv.models.pieChart()
            .x(function(d) { return d.key })
            .y(function(d) { return d.y })
            .width(width)
            .height(height)
            .labelType("percent")
            .donut(true)
            .donutRatio(0);

        chart.tooltipContent(function(key, x, y) {
            return '<h4>' + key + '</h4>' +
                   x.substr(0, x.length - 3) + '<br>';
        });

        d3.select("#item-count")
            .datum(item_count)
            .transition().duration(1200)
            .attr('width', width)
            .attr('height', height)
            .call(chart);

        return chart;
    });

    nv.addGraph(function() {
        var chart = nv.models.pieChart()
            .x(function(d) { return d.key })
            .y(function(d) { return d.y })
            .width(width)
            .height(height)
            .labelType("percent")
            .donut(true)
            .donutRatio(0);

        chart.tooltipContent(function(key, x, y) {
            return '<h4>' + key + '</h4>' +
                   x.substr(0, x.length - 3) + '<br>';
        });

        d3.select("#record-count")
            .datum(record_count)
            .transition().duration(1200)
            .attr('width', width)
            .attr('height', height)
            .call(chart);

        return chart;
    });

    nv.addGraph(function() {
        var chart = nv.models.pieChart()
            .x(function(d) { return d.key })
            .y(function(d) { return d.y })
            .width(width)
            .height(height)
            .labelType("percent")
            .donut(true)
            .donutRatio(0);

        chart.tooltipContent(function(key, x, y) {
            return '<h4>' + key + '</h4>' +
                   x.substr(0, x.length - 3) + '<br>';
        });

        d3.select("#amount-count")
            .datum(amount_count)
            .transition().duration(1200)
            .attr('width', width)
            .attr('height', height)
            .call(chart);

        return chart;
    });

</script>