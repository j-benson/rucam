<?
	if ($here == "")
		{
?>
<div id="div2" class=div_mainpage>
	<p class="p1">Welcome to the <? echo $application_title; ?> </p>
	<div id="div5" class=div_mainpage>
    <img src="./include/images/Logo RUCAM.png">
    </div>

<p>
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
      
      