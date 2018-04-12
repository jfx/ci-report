*** Settings ***
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/home.txt

*** Test Cases ***
Header should be visible
    When I go to Home
    Then Page Should Contain Link    css=a.navbar-brand

Title
    When I go to Home
    Then Title Should Be    ci-report

Click to open dropdown menu
    Given I go to Home
    And Element Should Not Be Visible    a-dropdown-home
    When Click Element    a-dropdown
    Then Element Should Be Visible    a-dropdown-home

"Home" menu item
    Given I go to Home
    And Click Element    a-dropdown
    When Click Element    a-dropdown-home
    Then Location Should Be    ${WEB_URL}${location_home}

"Add project" menu item
    Given I go to Home
    And Click Element    a-dropdown
    When Click Element    a-dropdown-project
    Then Location Should Be    ${WEB_URL}${location_home}#

"Documentation" menu item
    Given I go to Home
    And Click Element    a-dropdown
    When Click Element    a-dropdown-doc
    And Select Window    NEW
    Then Location Should Be    ${WEB_URL}${location_home}#

"API Documentation" menu item
    Given I go to Home
    And Click Element    a-dropdown
    When Click Element    a-dropdown-api
    And Select Window    NEW
    Then Location Should Be    ${WEB_URL}/api/doc
