<?php
switch ($type) {

case 'download':
    $chart_title = "銷售次數";
    break;

case 'amount':
    $chart_title = "銷售金額";
    break;

case 'favorite':
    $chart_title = "人氣";
    break;
}

$disc_god_obj = new DiscGod();
$data_array = $disc_god_obj->getChartInfo($type, $genre);
unset($disc_god_obj);
?>
<table class="table table-condensed table-bordered table-striped pull-left" style="width: 600px;">
    <thead>
        <tr>
            <th>編號</th>
            <th>唱片名稱</th>
            <th>唱片藝人</th>
            <th>分數</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach ($data_array as $data) {
        ?>
        <tr>
            <td><?php echo $data['disc_id']; ?></td>
            <td><?php echo addslashes($data['title']); ?></td>
            <td><?php echo addslashes($data['artist']); ?></td>
            <td><?php echo $data['score']; ?></td>
        </tr>
        <?php
        }
        ?>
    </tbody>
</table>
<div class="pull-right" style="width: 600px;">
    <svg id="disc-chart" style="height: 640px;"></svg>
</div>
<script>

    var data = [
        {
            key: "<?php echo $chart_title; ?>",
            color: "#ffffff",
            values: [
                <?php
                $first = true;
                foreach ($data_array as $data) {
                    if ($first) {
                        echo '{ label: "'.addslashes($data['title']).'", value: '.$data['score'].' }';
                        $first = false;
                    } else {
                        echo ",\n".'{ label: "'.addslashes($data['title']).'", value: '.$data['score'].' }';
                    }
                }
                ?>
            ]
        }
    ];

    nv.addGraph(function() {
        var chart = nv.models.multiBarHorizontalChart()
            .x(function(d) { return d.label })
            .y(function(d) { return d.value })
            .margin({top: 30, right: 20, bottom: 50, left: 250})
            .valueFormat(d3.format(',.0f'))
            .showValues(true)
            .tooltips(true)
            .showControls(false);

        chart.yAxis
            .tickFormat(d3.format(',.0f'));

        d3.select('#disc-chart')
            .datum(data)
            .call(chart);

        nv.utils.windowResize(chart.update);

        return chart;
    });

</script>