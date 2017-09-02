*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/api.txt

*** Test Cases ***
"GET Projects" request contains Project One
    ${resp} =    When Get Request    cir    /projects
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Response content should have x Items    ${resp.content}    6
    And Label item of list should be equal    ${resp.content}    "name"    ${P1.name}
    And Label item of list should be equal    ${resp.content}    "warning_limit"    ${P1.warning_limit}
    And Label item of list should be equal    ${resp.content}    "success_limit"    ${P1.success_limit}

"GET Projects" request should not contain not expose fields
    ${resp} =    When Get Request    cir    /projects
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Item of list should not countains label    ${resp.content}    id
    And Item of list should not countains label    ${resp.content}    token
    And Item of list should not countains label    ${resp.content}    email

"GET Projects" request with unknown route returns "404" error
    ${resp} =    When Get Request    cir    /projects/
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET project" request returns public project data
    ${resp} =    When Get Request    cir    /projects/${P1.refid}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    name    ${P1.name}
    And Dictionary Should Contain Item    ${resp.json()}    ref_id    ${P1.refid}
    And Dictionary Should Contain Item    ${resp.json()}    warning_limit    ${P1.warning_limit}
    And Dictionary Should Contain Item    ${resp.json()}    success_limit    ${P1.success_limit}

"GET project" request should not contain not expose fields
    ${resp} =    When Get Request    cir    /projects/${P1.refid}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Not Contain Key    ${resp.json()}    id
    And Dictionary Should Not Contain Key    ${resp.json()}    token
    And Dictionary Should Not Contain Key    ${resp.json()}    email

"GET Project" request with unknown project returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/X
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"POST projects" request with limit values returns private data
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=${P0.name}    email=${P0.email}    warning_limit=${P0.warning_limit}    success_limit=${P0.success_limit}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Contain Item    ${resp.json()}    name    ${P0.name}
    And Dictionary Should Contain Item    ${resp.json()}    ref_id    ${P0.refid}
    And Dictionary Should Contain Item    ${resp.json()}    warning_limit    ${P0.warning_limit}
    And Dictionary Should Contain Item    ${resp.json()}    success_limit    ${P0.success_limit}
    And Dictionary Should Contain Item    ${resp.json()}    email    ${P0.email}
    Check If Exists In Database    select id from cir_project where name="${P0.name}" and CHAR_LENGTH(token) = 38 and email="${P0.email}" and warning_limit=${P0.warning_limit} and success_limit=${P0.success_limit}

"POST projects" request with duplicate refId increments it by one
    [Tags]    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=Project-One    email=${P0.email}    warning_limit=${P0.warning_limit}    success_limit=${P0.success_limit}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Contain Item    ${resp.json()}    name    Project-One
    And Dictionary Should Contain Item    ${resp.json()}    ref_id    project-one-1
    And Dictionary Should Contain Item    ${resp.json()}    warning_limit    ${P0.warning_limit}
    And Dictionary Should Contain Item    ${resp.json()}    success_limit    ${P0.success_limit}
    And Dictionary Should Contain Item    ${resp.json()}    email    ${P0.email}

"POST projects" request save date of creation
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=${P0.name}    email=${P0.email}    warning_limit=${P0.warning_limit}    success_limit=${P0.success_limit}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    @{queryResults} =    Query    select created from cir_project where name="${P0.name}" and email="${P0.email}" and warning_limit=${P0.warning_limit} and success_limit=${P0.success_limit}
    And Time should be between 1 minute before and now    ${queryResults[0][0]}

"POST projects" request send an email with greetings and link to documentation
    [Tags]    EDIT    MAIL    GUI
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=${P0.name}    email=${P0.email}    warning_limit=${P0.warning_limit}    success_limit=${P0.success_limit}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    ${content}=    When I get content of last email
    Then Should Contain    ${content}    Welcome to ci-report!
    ${link} =    When I get href from html link id    ${content}    a-doc
    And Goto    ${link}
    Then Page Should Contain    ci-report API documentation

"POST projects" request send an email with link to project dashboard and API token
    [Tags]    EDIT    DB    MAIL    GUI
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=${P0.name}    email=${P0.email}    warning_limit=${P0.warning_limit}    success_limit=${P0.success_limit}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    ${content}=    When I get content of last email
    ${link} =    And I get href from html link id    ${content}    a-project
    And Goto    ${link}
    Then Page Should Contain    Project Created
    ${line}=    When Get Lines Containing String    ${content}    token
    ${token} =    And Get Regexp Matches    ${line}    ">(.*?)</div>    1
    @{queryResults} =    And Query    select token from cir_project where name="${P0.name}" and email="${P0.email}" and warning_limit=${P0.warning_limit} and success_limit=${P0.success_limit}
    Then Should Be Equal    ${queryResults[0][0]}    ${token[0]}

"POST projects" request should not contain not expose fields
    [Tags]    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=${P0.name}    email=${P0.email}    warning_limit=${P0.warning_limit}    success_limit=${P0.success_limit}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Not Contain Key    ${resp.json()}    id
    And Dictionary Should Not Contain Key    ${resp.json()}    token

"POST projects" request without name returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    email=${P0.email}    warning_limit=${P0.warning_limit}    success_limit=${P0.success_limit}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    name
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value should not be blank.

"POST projects" request without email returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=${P0.name}    warning_limit=${P0.warning_limit}    success_limit=${P0.success_limit}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    email
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value should not be blank.

