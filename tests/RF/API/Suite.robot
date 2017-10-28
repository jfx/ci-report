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
    And Label item of list should be equal    ${resp.content}    "warning"    ${P1C4S1.warning}
    And Label item of list should be equal    ${resp.content}    "success"    ${P1C4S1.success}
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
    And Dictionary Should Contain Item    ${resp.json()}    warning    ${P1C4S1.warning}
    And Dictionary Should Contain Item    ${resp.json()}    success    ${P1C4S1.success}
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

"PUT suite limits" request returns HTTP "200" with suite data
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=${P2C3S1M.warning}    success=${P2C3S1M.success}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P2C3S1M.srefid}
    And Dictionary Should Contain Item    ${resp.json()}    name    ${P2C3S1M.name}
    And Dictionary Should Contain Item    ${resp.json()}    warning    ${P2C3S1M.warning}
    And Dictionary Should Contain Item    ${resp.json()}    success    ${P2C3S1M.success}
    And Dictionary Should Contain Item    ${resp.json()}    passed    ${P2C3S1M.passed}
    And Dictionary Should Contain Item    ${resp.json()}    failed    ${P2C3S1M.failed}
    And Dictionary Should Contain Item    ${resp.json()}    errored    ${P2C3S1M.errored}
    And Dictionary Should Contain Item    ${resp.json()}    skipped    ${P2C3S1M.skipped}
    And Dictionary Should Contain Item    ${resp.json()}    disabled    ${P2C3S1M.disabled}
    And Dictionary Should Contain Item    ${resp.json()}    duration    ${P2C3S1M.duration}
    And Dictionary Should Contain Item    ${resp.json()}    datetime    ${P2C3S1M.time_iso}
    ${cposition} =    Evaluate    ${P2C3S1M.crefid} - 1
    ${sposition} =    Evaluate    ${P2C3S1M.srefid} - 1
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id ="${P2C3S1M.pid}" and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition} and cir_suite.warning = ${P2C3S1M.warning} and cir_suite.success = ${P2C3S1M.success} and cir_suite.passed=${P2C3S1M.passed} and cir_suite.failed=${P2C3S1M.failed} and cir_suite.errored=${P2C3S1M.errored} and cir_suite.skipped=${P2C3S1M.skipped} and cir_suite.disabled=${P2C3S1M .disabled}

"PUT suite limits" request should not contain not expose fields
    [Tags]    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=${P2C3S1M.warning}    success=${P2C3S1M.success}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Not Contain Key    ${resp.json()}    id
    And Dictionary Should Not Contain Key    ${resp.json()}    position

"PUT suite limits" request with wrong token returns HTTP "401" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=XXX
    &{data} =    And Create Dictionary    warning=${P2C3S1M.warning}    success=${P2C3S1M.success}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    401

"PUT suite limits" request without token returns HTTP "401" error
    &{headers} =    When Create Dictionary    Content-Type=application/json
    &{data} =    And Create Dictionary    warning=${P2C3S1M.warning}    success=${P2C3S1M.success}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    401

"PUT suite limits" request with invalid type warning limit sets it to 0
    [Tags]    DB    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=XXX    success=${P2C3S1M.success}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P2C3S1M.srefid}
    And Dictionary Should Contain Item    ${resp.json()}    warning    0
    ${cposition} =    Evaluate    ${P2C3S1M.crefid} - 1
    ${sposition} =    Evaluate    ${P2C3S1M.srefid} - 1
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id ="${P2C3S1M.pid}" and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition} and cir_suite.warning = 0 and cir_suite.success = ${P2C3S1M.success} and cir_suite.passed=${P2C3S1M.passed} and cir_suite.failed=${P2C3S1M.failed} and cir_suite.errored=${P2C3S1M.errored} and cir_suite.skipped=${P2C3S1M.skipped} and cir_suite.disabled=${P2C3S1M .disabled}

"PUT suite limits" request with float type warning limit round it
    [Tags]    DB    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=79.7    success=${P2C3S1M.success}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P2C3S1M.srefid}
    And Dictionary Should Contain Item    ${resp.json()}    warning    79
    ${cposition} =    Evaluate    ${P2C3S1M.crefid} - 1
    ${sposition} =    Evaluate    ${P2C3S1M.srefid} - 1
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id ="${P2C3S1M.pid}" and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition} and cir_suite.warning = 79 and cir_suite.success = ${P2C3S1M.success} and cir_suite.passed=${P2C3S1M.passed} and cir_suite.failed=${P2C3S1M.failed} and cir_suite.errored=${P2C3S1M.errored} and cir_suite.skipped=${P2C3S1M.skipped} and cir_suite.disabled=${P2C3S1M .disabled}

"PUT suite limits" request with warning limit out of range returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=120    success=${P2C3S1M.success}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    warning
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value should be 100 or less.

