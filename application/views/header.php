<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
    <!-- This website template was downloaded from http://www.nuviotemplates.com - visit us for more templates -->
    <meta http-equiv="content-type" content="text/html; charset=utf-8" />
    <meta http-equiv="content-language" content="en" />

    
    <link rel="stylesheet" media="screen,projection" type="text/css" href="/css/main.css" />
    <!--[if lte IE 6]><link rel="stylesheet" type="text/css" href="css/main-msie.css" /><![endif]-->
    <link rel="stylesheet" media="screen,projection" type="text/css" href="/css/scheme.css" />
    <link rel="stylesheet" media="print" type="text/css" href="/css/print.css" />

    <title>ModSec Console</title>

    <script src="http://code.jquery.com/jquery-1.8.0.min.js" type="text/javascript"></script>
    <script src="/js/highcharts.js" type="text/javascript"></script>
    <script>

/**
	var auto_refresh = setInterval(
	function(){
	   $('#perex').load('/stats/graph').fadeIn("slow");
	   $('#content-in').load('/stats/table').fadeIn("slow");
	}, 10000);
**/
        
	function init() {

		$("table tr.clickAttack:nth-child(odd)").addClass("color_impar");
                $("table tr.clickAttack:nth-child(even)").addClass("color_par");


        	$('#content-in').load('/stats/graph').fadeIn("slow");
        	/**$('#content-in').load('/stats/table').fadeIn("slow");**/
        	/**$('#content-in').load('/stats/velocimetro/norefresh').fadeIn("slow");**/

                function getData(div,check) {
                        $("#" + div).load(check);
                }
	}
    </script>
    <script>$(document).ready(init);</script>

</head>
<body>

<div id="main">

    <!-- Header -->
    <div id="header">

        <!-- Your logo -->
        <h1 id="logo"><a href="/">ModSec <span>Con</span>sole</a></h1>
        <hr class="noscreen" />

        <!-- Your slogan -->
        <div id="slogan">ServoTIC</div>
        <hr class="noscreen" />

        <!-- Hidden navigation -->
        <p class="noscreen noprint"><em>Quick links: <a href="#content">content</a>, <a href="#nav">navigation</a>.</em></p>
        <hr class="noscreen" />

    </div> <!-- /header -->
