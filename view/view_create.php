<?
// create view placed inside <div id='div_right'>

	$class_obj=$_REQUEST['class_obj'];
	
	echo "<p class='p1'>Create New ".singularName($class_obj, true)."</p>";

	/// POST ///
	if (isset($_REQUEST['post_create']))
	{
		post_create_message($_REQUEST['post_create'],$class_obj);
	}
	if (isset($_REQUEST['err_messages']))
	{
		post_create_error_message($_REQUEST['err_messages'],$class_obj);
	}
	////
	
	echo "<table class=table1><form id=form_create action=".$current_file_name."?here=".$here."&mode=confirm_create&class_obj=".$class_obj." method=post>";
	

	// Columns returns an array with the field name as the index and for each field the value is an array containing
	// information about the field.
	$w_columns = MyActiveRecord::Columns($class_obj);
	//echo "<pre>"; var_dump($w_columns); echo "</pre>";

	// Foreach of the columns in the table for the selected class
	foreach($w_columns as $wcolumns_key => $wcolumns_value)
	{
		//echo "<p>" . $wcolumns_key . "</p>";

		// LEAVE AT TOP - Check for and ignore hidden referred_as columns
		if ($wcolumns_key == "referred_as" && hiddenReferredAs($class_value)) {
			continue;
		}

		if ($wcolumns_key != "id")
		{
			if(MyActiveRecord::GetType($class_obj,$wcolumns_key) == 'date')
			{
				echo "<tr><td>".niceName($here, $wcolumns_key)."</td><td><input type=text id='input_".$wcolumns_key."' name='input_".$wcolumns_key."' value='' onclick=\"displayDatePicker('input_".$wcolumns_key."',false,'ymd','-');\"></td>";
				echo "<td><input type=button value='Set Date' onclick=\"displayDatePicker('input_".$wcolumns_key."',false,'ymd','-');\" /></td>";
			}
			else
			{
				// FIELD NAMES
				echo "<tr><td>".niceName($class_value, $wcolumns_key)."</td>";
				
				// FOREIGN KEY LINKS - creates dropdown boxes for fk referred_as column
				if (strlen($wcolumns_key)> 2 && !(strpos($wcolumns_key,"_id")===false))
				{
					// DEFAULT FOREIGN KEY
					$default = defaultForeignKey($mode, $class_value, $wcolumns_key);

					// Find the class name that the foreign field name ($wcolumns_key) relates to ie titles_id field relates to the titles table/class
					$related_class = find_relatedclass($wcolumns_key,$foreign_keys);
					//echo "<p>Related class: $related_class</p>";

					// Select will be disabled and will not post so add hidden input that will post.
					if ($default != null && $default["disable"]) {
						echo "<input type='hidden' name='input_". $wcolumns_key ."' value='". $default["id"] ."'/>";
					}
					// Starts a select tag for the foreign key table.
					echo "<td><select id='select_".$wcolumns_key."' name='input_".$wcolumns_key."'".($default != null && $default["disable"] ? " disabled" : "").">";
					echo "<option></option>"; //TODO: in option could put no cards etc
					
					// Adds options to the select tag containing the values from the foreign key's referred_as field.
					foreach ($obj_class = MyActiveRecord::FindBySql($related_class, 'SELECT * FROM '.$related_class.' WHERE id > -1 ORDER BY referred_as') as $obj_attribute => $obj_attr_value)
					{					
						echo "<option value='".$obj_attr_value->id."'". ($default != null && $default["id"] == $obj_attr_value->id ? " selected" : "").">".$obj_attr_value->referred_as." [".$obj_attr_value->id."]";
						
					//echo "(".$wcolumns_key.")";
						
					// Adds other related infomation about foreign key links in the dropdown box only appears on the auth page it seems
					// Turned it off as using hidden referred_as to have more control over what information is shown to the user.
						// if (strlen($wcolumns_key)> 2 && !(strpos($wcolumns_key,"_id")===false))
						// {
						// 	$related_superclass = find_relatedclass($wcolumns_key,$foreign_keys);
						// 	foreach ($super_obj = MyActiveRecord::Columns($related_superclass) as $super_obj_attribute => $super_obj_value)
						// 	{
						// 		if (strlen($super_obj_attribute)> 2 && !(strpos($super_obj_attribute,"_id")===false))
						// 		{
						// 			//$related_supersuperclass = substr($super_obj_attribute, 0, -3);
						// 			$related_supersuperclass = find_relatedclass($super_obj_attribute,$foreign_keys);
									
						// 			//$related_superobj = $obj_attr_value->find_parent($related_supersuperclass)->referred_as;
						// 			$related_superobj = $obj_attr_value->find_parent($related_supersuperclass,$super_obj_attribute)->referred_as;
						// 			//echo "<td>".$obj_value->$obj_attribute.". ".$obj_value->find_parent($related_class,$obj_attribute)->referred_as;
									
									
						// 			echo " (".$related_superobj.")";
						// 		}
						// 	}
						// }
						/////////////////////
						echo "</option>";
					}
					echo "</select></td>";
				}
				else // TEXT DATA
				{
					echo "<td><input type='text' id='input_".$wcolumns_key."' name='input_".$wcolumns_key."' value='' /></td>";
				}
			
			}
		}
	}
	
	foreach ($join_tables as $jt_key => $jt_value)
	{
		$pos = strpos($jt_value,$here);
		if($pos === false) {
						// string needle NOT found in haystack
		}
		else {		// string needle found in haystack
						
			$there = str_replace("_","",$jt_value);
			$there = str_replace($here,"",$there);

			include "view_displayjt.php";
		}
	}
	

	echo "<tr><td></td><td><input type='button' value='Create New ".singularName($here, true)."' onClick=\"javascript:confirm_create('form_create');\" /></td>";
	echo "<td><input type='reset'/></td></tr>";
	echo "</table></form>";
	
?>