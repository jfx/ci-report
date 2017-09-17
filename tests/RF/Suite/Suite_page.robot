*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/suite.txt

*** Test Cases ***
Title of window should contain project name, campaign and suite ID
    When I go to suite page    &{P1C4S1}
    Then Title Should Be    ci-report / ${P1.name} / Campaign #${P1C4S1.crefid} / Suite #${P1C4S1.srefid}

Top Next button goes to next suite
    Given I go to suite page    &{P1C4S1}
    When Click Element    a-top-next
    Then I should be on suite page    &{P1C4S2}

Top Prev button goes to previous suite
    Given I go to suite page    &{P1C4S2}
    When Click Element    a-top-prev
    Then I should be on suite page    &{P1C4S1}

Bottom Next button goes to next suite
    Given I go to suite page    &{P1C4S1}
    When Click Element    a-bottom-next
    Then I should be on suite page    &{P1C4S2}

Bottom Prev button goes to previous suite
    Given I go to suite page    &{P1C4S2}
    When Click Element    a-bottom-prev
    Then I should be on suite page    &{P1C4S1}

First suite should have previous buttons disabled
    When I go to suite page    &{P1C4S1}
    Then Element Should Be Disabled    b-top-prev
    And Element Should Be Disabled    b-bottom-prev

Last suite should have next buttons disabled
    When I go to suite page    &{P1C4S2}
    Then Element Should Be Disabled    b-top-next
    And Element Should Be Disabled    b-bottom-next

"Back to campaign" button should go to the campaign page
    Given I go to suite page    &{P1C4S1}
    When Click Element    a-campaign
    Then I should be on campaign page    &{P1C4}
