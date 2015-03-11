<?php 
	$function = isset($_GET["function"]) ? $_GET["function"] : "";
	$class_obj_id = isset($_GET["class_obj_id"]) ? $_GET["class_obj_id"] : "";

	// AUTHORISE ALL TEAM MEMBERS //
	if ($function == "team_link_cards") {
		$selectedFix = $_POST["fixtures"]; // null when empty, otherwise array of selected fixture ids.
		if ($selectedFix != null) {
			// Get a list of all the competitors belonging to this team.
			$competitors = MyActiveRecord::FindById(T_TEAMS, $class_obj_id)->find_children(T_COMPETITORS);
			
			if ($competitors !== false) {
				// Add authorisation to each competitor for each fixture
				foreach ($competitors as $competitor) {
					foreach ($selectedFix as $fixtureID) {
						$card = getCard($competitor);
						// get fixture object from its id
						$fixture = MyActiveRecord::FindById(T_FIXTURES, $fixtureID); 

						// BEGIN CHECKS //
						// $card and $fixture will be false if there were no reocords found.
						$stopLink = false;
						if ($card === false || $fixture === false) {
							// competitor does not have a card or no fixture
							$stopLink = true;
						}

						/// ONLY LINK IF VALID CARD ///
						if (!$stopLink) {
							if (!validCard($card)) {
								$stopLink = true;
							}
						}
						///////////////////////////////
						
						/// ONLY LINK CARD-FIXTURE IF NOT ALREADY LINKED ///
						if (!$stopLink) {
							// Check if this card - fixture combo already is linked and dont add again.
							$linkedCardsToFixture = $fixture->find_linked(T_CARDS);
							// check of this competitors card exists in this list
							foreach ($linkedCardsToFixture as $linkedCard) {
								if ($linkedCard->id == $card->id) {
									// This card is already lonked to this fixture.
									$stopLink = true;
								}
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
		}
	} // end team auth //

	// ISSUE ALL TEAM MEMBERS CARDS //
	// Foreach competitor create a card object linked to that competitor. 
	if ($function == "team_issue_cards") {
		// CHECKS //
		$issueCard = true;
		$validfromStr = $_POST["validfrom"];
		$validuntilStr = $_POST["validuntil"];
		if ($validfromStr == "" || $validuntilStr = "") {
			$issueCard = false;
			$errMessages["empty"] = "Both Valid From and Valid Until must be filled in.";
		}
		
		// Check until date is after from date
		if ($issueCard) {
			$from = new DateTime($validfromStr);
			$until = new DateTime($validuntilStr);
			if ($from > $until) {
				$issueCard = false;
				$errMessages["dateorder"] = "The Valid From date must be before the Valid Until date.";
			}
		}

		if ($issueCard) {
			// All competitors in team, false if no competitors
			$competitors = getCompetitorsInTeam($class_obj_id);
			if ($competitors !== false) {
				foreach ($competitors as $competitor) {
					$card = getCard($competitor);
					if ($card === false || ($card !== false && !validCard($card))) {
						// Competitor does not have a card or their current card is no longer valid.
						issueCard($competitor, $validfromStr, $validuntilStr);
					} // else do not issue so not to duplicate.
				}
			}
		}
	} // end team cancel //

	// EXPIRE ALL TEAM MEMBERS CARDS //
	// Foreach competitor set their card's status to cancelled. 
	if ($function == "team_expire_cards") {
		echo "Expire cards being called";die;
		// All competitors in team, false if no competitors
		$competitors = getCompetitorsInTeam($class_obj_id);
		if ($competitors !== false) {
			foreach ($competitors as $competitor) {
				expireCard($competitor);
			}
		}
	} // end team expire //

	// ISSUE COMPETITORS CARD //
	if ($function == "competitor_issue_card") {
		$competitor = MyActiveRecord::FindById(T_COMPETITORS, $_POST["competitor_id"]);
		if ($competitor !== false) {
			$card = getCard($competitor);
			if ($card === false || ($card !== false && !validCard($card))) {
				// Competitor does not have a card or their current card is no longer valid.
				$newCard = MyActiveRecord::Create(T_CARDS);
				$newCard->competitors_id = $competitor->id;
				$newCard->cardstatus_id = getCardStatusId(CS_VALID);
				$newCard->validfrom = ""; // get from post.
				$newCard->validuntil = ""; // get from post.
				setCardReferredAs($newCard, $competitor);
				$newCard->save();
			}
		}
	} // end issue competitors card //

	// RE-ISSUE COMPETITORS CARD //
	if ($function == "competitor_issue_card") {
		$competitor = MyActiveRecord::FindById(T_COMPETITORS, $_POST["competitor_id"]);
		if ($competitor !== false) {
			$card = getCard($competitor);
			if ($card === false || ($card !== false && !validCard($card))) {
				// Cannot re issue as dont have a card or it has expired, user should issue a new one

				// // Competitor does not have a card or their current card is no longer valid.
				// $newCard = MyActiveRecord::Create(T_CARDS);
				// $newCard->competitors_id = $competitor->id;
				// $newCard->cardstatus_id = getCardStatusId(CS_VALID);
				// $newCard->validfrom = ""; // get from post.
				// $newCard->validuntil = ""; // get from post.
				// setCardReferredAs($newCard, $competitor);
				// $newCard->save();
			} else {
				// Previous card need to be found and status changed to cancelled.
				// issue a new card.
				cancelCard($card);
				//TODO: finish
			}
		}
	} // end re-issue competitors card //

	// EXPIRE COMPETITORS CARD //
	if ($function == "competitor_expire_card") {
		$competitor = MyActiveRecord::FindById(T_COMPETITORS, $_POST["competitor_id"]);
		if ($competitor !== false) {
			expireCard($competitor);
		}
	} // end expire competitors card //
?>