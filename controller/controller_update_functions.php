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
		
		// Check until date is after from date
		if ($issueCard) {
			if (!validCardDates($validfromStr, $validuntilStr, $errMessages)) {
				$issueCard = false;
			}
		}

		if ($issueCard) {
			// All competitors in team, false if no competitors
			$competitors = getCompetitorsInTeam($class_obj_id);
			if ($competitors !== false) {
				foreach ($competitors as $competitor) {
					$card = getCard($competitor);
					//echo "<pre>"; var_dump($card); echo "</pre>"; 
					if ($card === false || ($card !== false && !validCard($card))) {
						// Competitor does not have a card or their current card is no longer valid.
						issueCard($competitor, $validfromStr, $validuntilStr);
					} // else do not issue so not to duplicate.
				}
			}
		}
	} // end team issue //

	// EXPIRE ALL TEAM MEMBERS CARDS //
	// Foreach competitor set their card's status to cancelled. 
	if ($function == "team_expire_cards") {
		// All competitors in team, false if no competitors
		$competitors = getCompetitorsInTeam($class_obj_id);
		if ($competitors !== false) {
			foreach ($competitors as $competitor) {
				expireCard(getCard($competitor));
			}
		}
	} // end team expire //

	// ISSUE REPLACE CANCEL COMPETITORS CARD //
	if ($function == "competitor_issuereplace_card") {
		$competitor = MyActiveRecord::FindById(T_COMPETITORS, $class_obj_id);
		if ($competitor !== false) {

			if (isset($_POST["replace"])) {
				// Doesnt replace card if
				// - Competitor doesnt have a card
				// - Competitors last card is not valid
				$card = getCard($competitor);
				if ($card === false) {
					$errMessages["nocard"] = "{$competitor->referred_as} does not have a card.";
				} else if (!validCard($card)) {
					$errMessages["expiredcard"] = "{$competitor->referred_as}s last card is no longer valid and cannot be replaced. A new one must be issued.";
				} else {
					// Previous card is valid so need to cancel that card, 
					// create a new one 
					// and add card fixture links from last card on new one.
					if (validCardDates($_POST["validfrom"], $_POST["validuntil"], $errMessages)) {
						cancelCard($card);
						$newCard = issueCard($competitor, $_POST["validfrom"], $_POST["validuntil"]);
						// find fixtures relating to the old card and apply them to the new card.
						$fixtures = $card->find_linked(T_FIXTURES); //empty array when no fixtures
						foreach ($fixtures as $fixture) {
							MyActiveRecord::Link($newCard, $fixture);
						}
					}
				}
			}

			if (isset($_POST["issue"])) {
				// Doesnt issue cards if 
				// - Dates entered arent valid
				// - Competitor already has a valid card
				$card = getCard($competitor);
				if ($card === false || ($card !== false && !validCard($card))) {
					// Competitor does not have a card or their current card is no longer valid.
					if (validCardDates($_POST["validfrom"], $_POST["validuntil"], $errMessages)) {
						$newCard = issueCard($competitor, $_POST["validfrom"], $_POST["validuntil"]);
					}
				} else {
					$errMessages["hascard"] = "{$competitor->referred_as} already has a valid card.";
				}
			}

			if (isset($_POST["expire"])) {
				// Doesnt expire card if 
				// - Competitor doesnt have a card to expire
				// - Competitor card isnt valid
				$card = getCard($competitor);
				if ($card === false) {
					$errMessages["nocard"] = "{$competitor->referred_as} does not have a card.";
				} else if (!validCard($card)) {
					$errMessages["expiredcard"] = "{$competitor->referred_as}s last card is no longer valid.";
				} else {
					expireCard($card);
				}
			}

		}
	} // end competitors card //
?>