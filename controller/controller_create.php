<?
 $class_obj=$_REQUEST['class_obj'];
	
	// $pino holds the data sent by the user form, which is later added to the database.
	$pino = array();	// this is a local array used to store retrieved attributes of selected objects
	
	foreach ($_REQUEST as $key_REQUEST => $value_REQUEST)
	{
		if (substr($key_REQUEST,0,6) == 'input_')
		{
			if ($key_REQUEST != "input_id")
			{
				$pino = $pino + array(substr($key_REQUEST,6) => $value_REQUEST);
			}
		}
	}
	$this_obj = MyActiveRecord::Create($class_obj, $pino);

	// Set this to true to prevent the controller inserting into the database.
	$stopSave = false;

	// -- Logic for individual classes creation
	if ($class_obj == T_FIXTURES) {
		// Check for clashing fixtures, prevent saving these.
		if ($this_obj->home_teams_id == "") {
			$errMessages["home_teams_id"] = "The Home Team must be filled in.";
		}
		if ($this_obj->away_teams_id == "") {
			$errMessages["away_teams_id"] = "The Away Team must be filled in.";
		}
		if ($this_obj->venues_id == "") {
			$errMessages["venues_id"] = "The Venue must be filled in.";
		}
		if ($this_obj->datetime == "") {
			$errMessages["datetime"] = "The Datetime must be filled in.";
		}
		if (hasErrors($errMessages)) { $stopSave = true; }

		if (!$stopSave) {
			setCardReferredAs($this_obj);
		}
	}
	if ($class_obj == T_TEAMS) {
		// Check for clashing team names, prevent saving these.
		if ($this_obj->referred_as == "") {
			$errMessages["referred_as"] = "The Nation must be filled in.";
		}
		if ($this_obj->controlledby == "") {
			$errMessages["controlledby"] = "The Controlled By must be filled in.";
		}
		if ($this_obj->acronym == "") {
			$errMessages["acronym"] = "The Acronym must be filled in.";
		}
		if ($this_obj->nickname == "") {
			$errMessages["nickname"] = "The Nickname must be filled in.";
		}
		if (hasErrors($errMessages)) { $stopSave = true; }

		if (!$stopSave && existingRecord(T_TEAMS, $this_obj)) {
			$stopSave = true;
			$errMessages["existing"] = "The team entered already exists.";
		}
	}
	if ($class_obj == T_COMPETITORS) {
		// when inserting new competitor, check for fixtures and add authorisation for those fixtures.
		// update their card and authorisations
		if ($this_obj->titles_id == "") {
			$errMessages["titles_id"] = "The Title must be filled in.";
		}
		if ($this_obj->referred_as == "") {
			$errMessages["referred_as"] = "The Name must be filled in.";
		}
		if ($this_obj->role == "") {
			$errMessages["role"] = "The Role must be filled in.";
		}
		if ($this_obj->teams_id == "") {
			$errMessages["teams_id"] = "The Team must be filled in.";
		}
		if (hasErrors($errMessages)) { $stopSave = true; }

		if (!$stopSave && existingRecord(T_COMPETITORS, $this_obj)) {
			$stopSave = true;
			$errMessages["existing"] = "The competitor entered already exists.";
		}
	}
	if ($class_obj == T_CARDS) {
		if ($this_obj->competitors_id == "") {
			$errMessages["competitors_id"] = "The Competitor must be filled in.";
		}
		if ($this_obj->cardstatus_id == "") {
			$errMessages["cardstatus_id"] = "The Card Status must be filled in.";
		}
		if ($this_obj->validfrom == "") {
			$errMessages["validfrom"] = "The Valid From must be filled in.";
		}
		if ($this_obj->validuntil == "") {
			$errMessages["validuntil"] = "The Valid Until must be filled in.";
		}
		if (hasErrors($errMessages)) { $stopSave = true; }

		if (!validCardDates($this_obj->validfrom, $this_obj->validuntil, $errMessages)) {
			$stopSave = true;
		}

		if (!$stopSave) {
			setCardReferredAs($this_obj);
		}
	}
	// -- End Logic for individual classes.

	if (!$stopSave) {
		// -- Insert the data in $pino into the database. --
	   $this_obj->save();			// crucial command: disactivate  only if you don't want to save... 
	   
	   $last_inserted_record = $this_obj->id;
	   
	   


	   // Dealing with the join tables
	   $relation_name = $_REQUEST['jt_name'];
	   $relation_class = $_REQUEST['jt_class'];
	      
	  // echo "<p>relation_name = ".$relation_name." - strpos = ".strpos ($relation_name,$class_obj)."";
	   
	   echo "<p>"; // What is this?
	   
	   foreach ($_REQUEST as $key_REQUEST => $value_REQUEST)
		{
			if (substr($key_REQUEST,0,9) == 'jt_input_')
			{
				//$pino = (substr($key_REQUEST,9) => $value_REQUEST);
					
					$that_id = $value_REQUEST;
					 //echo " that_id = ".$that_id;
					 //echo " key = ".$key_REQUEST;


					if (strpos($relation_name,$class_obj) > 0)
					{
						$obj2 = $this_obj;
						//$obj1 = $that_id;
						$obj1 = MyActiveRecord::FindById($relation_class, $that_id);
					}
					else
					{
						$obj1 = $this_obj;
						$obj2 = MyActiveRecord::FindById($relation_class, $that_id);
						//$obj2 = $that_id;
					}
					MyActiveRecord::Link($obj1,$obj2);
					//echo "rel_name = ".$relation_name." - class = ".$class_obj." pos = ".strpos($relation_name,$class_obj)." obj1 = ".$obj1->id." - obj2 = ".$obj2->id."; ";
			}
		}
	}
	// Goes back to main_page.php
?>