<table width='100%' class='table-style01'>

<?php
foreach ($getattackbyid as $attack) {
		$date = preg_replace("/^Date:/","", $attack['date']);
		$host = preg_replace("/^Host:/","", $attack['host']);
		$get = $attack['get'];
		$referer = preg_replace("/^Referer:/","", $attack['referer']);
		$cookie = preg_replace("/^Cookie:/","", $attack['cookie']);
		$xff = preg_replace("/^X-Forwarded-For: /","", $attack['xff']);
		$ae = preg_replace("/^Accept-Encoding:/","", $attack['ae']);
		$ua = preg_replace("/^User-Agent:/","", $attack['ua']);
		$message = preg_replace("/^Message:/","", $attack['message']);


		echo "<tr class='color_par'>";
		echo "	<td>File ID:</td>";	
		echo "	<td>$attack[file_id]</td>";	
		echo "</tr>";
		echo "<tr class='color_par'>";
		echo "	<td>date</td>";	
		echo "	<td>$date</td>";	
		echo "</tr>";
		echo "<tr class='color_par'>";
		echo "	<td>Host</td>";	
		echo "	<td>$host</td>";	
		echo "</tr>";
		echo "<tr class='color_par'>";
		echo "	<td>URI:</td>";	
		echo "	<td>$get</td>";	
		echo "</tr>";
		echo "<tr class='color_par'>";
		echo "	<td>Referer:</td>";	
		echo "	<td>$referer</td>";	
		echo "</tr>";
		echo "<tr class='color_par'>";
		echo "	<td>Cookie:</td>";	
		echo "	<td>$cookie</td>";	
		echo "</tr>";
		echo "<tr class='color_par'>";
		echo "	<td>X-Forwarded-for:</td>";	
		echo "	<td><a href='/stats/whois/$xff'>$xff</a></td>";	
		echo "</tr>";
		echo "<tr class='color_par'>";
		echo "	<td>Accept Encoding:</td>";	
		echo "	<td>$ae</td>";	
		echo "</tr>";
		echo "<tr class='color_par'>";
		echo "	<td>User Agent:</td>";	
		echo "	<td>$ua</td>";	
		echo "</tr>";
		echo "<tr class='color_par'>";
		echo "	<td>Message:</td>";	
		echo "	<td>$message</td>";	
		echo "</tr>";
}
?>

</table>
