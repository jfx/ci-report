*** Settings ***
Resource          ../Common/Function/common.txt
Resource          Function/suite.txt
Resource          ../Campaign/Function/campaign.txt

*** Test Cases ***
Title of window should contain project name, campaign and suite ID
    [Setup]    Setup
    When I go to suite page    &{P1C4S1}
    Then Title Should Be    ci-report / ${P1.name} / Campaign #${P1C4S1.cid} / Suite #${P1C4S1.id}
    [Teardown]    Teardown

Top Next button goes to next suite
    [Setup]    Setup
    Given I go to suite page    &{P1C4S1}
    When Click Element    a-top-next
    Then I should be on suite page    &{P1C4S2}
    [Teardown]    Teardown

Top Prev button goes to previous suite
    [Setup]    Setup
    Given I go to suite page    &{P1C4S2}
    When Click Element    a-top-prev
    Then I should be on suite page    &{P1C4S1}
    [Teardown]    Teardown

Bottom Next button goes to next suite
    [Setup]    Setup
    Given I go to suite page    &{P1C4S1}
    When Click Element    a-bottom-next
    Then I should be on suite page    &{P1C4S2}
    [Teardown]    Teardown

Bottom Prev button goes to previous suite
    [Setup]    Setup
    Given I go to suite page    &{P1C4S2}
    When Click Element    a-bottom-prev
    Then I should be on suite page    &{P1C4S1}
    [Teardown]    Teardown

First suite should have previous buttons disabled
    [Setup]    Setup
    When I go to suite page    &{P1C4S1}
    Then Element Should Be Disabled    b-top-prev
    And Element Should Be Disabled    b-bottom-prev
    [Teardown]    Teardown

Last suite should have next buttons disabled
    [Setup]    Setup
    When I go to suite page    &{P1C4S2}
    Then Element Should Be Disabled    b-top-next
    And Element Should Be Disabled    b-bottom-next
    [Teardown]    Teardown

"Back to campaign" button should go to the campaign page
    [Setup]    Setup
    Given I go to suite page    &{P1C4S1}
    When Click Element    a-campaign
    Then I should be on campaign page    &{P1C4}
    [Teardown]    Teardown
