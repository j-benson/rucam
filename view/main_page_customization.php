<?
	if ($here == "") {
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

	function post_create_error_message($urlPostMsg, $classino)
	{
		echo '<div class="post_error"><p>'.singularName(niceName($classino, $classino)).' was not created.</p><ul>';
		$messages = unserialize($urlPostMsg);
		foreach ($messages as $m) {
			echo '<li>'. $m .'</li>';
		}
		echo "</ul></div>";
	}

?>
      
      
