*** Settings ***
Suite Setup       Load DB
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/api.txt

*** Test Cases ***
"GET Suites" request on Project One campaign 4 contains its 2 suites
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Response content should have x Items    ${resp.content}    2
    And Label item of list should be equal    ${resp.content}    "refid"    ${P1C4S1.srefid}
    And Label item of list should be equal    ${resp.content}    "name"    ${P1C4S1.name}
    And Label item of list should be equal    ${resp.content}    "passed"    ${P1C4S1.passed}
    And Label item of list should be equal    ${resp.content}    "failed"    ${P1C4S1.failed}
    And Label item of list should be equal    ${resp.content}    "errored"    ${P1C4S1.errored}
    And Label item of list should be equal    ${resp.content}    "skipped"    ${P1C4S1.skipped}
    And Label item of list should be equal    ${resp.content}    "disabled"    ${P1C4S1.disabled}
    And Label item of list should be equal    ${resp.content}    "duration"    ${P1C4S1.duration}
    And Label item of list should be equal    ${resp.content}    "datetime"    ${P1C4S1.time_iso}

"GET Suites" request should not contain not expose fields
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Item of list should not countains label    ${resp.content}    id
    And Item of list should not countains label    ${resp.content}    position

"GET Suites" request with unknown project returns "404" error
    ${resp} =    When Get Request    cir    /projects/XXX/campaigns/${P1C4S1.crefid}/suites
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET Suites" request with unknown campaign for project returns "404" error
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/0/suites
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET Suites" request with unknown route returns "404" error
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET suite" request returns suite data
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/${P1C4S1.srefid}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C4S1.srefid}
    And Dictionary Should Contain Item    ${resp.json()}    name    ${P1C4S1.name}
    And Dictionary Should Contain Item    ${resp.json()}    passed    ${P1C4S1.passed}
    And Dictionary Should Contain Item    ${resp.json()}    failed    ${P1C4S1.failed}
    And Dictionary Should Contain Item    ${resp.json()}    errored    ${P1C4S1.errored}
    And Dictionary Should Contain Item    ${resp.json()}    skipped    ${P1C4S1.skipped}
    And Dictionary Should Contain Item    ${resp.json()}    disabled    ${P1C4S1.disabled}
    And Dictionary Should Contain Item    ${resp.json()}    duration    ${P1C4S1.duration}
    And Dictionary Should Contain Item    ${resp.json()}    datetime    ${P1C4S1.time_iso}

"GET suite" request should not contain not expose fields
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/${P1C4S1.srefid}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Not Contain Key    ${resp.json()}    id
    And Dictionary Should Not Contain Key    ${resp.json()}    position

"GET suite" request with unknown project refid returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/XXX/campaigns/${P1C4S1.crefid}/suites/${P1C4S1.srefid}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET suite" request with unknown campaign refid returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/0/suites/${P1C4S1.srefid}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET suite" request with unknown suite refid returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/0
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET suite" request with not numeric campaign refid returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/X/suites/${P1C4S1.srefid}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"GET suite" request with not numeric suite refid returns HTTP "404" error
    ${resp} =    When Get Request    cir    /projects/${P1C4S1.prefid}/campaigns/${P1C4S1.crefid}/suites/X
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"POST suites" request returns HTTP "201" with suite data
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    warning=${P1C0.warning}    success=${P1C0.success}    start=${P1C0.start_sql}    end=${P1C0.end_sql}
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C0.crefid}
    And Dictionary Should Contain Item    ${resp.json()}    warning    ${P1C0.warning}
    And Dictionary Should Contain Item    ${resp.json()}    success    ${P1C0.success}
    And Dictionary Should Contain Item    ${resp.json()}    passed    ${P1C0.passed}
    And Dictionary Should Contain Item    ${resp.json()}    failed    ${P1C0.failed}
    And Dictionary Should Contain Item    ${resp.json()}    errored    ${P1C0.errored}
    And Dictionary Should Contain Item    ${resp.json()}    skipped    ${P1C0.skipped}
    And Dictionary Should Contain Item    ${resp.json()}    disabled    ${P1C0.disabled}
    And Dictionary Should Contain Item    ${resp.json()}    start    ${P1C0.start_iso}
    Check If Exists In Database    select id from cir_campaign where project_id=${P1C0.pid} and position=${P1C0.position} and warning=${P1C0.warning} and success=${P1C0.success} and passed=${P1C0.passed} and failed=${P1C0.failed} and errored=${P1C0.errored} and skipped=${P1C0.skipped} and disabled=${P1C0.disabled} and start="${P1C0.start_sql}" and end="${P1C0.end_sql}"

