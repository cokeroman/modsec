<html>
<head>

        <script src="/js/highcharts.js" type="text/javascript"></script>
        <script src="/js/highcharts-more.js" type="text/javascript"></script>

<script type="text/javascript">

(function($){ // encapsulate jQuery

$(function () {
    var chart;
    $(document).ready(function() {
    	
    	// Radialize the colors
		Highcharts.getOptions().colors = Highcharts.map(Highcharts.getOptions().colors, function(color) {
		    return {
		        radialGradient: { cx: 0.5, cy: 0.3, r: 0.7 },
		        stops: [
		            [0, color],
		            [1, Highcharts.Color(color).brighten(-0.3).get('rgb')] // darken
		        ]
		    };
		});
		
		// Build the chart
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container-graph',
                plotBackgroundColor: null,
                plotBorderWidth: null,
                plotShadow: false
            },
            title: {
                text: 'Ataques por site'
            },
            tooltip: {
        	    pointFormat: '{point.name}: <b>{point.y}</b>',
            	percentageDecimals: 1
            },
            plotOptions: {
                pie: {
                    allowPointSelect: true,
                    cursor: 'pointer',
                    dataLabels: {
                        enabled: true,
                        color: '#000000',
                        connectorColor: '#000000',
                        formatter: function() {
                            return '<b>'+ this.point.name +'</b>: '+ this.point.y;
                        }
                    }
                }
            },
            series: [{
                type: 'pie',
                name: 'Ataques',
                data: [
<?php
foreach ($gettopsites as $line) {
        $site = preg_replace("/^Host: /", "", $line['host']);
	echo "{";
	echo "name: \"$site\"" . ",";	
	echo "y: $line[ataques]" . ",";
	echo "sliced: false";
	echo "},";
}
?>
                ]
            }]
        });
    });
    
});
})(jQuery);
</script>


</head>
<table width='50%' class='table-style01'>
	<tr>
		<td><div id="container-graph"></div></td>
	</tr>
</table>

<table width='50%' class='table-style01'>
<tr>
     <th>Ataques</th>
     <th>Sites</th>
</tr>
<?php
foreach ($gettopsites as $line) {
	$site = preg_replace("/^Host: /", "", $line['host']);
	echo "<tr class='clickAttack' onmouseover=\"this.style.fontWeight='bold'\" onmouseout=\"this.style.fontWeight='normal'\">";
	echo " <td>$line[ataques]</td>";
	echo " <td>$site</td>";
	echo "</tr>";
}
?>
</table>
</td>
</html>
