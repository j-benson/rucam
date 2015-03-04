<?
	foreach ($classes as $class_key => $class_value)
	{
		if ($here == $class_value)
		{
	
?>

<?
			if (($mode != "confirm_update") && ($mode != "confirm_create")) 
			{
				include "controller/controller_retrieve.php";
			}
?>

</div> <!-- closes div2 from menu.php -->

<?
			if ($mode == "create")
			{
				echo "<div id='div_right'>";
				//echo "<script>document.getElementById('div2').style.width = '60%';</script>";
				echo "<script>display_div_right();</script>"; 
				include "view_create.php";
				echo "</div>";
				
			}

			if ($mode == "confirm_create")
			{
				// Saves the submitted data to the database.
				include "controller/controller_create.php";
				// echo "<script>alert('".$here." ".$_REQUEST['input_referred_as']." has been created');</script>";
				// Redirects the page to show last added record.

				if (hasErrors($errMessages)) {
					echo "<script>this.location = '".$current_file_name."?here=".$here."&mode=create&class_obj=".$here."&post_error=".serialize($errMessages)."';</script>";
					//showErrorMsg(".errorStr($errMessages).")</script>";
				} else {
					echo "<script>this.location = '".$current_file_name."?here=".$here."&mode=create&class_obj=".$here."&post_create=".$last_inserted_record."';</script>";
				}
			}

			if ($mode == "update")
			{
				echo "<div id='div_right'>";
				//echo "<script>document.getElementById('div2').style.width = '60%';</script>";
				echo "<script>display_div_right();</script>"; 
				
				include "view_update.php";
				echo "</div>";
			}

			if ($mode == "confirm_update")
			{
				
				include "controller/controller_update.php";
				
				if (hasErrors($errMessages)) {
					echo "<script>this.location = '".$current_file_name."?here=".$here."&mode=update&class_obj_id=".$class_obj_id."&post_error=".serialize($errMessages)."';</script>";
				} else {
					echo "<script>this.location = '".$current_file_name."?here=".$here."&mode=update&class_obj_id=".$class_obj_id."&post_update=".$class_obj_id."';</script>";
				}
				
			}

			// cheeky hack!
			if ($mode == "confirm_search")
			{
				$mode = "search";
			}

			if ($mode == "search")
			{
				echo "<div id='div_right'>";
				//echo "<script>document.getElementById('div2').style.width = '60%';</script>";
				echo "<script>display_div_right();</script>"; 
				include "view_retrieve.php";
				echo "</div>";
			}



/*

if ($mode == "search" || $mode == "create" || $mode == "update")
{
	//echo "<p>HGAJSHGJSHGDJSGH";
	foreach ($join_tables as $jt_key => $jt_value)
	{
		$pos = strpos($jt_value,$here);
		if($pos === false) {
						// string needle NOT found in haystack
		}
		else {		// string needle found in haystack
						
			
			$there = str_replace("_"," ",$jt_value);
			$there = str_replace($here,"",$there);
			
			echo "<script>document.getElementById('div_right').style.height = '230px';document.getElementById('div_right').style.border = 'none';</script><div id=div3>";
			//echo "<p class=p1>manage the ".$jt_value." relationship by the following criterion: ";
			include "view_displayjt.php";
			echo "</div>";
		}
	}
}

*/
?>

<?
		}		//end $here == "class_value
		
	}	// end foreach ($classes as $class_key => $class_value)
?>
</div>