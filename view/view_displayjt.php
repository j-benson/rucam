<?	
	echo "<tr><th colspan='2'>".joinName($here, $there)."</th><th></th></tr>";

	if ($mode == 'create')
	{
		

		$i = 0;
		foreach ($obj_class = MyActiveRecord::FindBySql($there, 'SELECT * FROM '.$there.' WHERE id > -1 ORDER BY referred_as') as $obj_attribute => $obj_attr_value)
		{
			if ($there == T_CARDS) {
				// When showing cards to create links only show valid cards.
				validCard($obj_attr_value); //updates card status if vailuntil date has past.
				if ($obj_attr_value->cardstatus_id != getCardStatusId(CS_VALID)) {
					continue;
				}
			}

			echo "<tr><td colspan='2'>".$obj_attr_value->referred_as."</td>";
			echo "<td><input type=checkbox id='jt_input_".$there."_".$i."' name='jt_input_".$there."_".$i."' value='".$obj_attr_value->id."'/></td>";
			$i++;
			
		}
		echo "<input type=hidden name='jt_name' value='".$jt_value."'/>"
		    ."<input type=hidden name='jt_class' value='".$there."'/>";

	    if ($i == 0) {
	    	echo "<tr><td colspan='2'>No ".ucfirst($there)."</td>";
	    }
	
	}
	
	if ($mode == 'update')
   	{
   		if ($there == T_FIXTURES) {
			$competitor = MyActiveRecord::FindById(T_COMPETITORS, $class_obj_id);
		}

		$i = 0;
		foreach ($obj_class = MyActiveRecord::FindBySql($there, 'SELECT * FROM '.$there.' WHERE id > -1 ORDER BY referred_as') as $obj_attribute => $obj_attr_value)
		{
			/// INDIVIDUAL TABLE LOGIC ///
			$bold = false;
			if ($there == T_CARDS) {
				// When listing cards to authorise, only show valid cards.
				validCard($obj_attr_value); // updates card status if vailuntil date has past.
				if ($obj_attr_value->cardstatus_id != getCardStatusId(CS_VALID)) {
					continue;
				}
			}
			if ($there == T_FIXTURES) {
				if ($competitor->teams_id == $obj_attr_value->home_teams_id || $competitor->teams_id == $obj_attr_value->away_teams_id) {
					$bold= true;
				}
			}
			///
			
			echo "<tr><td colspan='2'>".($bold ? "<strong>" : "").$obj_attr_value->referred_as.($bold ? "</strong>" : "")."</td>";
			// echo "<tr><td colspan='2'>".$obj_attr_value->referred_as."</td>";
			echo "<td><input type=checkbox id='jt_input_".$there."_".$i."' name='jt_input_".$there."_".$i."' value='".$obj_attr_value->id."'";
			foreach ($class_obj->find_linked($there) as $pp_key => $pp_value)
			{
			//echo " xxx: ".$obj_attr_value->find_attached($there)->id;
				// echo " xxx: ".$pp_value->id;
				if ($pp_value->id == $obj_attr_value->id)
				{
					echo " checked='true' ";
				}
			}
			echo "/></tr>";
			
			$i++;
			
		}
		//echo "</table>";
		echo "<input type=hidden name='jt_name' value='".$jt_value."'/>"
		    ."<input type=hidden name='jt_class' value='".$there."'/>";
		    
	    if ($i == 0) {
	    	echo "<tr><td colspan='2'>No ".ucfirst($there)."</td>";
	    }
	}
	
	
	if($mode == 'search')
	{
		$i = 0;
		foreach ($obj_class = MyActiveRecord::FindBySql($there, 'SELECT * FROM '.$there.' WHERE id > -1 ORDER BY referred_as') as $obj_attribute => $obj_attr_value)
		{
			// echo "<option>".$obj_attribute." - ".$obj_attr_value->referred_as;    // it works, but...
			
			echo "<tr><td colspan='2'>".$obj_attr_value->referred_as."</td>";
			echo "<td><input type=checkbox id='jt_input_".$there."_".$i."' name='jt_input_".$there."_".$i."' value='".$obj_attr_value->id."'";
			
			if ($_REQUEST['jt_input_'.$there.'_'.$i] == $obj_attr_value->id)
			{
				echo " checked ";
			}
			
			echo "/></td>";
			$i++;
			
		}
		echo "<input type=hidden name='jt_name' value='".$jt_value."'/>"
		    ."<input type=hidden name='jt_class' value='".$there."'/>";

	    if ($i == 0) {
	    	echo "<tr><td colspan='2'>No ".ucfirst($there)."</td>";
	    }
	}

?>