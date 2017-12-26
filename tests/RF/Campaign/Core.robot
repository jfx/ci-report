*** Settings ***
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/campaign.txt

*** Test Cases ***
Header should be visible
    When I go to campaign page    &{P1C4}
    Then Page Should Contain Link    css=a.navbar-brand

Click to open dropdown menu
    Given I go to campaign page    &{P1C4}
    And Element Should Not Be Visible    a-dropdown-home
    When Click Element    a-dropdown
    Then Element Should Be Visible    a-dropdown-home

Footer should be visible
    When I go to campaign page    &{P1C4}
    Then Page Should Contain    ci-report -
