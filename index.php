<?
	include "model/model.php";
	// VF1 version 1.6
?>
<html>
<link rel="shortcut icon" href="include/images/Logo RUCAM - Small - 2.png" />
<link id="style_link" rel=StyleSheet href="include/vf1-1024x768.css" type="text/css" media=all />
<link id="style_link" rel=StyleSheet href="include/calendar.css" type="text/css" media=all />
<script src="include/vf1.js"></script>
<script type="text/javascript" src="include/calendar.js"></script>
<title><? echo $application_title; ?></title>
<body>
<div id="main_div">
<h2><img src="./include/images/Logo RUCAM - Small - 2.png"><? echo $application_title; ?>
<!--RUGBY UNION LOGOS-->
<img src="./include/images/RUG_aru_logo.png" height=35 align="right">
<img src="./include/images/RUG_fru_logo.png" height=35 align="right">
<img src="./include/images/RUG_rfu_logo.png" height=35 align="right">
<img src="./include/images/RUG_uru_logo.png" height=35 align="right">
<img src="./include/images/RUG_wru_logo.png" height=35 align="right"> </h2>
<?php
	function ReturnName()
	{
		//echo $current_file_name."<BR>";
		$cc = __FILE__;
		$cc_rev = strrev($cc);
		//echo "cc_rev = ".$cc_rev."<BR>";
		$c1 = "\\";
		$cc1 = strpos($cc_rev, $c1);
		//echo "cc1 = ".$cc1."<BR>";
		$cc2 = substr($cc_rev,0,$cc1);
		//echo "cc2 = ".$cc2."<BR>";
		$cc3 = strrev($cc2);
		return $cc3;
	}

	//echo ReturnName()."<BR>";
	$current_file_name = ReturnName();

	
	include "controller/controller.php";
	//include "model/model.php";
	
	// Takes $pino which is the column name in a db table sees if it is in the foreign keys array.
	// returns the class name $pino is a forgien key of.
	function find_relatedclass($pino,$gino)
	{
		if(in_array($pino,$gino))
		{
			$prepino = substr($pino, 0, -3);
			$pos = strpos($prepino, "_");
			if (!($pos === false)) 
			{
				$prepino = substr($prepino, $pos+1);
			}
			return $prepino;
		}
	}
	
	
	
	//print_r($classes);
?>








</div>

</body>
</html>