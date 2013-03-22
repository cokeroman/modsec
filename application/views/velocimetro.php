<html>
	<head>
		    <script src="http://code.jquery.com/jquery-latest.min.js" type="text/javascript"></script>
		    <script src="/js/highcharts.js" type="text/javascript"></script>
		    <script src="/js/highcharts-more.js" type="text/javascript"></script>
	<script>
	
$(function () {
	
    var chart = new Highcharts.Chart({
	
	    chart: {
	        renderTo: 'container-velocimetro',
	        type: 'gauge',
	        plotBackgroundColor: null,
	        plotBackgroundImage: null,
	        plotBorderWidth: 0,
	        plotShadow: false
	    },
	    
	    title: {
	        text: 'Total Attacks'
	    },
	    
	    pane: {
	        startAngle: -150,
	        endAngle: 150,
	        background: [{
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#FFF'],
	                    [1, '#333']
	                ]
	            },
	            borderWidth: 0,
	            outerRadius: '109%'
	        }, {
	            backgroundColor: {
	                linearGradient: { x1: 0, y1: 0, x2: 0, y2: 1 },
	                stops: [
	                    [0, '#333'],
	                    [1, '#FFF']
	                ]
	            },
	            borderWidth: 1,
	            outerRadius: '107%'
	        }, {
	            // default background
	        }, {
	            backgroundColor: '#DDD',
	            borderWidth: 0,
	            outerRadius: '105%',
	            innerRadius: '103%'
	        }]
	    },
	       
	    // the value axis
	    yAxis: {
	        min: 0,
	        max: 500,
	        
	        minorTickInterval: 'auto',
	        minorTickWidth: 1,
	        minorTickLength: 10,
	        minorTickPosition: 'inside',
	        minorTickColor: '#666',
	
	        tickPixelInterval: 30,
	        tickWidth: 2,
	        tickPosition: 'inside',
	        tickLength: 10,
	        tickColor: '#666',
	        labels: {
	            step: 2,
	            rotation: 'auto'
	        },
	        title: {
	            text: 'Attacks/min'
	        },
	        plotBands: [{
	            from: 0,
	            to: 100,
	            color: '#55BF3B' // green
	        }, {
	            from: 100,
	            to: 250,
	            color: '#DDDF0D' // yellow
	        }, {
	            from: 250,
	            to: 500,
	            color: '#DF5353' // red
	        }]        
	    },
	
	    series: [{
	        name: 'Attacks',
	        data: [<?php echo $getattackspermin; ?>],
	        tooltip: {
	            valueSuffix: ' Attacks/min'
	        }
	    }]
	
	}, 
	// Add some life
	function (chart) {
	    setInterval(function () {
	        var point = chart.series[0].points[0];
	            $.get("/stats/velocimetro/refresh", function (inc) {
	        	point.update(parseInt(inc));
			})
	        
	    }, 3000);
	});
});


	</script>
	</head>
	<body>
	</body>
</html>
<div id="container-velocimetro"></div>
