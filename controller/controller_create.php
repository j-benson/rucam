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
		setFixtureReferredAs($this_obj);
	}
	if ($class_obj == T_TEAMS) {
		// Check for clashing team names, prevent saving these.
	}
	if ($class_obj == T_COMPETITORS) {
		// when inserting new competitor, check for fixtures and add authorisation for those fixtures.
		// update their card and authorisations

		// TESTING force error.
		// $errMessages["referred_as"] = "This nation already exists.";
		// $errMessages["nickname"] = "Nick name was not filled in.";
		// $stopSave = true;
	}
	if ($class_obj == T_CARDS) {
		setCardReferredAs($this_obj);
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