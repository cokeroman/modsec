<html>
<head>
        <script type="text/javascript" src="/js/jquery.qtip-1.0.0-rc3.min.js"></script>

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
                    content     : "File ID: " + file_id + "<br>" + get + "<br>" + message,
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
			<td>Time From: <input type='text' name='timefrom' id='timefrom' value='<?php echo $timefrom; ?>' maxlength="25" size="20"></td>
			<td>Time To: <input type='text' name='timeto' id='timeto' value='<?php echo $timeto; ?>' maxlength="25" size="20"></td>
			<td>URI: <input type='text' name='uri' id='uri' value='' maxlength="255" size="15"></td>
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


                <table class='table-style01'>
                    <tr>
                        <th width='20%'>Time</th>
                        <th>Site</th>
                        <th>URL</th>
                    </tr>
<?php
foreach ($getattacksdetail as $attacks) {
		$fecha = substr($attacks['file_id'], 0,15);
		$host = str_replace("Host:","",$attacks['host']);
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
                echo " <td class='tooltip' file_id='$attacks[file_id]' timestamp='$attacks[timestamp]' host='$attacks[host]' get='$attacks[get]' dateh='$attacks[date]' cookie='$cookie' referer='$attacks[referer]' ua='$attacks[ua]' ae='$attacks[ae]' xff='$attacks[xff]' message='$message'>$get</td>";
                echo " </tr>";

}
?>
                </table>
</html>
