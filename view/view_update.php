<?

	$class_obj_id=$_REQUEST['class_obj_id'];
	
	$class_obj = MyActiveRecord::FindById($here, $class_obj_id);

	
	echo "<p class=p1>Update ".singularName($here, true)."</p>";
	
	/// POST ///
	if (isset($_REQUEST['post_update']))
	{
		post_update_message($_REQUEST['post_update'],$here);
	}
	if (isset($_REQUEST['err_messages']))
	{
		post_update_error_message($_REQUEST['err_messages'],$here);
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
	
	echo "<tr><td></td><td><input type='button' value='Update ".singularName($here, true)."' onClick=\"javascript:confirm_update('update_".$here."')\" /></td><td><input type=reset></td></table></form>";
	

	
	if ($class_value == T_TEAMS) {
		// Competitors in the team
		$competitors = MyActiveRecord::FindById(T_TEAMS, $class_obj_id)->find_children(T_COMPETITORS);
		
		/// WHOLE TEAM ISSUE CARDS ///
		echo "<table class='table1'>";
		echo "<form method='post' action='index.php?here=".$class_value."&mode=update_function&function=team_issue_cards&class_obj_id=".$class_obj_id."' >";
		echo "<tr><th colspan='3'>Team Member Cards</th></tr>";
		
		echo "<tr><td>Valid From</td>";
		echo "<td><input type='text' id='validfrom' name='validfrom' value='2015-03-11' onclick=\"displayDatePicker('validfrom',false,'ymd','-');\" /></td>";
		echo "<td><input type='button' value='Set Date' onclick=\"displayDatePicker('validfrom',false,'ymd','-');\" /></td></tr>";

		echo "<tr><td>Valid Until</td>";
		echo "<td><input type='text' id='validuntil' name='validuntil' value='2015-03-18' onclick=\"displayDatePicker('validuntil',false,'ymd','-');\" /></td>";
		echo "<td><input type='button' value='Set Date' onclick=\"displayDatePicker('validuntil',false,'ymd','-');\" /></td></tr>";

		echo "<tr><td></td><td><input type='submit' value='Issue Cards' /></td>";
		echo "<td><input type='button' class='redText' value='Expire All Cards' onClick=\"javascript:confirm_expire_all_cards('team_expire_cards')\" /></td></tr>";

		echo "</form>";
		echo "</table>";

		echo "<form id='team_expire_cards' method='post' action='index.php?here=".$class_value."&mode=update_function&function=team_expire_cards&class_obj_id=".$class_obj_id."' ></form>";

		/// WHOLE TEAM AUTHORISATION ///
		$numCompetitors = count($competitors);
		$cardIds = array();
		foreach ($competitors as $c) {
			// find first most recent card
			$card = MyActiveRecord::FindFirst(T_CARDS, T_COMPETITORS."_id=".$c->id, "validfrom DESC");
			// if card found add its id to array
			if ($card !== false && validCard($card)) {
				array_push($cardIds, $card->id);
			}
		}

		$fixturesObj = MyActiveRecord::FindAll(T_FIXTURES, null, "datetime");

		echo "<table class='table1'>";
		echo "<form method='post' action='index.php?here=".$class_value."&mode=update_function&function=team_link_cards&class_obj_id=".$class_obj_id."' >";
		echo "<tr><th colspan='2'>Team Fixture Authorisation</th></tr>";
		foreach ($fixturesObj as $fix) {
			$numCards = 0;
			// Find all the records in the auth join table for this fix that link to one of the competitors ids
			$auths = null;
			if (count($cardIds) == 0) {
				$auths = false;
			} else {
				$auths = MyActiveRecord::FindAll(T_CARDS_FIXTURES, T_FIXTURES."_id=".$fix->id." AND ".T_CARDS."_id IN (".implode(",", $cardIds).")");
			}
			
			// make bold if this fixture contains the team.
			$bold = $fix->home_teams_id == $class_obj_id || $fix->away_teams_id == $class_obj_id;
			echo "<tr><td>". ($bold?"<strong>":"") . $fix->referred_as;
			echo "<br/>" . "(Competitors Currently Authorised: " . ($auths === false ? "0" : count($auths)) . "/". $numCompetitors . ")" . ($bold?"</strong>":""); 
			echo "</td>";
			echo "<td><input name='fixtures[]' type='checkbox' value='". $fix->id ."' /></td></tr>";
			
		}
		echo "<tr><td colspan='2'><input type='submit' value='Add Authorisation'/></td></tr>";
		echo "</form>";
		echo "</table>";
		echo "<div style='font-size:12px; font-weight:bold; width:100%; text-align:center;'>Fixtures that this team are participating in have been highlighted bold.</div>";
	}
?>