"POST campaigns" request with default values returns HTTP "201" with campaign data
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C0.crefid}
    And Dictionary Should Contain Item    ${resp.json()}    warning    ${P1.warning}
    And Dictionary Should Contain Item    ${resp.json()}    success    ${P1.success}
    And Dictionary Should Contain Item    ${resp.json()}    passed    ${P1C0.passed}
    And Dictionary Should Contain Item    ${resp.json()}    failed    ${P1C0.failed}
    And Dictionary Should Contain Item    ${resp.json()}    errored    ${P1C0.errored}
    And Dictionary Should Contain Item    ${resp.json()}    skipped    ${P1C0.skipped}
    And Dictionary Should Contain Item    ${resp.json()}    disabled    ${P1C0.disabled}
    And Dictionary Should Contain Key    ${resp.json()}    start
    And Dictionary Should Not Contain Key    ${resp.json()}    end
    Check If Exists In Database    select id from cir_campaign where project_id=${P1C0.pid} and position=${P1C0.position} and warning=${P1.warning} and success=${P1.success} and passed=${P1C0.passed} and failed=${P1C0.failed} and errored=${P1C0.errored} and skipped=${P1C0.skipped} and disabled=${P1C0.disabled} and end is null

"POST campaigns" request without start datetime sets it to actual datetime
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    @{queryResults} =    Query    select start from cir_campaign where project_id=${P1C0.pid} and position=${P1C0.position}
    And Time should be between 1 minute before and now    ${queryResults[0][0]}

"POST campaigns" request should not contain not expose fields
    [Tags]    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Not Contain Key    ${resp.json()}    id
    And Dictionary Should Not Contain Key    ${resp.json()}    position

"POST campaigns" request with wrong token returns HTTP "401" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=XXX
    &{data} =    And Create Dictionary    warning=${P1C0.warning}    success=${P1C0.success}    start=${P1C0.start_sql}    end=${P1C0.end_sql}
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    401

"POST campaigns" request without token returns HTTP "401" error
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    warning=${P1C0.warning}    success=${P1C0.success}    start=${P1C0.start_sql}    end=${P1C0.end_sql}
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    401

"POST campaigns" request with invalid type warning limit sets it to 0
    [Tags]    DB    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    warning=XXX
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C0.crefid}
    And Dictionary Should Contain Item    ${resp.json()}    warning    0
    Check If Exists In Database    select id from cir_campaign where project_id=${P1C0.pid} and position=${P1C0.position} and warning=0 and success=${P1.success} and passed=${P1C0.passed} and failed=${P1C0.failed} and errored=${P1C0.errored} and skipped=${P1C0.skipped} and disabled=${P1C0.disabled} and end is null

"POST campaigns" request with float type warning limit round it
    [Tags]    DB    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    warning=79.7
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C0.crefid}
    And Dictionary Should Contain Item    ${resp.json()}    warning    79
    Check If Exists In Database    select id from cir_campaign where project_id=${P1C0.pid} and position=${P1C0.position} and warning=79 and success=${P1.success} and passed=${P1C0.passed} and failed=${P1C0.failed} and errored=${P1C0.errored} and skipped=${P1C0.skipped} and disabled=${P1C0.disabled} and end is null

"POST campaigns" request with warning limit out of range returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    warning=120
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    warning
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value should be 100 or less.

"POST campaigns" request with invalid type success limit sets it to 0
    [Tags]    DB    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    success=XXX
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C0.crefid}
    And Dictionary Should Contain Item    ${resp.json()}    success    0
    Check If Exists In Database    select id from cir_campaign where project_id=${P1C0.pid} and position=${P1C0.position} and warning=${P1.warning} and success=0 and passed=${P1C0.passed} and failed=${P1C0.failed} and errored=${P1C0.errored} and skipped=${P1C0.skipped} and disabled=${P1C0.disabled} and end is null

"POST campaigns" request with float type success limit round it
    [Tags]    DB    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    success=79.7
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P1C0.crefid}
    And Dictionary Should Contain Item    ${resp.json()}    success    79
    Check If Exists In Database    select id from cir_campaign where project_id=${P1C0.pid} and position=${P1C0.position} and warning=${P1.warning} and success=79 and passed=${P1C0.passed} and failed=${P1C0.failed} and errored=${P1C0.errored} and skipped=${P1C0.skipped} and disabled=${P1C0.disabled} and end is null

"POST campaigns" request with success limit out of range returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    success=120
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    success
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value should be 100 or less.

"POST campaigns" request with invalid start datetime returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    start=2017-07-01
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Should Contain    ${resp.json()['message']}    Invalid datetime

"POST campaigns" request with invalid end datetime returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    end=2017-07-01
    ${resp} =    And Post Request    cir    /projects/${P1C0.prefid}/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Should Contain    ${resp.json()['message']}    Invalid datetime

"POST campaigns" request with unknown project refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    warning=${P1C0.warning}    success=${P1C0.success}    start=${P1C0.start_sql}    end=${P1C0.end_sql}
    ${resp} =    And Post Request    cir    /projects/X/campaigns    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
