<html>
<head>
        <script type="text/javascript" src="/js/jquery.qtip-1.0.0-rc3.min.js"></script>
        <script src="/js/highcharts.js" type="text/javascript"></script>
        <script src="/js/highcharts-more.js" type="text/javascript"></script>


	<script>
$(function () {
    var chart;
    $(document).ready(function() {
        chart = new Highcharts.Chart({
            chart: {
                renderTo: 'container-graph',
                type: 'spline',
                height: 200,
                marginRight: 130,
                marginBottom: 25
            },
            title: {
                text: 'Attacks',
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
                name: 'ataques',
                data: [ 
                <?php
                {
                        foreach ($array_ataques as $dato) {
				$tstimefrom = strtotime($dato['minuto']);
                                $tstimeto = strtotime($dato['minuto']) + 60;
                                echo "{";
				echo "name: \"$dato[minuto]\"" . ",";
                                echo "y: $dato[ataque]" . ",";
				echo "url: \"http://" . $_SERVER['SERVER_NAME'] . "/stats/table/$tstimefrom/$tstimeto \"";
                                echo "}" . ",";
                        }

                }
                ?>
                ]
            }]
        });
    });


});



	</script>




        <script type="text/javascript">
        $( document ).ready( function( ) {
            $.fn.qtip.styles.tooltipDefault = {
                background  : '#132531',
                color       : '#FFFFFF',
                textAlign   : 'left',
                border      : {
                    width   : 2,
                    radius  : 4,
                    color   : '#C1CFDD'
                },
                width       : 820
            }

            // we are going to run through each element with the classRating class
            $( '.tooltip' ).each( function( ) {
                var file_id = $( this ).attr( 'file_id' );
                var timestamp = $( this ).attr( 'timestamp' );
                var host = $( this ).attr( 'host' );
                var get = $( this ).attr( 'get' );
                var xff = $( this ).attr( 'xff' );
                var ua = $( this ).attr( 'ua' );
                var ae = $( this ).attr( 'ae' );
                var referer = $( this ).attr( 'referer' );
                var cookie = $( this ).attr( 'cookie' );
                var dateh = $( this ).attr( 'dateh' );
                var message = $( this ).attr( 'message' ).replace(/"/g, '');

                // create the tooltip for the current element
                $( this ).qtip( {
                    content     : "File ID: " + file_id + "<br>" + xff + "<br>" + get + "<br>" + message,
                    position    : {
                        target  : 'mouse'
                    },
                    style       : 'tooltipDefault'
                } );
            } );
        } );

        </script>
</head>

<?php


$timefrom = $_SESSION['timefrom'];
$timeto = $_SESSION['timeto'];
//$uri = $_SESSION['uri'];
?>

<form method='POST' action="<?php echo "http://" . $_SERVER['SERVER_NAME'] . "/stats/table";?>">
	<table width='100%' align='center'>
		<tr>
			<td>Time From: <input type='text' name='timefrom' id='timefrom' value='<?php echo $timefrom; ?>' maxlength="25" size="15"></td>
			<td>Time To: <input type='text' name='timeto' id='timeto' value='<?php echo $timeto; ?>' maxlength="25" size="15"></td>
			<td>URI: <input type='text' name='uri' id='uri' value='' maxlength="255" size="15"></td>
			<td>IP: <input type='text' name='xff' id='xff' value='' maxlength="15" size="15"></td>
			<td>Site: 
				<select name='site' id='site'>
<?php
foreach ($sites as $s) { 
	if ($s == $_SESSION['site']) {
		echo "					  <option value='$s' selected>$s</option>";
	} else {
		echo "					  <option value='$s'>$s</option>";
	}
}
?>
				</select>

			</td>
			<td><input type='submit' name='search' id='search' value="Search"></td>
		</tr>
	</table>

</form>

<div id="container-graph"></div>

                <table class='table-style01'>
                    <tr>
                        <th>Time</th>
                        <th >Site</th>
                        <th width='70%'>URL</th>
                    </tr>
<?php
foreach ($getattacksdetail as $attacks) {
		$fecha = substr($attacks['file_id'], 0,15);
		$host = str_replace("Host:","",$attacks['host']);
		$xff = preg_replace("/^X-Forwarded-For: /","",$attacks['xff']);
		if (strlen($attacks['get']) <= 80) {
			$get = $attacks['get'];
		} else {
			$get = substr($attacks['get'], 0,80) . "...";
		}
		$message = preg_replace("/\'/", "",$attacks['message']);
		$cookie = preg_replace("/\'/", "",$attacks['cookie']);
                echo " <tr class='clickAttack' onmouseover=\"this.style.fontWeight='bold'\" onmouseout=\"this.style.fontWeight='normal'\">";
                echo " <td><a href='/stats/show/$attacks[id]'>$fecha</a></td>";
                echo " <td>$host</td>";
//                echo " <td>$xff</td>";
                echo " <td class='tooltip' file_id='$attacks[file_id]' timestamp='$attacks[timestamp]' host='$attacks[host]' get='$attacks[get]' dateh='$attacks[date]' cookie='$cookie' referer='$attacks[referer]' ua='$attacks[ua]' ae='$attacks[ae]' xff='$attacks[xff]' message='$message'>$get</td>";
                echo " </tr>";

}
?>
                </table>
</html>
