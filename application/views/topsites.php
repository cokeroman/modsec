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
