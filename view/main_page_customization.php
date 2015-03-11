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

	function post_create_error_message($urlPostVar, $classino)
	{
		echo '<div class="post_error"><p>'.singularName(niceName($classino, $classino)).' Not Created</p>';
		echo showMessagesList($urlPostVar);
		echo "</div>";
	}

	function post_update_error_message($urlPostVar, $classino)
	{
		echo '<div class="post_error"><p>'.singularName(niceName($classino, $classino)).' Not Updated</p>';
		echo showMessagesList($urlPostVar);
		echo "</div>";
	}

	function showMessagesList($urlPostVar) {
		$str = "";
		$messages = unserialize($urlPostVar);
		foreach ($messages as $m) {
			$str .= '<p class="p_errmessage">'. $m .'</p>';
		}
		return $str;
	}

?>
      
      
