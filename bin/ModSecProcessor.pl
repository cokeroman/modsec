#!/usr/bin/perl
# Este script (cron) es el encargado de recoger los logs del mod_security y meterlos en la db para eso el mod_sec debe tener las siguientes directivas de configuracion:
#		SecAuditLogParts BFH
#		SecAuditLogType Concurrent
#		SecAuditLogStorageDir /LOGS/mod_sec/logs/
#
#
#
# La base de datos soportada es mysql y debe crearse un usuario con permisos SELECT,INSERT,UPDATE y DELETE.
# El create table es:
#	CREATE TABLE `log` (
#	  `id` int(11) NOT NULL AUTO_INCREMENT,
#	  `file_id` varchar(45) NOT NULL,
#	  `host` varchar(45) DEFAULT NULL,
#	  `get` varchar(255) DEFAULT NULL,
# 	  `date` varchar(45) DEFAULT NULL,
#	  `cookie` varchar(255) DEFAULT NULL,
#	  `referer` varchar(255) DEFAULT NULL,
#	  `ua` varchar(255) DEFAULT NULL,
#	  `xff` varchar(45) DEFAULT NULL,
#	  `ae` varchar(45) DEFAULT NULL,
#	  `message` text,
#	  `timestamp` varchar(20) DEFAULT NULL,
#	  PRIMARY KEY (`id`),
#	  UNIQUE KEY `file_id_UNIQUE` (`file_id`),
#	  KEY `timestamp` (`timestamp`),
#	  KEY `host` (`host`)
#	) ENGINE=InnoDB
#
#
#

use File::Find;
use File::Copy;
use Time::Local;
use DBI;

# Directorios a utilizar
my $tmpdir = "/tmp";
my $basedir = "/LOGS/mod_sec";
my $logsdir = $basedir . "/logs";
my $processed = $basedir . "/processed";

($sec,$min,$hour,$mday,$mon,$year,$wday,$yday,$isdst) = localtime(time);
$year += 1900;

my $sqlfile = "$sqlin" . "/" . "$year" . "$mon" . "$mday" . "$hour" . "$min" . ".sql";


sub parse_file{
	my $file = $_;

	if (-f $File::Find::name) {

		# Sacamos la fecha y la hora del ataque por el nombre del fichero y lo pasamos a timestamp para meterlo en la db
		$attack_year = substr $file, 0, 4; 
		$attack_month = substr $file, 4, 2; 
		$attack_month = $attack_month - 1;
		$attack_day = substr $file, 6, 2; 
		$attack_hour = substr $file, 9, 2;
		# Le sumamos 1 para poner la hora peninsular 
		#$attack_hour = $attack_hour + 1;
		if ($attack_hour == '24') { $attack_hour = 0;}
		$attack_min = substr $file, 11, 2; 
		$attack_sec = substr $file, 13, 2; 
		
		my $timestamp = timelocal($attack_sec,$attack_min,$attack_hour,$attack_day,$attack_month,$attack_year);
		

		open(FILE, $File::Find::name);
		while ( $line = <FILE>) {
			# Recogemos los varoles que nos interesan del log
			# GET 
			$get = $1 if ($line =~ /(GET.*)HTTP/);
			# Date
			$date = $1 if ($line =~ /(Date.*)/);
			# Host
			$host = $1 if ($line =~ /(Host.*)/);
			# Cookie 
			$cookie = $1 if ($line =~ /(Cookie.*)/);
			# Referer 
			$referer = $1 if ($line =~ /(Referer.*)/);
			# User-Agent 
			$ua = $1 if ($line =~ /(User-Agent.*)/);
			# X-Forwarded-For 
			$xff = $1 if ($line =~ /(X-Forwarded-For.*)/);
			# Accept-Encoding 
			$ae = $1 if ($line =~ /(Accept-Encoding.*)/);
			# Message 
			$message = $1 if ($line =~ /(Message.*)/);
		}
		close(FILE);

		my $query = "INSERT INTO log (file_id, host, get, date, cookie, referer, ua, xff, ae, message, timestamp) VALUES (" . $dbh->quote($file) . "," . $dbh->quote($host) . "," . $dbh->quote($get) . "," . $dbh->quote($date) . "," . $dbh->quote($cookie) . "," . $dbh->quote($referer) . "," . $dbh->quote($ua) . "," . $dbh->quote($xff) . "," . $dbh->quote($ae) . "," . $dbh->quote($message) . ","  . $dbh->quote($timestamp) . ");";

		$dbh->do($query);

		# Movemos los ficheros procesados al directorio processed
		move("$File::Find::name", "$processed" . "/" . "$file");
	
	}



}


# Conectamos con la DB
$dsn = "DBI:mysql:database=mod_sec;host=vipdbsistemas";
$dbh = DBI->connect($dsn, 'modsec', 'xxxxxxx');

# Hacemos un find de ficheros y los empezamos a procesar
find(\&parse_file, $logsdir);

$dbh->disconnect();
