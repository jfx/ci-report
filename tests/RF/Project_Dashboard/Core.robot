*** Settings ***
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/dashboard.txt

*** Test Cases ***
Header should be visible
    When I go to project dahsboard    ${P1.prefid}
    Then Page Should Contain Link    css=a.navbar-brand

Click to open dropdown menu
    Given I go to project dahsboard    ${P1.prefid}
    And Element Should Not Be Visible    a-dropdown-home
    When Click Element    a-dropdown
    Then Element Should Be Visible    a-dropdown-home

Footer should be visible
    When I go to project dahsboard    ${P1.prefid}
    Then Page Should Contain    ci-report -
