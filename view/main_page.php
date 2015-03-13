<?
function redirectCreateWithErrorCheck($current_file_name, $here, $last_inserted_record, $errMessages) {
	echo "<script>this.location = '".$current_file_name."?here=".$here."&mode=create&class_obj=".$here;
	if (hasErrors($errMessages)) {
		echo "&err_messages=".serialize($errMessages);
	} else {
		echo "&post_create=".$last_inserted_record;
	}
	echo "';</script>";
}

function redirectUpdateWithErrorCheck($current_file_name, $here, $class_obj_id, $errMessages) {
	echo "<script>this.location = '".$current_file_name."?here=".$here."&mode=update&class_obj_id=".$class_obj_id;
	if (hasErrors($errMessages)) {
		echo "&err_messages=".serialize($errMessages);
	} else {
		echo "&post_update=".$class_obj_id;
	}
	echo "';</script>";
}


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
				redirectCreateWithErrorCheck($current_file_name, $here, $last_inserted_record, $errMessages);
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
				redirectUpdateWithErrorCheck($current_file_name, $here, $class_obj_id, $errMessages);
				
			}

			if ($mode == "update_function") 
			{
				include "controller/controller_update_functions.php";
				redirectUpdateWithErrorCheck($current_file_name, $here, $class_obj_id, $errMessages);
				echo "hey";
			}

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