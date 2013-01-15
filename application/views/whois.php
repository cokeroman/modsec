<table width='100%' class='table-style01'>
<?php
	$response = json_decode($whois,true);
	echo "<tr>";
	echo "<td><b>Domain Name</b></td>";
	echo "<td>$response[DomainName]</td>";
	echo "</tr>";
	echo "<tr><td colspan='2'><b>Abuse Contact</b></td></tr>";
	foreach ($response['RegistryData']['AbuseContact'] as $k => $v) {
		echo "<tr>";
		echo "<td>$k</td>";
		echo "<td>$v</td>";
		echo "</tr>";
	}
	echo "<tr><td colspan='2'><b>Administrative Contact</b></td></tr>";
        foreach ($response['RegistryData']['AdministrativeContact'] as $k => $v) {
                echo "<tr>";
                echo "<td>$k</td>";
                echo "<td>$v</td>";
                echo "</tr>";
        }
	echo "<tr><td colspan='2'><b>Technical Contact</b></td></tr>";
        foreach ($response['RegistryData']['TechnicalContact'] as $k => $v) {
                echo "<tr>";
                echo "<td>$k</td>";
                echo "<td>$v</td>";
                echo "</tr>";
        }
	echo "<tr><td colspan='2'><b>Registrant</b></td></tr>";
        foreach ($response['RegistryData']['Registrant'] as $k => $v) {
                echo "<tr>";
                echo "<td>$k</td>";
                echo "<td>$v</td>";
                echo "</tr>";
        }
?>
</table>
