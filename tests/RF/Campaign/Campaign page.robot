*** Settings ***
Resource          ../Common/Function/common.txt
Resource          Function/campaign.txt
Resource          ../Project_Dashboard/Function/dashboard.txt

*** Test Cases ***
Title of window should contain project name and campaign ID
    [Setup]    Setup
    When I go to campaign page    &{P1C4}
    Then Title Should Be    ci-report / ${P1.name} / Campaign #${P1C4.id}
    [Teardown]    Teardown

Top Next button goes to next campaign
    [Setup]    Setup
    Given I go to campaign page    &{P1C3}
    When Click Element    a-top-next
    Then I should be on campaign page    &{P1C4}
    [Teardown]    Teardown

Top Prev button goes to previous campaign
    [Setup]    Setup
    Given I go to campaign page    &{P1C4}
    When Click Element    a-top-prev
    Then I should be on campaign page    &{P1C3}
    [Teardown]    Teardown

Bottom Next button goes to next campaign
    [Setup]    Setup
    Given I go to campaign page    &{P1C3}
    When Click Element    a-bottom-next
    Then I should be on campaign page    &{P1C4}
    [Teardown]    Teardown

Bottom Prev button goes to previous campaign
    [Setup]    Setup
    Given I go to campaign page    &{P1C4}
    When Click Element    a-bottom-prev
    Then I should be on campaign page    &{P1C3}
    [Teardown]    Teardown

First campaign should have previous buttons disabled
    [Setup]    Setup
    When I go to campaign page    &{P1C1}
    Then Element Should Be Disabled    b-top-prev
    And Element Should Be Disabled    b-bottom-prev
    [Teardown]    Teardown

Last campaign should have next buttons disabled
    [Setup]    Setup
    When I go to campaign page    &{P1C4}
    Then Element Should Be Disabled    b-top-next
    And Element Should Be Disabled    b-bottom-next
    [Teardown]    Teardown

"Back to project" button should go to the project dashboard of the campaign
    [Setup]    Setup
    Given I go to campaign page    &{P1C4}
    When Click Element    a-project
    Then I should be on project dashboard    ${P1C4.pid}
    [Teardown]    Teardown
