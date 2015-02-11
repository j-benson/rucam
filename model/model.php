<?
	// Hello Benson
	// The application title is defined here 
	$application_title = "RUCAM System";
	
	// The application database and the connection string are defined here
	// syntax is: 'username@database.server/database_name IDENTIFIED BY PASSWORD ' 
	define('MYACTIVERECORD_CONNECTION_STR', 'mysql://root@localhost/rucam');
	
	// includes used implementation of MyActiveRecord class 
	include './include/MyActiveRecord.0.4.php';
	
	//in this array we list all and only those classes we like to CRUD manage from the main menu 
	$classes = array('fixtures','teams','competitors','cards','authorisation');  
	
	// in this array we list all join tables which hold many to many relationships between two given classes of objects
	$join_tables = array('authorisation');	
	
	// in this array below we list all foreign keys: this array MUST EXIST: if empty then uncomment line below (and comment the following one!)
	//foreign_keys=array();
	$foreign_keys = array('titles_id','teams_id','competitors_id','cardstatus_id','home_teams_id','away_teams_id','venues_id','cards_id','fixtures_id'); 
	
	// relationships between entities/classes are named below: if no name has
	// been given to a certain relationship, the bare foreign key would be displayed
	function name_child_relationship($class_name,$foreign_key)
	{
		if ($class_name == 'competitors' && $foreign_key == 'titles_id')
		{
			return "title";
		}
		else if ($class_name == 'competitors' && $foreign_key == 'teams_id')
		{
			return "team";
		}
		else if ($class_name == 'cards' && $foreign_key == 'competitors_id')
		{
			return "competitor";
		}
		else if ($class_name == 'cards' && $foreign_key == 'cardstatus_id')
		{
			return "status";
		}
		else if ($class_name == 'fixtures' && $foreign_key == 'home_teams_id')
		{
			return "home team";
		}
		else if ($class_name == 'fixtures' && $foreign_key == 'away_teams_id')
		{
			return "away team";
		}
		else if ($class_name == 'fixtures' && $foreign_key == 'venues_id')
		{
			return "venue";
		}
		else if ($class_name == 'authorisation' && $foreign_key == 'cards_id')
		{
			return "card";
		}
		else if ($class_name == 'authorisation' && $foreign_key == 'fixtures_id')
		{
			return "fixture";
		}
		else if ($class_name == 'entries' && $foreign_key == 'cards_id')
		{
			return "card";
		}
		else if ($class_name == 'entries' && $foreign_key == 'venues_id')
		{
			return "venue";
		}
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
	

?>