"PUT suite limits" request without warning limit returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    success=${P2C3S1M.success}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    warning
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value should not be blank.

"PUT suite limits" request with invalid type success limit sets it to 0
    [Tags]    DB    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=${P2C3S1M.warning}    success=XXX
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P2C3S1M.srefid}
    And Dictionary Should Contain Item    ${resp.json()}    success    0
    ${cposition} =    Evaluate    ${P2C3S1M.crefid} - 1
    ${sposition} =    Evaluate    ${P2C3S1M.srefid} - 1
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id ="${P2C3S1M.pid}" and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition} and cir_suite.warning = ${P2C3S1M.warning} and cir_suite.success = 0 and cir_suite.passed=${P2C3S1M.passed} and cir_suite.failed=${P2C3S1M.failed} and cir_suite.errored=${P2C3S1M.errored} and cir_suite.skipped=${P2C3S1M.skipped} and cir_suite.disabled=${P2C3S1M .disabled}

"PUT suite limits" request with float type success limit round it
    [Tags]    DB    EDIT
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=${P2C3S1M.warning}    success=79,7
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    refid    ${P2C3S1M.srefid}
    And Dictionary Should Contain Item    ${resp.json()}    success    79
    ${cposition} =    Evaluate    ${P2C3S1M.crefid} - 1
    ${sposition} =    Evaluate    ${P2C3S1M.srefid} - 1
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id ="${P2C3S1M.pid}" and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition} and cir_suite.warning = ${P2C3S1M.warning} and cir_suite.success = 79 and cir_suite.passed=${P2C3S1M.passed} and cir_suite.failed=${P2C3S1M.failed} and cir_suite.errored=${P2C3S1M.errored} and cir_suite.skipped=${P2C3S1M.skipped} and cir_suite.disabled=${P2C3S1M .disabled}

"PUT suite limits" request with success limit out of range returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=${P2C3S1M.warning}    success=120
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    success
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value should be 100 or less.

"PUT suite limits" request without success limit returns HTTP "400" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=${P2C3S1M.warning}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    success
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value should not be blank.

"PUT suite limits" request with unknown project refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=${P2C3S1M.warning}    success=${P2C3S1M.success}
    ${resp} =    And Put Request    cir    /projects/X/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404

"PUT suite limits" request with unknown campaign refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=${P2C3S1M.warning}    success=${P2C3S1M.success}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/0/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"PUT suite limits" request with not numeric campaign refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=${P2C3S1M.warning}    success=${P2C3S1M.success}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/X/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"PUT suite limits" request with unknown suite refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=${P2C3S1M.warning}    success=${P2C3S1M.success}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/0/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"PUT suite limits" request with not numeric suite refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=${P2C3S1M.warning}    success=${P2C3S1M.success}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/X/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE suites" request removes suite 2 of campaign 4 of Project One
    [Tags]    EDIT    DB
    ${cposition} =    Evaluate    ${P1C4S2.crefid} - 1
    ${sposition} =    Evaluate    ${P1C4S2.srefid} - 1
    Check If Exists In Database    select id from cir_campaign where project_id ="${P1.id}" and position = ${cposition}
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id ="${P1.id}" and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition}
    Check If Exists In Database    select cir_test.id from cir_test, cir_suite, cir_campaign where project_id ="${P1.id}" and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.id = suite_id and cir_suite.position = ${sposition}
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    When Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    204
    Check If Exists In Database    select id from cir_campaign where project_id ="${P1.id}" and position = ${cposition}
    Check If Not Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id ="${P1.id}" and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition}
    Check If Not Exists In Database    select cir_test.id from cir_test, cir_suite, cir_campaign where project_id ="${P1.id}" and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.id = suite_id and cir_suite.position = ${sposition}

"DELETE suites" request with wrong token returns HTTP "401" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=XXX
    ${resp} =    When Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    401
    And Dictionary Should Contain Item    ${resp.json()}    code    401
    And Dictionary Should Contain Item    ${resp.json()}    message    Invalid token

"DELETE suites" request without token returns HTTP "401" error
    ${resp} =    When Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}
    Then Should Be Equal As Strings    ${resp.status_code}    401
    And Dictionary Should Contain Item    ${resp.json()}    code    401
    And Dictionary Should Contain Item    ${resp.json()}    message    Invalid token

"DELETE suites" request with unknown project refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    When Delete Request    cir    /projects/X/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}    headers=${headers}
    Then Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE suites" request with unknown campaign refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    And Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/0/suites/${P1C4S2.srefid}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE suites" request with not numeric campaign refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    And Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/X/suites/${P1C4S2.srefid}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE suites" request with unknown suite refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    And Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/0    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE suites" request with not numeric suite refid returns HTTP "404" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    And Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/X    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"DELETE suites" request without campaign refid returns HTTP "405" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    And Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    405
