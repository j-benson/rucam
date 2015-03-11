<?

	$class_obj=$_REQUEST['class_obj'];
	echo "<p class='p1'>Search ".niceName($class_value, $class_obj)."</p>";
	echo "<table class=table1><form action=".$current_file_name."?here=".$here."&mode=confirm_search&class_obj=".$class_obj." method=post>";
	
	// $w_columns contains the table columns and values for table name class_obj ie competitor whatever the value of here is.
	// accessable by $w_columns['tablecolumn'] = columnvalue;
	// $wcolumns_key is the db field name
	// $wcolumns_value is the value in the db
	$w_columns = MyActiveRecord::Columns($class_obj);
	foreach($w_columns as $wcolumns_key => $wcolumns_value)
	{
		// LEAVE AT TOP - Check for and ignore hidden referred_as columns
		if ($wcolumns_key == "referred_as" && hiddenReferredAs($class_value)) {
			continue;
		}
		
		echo "<tr><td>".niceName($class_value, $wcolumns_key)."</td>";

		if(MyActiveRecord::GetType($class_obj,$wcolumns_key) == 'date')
		{
			// For date data types show date picker button
			echo "<td><input type=text id='input_".$wcolumns_key."' name='input_".$wcolumns_key."' value='".$_REQUEST['input_'.$wcolumns_key]."' onclick=\"displayDatePicker('input_".$wcolumns_key."',false,'ymd','-');\"/></td>";
			echo "<td><input type=button value='Set Date' onclick=\"displayDatePicker('input_".$wcolumns_key."',false,'ymd','-');\"/></td>";
				
				//echo "<tr id='arow'><td>".$wcolumns_key."<td><input type=text id='input_".$wcolumns_key."' name='input_".$wcolumns_key."' value='' datepicker='true' datepicker_format='DD/MM/YYYY'>";
				//echo "<tr><td>".$wcolumns_key."<td><input type=text id='input_".$wcolumns_key."' name='input_".$wcolumns_key."' value='' datepicker='true' datepicker_format='DD/MM/YYYY'><td><div id='aaa'>&nbsp;</div><script>var b = new free_date_picker('b', 'aaa', 'input_".$wcolumns_key."', 1, true, 'en');</script>";
		}
		else
		{
			// If the column is a foreign key add dropdown box.
			if (strlen($wcolumns_key) > 2 && !(strpos($wcolumns_key,"_id")===false))
			{ // FOREIGN KEYS
				$related_class = find_relatedclass($wcolumns_key,$foreign_keys);
				echo "<td><select id='select_".$wcolumns_key."' name='input_".$wcolumns_key."' >";
				echo "<option></option>"; //TODO: could put no cards etc when empty
				
				foreach ($obj_class = MyActiveRecord::FindBySql($related_class, 'SELECT * FROM '.$related_class.' WHERE id > -1 ORDER BY referred_as') as $obj_attribute => $obj_attr_value)
				{
					echo "<option value='".$obj_attr_value->id."' ";
					if ($_REQUEST['input_'.$wcolumns_key] == $obj_attr_value->id)
					{
						echo " selected ";
					}
					echo ">".$obj_attr_value->id." - ".$obj_attr_value->referred_as;
					
				// Turn off this feature
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
					// 			// $related_superobj = $obj_attr_value->find_parent($related_supersuperclass)->referred_as;
								
					// 			$related_superobj = $obj_attr_value->find_parent($related_supersuperclass,$super_obj_attribute)->referred_as;
								
					// 			//echo " (".$related_superobj.")";
					// 			echo " (".$related_superobj.")";
					// 		}
					// 	}
					// }
				///////
					echo "</option>";
				}
				echo "</select></td>";
			} 
			else 
			{ // DATA

				echo "<td><input type=text id='input_".$wcolumns_key."' name='input_".$wcolumns_key."' value='".$_REQUEST['input_'.$wcolumns_key]."'/></td>";
			} 
			echo "</td>";
		}
		echo "</tr>";
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
	
	
	echo "<tr><td>Search condition<td><select name='search_operator'><option value='AND'";
	if ($_REQUEST['search_operator'] == 'AND')
	{
		echo " selected ";
	}
	echo ">AND</option><option value='OR'";
	if ($_REQUEST['search_operator'] == 'OR')
	{
		echo " selected ";
	}
	echo ">OR</option></select>";
	echo "<td><input type=submit></table></form>";
	
?>