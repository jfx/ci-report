*** Settings ***
Resource          Function/api.txt

*** Test Cases ***
"GET Projects" request contains Project One
    [Setup]    Setup
    ${resp} =    When Get Request    cir    /projects
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Response content should have x Items    ${resp.content}    6
    And Label item of list should be equal    ${resp.content}    "name"    Project One
    And Label item of list should be equal    ${resp.content}    "warning_limit"    80
    And Label item of list should be equal    ${resp.content}    "success_limit"    95
    [Teardown]    Teardown

"GET Projects" request should not contain not expose fields
    [Setup]    Setup
    ${resp} =    When Get Request    cir    /projects
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Item of list should not countains label    ${resp.content}    id
    And Item of list should not countains label    ${resp.content}    token
    And Item of list should not countains label    ${resp.content}    email
    [Teardown]    Teardown

"GET Projects" request with unknown route returns 404
    [Setup]    Setup
    ${resp} =    When Get Request    cir    /projects/
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404
    [Teardown]    Teardown

"GET project" request returns public project data
    [Setup]    Setup
    ${resp} =    When Get Request    cir    /projects/project-one
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    name    Project One
    And Dictionary Should Contain Item    ${resp.json()}    ref_id    project-one
    And Dictionary Should Contain Item    ${resp.json()}    warning_limit    80
    And Dictionary Should Contain Item    ${resp.json()}    success_limit    95
    [Teardown]    Teardown

"GET project" request should not contain not expose fields
    [Setup]    Setup
    ${resp} =    When Get Request    cir    /projects/project-one
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Not Contain Key    ${resp.json()}    id
    And Dictionary Should Not Contain Key    ${resp.json()}    token
    And Dictionary Should Not Contain Key    ${resp.json()}    email
    [Teardown]    Teardown

"GET Project" request with unknown project returns 404
    [Setup]    Setup
    ${resp} =    When Get Request    cir    /projects/X
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404
    [Teardown]    Teardown

"POST projects" request with limit values
    [Tags]    EDIT    DB
    [Setup]    Setup
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=Project Created    email=${EMAIL_TEST}    warning_limit=90    success_limit=100
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Contain Item    ${resp.json()}    name    Project Created
    And Dictionary Should Contain Item    ${resp.json()}    ref_id    project-created
    And Dictionary Should Contain Item    ${resp.json()}    warning_limit    90
    And Dictionary Should Contain Item    ${resp.json()}    success_limit    100
    Check If Exists In Database    select id from cir_project where name="Project Created" and CHAR_LENGTH(token) = 38 and email="${EMAIL_TEST}" and warning_limit=90 and success_limit=100
    [Teardown]    Teardown

"POST projects" request without limit values
    [Tags]    EDIT    DB
    [Setup]    Setup
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=Project Created    email=${EMAIL_TEST}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Contain Item    ${resp.json()}    name    Project Created
    And Dictionary Should Contain Item    ${resp.json()}    ref_id    project-created
    And Dictionary Should Contain Item    ${resp.json()}    warning_limit    ${DEFAULT_WARNING_LIMIT}
    And Dictionary Should Contain Item    ${resp.json()}    success_limit    ${DEFAULT_SUCCESS_LIMIT}
    Check If Exists In Database    select id from cir_project where name="Project Created" and CHAR_LENGTH(token) = 38 and email="${EMAIL_TEST}" and warning_limit=${DEFAULT_WARNING_LIMIT} and success_limit=${DEFAULT_SUCCESS_LIMIT}
    [Teardown]    Teardown

"POST projects" request with duplicate refId increments it by one
    [Tags]    EDIT
    [Setup]    Setup
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=Project-One    email=${EMAIL_TEST}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Contain Item    ${resp.json()}    name    Project-One
    And Dictionary Should Contain Item    ${resp.json()}    ref_id    project-one-1
    And Dictionary Should Contain Item    ${resp.json()}    warning_limit    ${DEFAULT_WARNING_LIMIT}
    And Dictionary Should Contain Item    ${resp.json()}    success_limit    ${DEFAULT_SUCCESS_LIMIT}
    [Teardown]    Teardown

"POST projects" request save date of creation
    [Tags]    EDIT    DB
    [Setup]    Setup
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=Project Created    email=${EMAIL_TEST}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    @{queryResults} =    Query    select created from cir_project where name="Project Created" and email="${EMAIL_TEST}" and warning_limit=${DEFAULT_WARNING_LIMIT} and success_limit=${DEFAULT_SUCCESS_LIMIT}
    And Time should be between 1 minute before and now    ${queryResults[0][0]}
    [Teardown]    Teardown

"POST projects" request send an email with greetings and link to documentation
    [Tags]    EDIT    MAIL    GUI
    [Setup]    Setup
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=Project Created    email=${EMAIL_TEST}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    ${content}=    When I get content of last email
    Then Should Contain    ${content}    Welcome to ci-report!
    ${link} =    When I get href from html link id    ${content}    a-doc
    And Goto    ${link}
    Then Page Should Contain    ci-report API documentation
    [Teardown]    Teardown

"POST projects" request send an email with link to project dashboard and API token
    [Tags]    EDIT    DB    MAIL    GUI
    [Setup]    Setup
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=Project Created    email=${EMAIL_TEST}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    ${content}=    When I get content of last email
    ${link} =    And I get href from html link id    ${content}    a-project
    And Goto    ${link}
    Then Page Should Contain    Project Created
    ${line}=    When Get Lines Containing String    ${content}    token
    ${token} =    And Get Regexp Matches    ${line}    ">(.*?)</div>    1
    @{queryResults} =    And Query    select token from cir_project where name="Project Created" and email="${EMAIL_TEST}" and warning_limit=${DEFAULT_WARNING_LIMIT} and success_limit=${DEFAULT_SUCCESS_LIMIT}
    Then Should Be Equal    ${queryResults[0][0]}    ${token[0]}
    [Teardown]    Teardown

"POST projects" request without name returns bad request message
    [Setup]    Setup
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    email=${EMAIL_TEST}    warning_limit=90    success_limit=100
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    name
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value should not be blank.
    [Teardown]    Teardown

"POST projects" request without email returns bad request message
    [Setup]    Setup
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=Invalid Project    warning_limit=90    success_limit=100
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    email
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value should not be blank.
    [Teardown]    Teardown

"POST projects" request with invalid email adress returns bad request message
    [Setup]    Setup
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=Invalid Project    email=.@example.com    warning_limit=90    success_limit=100
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    email
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value is not a valid email address.
    [Teardown]    Teardown

"POST projects" request with invalid warning limit returns bad request message
    [Setup]    Setup
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=Invalid Project    email=${EMAIL_TEST}    warning_limit=wrong_limit    success_limit=100
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    email
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value is not a valid email address.
    [Teardown]    Teardown

"POST projects" request with an existing name returns bad request message
    [Setup]    Setup
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=Project One    email=${EMAIL_TEST}    warning_limit=90    success_limit=100
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    name
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value is already used.
    [Teardown]    Teardown

"POST projects" request should not contain not expose fields
    [Tags]    EDIT    DB
    [Setup]    Setup
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=Project Created    email=${EMAIL_TEST}    warning_limit=90    success_limit=100
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Not Contain Key    ${resp.json()}    id
    And Dictionary Should Not Contain Key    ${resp.json()}    token
    And Dictionary Should Not Contain Key    ${resp.json()}    email
    [Teardown]    Teardown
