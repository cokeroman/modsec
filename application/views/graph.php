<html>
	<head>
		    <script src="/js/highcharts.js" type="text/javascript"></script>
                    <script src="/js/highcharts-more.js" type="text/javascript"></script>
<script>
<?php
	foreach ($sites as $site) {
?>

$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container-graph-<?php echo $site;?>',
                type: 'spline',
		height: 300,
                marginRight: 130,
                marginBottom: 25,
		events: {
                	load: requestData
            	}
            },
            title: {
                text: 'Attacks in <?php echo $site;?>',
                x: -20 //center
            },
            subtitle: {
                text: '',
                x: -20
            },
            xAxis: {
		type: 'datetime',
		labels: {
                	rotation: 0,
			enabled: false
            	}
            },
       	    plotOptions: {
            	series: {
	                marker: {
                                enabled: false
                	},
                	cursor: 'pointer',
                	point: {
                    	events: {
                        	click: function() {
                            	location.href = this.options.url;
                        	}
                    	}
                	}
            	}
            },
            yAxis: {
                title: {
                    text: 'Attacks/min'
                },
                plotLines: [{
                    value: 0,
                    width: 1,
                    color: '#808080'
                }]
            },
            tooltip: {
                formatter: function() {
                        return '<b>'+ this.series.name +'</b><br/>'+
			this.point.name +': '+ this.y +' Attacks';
                }
            },
            legend: {
                layout: 'vertical',
                align: 'right',
                verticalAlign: 'top',
                x: -10,
                y: 100,
                borderWidth: 0
            },
            colors: [
		'<?php echo sprintf('#%06X', mt_rand(0, 0xFFFFFF));?>'
	    ],
            series: [{
		name: '<?php echo $site;?>',
		data: [
		<?php
		{
			foreach (${'array_ataques_' . $site} as $k => $v) {
				$tstimefrom = strtotime($k);
				$tstimeto = strtotime($k) + 60;
				echo "{";
				echo "name: \"$k\"" . ",";
				echo "y: $v" . ",";
				echo "url: \"http://" . $_SERVER['SERVER_NAME'] . "/stats/table/$tstimefrom/$tstimeto/$site \"";
				echo "}" . ",";
			}
			
            	}
		?>
		]
	    }]
        });
    });


function requestData() {
	$.get("/stats/updategraph/<?php echo $site;?>", function (inc) {
		var dd = new Date();
		var YY = dd.getFullYear();
		var MM = dd.getMonth() + 1;
		var DD = dd.getDate();
		var hh = dd.getHours();
		var mm = dd.getMinutes();
		var ss = dd.getSeconds();
		var ts = dd.getTime()/1000;
		var tstimefrom = ts - 120;
		var tstimeto = ts - 60;

		chart.series[0].addPoint({name: YY + "/" + MM + "/" + DD + " " + hh + ":" + mm, y: parseInt(inc), url: "http://<?php echo $_SERVER['SERVER_NAME']?>/stats/table/" + tstimefrom + "/" + tstimeto + "/<?php echo $site;?>"},true, true);
        })
	setTimeout(requestData, 60000);
}
    
});

<?php
}
?>
</script>
	</head>
	<body>

		
	</body>
</html>
<?php
foreach ($sites as $site) {
?>
<div id="container-graph-<?php echo $site;?>"></div>
<br>
<?php
}
?>
