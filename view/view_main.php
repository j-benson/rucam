<?php
//KNOWN errors: fix checkin and checkout to display correct dates and times

// About the hours min and sec not initialising, I removed them anyway so can use $today for comparing fixture dates which dont include the time aspect.


	$fixture_selection = (isset($_POST['fixture_selection']) ? $_POST['fixture_selection'] : -1);
	if (isset($_POST['selected_venue'])) {
		$selected_venue = $_POST['selected_venue'];
		setcookie('selected_venue', $selected_venue); // for persistance so the same register is shown.. maybe if I do this
	} else if (isset($_COOKIE['selected_venue'])) {
		$selected_venue = $_COOKIE['selected_venue'];
	} else {
		$selected_venue = 0;
	}

	$now = getdate(time()); // infomation about the real time
    if (isset($_COOKIE['custom_time'])) { //this checks if a cookie has been set
    	//gets the time from getDate
    	$todayinfo = getDate($_COOKIE['custom_time']); // cookie holds the unix timestamp
    }
    else
    { //if the cookie hasn't been set, just takes the time from the system.
    	$todayinfo = getDate(time());
    }
    $today = date_create("{$todayinfo['year']}-{$todayinfo['mon']}-{$todayinfo['mday']}"); // today with just the (simulated)date as need this to compare with fixture date
    $todaytime = date_create("{$todayinfo['year']}-{$todayinfo['mon']}-{$todayinfo['mday']} {$now['hours']}:{$now['minutes']}:{$now['seconds']}"); // today with the (simulated)date and (real)time for the logs
   
    $formattedDate = $today->format('Y/m/d'); //this formats the date into a nice format.
?>
<h2>System Date: <? echo $formattedDate; ?> <a class="subscript" href="help.html"><img src="include/images/Question-small.png" title="Help" /></a> <a id="setDateLink" class="subscript" href=<? echo $current_file_name."?here=&mode=set_time"?>>Set Date<img src='./include/images/cog-small.png' height=25></a></h2>
<div class="cardsearch">
<h3>
	<form id="select_venue" method = "POST" action="<?php echo $current_file_name; ?>?mode=view_register">
		<select id='selected_venue' name='selected_venue'>
			<?php $venues = MyActiveRecord::FindAll(T_VENUES); 
			echo "<option value='0'>Select a Venue</option>";
			foreach ($venues as $v) {
				echo "<option ".($v->id == $selected_venue ? "selected " : "")."value='{$v->id}'>{$v->referred_as} ({$v->town})</option>";
			} ?>
		</select>
		<input type='submit' value='View Register'>
		<input type='submit' value='View Logs'>
	</form>
	<script type="text/javascript">
		document.getElementById('selected_venue').addEventListener("change", function(e) {
			document.getElementById('select_venue').submit();
		});
	</script>
	<?php if ($selected_venue > 0) {  // REMOVE IF, IF WANT TO ALWAYS SHOW ?>
		<table>
			<form method="POST" action="<?php echo $current_file_name; ?>?mode=request_access">
				<tr>
					<td>Enter Card ID:</td>
					<td><input type='text' name='card_id'></td>
					<td><input type='submit' value='Access'></td>
				</tr>
			</form>
		</table>
	<?php } ?>
</div>
<?php if ($selected_venue == 0) { ?>
	<div class="main_img">
		<img src="include/images/Logo RUCAM.png" />
	</div>
<?php } ?>

