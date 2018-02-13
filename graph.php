<?php
error_reporting(0); // Disable all errors.
if(!isset($_GET['g']) || !is_file($_GET['g'].'.txt')) {
  echo "error";
  exit;
}
?>
<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta http-equiv="refresh" content="10">
</head>
<body>

    <svg id="chart" style="width:100%; height:900px;"></svg>
    <p>This graph plots the facebook activity of fb-user-<?=$_GET['g']?>.</p>

<style>
    .nvd3 g.nv-groups path.nv-line {
          stroke-width: 5px;
    }
</style>



<script src="https://cdnjs.cloudflare.com/ajax/libs/d3/3.5.2/d3.min.js" charset="utf-8"></script>

<link href="https://cdn.rawgit.com/defaultnamehere/zzzzz/70d407736092304ee247fbbacbe9f82bc0cba472/static/css/nv.d3.min.css" rel="stylesheet">
<script src="https://cdn.rawgit.com/defaultnamehere/zzzzz/70d407736092304ee247fbbacbe9f82bc0cba472/static/js/nv.d3.min.js"></script>

<script src="https://code.jquery.com/jquery-2.2.0.min.js"></script>

<script type="text/javascript">


$("body").ready(function(e) {

    var uid = $("#uid-input").val()
    var statuses = "online".split(" ");
    var legit_url = 'https://delta.phoenixpeca.xyz/dev/<?=$_GET['g'] ?>.txt'

    d3.csv(legit_url, function(error, data) {

        var ch = nv.addGraph(function() {

            var chart = nv.models.lineWithFocusChart()
            .width(1400)
            .height(800)
            .x(function(d){return d.x})
            .y(function(d){return d.y});

            graphData = [
                {
                    key: "online",
                    values: []
                }
            ];

            data.forEach(function(d) {
                // That's right I actually want to loop through the array indices take that ES7
                for (var index in statuses) {
                    graphData[index]["values"].push({
                        // Multiply by 1000 to get to millis okay then js.
                            x: new Date(Number(d["time"]) * 1000),
                            y: d[statuses[index]]
                        });
                }
            });

            chart.xAxis.tickFormat(function(d) {
                return d3.time.format('%-I:%M:%S %p')(new Date(d))
              });

            chart.x2Axis.tickFormat(function(d) {
                return d3.time.format('%x')(new Date(d));
              });


            //var statusTypes = "not_active active".split(" ");
            var statusTypes = "not_active active".split(" ");
            chart.yAxis
                .tickFormat(function(val) {
                    return statusTypes[val];
                });

            chart.y2Axis
              .tickFormat(d3.format(',.2f'));


            d3.select("#chart")
              .datum(graphData)
              .call(chart);

            nv.utils.windowResize(chart.update);

            return chart;
        });
    });
});


</script>
</body>

</html>
