<?
$class_obj_id = $_REQUEST['class_obj_id'];
	$this_obj = MyActiveRecord::FindById($here,$class_obj_id);
	
	$pino = array();
	
	foreach ($_REQUEST as $key_REQUEST => $value_REQUEST)
	{
		if (substr($key_REQUEST,0,6) == 'input_')
		{
			$local_attrib = substr($key_REQUEST,6);
			//if ($key_REQUEST == "input_id")
			{
			$this_obj->$local_attrib = $value_REQUEST;
			}
			
		}
	}
	
	// Set this to true to prevent the controller updating the database.
	$stopSave = false;

	// -- Logic for individual classes updates
	if ($class_obj == "fixtures") {
		// Check for clashing fixtures, prevent saving these.
	}
	if ($class_obj == "teams") {
		// Check for clashing team names, prevent saving these.
	}
	if ($class_obj == "competitors") {
		// when inserting new competitor, check for fixtures and add authorisation for those fixtures.
		// update their card and authorisations

		// TESTING force error.
		$errMessages["referred_as"] = "This nation already exists.";
		$errMessages["nickname"] = "Nick name was not filled in.";
		$stopSave = true;
	}
	if ($class_obj == "cards") {
		
	}
	if ($class_obj == "authorisation") {
		//$pino['cards_id']
		// Card must be valid
		// 
	}
	// -- End Logic for individual classes.


	if (!$stopSave) {
		$this_obj->save();

		$relation_class = '';
		$relation_name = $_REQUEST['jt_name'];
		$relation_class = $_REQUEST['jt_class'];


		// echo "<p>relation_name = ".$relation_name." - strpos = ".strpos ($relation_name,$class_obj)."";

		if($relation_class != '')
		{

			$this_obj->whatever_relationship = $this_obj->find_attached($relation_class);
			foreach ($this_obj->whatever_relationship as $wr_key => $wr_value)
					{
						$that_obj = MyActiveRecord::FindById($relation_class, $wr_value->id);
						
						MyActiveRecord::unlink($this_obj, $that_obj);
						//echo "<td>".($downs_value->make). " ".($downs_value->model);
					}
			echo "<p>";

			foreach ($_REQUEST as $key_REQUEST => $value_REQUEST)
			{
				if (substr($key_REQUEST,0,9) == 'jt_input_')
				{
				//$pino = (substr($key_REQUEST,9) => $value_REQUEST);
					
					$that_id = $value_REQUEST;
					 //echo " that_id = ".$that_id;
					 //echo " key = ".$key_REQUEST;


					if (strpos($relation_name,$class_obj)>0)
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
					//MyActiveRecord::Link($obj1,$obj2);
					MyActiveRecord::Link($obj1,$obj2);
					//echo "rel_name = ".$relation_name." - class = ".$class_obj." pos = ".strpos($relation_name,$class_obj)." obj1 = ".$obj1->id." - obj2 = ".$obj2->id."; ";

				}
			}

		}    // end    if($relation_class != '')
	} // end !stopSave
	
   
?>