<?php 
if ($mode == "set_time") //SETS THE TIME
{
	// The constructer for DateTime doesn't parse DD/MM/YYYY (does parse MM/DD/YYYY though because 'murica) so changed the input to YYYY-MM-DD so can use the date
	// picker provided by the framework. --> OK for some reason the date picker isnt working put TEMP in front of onclick to stop it running and erroring
	// Now can call date_create (same as new DateTime but date_create returns false on fail DateTime throws an exception) to
	// check the date entered is ok. Although as swapped the order of date strtok didnt work so used Unix timestamp as quicker.

	if (isset($_POST['reset_time_input'])) {
		// deletes the cookie so system looks at real date
		setCookie('custom_time', '', time() - 3600); //overwrite cookie with one that expired one hour ago so browser deletes it
		unset($_COOKIE['custom_time']); // so php can no longer access it.
		// force refresh of page so date updates
		header("Location: index.php");
		//echo "<script>document.location = </script>";
	}
	else if (isset($_POST['set_time_input']))  //checks to see if the time has been inputted.
	{
		// Check entered date before setting the cookie.
		$datetoset = date_create($_POST['set_time_input']);
		if ($datetoset === false) {
			// The date is invalid.
			echo "<h3>Invalid Date: ".$_POST['set_time_input']."</h3>";
		} else {
			// Setting the cookie with the Unix timestamp of the date.
			setCustomDate($datetoset->format('U')); //calls a function which sets the cookie setCookie('custom_time', $dateString);
			echo "<h3>System Date has been modified to: ".$_POST['set_time_input']."</h3>";
			// force refresh of page so date updates
			header("Location: index.php");
		}
	} 
	else //if the time hasn't been inputted, it just displays a form.
	{ ?>
	<h3>
		<table>
			<form method="POST" action="<?php echo $current_file_name; ?>?mode=set_time">
				<tr>
				<td>Insert Date [YYYY-MM-DD]:</td>
				<td><input id="date_picker" type='text' name='set_time_input' TEMPonclick="displayDatePicker('date_picker',false,'ymd','-');" ></td>
				<td><input type='submit' value='Set'></td>
				<td><input form="reset-date" type='submit' name="reset_time_input" value='Reset Date'></td>
				</tr>
			</form>
			<form id="reset-date" method="post" action="<?php echo $current_file_name; ?>?mode=set_time"></form>
		</table>
	</h3>
	<? }
}
if ($mode == "request_access")
{
	$granted = "<h3 class='greenText'>Access Granted - ";
	$denied = "<h3 class='redText'>Access Denied - ";
	$closeh = "</h3>";

    $req_card = MyActiveRecord::FindById(T_CARDS, $_POST['card_id']);
    if ($req_card === false || $currentFixture == -1) {
		echo $denied."Invalid card.".$closeh;
    } else {
    	if(validCard($req_card)) {
    		$venue = MyActiveRecord::FindById(T_VENUES, $selected_venue);
    		// Only check fixtures for the selected venue
	    	$linkedFixtures = $req_card->find_linked(T_FIXTURES); //empty array if none
	    	if (count($linkedFixtures) == 0) {
	    		echo $denied."Not authorised for any of todays fixtures at {$venue->referred_as}: {$req_card->referred_as}".$closeh;
	    	} else {
	    		$found = 0;
		    	foreach ($linkedFixtures as $f) {
		    		// is one of these fxtures today?
		    		//In order to test the system, this part 
		    		if (date_create($f->date) == $today && $f->venues_id == $selected_venue) {
		    			// This fixture is today at the selected venue.
		    			// REMOVE && $f->venues_id == $selected_venue IF WE WANT CARD TO SCAN IN FOR ANY FIXTURE
		    			++$found;
		    			$access = MyActiveRecord::FindFirst(T_ACCESS, "venues_id=".$f->venues_id." AND cards_id=".$req_card->id, "id DESC");

			    		// Create new entry when 
			    		//  - No previous entry
			    		//  - Previous entry was checked out
			    		// Update entry when 
			    		//  - Previous entry was not checked out
		    			if ($access === false || $access->checkout != D_DATE) {
		    				$access = MyActiveRecord::Create(T_ACCESS);
		    				$access->cards_id = $req_card->id;
		    				$access->venues_id = $selected_venue;
		    				$access->checkin = $todaytime->format("Y-m-d H:i:s");
		    				$access->checkout = D_DATE; // as myactiverecord cant seem to set null values have to use a default timestamp to indicate empty
		    				echo $granted."{$req_card->referred_as} checked in at {$access->checkin}".$closeh;
		    			} else {
		    				$access->checkout = $todaytime->format("Y-m-d H:i:s");
		    				echo "<h3 class='greenText'>{$req_card->referred_as} checked out at {$access->checkout}".$closeh;
		    			}
		    			$access->save();
		    		}
		    	}
		    	if ($found == 0) {
		    		echo $denied."Not authorised for any of todays fixtures at {$venue->referred_as}: {$req_card->referred_as}".$closeh;
		    	}
		    }
		} else {
			echo $denied."Card not valid: {$req_card->referred_as}".$closeh;
		}	    
    }
}
if ($selected_venue > 0)
{ // VIEW REGISTER //
	// The register shows two things, those who are allowed to be on site and
	// those who are actuallly on site.
	// Need to find fixtures that are at this venue today
	// Then look at what cards are linked to that fixture.
	// Look for those cards in the access logs
	// List those cards that are onsite (those checked in but not out)
	// List those cards that are Authorised onsite (all).
	$venue = MyActiveRecord::FindById(T_VENUES, $selected_venue);
	$fixtures = MyActiveRecord::FindAll(T_FIXTURES, "venues_id=".$venue->id." AND date='".$today->format("Y-m-d")."'", "time");
	$cardIds = array();
	$cards = array();
	foreach ($fixtures as $f) {
		// As there could be more than one fixture in a day
		$fcards = $f->find_linked(T_CARDS);
		// These arrays will show duplicates if same person/team plays twice. or more.
		$cards = array_merge($cards, $fcards);
		foreach ($fcards as $c) {
			array_push($cardIds, $c->id);
		}
	}
	
	if (count($cardIds) > 0) {
		$access = MyActiveRecord::FindAll(T_ACCESS, "venues_id=".$venue->id." AND cards_id IN (".implode(",", $cardIds).")");
	} else {
		$access = array();
	}
	echo "<h2>Showing register for: ".$venue->referred_as." (".$venue->town.")</h2>";
	?>
	<div style="margin: 10px auto 0 auto; width: 870px;">
		<div style="float:left; width:40%; margin-right:15px;">
			<table class="table1">
				<tr><th>Authorised Onsite Today</th></tr>
				<?php foreach ($cards as $c) {
					echo "<tr><td>{$c->referred_as}</td></tr>";
				} ?>
			</table>
		</div>
		<div style="float:left; width:56%; margin-left:15px;">
			<table class="table1">
				<tr><th>Currently Onsite</th><th>Time Entered</th></tr>
				<?php foreach ($access as $a) {
					if ($a->checkout == D_DATE) {
						echo "<tr><td>".$a->find_parent(T_CARDS)->referred_as."</td><td>{$a->checkin}</td></tr>";
					}
				} ?>
			</table>
		</div>
		<div style="clear:both;"></div>
	</div>
	<?php
    // $currentFixture = MyActiveRecord::FindById('fixtures', $fixture_selection);
    // if ($currentFixture === false) {
    //     echo "<h1>No fixture information could be found.</h1>";
    // }else {
    //     $authorisations = MyActiveRecord::FindAll(T_CARDS_FIXTURES,"fixtures_id=".$currentFixture->id);
    //     echo "<h2>Showing register for: ".$currentFixture->referred_as."</h2>";
    //     echo "<table class=table1>";
    //     echo "<tr><th>Card ID</th><th>Check In</th><th>Check Out</th></tr>";
    //     foreach ($authorisations as $auth)
    //     {
    //         $card = $auth->find_parent(T_CARDS);
    //         // Now need to get infomation from the access
    //         echo "<tr><td>[".$auth->cards_id."] ".$card->referred_as."</td><td>".$auth->checkin."</td><td>".$auth->checkout."</td></tr>";
    //     }
    //     echo "</table>";
    // }
}

function setCustomDate($dateString)
{
	setCookie('custom_time', $dateString);
}
