<?
	// The application title is defined here 
	$application_title = "RUCAM System";
	
	// The application database and the connection string are defined here
	// syntax is: 'username@database.server/database_name IDENTIFIED BY PASSWORD ' 
	define('MYACTIVERECORD_CONNECTION_STR', 'mysql://root@localhost/rucam');
	
	// includes used implementation of MyActiveRecord class 
	include './include/MyActiveRecord.0.4.php';
	
	//in this array we list all and only those classes we like to CRUD manage from the main menu 
	$classes = array('teams','fixtures','competitors','cards','authorisation');  
	
	// in this array we list all join tables which hold many to many relationships between two given classes of objects
	$join_tables = array();	
	
	// in this array below we list all foreign keys: this array MUST EXIST: if empty then uncomment line below (and comment the following one!)
	//foreign_keys=array();
	$foreign_keys = array('titles_id','teams_id','competitors_id','cardstatus_id','home_teams_id','away_teams_id','venues_id','cards_id','fixtures_id'); 

	$errMessages = array();
	
	// relationships between entities/classes are named below: if no name has
	// been given to a certain relationship, the bare foreign key would be displayed
	function name_child_relationship($class_name,$foreign_key)
	{
		if ($class_name == 'competitors' && $foreign_key == 'titles_id')
		{
			return "Title";
		}
		else if ($class_name == 'competitors' && $foreign_key == 'referred_as')
		{
			return "Name";
		}
		else if ($class_name == 'competitors' && $foreign_key == 'teams_id')
		{
			return "Team";
		}
		else if ($class_name == 'cards' && $foreign_key == 'competitors_id')
		{
			return "Competitor";
		}
		else if ($class_name == 'cards' && $foreign_key == 'cardstatus_id')
		{
			return "Status";
		}
		else if ($class_name == 'fixtures' && $foreign_key == 'home_teams_id')
		{
			return "Home Team";
		}
		else if ($class_name == 'fixtures' && $foreign_key == 'away_teams_id')
		{
			return "Away Team";
		}
		else if ($class_name == 'fixtures' && $foreign_key == 'venues_id')
		{
			return "Venue";
		}
		else if ($class_name == 'authorisation' && $foreign_key == 'cards_id')
		{
			return "Card";
		}
		else if ($class_name == 'authorisation' && $foreign_key == 'fixtures_id')
		{
			return "Fixture";
		}
		else if ($class_name == 'entries' && $foreign_key == 'cards_id')
		{
			return "Card";
		}
		else if ($class_name == 'entries' && $foreign_key == 'venues_id')
		{
			return "Venue";
		} 
	}

	// For displaying to the user only, define better names for each field or at least capitalise the words.
	function niceName($class_name, $field) {
		if (strlen($field) > 2 && !(strpos($field, '_id')===false))
		{
			// Is a foreign key so resolve with
			return name_child_relationship($class_name,$field);
		}

		// Not a foreign key so replace given field names with nicer names to display to the user.
		// If nothing has been defined just make sure the first letter is capitalised.
		switch ($field) {
			case "id":
				return "ID";
			case "controlledby":
				return "Controlled By";
			case "validfrom":
				return "Valid From";
			case "validuntil":
				return "Valid Until";
			case "checkin":
				return "Check In";
			case "checkout":
				return "Check Out";
			case "referred_as":
				if ($class_name == "competitors") return "Name";
				if ($class_name == "teams") return "Nation";
			default:
				return ucfirst($field);
		}
	}

	// Remove the S from a word, if does not end in an s return the word back.
	function singularName($name, $ucfirst = false) 
	{
		// if ends in an s
		if (strlen($name) > 3 && strrpos(strtolower($name), "s") === strlen($name) - 1)
		{
			$name = substr($name, 0, -1);
		}
		if ($ucfirst) {
			$name = ucfirst($name);
		}
		return $name;
	}

	/**
	 * Whether the refered_as field in a perticular table/class should be hidden from the user or not.
	 * @param $class The name of the class or table to check if the referred_as field should be hidden.
	 * @return True when the referred_as field should ne hidden and false if it should be shown.
	 */
	function hiddenReferredAs($class) {
		if ($class == "fixtures") return true;
		if ($class == "cards") return true;

		return false;
	}


	// this array has been initiated, but its usage will be defined in future versions of VF1
	$objects = array();
	
	// classes are defined below as extensions of MyActiveRecord class
	class competitors extends MyActiveRecord{
		function destroy(){
		}	
	}
		
	class teams extends MyActiveRecord{
		function destroy(){
		}	
	}
		
	class cards extends MyActiveRecord{
		function destroy(){
		}	
	}
		
	class fixtures extends MyActiveRecord{
		function destroy(){
		}	
	}
	
	class authorisation extends MyActiveRecord{
		function destroy(){
		}	
	}
		
	class venues extends MyActiveRecord{
		function destroy(){
		}	
	}
		
	class titles extends MyActiveRecord{
		function destroy(){
		}	
	}
		
	class cardstatus extends MyActiveRecord{
		function destroy(){
		}	
	}

	/**
	 * Checks whether there are any errors.
	 * @param $errorArray The array holding the errors.
	 * @return True when there are errors, false when not.
	 */
	function hasErrors($errorArray) {
		return count($errorArray) > 0;
	}
?>