"POST projects" request without warning limit returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=${P0.name}    email=${P0.email}    success_limit=${P0.success_limit}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    warningLimit
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    warning_limit value should not be blank.

"POST projects" request without success limit returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=${P0.name}    email=${P0.email}    warning_limit=${P0.warning_limit}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    successLimit
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    success_limit value should not be blank.

"POST projects" request with invalid email adress returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=${P0.name}    email=.@example.com    warning_limit=${P0.warning_limit}    success_limit=${P0.success_limit}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    email
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value is not a valid email address.

"POST projects" request with an existing name returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=Project One    email=${P0.email}    warning_limit=${P0.warning_limit}    success_limit=${P0.success_limit}
    ${resp} =    And Post Request    cir    /projects    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    name
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value is already used.

"PUT projects" request updates Project One
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    name=${P1M.name}    email=${P1M.email}    warning_limit=${P1M.warning_limit}    success_limit=${P1M.success_limit}
    ${resp} =    And Put Request    cir    /projects/${P1.refid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    name    ${P1M.name}
    And Dictionary Should Contain Item    ${resp.json()}    ref_id    ${P1.refid}
    And Dictionary Should Contain Item    ${resp.json()}    warning_limit    ${P1M.warning_limit}
    And Dictionary Should Contain Item    ${resp.json()}    success_limit    ${P1M.success_limit}
    And Dictionary Should Contain Item    ${resp.json()}    email    ${P1M.email}
    Check If Exists In Database    select id from cir_project where refid="${P1.refid}" and name="${P1M.name}" and token="${P1.token}" and email="${P1M.email}" and warning_limit=${P1M.warning_limit} and success_limit=${P1M.success_limit}

"PUT projects" request should not change token and refid values
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    ref_id=project    token=XXXX    name=${P1M.name}    email=${P1M.email}    warning_limit=${P1M.warning_limit}
    ...    success_limit=${P1M.success_limit}
    ${resp} =    And Put Request    cir    /projects/${P1.refid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    name    ${P1M.name}
    And Dictionary Should Contain Item    ${resp.json()}    ref_id    ${P1.refid}
    And Dictionary Should Contain Item    ${resp.json()}    warning_limit    ${P1M.warning_limit}
    And Dictionary Should Contain Item    ${resp.json()}    success_limit    ${P1M.success_limit}
    And Dictionary Should Contain Item    ${resp.json()}    email    ${P1M.email}
    Check If Exists In Database    select id from cir_project where refid="${P1.refid}" and name="${P1M.name}" and token="${P1.token}" and email="${P1M.email}" and warning_limit=${P1M.warning_limit} and success_limit=${P1M.success_limit}

"PUT projects" request should not contain not expose fields
    [Tags]    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    name=${P1M.name}    email=${P1M.email}    warning_limit=${P1M.warning_limit}    success_limit=${P1M.success_limit}
    ${resp} =    And Put Request    cir    /projects/${P1.refid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Not Contain Key    ${resp.json()}    id
    And Dictionary Should Not Contain Key    ${resp.json()}    token

"PUT projects" request with wrong token returns HTTP "401" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=XXX
    &{data} =    And Create Dictionary    name=${P1M.name}    email=${P1M.email}    warning_limit=${P1M.warning_limit}    success_limit=${P1M.success_limit}
    ${resp} =    And Put Request    cir    /projects/${P1.refid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    401
    And Dictionary Should Contain Item    ${resp.json()}    code    401
    And Dictionary Should Contain Item    ${resp.json()}    message    Invalid token

"PUT projects" request without token returns HTTP "401" error
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    name=${P1M.name}    email=${P1M.email}    warning_limit=${P1M.warning_limit}    success_limit=${P1M.success_limit}
    ${resp} =    And Put Request    cir    /projects/${P1.refid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    401
    And Dictionary Should Contain Item    ${resp.json()}    code    401
    And Dictionary Should Contain Item    ${resp.json()}    message    Invalid token

"PUT projects" request without name returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    email=${P1M.email}    warning_limit=${P1M.warning_limit}    success_limit=${P1M.success_limit}
    ${resp} =    And Put Request    cir    /projects/${P1.refid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    name
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value should not be blank.

"PUT projects" request without email returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    name=${P1M.name}    warning_limit=${P1M.warning_limit}    success_limit=${P1M.success_limit}
    ${resp} =    And Put Request    cir    /projects/${P1.refid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    email
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value should not be blank.

"PUT projects" request without warning limit returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    name=${P1M.name}    email=${P1M.email}    success_limit=${P1M.success_limit}
    ${resp} =    And Put Request    cir    /projects/${P1.refid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    warningLimit
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    warning_limit value should not be blank.

"PUT projects" request without success limit returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    name=${P1M.name}    email=${P1M.email}    warning_limit=${P1M.warning_limit}
    ${resp} =    And Put Request    cir    /projects/${P1.refid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    successLimit
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    success_limit value should not be blank.

"PUT projects" request with invalid email adress returns HTTP "400" error
    [Tags]    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    name=${P1M.name}    email=.@example.com    warning_limit=${P1M.warning_limit}    success_limit=${P1M.success_limit}
    ${resp} =    And Put Request    cir    /projects/${P1.refid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    email
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value is not a valid email address.

"PUT projects" request with an existing name returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    name=Project Six    email=${P1M.email}    warning_limit=${P1M.warning_limit}    success_limit=${P1M.success_limit}
    ${resp} =    And Put Request    cir    /projects/${P1.refid}    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    name
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value is already used.
