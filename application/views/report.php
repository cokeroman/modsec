<html>
<head>
</head>


<h3>Top 10 ataques por url<h3>
<table width='100%' border='1'>
<tr>
     <th align='left'>Ataques</th>
     <th align='left'>Site</th>
     <th align='left'>URL</th>
</tr>
<?php
foreach ($urls as $line) {
        $site = preg_replace("/^Host: /", "", $line['host']);
        $url = $line['get'];
        echo "<tr class='clickAttack' onmouseover=\"this.style.fontWeight='bold'\" onmouseout=\"this.style.fontWeight='normal'\">";
        echo " <td>$line[ataques]</td>";
        echo " <td>$site</td>";
        echo " <td class='tooltip' host='$line[host]' get='$line[get]'>$url</td>";
        echo "</tr>";
}
?>

</table>

<h3>Top 10 ataques por url<h3>

<table width='100%' border='1'>
<tr>
     <th align='left' >Ataques</th>
     <th align='left'>IP</th>
</tr>
<?php
foreach ($ips as $line) {
        $xff = preg_replace("/^X-Forwarded-For: /", "", $line['xff']);
        echo "<tr class='clickAttack' onmouseover=\"this.style.fontWeight='bold'\" onmouseout=\"this.style.fontWeight='normal'\">";
        echo " <td>$line[ataques]</td>";
        echo " <td><a href='/stats/whois/$xff'>$xff</a></td>";
        echo "</tr>";
}
?>

</table>


</html>
