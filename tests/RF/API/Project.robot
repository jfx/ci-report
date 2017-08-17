*** Settings ***
Resource          Function/api.txt

*** Test Cases ***
Create project with default values
    Given I create an API session named "cir"
    [Teardown]    Teardown API

Projects list contains Project One
    Given I create an API session named "cir"
    ${resp} =    Get Request    cir    /projects
    Should Be Equal As Strings    ${resp.status_code}    200
    ${json} =    To Json    ${resp.content}
    ${count} =    Get Length    ${json}
    Should Be Equal As Integers    ${count}    6
    Label item of list should be equal    ${json}    "name"    Project One
    Label item of list should be equal    ${json}    "warning_limit"    80
    Label item of list should be equal    ${json}    "success_limit"    95
    [Teardown]    Teardown API

Projects list should not contain none expose fields
    Given I create an API session named "cir"
    ${resp} =    Get Request    cir    /projects
    Should Be Equal As Strings    ${resp.status_code}    200
    ${json} =    To Json    ${resp.content}
    Item of list should not countains label    ${json}    id
    [Teardown]    Teardown API
