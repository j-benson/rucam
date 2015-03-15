<?
	// this is a hybrid controller/view file
	// to be amended in future versions of VF1

	if ($mode != "confirm_search") 
	{
?>	
	<table class="table1">
		<tr>
<?
			// Build the table headers.
			foreach (MyActiveRecord::Columns($class_value) as $class_attribute => $class_attr_value)
			{
				// If the column is the referred as columns check if it can be shown.
				if ($class_attribute == "referred_as" && hiddenReferredAs($class_value)) {
					continue;
				}

				if (in_array($class_attribute,$foreign_keys))
				{
					// $class_attribute is a foreign key.
					foreach ($foreign_keys as $fk_key => $fk_value)
					{
						if ($class_attribute == $fk_value)
						{
							echo "<th><!--class_attribute=".$class_attribute." fk_key=".$fk_key." -->".name_child_relationship($class_value,$fk_value);
						}
					}
				}
				else
				{
					// Is not a foreign key.
					echo "<th>".niceName($class_value, $class_attribute)."</th>";
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

					echo "<th>".joinName($here, $there)."</th>";
					//echo "<script>document.getElementById('div_right').style.height = '230px';document.getElementById('div_right').style.border = 'none';</script><div id=div3>";
					//echo "<p class=p1>manage the ".$jt_value." relationship by the following criterion: ";
					//include "view_displayjt.php";
					//echo "</div>";
				}
			}
?>
		</tr>
<?php 	

	// Put the data from the db into the table.
	$obj_class = MyActiveRecord::FindBySql($class_value, 'SELECT * FROM '.$class_value.' WHERE id > -1 ORDER BY id');
	//echo "<pre>"; var_dump($obj_class); echo "</pre>";

	foreach ($obj_class as $obj_key => $obj_value)
	{
		// UPDATE CARD STATUS //
		if ($class_value == T_CARDS) {
			validCard($obj_value);
		}


		echo "<tr>";
		foreach (MyActiveRecord::Columns($class_value) as $obj_attribute => $obj_attr_value)
		{
			// LEAVE AT TOP - Now create the table columns but not the referred_as column if defined as hidden.
			if ($obj_attribute == "referred_as" && hiddenReferredAs($class_value)) {
				continue;
			}

			if ($obj_attribute=="id")
			{
				// PRIMARY KEYS
				echo "<td class='idval'><a href=javascript:update_obj('".$current_file_name."','".$class_value."',".$obj_value->$obj_attribute.");>".$obj_value->$obj_attribute."</a></td>";
			}
			else if (strlen($obj_attribute)> 2 && !(strpos($obj_attribute,"_id")===false))
			{
				// FOREIGN KEYS
				$related_class = find_relatedclass($obj_attribute,$foreign_keys);
				//echo " related_class = ".$related_class;
				//echo " obj_attribute = ".$obj_attribute;

				// 'foreign key'. 'foreign key referred as field' 
				//echo "<td>".$obj_value->$obj_attribute.". ".$obj_value->find_parent($related_class,$obj_attribute)->referred_as . "</td>";			
				echo "<td>".$obj_value->find_parent($related_class,$obj_attribute)->referred_as . " [".$obj_value->$obj_attribute."]</td>";			
			}
			else
			{
				// DATA
				if(MyActiveRecord::GetType($class_value,$obj_attribute) == 'datetime' && $obj_value->$obj_attribute == D_DATE) {
					// Don't show datetimes that are 0000-00-00 00:00:00 
					echo "<td></td>";
				} else {
					echo "<td>".$obj_value->$obj_attribute."</td>";
				}
			}
		} // end foreach of result row

		//////
		
		foreach ($join_tables as $jt_key => $jt_value)
		{
			$pos = strpos($jt_value,$here);
			if($pos === false) {
							// string needle NOT found in haystack
			}
			else {		// string needle found in haystack	
				$there = str_replace("_","",$jt_value);
				$there = str_replace($here,"",$there);
				// $there = the other table this table ($here) links to 
				// ie if the join table is 'here_there' or 'there_here' when $here is 'here' $there will be 'there' 
				
				// $obj_value is the MyActiveRecord obj of the current class.
				echo "<td>";
				foreach ($obj_value->find_attached($there) as $_fakey => $_favalue)
				{
					echo $_favalue->referred_as."<br/>";
				}
				echo "</td>";
				
				//echo "<script>document.getElementById('div_right').style.height = '230px';document.getElementById('div_right').style.border = 'none';</script><div id=div3>";
				//echo "<p class=p1>manage the ".$jt_value." relationship by the following criterion: ";
				//include "view_displayjt.php";
				//echo "</div>";
			}
		} // end foreach of join tables
		
		///////
		echo "</tr>";
	} // end foreach of results collection
	
?>
	</table>
<?
	} // end $mode != "confirm_search"
	else //  if $mode is equal to "confirm_search"!!!
	{
	
?>
<table class="table1">
<tr>
<?
	
	$class_obj = $_REQUEST['class_obj'];
	
	$search_operator = $_REQUEST['search_operator'];
	
	// Build the table headers for the search results.
	foreach (MyActiveRecord::Columns($class_value) as $class_attribute => $class_attr_value)
	{
		// If the column is the referred as columns check if it can be shown.
		if ($class_attribute == "referred_as" && hiddenReferredAs($class_value)) {
			continue;
		}

		// Column header for foreign keys.
		if (in_array($class_attribute,$foreign_keys))
		{
			foreach ($foreign_keys as $fk_key => $fk_value)
			{
				if ($class_attribute == $fk_value)
				{
					echo "<th><!--class_attribute=".$class_attribute." fk_key=".$fk_key." -->".name_child_relationship($class_value,$fk_value)."</th>";
				}
			}
			//echo "<th>owned by";
		}
		else //Column header for other fields.
		{
			echo "<th>".niceName($class_value, $class_attribute)."</th>";
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
			
			echo "<th>".joinName($here, $there)."</th>";
			//echo "<script>document.getElementById('div_right').style.height = '230px';document.getElementById('div_right').style.border = 'none';</script><div id=div3>";
			//echo "<p class=p1>manage the ".$jt_value." relationship by the following criterion: ";
			//include "view_displayjt.php";
			//echo "</div>";
		}
	}
	
	//// SEARCH STUFF ////
	
	$strSQLsearch = 'Select * from '.strtolower($class_obj).' where id>=0 ';  // the search query has been initialised
	$strSQLor = 'Select * from '.strtolower($class_obj).' where id<0 ';
	$strSQLor_mod = 0;
	
	$pino = array();		// this is a local array, unneeded here: it should have been commented: but since it is not used, it doesn't cause too many troubles
	
	// by means of the following loop, the search query is developed (each loop adds a condition after the "where")
	
	foreach ($_REQUEST as $key_REQUEST => $value_REQUEST)
	{
		if (substr($key_REQUEST,0,6) == 'input_')
		{
			$local_attrib = substr($key_REQUEST,6);
			if ($value_REQUEST != "")
			{
				if ($search_operator == 'AND')
				{
				$strSQLsearch = $strSQLsearch." ".$search_operator." ".$local_attrib." = '".$value_REQUEST."' ";    // the search query gets incremented by "and columnX = 'valueX.'.. and columnY = 'valueY'
				}
				else
				{
				$strSQLor = $strSQLor." ".$search_operator." ".$local_attrib." = '".$value_REQUEST."' "; 
				$strSQLor_mod = 1;
				}
			}							// in mySQL, also integers can be "quoted"... This is a loop to create a very basic search query			
		}
	}
	
	if ($search_operator == 'OR')
	{
		if ($strSQLor_mod == 1)
		{
			$strSQLsearch = $strSQLor;
		}
		else
		{
			$strSQLsearch = $strSQLsearch;
		}
	}
	else
	{
		$strSQLsearch = $strSQLsearch;
	}	
	
	//here the search criteria include any further filter based on the related join_table(s) 
	$relation_class = '';
	$relation_name = $_REQUEST['jt_name'];
	$relation_class = $_REQUEST['jt_class'];
	
	if($relation_class != '')
	{
		$sqlSQLmod_rel = 0;
		
		if ($search_operator == 'OR')
		{
			$innerSelect = "Select ".$class_value."_id from ".$relation_name." where false ";
			foreach ($_REQUEST as $key_REQUEST => $value_REQUEST)
			{
				if (substr($key_REQUEST,0,9) == 'jt_input_')
				{
					$that_id = $value_REQUEST;
					$innerSelect = $innerSelect." or ".$relation_class."_id = ".$that_id;
				
					$sqlSQLmod_rel = 1;
				}
			}
		}
		else   //search_operator = 'AND'
		{
			$innerSelect = "Select ".$class_value."_id from ".$relation_name." where true ";
			$innerSelect1 = "Select ".$class_value."_id from ".$relation_name." where true ";      //da sistemare...
			foreach ($_REQUEST as $key_REQUEST => $value_REQUEST)
			{
				if ($sqlSQLmod_rel == 0)
				{
					if (substr($key_REQUEST,0,9) == 'jt_input_')
					{
						$that_id = $value_REQUEST;
						$innerSelect = $innerSelect." and ".$relation_class."_id = ".$that_id;
						$sqlSQLmod_rel = 1;
					}
				}
				if($sqlSQLmod_rel >= 1)
				{
					if (substr($key_REQUEST,0,9) == 'jt_input_')
					{
						$that_id = $value_REQUEST;
						$innerSelect = $innerSelect." and ".$class_value."_id in (".$innerSelect1." and ".$relation_class."_id = ".$that_id.") ";
						$sqlSQLmod_rel ++;
					}
				}
			}
		}
		
		if ($sqlSQLmod_rel == 0)
		{
			$strSQLsearch = $strSQLsearch;
		}
		else
		{
			if ($search_operator == 'OR')
			{
				$strSQLsearch = $strSQLor;
			}
			$strSQLsearch = $strSQLsearch." ".$search_operator." id in (".$innerSelect.")";
		}
	}
	////////// END SEARCH STUFF //////////
	
	//echo "<p>".$strSQLsearch;
	
	$obj_class = MyActiveRecord::FindBySql($class_value, $strSQLsearch);

	foreach ($obj_class as $obj_key => $obj_value)
	{
		// UPDATE CARD STATUS //
		if ($class_value == T_CARDS) {
			validCard($obj_value);
		}

		
		echo "<tr>";
		foreach (MyActiveRecord::Columns($class_value) as $obj_attribute => $obj_attr_value)
		{
			// Check for and ignore hidden referred_as columns
			if ($obj_attribute == "referred_as" && hiddenReferredAs($class_value)) {
				continue;
			}

			if ($obj_attribute=="id")
			{
				echo "<td class='idval'><a href=javascript:update_obj('".$current_file_name."','".$class_value."',".$obj_value->$obj_attribute.");>".$obj_value->$obj_attribute."</a></td>";
			}
			else if (strlen($obj_attribute)> 2 && !(strpos($obj_attribute,"_id")===false))
			{
				//$related_class = substr($obj_attribute, 0, -3);
				$related_class = find_relatedclass($obj_attribute,$foreign_keys);
				
				//echo "<td>".$obj_value->$obj_attribute.". ".$obj_value->find_parent($related_class,$obj_attribute)->referred_as."</td>";
				echo "<td>".$obj_value->find_parent($related_class,$obj_attribute)->referred_as . " [".$obj_value->$obj_attribute."]</td>";
			}
			else
			{
				// DATA //
				if(MyActiveRecord::GetType($class_value,$obj_attribute) == 'datetime' && $obj_value->$obj_attribute == D_DATE) {
					// Don't show datetimes that are 0000-00-00 00:00:00 
					echo "<td></td>";
				} else {
					echo "<td>".$obj_value->$obj_attribute."</td>";
				}
			}
		}
		
				//////
		
		/// For the Associated column
		foreach ($join_tables as $jt_key => $jt_value)
		{
			$pos = strpos($jt_value,$here);
			if($pos === false) {
							// string needle NOT found in haystack
			}
			else {		// string needle found in haystack
							
				$there = str_replace("_","",$jt_value);
				$there = str_replace($here,"",$there);
				
				echo "<td>";
				foreach ($obj_value->find_attached($there) as $_fakey => $_favalue)
				{
					echo $_favalue->referred_as . "<br/>";
				}
				echo "</td>";
				
				//echo "<script>document.getElementById('div_right').style.height = '230px';document.getElementById('div_right').style.border = 'none';</script><div id=div3>";
				//echo "<p class=p1>manage the ".$jt_value." relationship by the following criterion: ";
				//include "view_displayjt.php";
				//echo "</div>";
			}
			
		}
		echo "</tr>";
	} // End foreach of result collection
	
	
	//echo $strSQLsearch."<br>";  Check the SQl string has been properly formed
	
?>
</table>	
<?
}   //end else ($mode is equal to "confirm_search)" !!!
?>