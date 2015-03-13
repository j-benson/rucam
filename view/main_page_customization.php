<?php
	if ($here == "") {
?>
<div id="div2" class=div_mainpage>
	<p class="p1">Welcome to the <? echo $application_title; ?> </p>
	<p class="cardsearch">
	<h2>
	<form method = "POST" action="<?php echo $current_file_name; ?>?mode=view_register">
	<?
	$chosen_fixture = (isset($_POST['fixture_selection']) ? $_POST['fixture_selection'] : -1);
	?>
	<select name='fixture_selection'>
	<?
	$fixtures = MyActiveRecord::FindAll(T_FIXTURES, null, "datetime");
	if ($fixtures === false)
	{
		echo "<option>No Fixtures</option>";
	} else
		foreach($fixtures as $fix)
		{
			echo "<option ".($chosen_fixture == -1 ? "": "selected = ".$chosen_fixture)."value = '".$fix->id."'>".$fix->referred_as."</option>";	
		}
	}
	echo $chosen_fixture;
		?>
	<td><input type='submit' value='View Register'></td>
	</select>
	</form>
	<form method="POST" action="<?php echo $current_file_name; ?>?mode=request_access">
	<tr><td>Insert Card ID:</td>
	<td><input type='text' name='card_id'></td>
	<td><input type='submit' value='Access'></td></tr>
	</form>
	</h2>
	</p>
	
    </div>

<p>
  <?php
	    
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
		echo '<div class="err_messages"><p>'.singularName(niceName($classino, $classino)).' Not Created</p>';
		echo showErrMessages($urlPostVar);
		echo "</div>";
	}

	function post_update_error_message($urlPostVar, $classino)
	{
		echo '<div class="err_messages"><p>'.singularName(niceName($classino, $classino)).' Not Updated</p>';
		echo showErrMessages($urlPostVar);
		echo "</div>";
	}

	function showErrMessages($urlPostVar) {
		$str = "";
		$messages = unserialize($urlPostVar);
		foreach ($messages as $m) {
			$str .= '<p class="p_errmessage">'. $m .'</p>';
		}
		return $str;
	}

?>
      
      
