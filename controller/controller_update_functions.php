<?php 
	$function = isset($_GET["function"]) ? $_GET["function"] : "";
	$class_obj_id = isset($_GET["class_obj_id"]) ? $_GET["class_obj_id"] : "";

	// AUTHORISE WHOLE TEAMS //
	if ($function == "team_auth") {
		$selectedFix = $_POST["fixtures"]; // null when empty, otherwise array of selected fixture ids.
		if ($selectedFix != null) {
			// Get a list of all the competitors belonging to this team.
			$competitors = MyActiveRecord::FindById(T_TEAMS, $class_obj_id)->find_children(T_COMPETITORS);
			
			// Add authorisation to each competitor for each fixture
			foreach ($competitors as $competitor) {
				foreach ($selectedFix as $fixtureID) {
					// get the card id for the competitor, 
					// if the competitor has muliple cards choose the newest (valid from)
					$card = MyActiveRecord::FindFirst(T_CARDS, T_COMPETITORS."_id=".$competitor->id, "validfrom DESC");
					// get fixture object from its id
					$fixture = MyActiveRecord::FindById(T_FIXTURES, $fixtureID); 

					// BEGIN CHECKS //
					// $card and $fixture will be false if there were no reocords found.
					$stopLink = false;
					if ($card === false) {
						// competitor does not have a card
					}
					if ($fixture === false) {
						// no fixture found
					}

					/// ONLY LINK IF VALID CARD ///
					if (!$stopLink) {
						
						
					}
					///////////////////////////////
					
					/// ONLY LINK CARD-FIXTURE IF NOT ALREADY LINKED ///
					// Check if this card - fixture combo already is linked and dont add again.
					$linkedCardsToFixture = $fixture->find_linked(T_CARDS);
					// check of this competitors card exists in this list
					foreach ($linkedCardsToFixture as $linkedCard) {
						if ($linkedCard->id == $card->id) {
							// This card is already lonked to this fixture.
							$stopLink = true;
						}
					}
					//////////////////////////////////////////
					// END CHECKS //

					// Create link if nothing has happened to prevent it.
					if (!$stopLink) {
						$success = MyActiveRecord::Link($card, $fixture);
					}
				}
			}
		}
		


		die;
	}
?>