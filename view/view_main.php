<?php
	$fixture_selection = (isset($_POST['fixture_selection']) ? $_POST['fixture_selection'] : -1);
?>

<div class="cardsearch">
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
	$granted = "<h3 class='access_granted'>Access Granted</h3>";
	$denied = "<h3 class='access_denied'>Access Denied</h3>";

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
	    		$now = getDate(time());
	    		$today = new DateTime("{$now['year']}-{$now['mon']}-{$now['mday']}");
	    		$found = 0;
		    	foreach ($linkedFixtures as $f) {
		    		// is one of these fxtures today?
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
		    		echo "<p>Not authorised for any of todays fixtures: {$req_card->referred_as}</p>";
		    	}
		    }
		} else {
			echo $denied;
			echo "<p>Card not valid: {$req_card->referred_as}</p>";
		}	    
    }
}
?>