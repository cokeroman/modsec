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
                var host = $( this ).attr( 'host' );
                var get = $( this ).attr( 'get' );

                // create the tooltip for the current element
                $( this ).qtip( {
                    content     : host + "<br>" + get,
                    position    : {
                        target  : 'mouse'
                    },
                    style       : 'tooltipDefault'
                } );
            } );
        } );

        </script>

</head>

<table width='50%' class='table-style01'>
<tr>
     <th>Ataques</th>
     <th>Site</th>
     <th>URL</th>
</tr>
<?php
foreach ($gettopurls as $line) {
	$site = preg_replace("/^Host: /", "", $line['host']);
	$url = substr($line['get'], 0,80) . "...";
	echo "<tr class='clickAttack' onmouseover=\"this.style.fontWeight='bold'\" onmouseout=\"this.style.fontWeight='normal'\">";
	echo " <td>$line[ataques]</td>";
	echo " <td>$site</td>";
	echo " <td class='tooltip' host='$line[host]' get='$line[get]'>$url</td>";
	echo "</tr>";
}
?>

</table>
</html>
