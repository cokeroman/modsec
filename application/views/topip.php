<table width='50%' class='table-style01'>
<tr>
     <th>Ataques</th>
     <th>IP</th>
</tr>
<?php
foreach ($gettopip as $line) {
	$xff = preg_replace("/^X-Forwarded-For: /", "", $line['xff']);
	echo "<tr class='clickAttack' onmouseover=\"this.style.fontWeight='bold'\" onmouseout=\"this.style.fontWeight='normal'\">";
	echo " <td>$line[ataques]</td>";
	echo " <td><a href='/stats/whois/$xff'>$xff</a></td>";
	echo "</tr>";
}
?>

</table>
