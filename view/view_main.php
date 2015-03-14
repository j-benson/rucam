<?php
//TODO: add a checkout button or add functioanlity to access button to check if checked in.
//: add a reset to system date button which will unset the 'custom_date' cookie and set system time as normal.
//: add a validility check to the date input which will check if the date is correct
//KNOWN errors: fix checkin and checkout to display correct dates and times
//hours, minutes and seconds are not initilized properly when the date is custom set.
//the system can throw a error if the cookie is incorrectly set. you must call the method unset($_COOKIE['custom_time'])
	$fixture_selection = (isset($_POST['fixture_selection']) ? $_POST['fixture_selection'] : -1);
    if (isset($_COOKIE['custom_time'])) { //this checks if a cookie has been set
    	//gets the time from getDate
    	$now = getDate(time());
    	//uses a string tokenizer to get all the required info.
    	$now['mday'] = strtok($_COOKIE['custom_time'],'/'); //first parameter is the string to be broken up.
    	//second parameter is the delimiter for the string tokenizer to break the string at.
    	$now['mon'] = strtok("/");
    	$now['year'] = strtok("/"); //works like a scanner, splitting the string into seperate strings at strtok method call.
    } else
    { //if the cookie hasn't been set, just takes the time from the system.
    	$now = getDate(time());
    }
    $today = new DateTime("{$now['year']}-{$now['mon']}-{$now['mday']}-{$now['hours']}-{$now['minutes']}-{$now['seconds']}");
    $formattedDate = $today->format('Y/m/d  H:i:s'); //this formats the date into a nice format.
?>
<h2>System Date: <?echo $formattedDate; ?> <a id="setDateLink" class="subscript" href=<? echo $current_file_name."?here=&mode=set_time"?>>Set Date<img src='./include/images/cog-small.png' height=25></a></h2>
<div class="cardsearch">
<h3>
	<form method = "POST" action="<?php echo $current_file_name; ?>?mode=view_register">
	<select name='fixture_selection'>
	<?
	$fixtures = MyActiveRecord::FindAll(T_FIXTURES, null, "date, time");
	if ($fixtures === false)
	{
		echo "<option>No Fixtures</option>";
	} else {
		foreach($fixtures as $fix) {
			echo "<option ".($fixture_selection == $fix->id ? "selected " : "")."value = '".$fix->id."'>".$fix->referred_as."</option>";	
		}
	}
	?>
	</select>
	<input type='submit' value='View Register'>
	<input type='submit' value='View Logs'>
	</form>

	<table>
		<form method="POST" action="<?php echo $current_file_name; ?>?mode=request_access">
			<tr>
				<td>Insert Card ID:</td>
				<td><input type='text' name='card_id'></td>
				<td><input type='submit' value='Access'></td>
			</tr>
		</form>
	</table>
</div>
<?php if ($mode == "") { ?>
	<div class="main_img">
		<img src="include/images/Logo RUCAM.png" />
	</div>
<?php } ?>

<?php 
if ($mode == "set_time") //SETS THE TIME
{
	if (isset($_POST['set_time_input']))  //checks to see if the time has been inputted.
	{
		setCustomDate($_POST['set_time_input']); //calls a function which sets the cookie setCookie('custom_time', $dateString);
		echo "<h3>System Date has been modified to: ".$_POST['set_time_input'];
	} else //if the time hasn't been inputted, it just displays a form.
	{
	?><h3>
	<table>
		<form method="POST" action="<?php echo $current_file_name; ?>?mode=set_time">
		<tr>
		<td>Insert Date [DD/MM/YYYY]:</td>
		<td><input type='text' name='set_time_input'></td>
		<td><input type='submit' value='Set'></td>
		</tr></h3>
<?
	}
}
if ($mode == "view_register")
{
    $currentFixture = MyActiveRecord::FindById('fixtures', $fixture_selection);
    if ($currentFixture === false) {
        echo "<h1>No fixture information could be found.</h1>";
    }else {
        $authorisations = MyActiveRecord::FindAll(T_CARDS_FIXTURES,"fixtures_id=".$currentFixture->id);
        echo "<h2>Showing register for: ".$currentFixture->referred_as."</h2>";
        echo "<table class=table1>";
        echo "<tr><th>Card ID</th><th>Check In</th><th>Check Out</th></tr>";
        foreach ($authorisations as $auth)
        {
            $card = $auth->find_parent(T_CARDS);
            echo "<tr><td>".$auth->cards_id." (".$card->referred_as.")</td><td>".$auth->checkin."</td><td>".$auth->checkout."</td></tr>";
        }
        echo "</table>";
    }
}
if ($mode == "request_access")
{
	$granted = "<h3 class='greenText'>Access Granted</h3>";
	$denied = "<h3 class='redText'>Access Denied</h3>";

    $req_card = MyActiveRecord::FindById(T_CARDS, $_POST['card_id']);
    if ($req_card === false || $currentFixture == -1) {
		echo $denied;
    	echo "<p>Invalid card.</p>";
    } else {
    	if(validCard($req_card)) {
	    	$linkedFixtures = $req_card->find_linked(T_FIXTURES); //empty array if none
	    	if (count($linkedFixtures) == 0) {
	    		echo $denied;
		    	echo "<p>Not authorised for any of todays fixtures: {$req_card->referred_as}</p>";
	    	} else {
	    		$found = 0;
		    	foreach ($linkedFixtures as $f) {
		    		// is one of these fxtures today?
		    		//In order to test the system, this part 
		    		if (date_create($f->date) == $today) {
		    			// This fixture is today
		    			++$found;
		    			$auth = MyActiveRecord::FindFirst(T_CARDS_FIXTURES, "fixtures_id=".$f->id." AND cards_id=".$req_card->id);

		    			if ($auth->checkin == null) {
					    	// set check in time
					    	$auth->checkin = "{$now['year']}-{$now['mon']}-{$now['mday']} {$now['hours']}:{$now['minutes']}:{$now['seconds']}";
					    	$auth->save();
					        echo $granted;
					       	echo "{$req_card->referred_as} checked in at {$auth->checkin}";
					    } else {
					    	$auth->checkout = "{$now['year']}-{$now['mon']}-{$now['mday']} {$now['hours']}:{$now['minutes']}:{$now['seconds']}";
					    	$auth->save();
					       	echo "{$req_card->referred_as} checked out at {$auth->checkout}";
					    }
		    		}
		    	}
		    	if ($found == 0) {
		    		echo $denied;
		    		echo "<p class='redText'>Not authorised for any of todays fixtures: {$req_card->referred_as}</p>";
		    	}
		    }
		} else {
			echo $denied;
			echo "<p>Card not valid: {$req_card->referred_as}</p>";
		}	    
    }
}

function setCustomDate($dateString)
{
	setCookie('custom_time', $dateString);
}

?>