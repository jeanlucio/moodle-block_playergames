@block @block_playergames @javascript
Feature: PlayerGames widget gamification opt-out
  As a player using the site-wide PlayerGames widget
  I want to pause and resume gamification directly from the widget

  Background:
    Given an active PlayerGames season exists
    And I log in as "admin"
    And I turn editing mode on
    And I add the "PlayerGames" block if not present
    And I turn editing mode off

  Scenario: Active player sees the normal state with a working pause link
    Then I should see "Open Player Hub" in the "PlayerGames" "block"
    When I click on "Pause gamification" "link" in the "PlayerGames" "block"
    Then I should see "Gamification preferences"
    And the field "Participate in gamification" matches value "1"

  Scenario: Opting out from the widget shows the paused state
    When I click on "Pause gamification" "link" in the "PlayerGames" "block"
    And I set the field "Participate in gamification" to "0"
    And I press "Save changes"
    And I am on homepage
    Then I should see "Gamification is paused" in the "PlayerGames" "block"
    And I should see "Reactivate" in the "PlayerGames" "block"

  Scenario: Reactivating from the paused widget restores the normal state
    Given "admin" has disabled gamification
    When I am on homepage
    Then I should see "Gamification is paused" in the "PlayerGames" "block"
    When I click on "Reactivate" "link" in the "PlayerGames" "block"
    And I set the field "Participate in gamification" to "1"
    And I press "Save changes"
    And I am on homepage
    Then I should see "Open Player Hub" in the "PlayerGames" "block"
