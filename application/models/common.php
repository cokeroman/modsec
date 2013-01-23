<?php

Class Common extends CI_Model
{
	
	function getattackbyid($id)
	{
                $this->load->database();
                $site = $this->db->escape_str($id);

		$sql = "SELECT * FROM log WHERE id = '$id';";

                if ($query = $this->db->query($sql)) {
                        return $query->result_array();
                } else {
                        return FALSE;
                }
		
	}

	function whois($ip)
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, "http://adam.kahtava.com/services/whois.json?query=$ip");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
		curl_setopt($ch, CURLOPT_HEADER, 0);
		curl_setopt($ch, CURLOPT_TIMEOUT, 10);
		$output = curl_exec($ch);
		curl_close($ch);

		return $output;
	}

	function gettopip()
	{
                $this->load->database();

                $sql = "select count(id) as ataques, xff from log WHERE  FROM_UNIXTIME(TIMESTAMP) >= (NOW() - INTERVAL 1 HOUR) group by xff order by ataques desc limit 0,10;";

                if ($query = $this->db->query($sql)) {
                        return $query->result_array();
                } else {
                        return FALSE;
                }
	}

        function gettopurls()
        {
                $this->load->database();

                $sql = "select count(id) as ataques, host ,get from log WHERE  FROM_UNIXTIME(TIMESTAMP) >= (NOW() - INTERVAL 1 HOUR) group by get order by ataques desc limit 0,10;";

                if ($query = $this->db->query($sql)) {
                        return $query->result_array();
                } else {
                        return FALSE;
                }
        }


	function ataquestotales($site) {
                $this->load->database();
                $site = $this->db->escape_str($site);

		if ($site == 'all') {
			$sql = "select id from log WHERE FROM_UNIXTIME(TIMESTAMP) >= (NOW() - INTERVAL 24 HOUR)";
		} else {
			$sql = "select id from log WHERE FROM_UNIXTIME(TIMESTAMP) >= (NOW() - INTERVAL 24 HOUR) AND host like '%$site%'";
		}

                if ($query = $this->db->query($sql)) {
                        return $query->num_rows();

                } else {
                        return FALSE;
                }

	}

        function reportips($site)
        {
                $this->load->database();

		if ($site == 'all') {
                	$sql = "select count(id) as ataques, xff from log WHERE  FROM_UNIXTIME(TIMESTAMP) >= (NOW() - INTERVAL 24 HOUR) group by xff order by ataques desc limit 0,10;";
		} else {
                	$sql = "select count(id) as ataques, xff from log WHERE  FROM_UNIXTIME(TIMESTAMP) >= (NOW() - INTERVAL 24 HOUR) AND host like '%$site%' group by xff order by ataques desc limit 0,10;";
		}

                if ($query = $this->db->query($sql)) {
                        return $query->result_array();
                } else {
                        return FALSE;
                }
        }

        function reporturls($site)
        {
                $this->load->database();
		
		if ($site == 'all') {
                	$sql = "select count(id) as ataques, host ,get from log WHERE  FROM_UNIXTIME(TIMESTAMP) >= (NOW() - INTERVAL 24 HOUR) group by get order by ataques desc limit 0,10;";
		} else {
                	$sql = "select count(id) as ataques, host ,get from log WHERE  FROM_UNIXTIME(TIMESTAMP) >= (NOW() - INTERVAL 24 HOUR) AND host like '%$site%' group by get order by ataques desc limit 0,10;";
		}
                if ($query = $this->db->query($sql)) {
                        return $query->result_array();
                } else {
                        return FALSE;
                }
        }

        function gettopsites()
        {
                $this->load->database();

                $sql = "select count(id) as ataques, host from log WHERE  FROM_UNIXTIME(TIMESTAMP) >= (NOW() - INTERVAL 1 HOUR) group by host order by ataques desc limit 0,10;";

                if ($query = $this->db->query($sql)) {
                        return $query->result_array();
                } else {
                        return FALSE;
                }
        }

	function getattackspermin() 
	{
		$this->load->database();

		# Calculamos el timestamp hace 2min y hace 1min y lo utilizamos para hacer la query
		# Le sumamos 3600 para hacer GMT +1 
		$time2minago = time() - 120; // 3600 - 120
                $time1minago = time() - 60; // 3600 - 60

		$sql = "SELECT * from log where timestamp > $time2minago and timestamp < $time1minago";	

		if ($query = $this->db->query($sql)) {
			return $query->num_rows();
			#return rand(12,100);

		} else {
			return FALSE;	
		}

	}
	
	// Numero de ataques por minuto por host	
	function getattackspeminpersite($site, $sites)
	{
		$this->load->database();
		$site = $this->db->escape_str($site);

                # Calculamos el timestamp hace 2min y hace 1min y lo utilizamos para hacer la query
                # Le sumamos 3600 para hacer GMT +1 
                $time2minago = time() - 120; // 3600 - 120
                $time1minago = time(); // 3600 - 60


		if ($site != 'otros') {
                	$sql = "SELECT * from log where host like '%$site%' AND timestamp >= $time2minago and timestamp <= $time1minago";
		} else {
			$sql = "SELECT * from log where";
			foreach ($sites as $s) {
                                if ($s == 'otros') {continue;}
				$sql .= " host not like '%$s%' AND";
			}
			$sql .= " timestamp >= $time2minago and timestamp <= $time1minago";
		}
                if ($query = $this->db->query($sql)) {
                        return $query->num_rows();
			#return json_encode(array(date('H:i'),$query->num_rows()));
                        #return rand(12,100);

                } else {
                        return FALSE;
                }
	}


	function getattacksdetail($timefrom, $timeto, $site, $sites, $uri, $xff) {
		$this->load->database();
                $timefrom = $this->db->escape_str($timefrom);
                $timeto = $this->db->escape_str($timeto);
                $site = $this->db->escape_str($site);
                $uri = $this->db->escape_str($uri);
                $xff = $this->db->escape_str($xff);
	
		
		if ($site != 'otros') {
			$sql = "SELECT *  FROM log WHERE timestamp >= '$timefrom' and timestamp <= '$timeto'";
                        if ($site) {
                                $sql .= " AND host LIKE '%$site%'";
                        }
			if ($uri) {
				$sql .= " AND get LIKE '%$uri%'";
			}
			if ($xff) {
				$sql .= " AND xff like '%$xff%'";
			}
			$sql .= " ORDER BY timestamp DESC";



		} else{
			$sql = "SELECT * FROM log WHERE timestamp >= '$timefrom' and timestamp <= '$timeto' AND";
			// Si el ultimo elemento del array es otros lo sacamos del array
			if (end($sites) == 'otros') {
				array_pop($sites);
			}
			foreach ($sites as $s) {
                                if ($s == 'otros') {
					continue;
				}

				// Si es el ultmimo elemento del array le quitamos el AND.
				if (end($sites) == "$s") {
					$sql .= " host not like '%$s%'";
				} else {
					$sql .= "  host not like '%$s%' AND";
				}
			}
                        if ($uri) {
                               	$sql .= " AND get LIKE '%$uri%'";
                       	}
                       	if ($xff) {
                               	$sql .= " AND xff like '%$xff%'";
                       	}				
			$sql .= " ORDER BY timestamp DESC";
		}

		if ($query = $this->db->query($sql)) {
                        return $query->result_array();
                } else {
                        return FALSE;
                }
		

	}

        function getattackspertime($tstimefrom, $tstimeto, $site, $sites, $uri, $xff)
        {
                $this->load->database();
                $tstimefrom = $this->db->escape_str($tstimefrom); // Numero de segundos que queremos consultar hacia atras
                $tstimeto = $this->db->escape_str($tstimeto);
                $site = $this->db->escape_str($site);
                $uri = $this->db->escape_str($uri);
                $xff = $this->db->escape_str($xff);


		if ($site != 'otros') {
	                $sql = "SELECT
        	                COUNT(id) AS ataque, DATE_FORMAT(FROM_UNIXTIME(TIMESTAMP), '%Y/%m/%d %H:%i') AS minuto
               	         	FROM log      
                	        WHERE timestamp >= $tstimefrom AND timestamp <= $tstimeto"; 
                        	if ($site) {
                                	$sql .= " AND host LIKE '%$site%'";
                        	}
                        	if ($uri) {
                                	$sql .= " AND get LIKE '%$uri%'";
                       		}
                        	if ($xff) {
                                	$sql .= " AND xff like '%$xff%'";
                        	}				

                       		$sql .= " GROUP BY DATE_FORMAT(FROM_UNIXTIME(TIMESTAMP), '%Y-%m-%d %H:%i')"; 
                       		$sql .= " ORDER BY DATE_FORMAT(FROM_UNIXTIME(TIMESTAMP), '%Y-%m-%d %H:%i') ASC;";
		} else {
                	$sql = "SELECT
                        	COUNT(id) AS ataque, DATE_FORMAT(FROM_UNIXTIME(TIMESTAMP), '%Y/%m/%d %H:%i') AS minuto
                        	FROM log      
                        	WHERE timestamp >= $tstimefrom AND timestamp <= $tstimeto AND"; 

                        if (end($sites) == 'otros') {
                                array_pop($sites);
                        }
                        foreach ($sites as $s) {
                                if ($s == 'otros') {
                                        continue;
                                }

                                // Si es el ultmimo elemento del array le quitamos el AND.
                                if (end($sites) == "$s") {
                                        $sql .= " host not like '%$s%'";
                                } else {
                                        $sql .= "  host not like '%$s%' AND";
                                }
                        }
                        if ($uri) {
                                $sql .= " AND get LIKE '%$uri%'";
                        }
                        if ($xff) {
                                $sql .= " AND xff like '%$xff%'";
                        }
				
                        $sql .= " GROUP BY DATE_FORMAT(FROM_UNIXTIME(TIMESTAMP), '%Y-%m-%d %H:%i')"; 
                        $sql .= " ORDER BY DATE_FORMAT(FROM_UNIXTIME(TIMESTAMP), '%Y-%m-%d %H:%i') ASC;";
		}


                if ($query = $this->db->query($sql)) {
                        return $query->result_array();
                } else {
                        return FALSE;
                }

        }




	// Devuelve un array con todos los datos de un periodo dado para pintar la grafica de ataques por site
	function getattackspertimepersite($horas, $site, $sites) 
	{
                $this->load->database();
                $horas = $this->db->escape_str($horas); // Numero de segundos que queremos consultar hacia atras
                $site = $this->db->escape_str($site);	



		if ($site != 'otros'){
			$sql = "SELECT
                               COUNT(id) AS ataque, DATE_FORMAT(FROM_UNIXTIME(TIMESTAMP), '%Y/%m/%d %H:%i') AS minuto
                        FROM log      
                        WHERE                                 host like '%$site%' AND FROM_UNIXTIME(TIMESTAMP) <= NOW() AND FROM_UNIXTIME(TIMESTAMP) >= (NOW() - INTERVAL $horas HOUR) 
                        GROUP BY DATE_FORMAT(FROM_UNIXTIME(TIMESTAMP), '%Y-%m-%d %H:%i') 
                        ORDER BY DATE_FORMAT(FROM_UNIXTIME(TIMESTAMP), '%Y-%m-%d %H:%i') ASC;";
		} else {
			$sql = "SELECT COUNT(id) AS ataque, DATE_FORMAT(FROM_UNIXTIME(TIMESTAMP), '%Y/%m/%d %H:%i') AS minuto FROM log WHERE";
			foreach ($sites as $s) {
				if ($s == 'otros') {continue;}
				$sql .= " host not like '%$s%' AND";
			}
			$sql .= " FROM_UNIXTIME(TIMESTAMP) <= NOW() AND FROM_UNIXTIME(TIMESTAMP) >= (NOW() - INTERVAL $horas HOUR) GROUP BY DATE_FORMAT(FROM_UNIXTIME(TIMESTAMP), '%Y-%m-%d %H:%i') ORDER BY DATE_FORMAT(FROM_UNIXTIME(TIMESTAMP), '%Y-%m-%d %H:%i') ASC;";
		}


		if ($query = $this->db->query($sql)) {
                        return $query->result_array();
                } else {
                        return FALSE;
                }

	}

}
