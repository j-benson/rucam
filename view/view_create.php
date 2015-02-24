<?
// create view placed inside <div id='div_right'>

	$class_obj=$_REQUEST['class_obj'];
	
	echo "<p class='p1'>Create New ".ucfirst($class_obj)."</p>";
	if (isset($_REQUEST['post_create']))
	{
		post_create_message($_REQUEST['post_create'],$class_obj);
	}
	
	
	echo "<table class=table1><form id=form_create action=".$current_file_name."?here=".$here."&mode=confirm_create&class_obj=".$class_obj." method=post>";
	

	// Columns returns an array with the field name as the index and for each field the value is an array containing
	// information about the field.
	// Columns for competitors looks like this.
// array(5) {
//   ["id"]=>
//   array(6) {
//     ["Field"]=>
//     string(2) "id"
//     ["Type"]=>
//     string(7) "int(11)"
//     ["Null"]=>
//     string(2) "NO"
//     ["Key"]=>
//     string(3) "PRI"
//     ["Default"]=>
//     NULL
//     ["Extra"]=>
//     string(14) "auto_increment"
//   }
//   ["titles_id"]=>
//   array(6) {
//     ["Field"]=>
//     string(9) "titles_id"
//     ["Type"]=>
//     string(7) "int(11)"
//     ["Null"]=>
//     string(2) "NO"
//     ["Key"]=>
//     string(0) ""
//     ["Default"]=>
//     NULL
//     ["Extra"]=>
//     string(0) ""
//   }
//   ["name"]=>
//   array(6) {
//     ["Field"]=>
//     string(4) "name"
//     ["Type"]=>
//     string(12) "varchar(150)"
//     ["Null"]=>
//     string(2) "NO"
//     ["Key"]=>
//     string(0) ""
//     ["Default"]=>
//     NULL
//     ["Extra"]=>
//     string(0) ""
//   }
//   ["role"]=>
//   array(6) {
//     ["Field"]=>
//     string(4) "role"
//     ["Type"]=>
//     string(12) "varchar(100)"
//     ["Null"]=>
//     string(2) "NO"
//     ["Key"]=>
//     string(0) ""
//     ["Default"]=>
//     NULL
//     ["Extra"]=>
//     string(0) ""
//   }
//   ["teams_id"]=>
//   array(6) {
//     ["Field"]=>
//     string(8) "teams_id"
//     ["Type"]=>
//     string(7) "int(11)"
//     ["Null"]=>
//     string(2) "NO"
//     ["Key"]=>
//     string(0) ""
//     ["Default"]=>
//     NULL
//     ["Extra"]=>
//     string(0) ""
//   }
// }

	$w_columns = MyActiveRecord::Columns($class_obj);
	//echo "<pre>"; var_dump($w_columns); echo "</pre>";

	foreach($w_columns as $wcolumns_key => $wcolumns_value)
	{
		//echo "<p>" . $wcolumns_key . "</p>";

		if ($wcolumns_key != "id")
		{
			if(MyActiveRecord::GetType($class_obj,$wcolumns_key) == 'date')
			{
				echo "<tr><td>".niceName($here, $wcolumns_key)."</td><td><input type=text id='input_".$wcolumns_key."' name='input_".$wcolumns_key."' value=''></td>";
				echo "<td><input type=button value='Set Date' onclick=displayDatePicker('input_".$wcolumns_key."',false,'ymd','-'); /></td>";
			}
			else
			{
			
				echo "<tr><td>".niceName($here, $wcolumns_key)."</td><td><input type=text id='input_".$wcolumns_key."' name='input_".$wcolumns_key."' value='' /></td>";
				
				// Where the field name is greater than 2 ie not 'id' and the field name contains '_id' so 
				// this statement if true for foreign key fields.
				if (strlen($wcolumns_key)> 2 && !(strpos($wcolumns_key,"_id")===false))
				{
					//echo "</p>" . $wcolumns_key . " is a foreign key.</p>";

					// Find the class name that the foreign field name ($wcolumns_key) relates to ie titles_id field relates to the titles table/class
					$related_class = find_relatedclass($wcolumns_key,$foreign_keys);
					//echo "<p>Related class: $related_class</p>";

					// Starts a select tag for the forgeign key table.
					echo "<td><select id='select_".$wcolumns_key."' onChange=javascript:change_obj('".$wcolumns_key."') ><option></option>";
					// Adds options to the select tag containing the values from the foreign key.

					// ERROR: Unknown column referred as
					foreach ($obj_class = MyActiveRecord::FindBySql($related_class, 'SELECT * FROM '.$related_class.' WHERE id > -1 ORDER BY referred_as') as $obj_attribute => $obj_attr_value)
					{					
						echo "<option value='".$obj_attr_value->id."'>".$obj_attr_value->referred_as;
						
					//echo "(".$wcolumns_key.")";
						
						// True when $wcolumns_key has more than length 2 and contains '_id' so for foreign keys
						if (strlen($wcolumns_key)> 2 && !(strpos($wcolumns_key,"_id")===false))
						{
							$related_superclass = find_relatedclass($wcolumns_key,$foreign_keys);
							foreach ($super_obj = MyActiveRecord::Columns($related_superclass) as $super_obj_attribute => $super_obj_value)
							{
								if (strlen($super_obj_attribute)> 2 && !(strpos($super_obj_attribute,"_id")===false))
								{
									//$related_supersuperclass = substr($super_obj_attribute, 0, -3);
									$related_supersuperclass = find_relatedclass($super_obj_attribute,$foreign_keys);
									
									//$related_superobj = $obj_attr_value->find_parent($related_supersuperclass)->referred_as;
									$related_superobj = $obj_attr_value->find_parent($related_supersuperclass,$super_obj_attribute)->referred_as;
									//echo "<td>".$obj_value->$obj_attribute.". ".$obj_value->find_parent($related_class,$obj_attribute)->referred_as;
									
									
									echo " (".$related_superobj.")";
								}
							}
						}
					}
					echo "</select ></td>";
				}
			
			}
		}

		//echo "<p>end loop</p>";
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
	
	
	echo "<tr><td><td><input type=button value='Create New ".ucfirst($here)."' onClick=javascript:confirm_create('form_create');><td><input type=reset >";
	echo "</table></form>";
	
?>