<?

	$class_obj_id=$_REQUEST['class_obj_id'];
	
	$class_obj = MyActiveRecord::FindById($here, $class_obj_id);

	
	echo "<p class=p1>Update ".singularName($here, true)."</p>";
	
	/// POST ///
	if (isset($_REQUEST['post_update']))
	{
		post_update_message($_REQUEST['post_update'],$here);
	}
	if (isset($_REQUEST['post_error']))
	{
		post_update_error_message($_REQUEST['post_error'],$here);
	}
	////
	
	//echo "</p>";
	echo "<table class=table1><form id='update_".$here."' action=".$current_file_name."?here=".$here."&mode=confirm_update&class_obj=".$here."&class_obj_id=".$class_obj_id." method=post>";
	
	$w_columns = MyActiveRecord::Columns($class_obj);
	foreach($w_columns as $wcolumns_key => $wcolumns_value)
	{
		// LEAVE AT TOP - Check for and ignore hidden referred_as columns
		if ($wcolumns_key == "referred_as" && hiddenReferredAs($class_value)) {
			continue;
		}		

		// FIELD NAMES
		echo "<tr><td>".niceName($class_value, $wcolumns_key)."</td>";

		if ($wcolumns_key == "id")
		{ // PRIAMRY KEY
			echo "<td><input type=text name='input_".$wcolumns_key."' value='".$class_obj->$wcolumns_key."' readonly=true></td>";
		}
		else
		{
			if(MyActiveRecord::GetType($class_obj,$wcolumns_key) == 'date')
			{
				echo "<td><input type=text id='input_".$wcolumns_key."' name='input_".$wcolumns_key."' value='".$class_obj->$wcolumns_key."' onclick=\"displayDatePicker('input_".$wcolumns_key."',false,'ymd','-');\"></td>";
				echo "<td><input type=button value='Set Date' onclick=\"displayDatePicker('input_".$wcolumns_key."',false,'ymd','-');\" ></td>";
			}
			elseif (strlen($wcolumns_key)> 2 && !(strpos($wcolumns_key,"_id")===false))
			{ // FOREIGN KEYS
				
				//$related_class = substr($wcolumns_key, 0, -3);
				$related_class = find_relatedclass($wcolumns_key,$foreign_keys);

				// Starts a select tag for the foreign key table.
				echo "<td><select id='select_".$wcolumns_key."' name='input_".$wcolumns_key."'>";
				//echo "<option></option>"; // no need in the update mode for this
				
				foreach ($obj_class = MyActiveRecord::FindBySql($related_class, 'SELECT * FROM '.$related_class.' WHERE id > -1 ORDER BY referred_as') as $obj_attribute => $obj_attr_value)
				{
					// echo "<option>".$obj_attribute." - ".$obj_attr_value->referred_as;    // it works, but...
					
					echo "<option ";
					if ($obj_attr_value->id == $class_obj->$wcolumns_key)
					{
						echo "selected";
					}
					
					echo "  value='".$obj_attr_value->id."' >".$obj_attr_value->id." - ".$obj_attr_value->referred_as;
					
					//echo "(".$wcolumns_key.")";
					
					// Adds other related infomation about foreign key links in the dropdown box only appears on the auth page it seems
					// Turned it off as using hidden referred as to show this infomation and that duplicates the information
					// if (strlen($wcolumns_key)> 2 && !(strpos($wcolumns_key,"_id")===false))
					// {
					// 	//$related_superclass = substr($wcolumns_key, 0, -3);
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
					/////////////////////////

					echo "</option>";
				}
				echo "</select>";
			}
			else
			{ // DATA
				echo "<td><input type=text name='input_".$wcolumns_key."' value='".$class_obj->$wcolumns_key."'></td>";
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
			
			//echo "<script>document.getElementById('div_right').style.height = '230px';document.getElementById('div_right').style.border = 'none';</script><div id=div3>";
			//echo "<p class=p1>manage the ".$jt_value." relationship by the following criterion: ";
			include "view_displayjt.php";
			//echo "</div>";
		}
	}
	
	echo "<tr><td><td><input type=button value='Update ".singularName($here, true)."' onClick=javascript:confirm_update('update_".$here."')><td><input type=reset></table></form>";
	
?>