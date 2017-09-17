*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/campaign.txt

*** Test Cases ***
Title of window should contain project name and campaign ID
    When I go to campaign page    &{P1C4}
    Then Title Should Be    ci-report / ${P1.name} / Campaign #${P1C4.crefid}

Top Next button goes to next campaign
    Given I go to campaign page    &{P1C3}
    When Click Element    a-top-next
    Then I should be on campaign page    &{P1C4}

Top Prev button goes to previous campaign
    Given I go to campaign page    &{P1C4}
    When Click Element    a-top-prev
    Then I should be on campaign page    &{P1C3}

Bottom Next button goes to next campaign
    Given I go to campaign page    &{P1C3}
    When Click Element    a-bottom-next
    Then I should be on campaign page    &{P1C4}

Bottom Prev button goes to previous campaign
    Given I go to campaign page    &{P1C4}
    When Click Element    a-bottom-prev
    Then I should be on campaign page    &{P1C3}

First campaign should have previous buttons disabled
    When I go to campaign page    &{P1C1}
    Then Element Should Be Disabled    b-top-prev
    And Element Should Be Disabled    b-bottom-prev

Last campaign should have next buttons disabled
    When I go to campaign page    &{P1C4}
    Then Element Should Be Disabled    b-top-next
    And Element Should Be Disabled    b-bottom-next

"Back to project" button should go to the project dashboard of the campaign
    Given I go to campaign page    &{P1C4}
    When Click Element    a-project
    Then I should be on project dashboard    ${P1C4.prefid}
