<?
	if ($here == "")
		{
?>
<div id="div2" class=div_mainpage>
	<p class="p1">Welcome to the <? echo $application_title; ?></p>
	<div class=div_image>
		<img src="./include/images/airport.jpg"/> 
		<span class="caption">From: <a href="http://flightxblog.altervista.org/?p=174">FlightX © 2010</a></span>
	</div>
	<div class=div_image>
		<img src="./include/images/vf1_erd.jpeg"/> 
		<span class="caption">ERD developed using <a href="http://dia-installer.de/">DIA Diagram Editor</a></span>
	</div>
</div>
<?
		}
?>

<?
	function post_update_message($pino,$classino)
	{
		echo "<p class=p_message>[".date('H:i:s')."] ".$classino." record (id = ".$pino.") has been updated!</p>";
	}
	
	function post_create_message($pino,$classino)
	{
		echo "<p class=p_message>[".date('H:i:s')."] ".$classino." new record (id = ".$pino.") has been created!</p>";
	}

?>