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

"POST suites" upload junit file request and limits returns HTTP "201" with suite data
    [Tags]    EDIT    DB
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-ok1.xml    mime_type=application/xml
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    warning=${P1C4S3Ja.warning}    success=${P1C4S3Ja.success}
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S3Ja.prefid}/campaigns/${P1C4S3Ja.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Response content should have x Items    ${resp.content}    2
    And Label item of list should be equal    ${resp.content}    "refid"    ${P1C4S3Ja.srefid}
    And Label item of list should be equal    ${resp.content}    "refid"    ${P1C4S4Ja.srefid}
    And Label item of list should be equal    ${resp.content}    "name"    ${P1C4S3Ja.name}
    And Label item of list should be equal    ${resp.content}    "name"    ${P1C4S4Ja.name}
    ${cposition} =    Evaluate    ${P1C4S3Ja.crefid} - 1
    ${s3position} =    Evaluate    ${P1C4S3Ja.srefid} - 1
    ${s4position} =    Evaluate    ${P1C4S4Ja.srefid} - 1
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id =${P1C4S3Ja.pid} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${s3position} and cir_suite.warning = ${P1C4S3Ja.warning} and cir_suite.success = ${P1C4S3Ja.success} and cir_suite.passed=${P1C4S3Ja.passed} and cir_suite.failed=${P1C4S3Ja.failed} and cir_suite.errored=${P1C4S3Ja.errored} and cir_suite.skipped=${P1C4S3Ja.skipped} and cir_suite.disabled=${P1C4S3Ja.disabled}
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id =${P1C4S4Ja.pid} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${s4position} and cir_suite.warning = ${P1C4S4Ja.warning} and cir_suite.success = ${P1C4S4Ja.success} and cir_suite.passed=${P1C4S4Ja.passed} and cir_suite.failed=${P1C4S4Ja.failed} and cir_suite.errored=${P1C4S4Ja.errored} and cir_suite.skipped=${P1C4S4Ja.skipped} and cir_suite.disabled=${P1C4S4Ja.disabled}
    Check If Exists In Database    select id from cir_campaign where project_id=${P1C4Ja.pid} and position=${cposition} and passed=${P1C4Ja.passed} and failed=${P1C4Ja.failed} and errored=${P1C4Ja.errored} and skipped=${P1C4Ja.skipped} and disabled=${P1C4Ja.disabled}
    Upload tmp directory should be empty

"POST suites" upload junit file request and without limits returns HTTP "201" with suite data and sets project limits
    [Tags]    EDIT    DB
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-ok1.xml
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4Ja.prefid}/campaigns/${P1C4Ja.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Response content should have x Items    ${resp.content}    2
    And Label item of list should be equal    ${resp.content}    "refid"    ${P1C4S3Ja.srefid}
    And Label item of list should be equal    ${resp.content}    "refid"    ${P1C4S4Ja.srefid}
    And Label item of list should be equal    ${resp.content}    "name"    ${P1C4S3Ja.name}
    And Label item of list should be equal    ${resp.content}    "name"    ${P1C4S4Ja.name}
    ${cposition} =    Evaluate    ${P1C4Ja.crefid} - 1
    ${s3position} =    Evaluate    ${P1C4S3Ja.srefid} - 1
    ${s4position} =    Evaluate    ${P1C4S4Ja.srefid} - 1
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id =${P1C4S3Ja.pid} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${s3position} and cir_suite.warning = ${P1.warning} and cir_suite.success = ${P1.success} and cir_suite.passed=${P1C4S3Ja.passed} and cir_suite.failed=${P1C4S3Ja.failed} and cir_suite.errored=${P1C4S3Ja.errored} and cir_suite.skipped=${P1C4S3Ja.skipped} and cir_suite.disabled=${P1C4S3Ja.disabled}
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id =${P1C4S4Ja.pid} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${s4position} and cir_suite.warning = ${P1.warning} and cir_suite.success = ${P1.success} and cir_suite.passed=${P1C4S4Ja.passed} and cir_suite.failed=${P1C4S4Ja.failed} and cir_suite.errored=${P1C4S4Ja.errored} and cir_suite.skipped=${P1C4S4Ja.skipped} and cir_suite.disabled=${P1C4S4Ja.disabled}
    Check If Exists In Database    select id from cir_campaign where project_id=${P1C4Ja.pid} and position=${cposition} and passed=${P1C4Ja.passed} and failed=${P1C4Ja.failed} and errored=${P1C4Ja.errored} and skipped=${P1C4Ja.skipped} and disabled=${P1C4Ja.disabled}
    Upload tmp directory should be empty

"POST suites" upload junit file request could change campaign status
    [Tags]    EDIT    DB
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-ok1.xml
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4Ja.prefid}/campaigns/${P1C4Ja.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    ${cposition} =    Evaluate    ${P1C4Ja.crefid} - 1
    Check If Exists In Database    select cir_campaign.id from cir_campaign where project_id = ${P1C4Ja.pid} and cir_campaign.position = ${cposition} and status = 4

"POST suites" upload junit file request should not contain not expose fields
    [Tags]    EDIT
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-ok1.xml
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4Ja.prefid}/campaigns/${P1C4Ja.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Item of list should not countains label    ${resp.content}    id
    And Item of list should not countains label    ${resp.content}    position

"POST suites" upload junit file request with wrong junit syntax file returns HTTP "400"
    [Tags]    EDIT
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-err-syntax.xml
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4Ja.prefid}/campaigns/${P1C4Ja.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    level    Error
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    Element 'testXcase': This element is not expected.\n
    Upload tmp directory should be empty

"POST suites" upload junit file request with wrong token returns HTTP "401" error
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-ok1.xml
    &{headers} =    When Create Dictionary    X-CIR-TKN=XXX
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4Ja.prefid}/campaigns/${P1C4Ja.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    401

"POST suites" upload junit file request without token returns HTTP "401" error
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-ok1.xml
    &{headers} =    When Create Dictionary
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4Ja.prefid}/campaigns/${P1C4Ja.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    401

"POST suites" upload junit file request with invalid type warning limit sets it to 0
    [Tags]    DB    EDIT
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-ok2.xml
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    warning=XXX    success=${P1C4S3Jb.success}
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S3Jb.prefid}/campaigns/${P1C4S3Jb.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Response content should have x Items    ${resp.content}    1
    And Label item of list should be equal    ${resp.content}    "refid"    ${P1C4S3Jb.srefid}
    And Label item of list should be equal    ${resp.content}    "name"    ${P1C4S3Jb.name}
    And Label item of list should be equal    ${resp.content}    "warning"    0
    And Label item of list should be equal    ${resp.content}    "success"    ${P1C4S3Jb.success}
    ${cposition} =    Evaluate    ${P1C4S3Jb.crefid} - 1
    ${sposition} =    Evaluate    ${P1C4S3Jb.srefid} - 1
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id =${P1C4S3Jb.pid} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition} and cir_suite.warning = 0 and cir_suite.success = ${P1C4S3Jb.success} and cir_suite.passed=${P1C4S3Jb.passed} and cir_suite.failed=${P1C4S3Jb.failed} and cir_suite.errored=${P1C4S3Jb.errored} and cir_suite.skipped=${P1C4S3Jb.skipped} and cir_suite.disabled=${P1C4S3Jb.disabled}

"POST suites" upload junit file request with float type warning limit round it
    [Tags]    DB    EDIT
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-ok2.xml
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    warning=79.7    success=${P1C4S3Jb.success}
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S3Jb.prefid}/campaigns/${P1C4S3Jb.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Response content should have x Items    ${resp.content}    1
    And Label item of list should be equal    ${resp.content}    "refid"    ${P1C4S3Jb.srefid}
    And Label item of list should be equal    ${resp.content}    "name"    ${P1C4S3Jb.name}
    And Label item of list should be equal    ${resp.content}    "warning"    79
    And Label item of list should be equal    ${resp.content}    "success"    ${P1C4S3Jb.success}
    ${cposition} =    Evaluate    ${P1C4S3Jb.crefid} - 1
    ${sposition} =    Evaluate    ${P1C4S3Jb.srefid} - 1
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id =${P1C4S3Jb.pid} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition} and cir_suite.warning = 79 and cir_suite.success = ${P1C4S3Jb.success} and cir_suite.passed=${P1C4S3Jb.passed} and cir_suite.failed=${P1C4S3Jb.failed} and cir_suite.errored=${P1C4S3Jb.errored} and cir_suite.skipped=${P1C4S3Jb.skipped} and cir_suite.disabled=${P1C4S3Jb.disabled}

"POST suites" upload junit file request with warning limit out of range returns HTTP "400" error
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-ok2.xml
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    warning=120    success=${P1C4S3Jb.success}
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S3Jb.prefid}/campaigns/${P1C4S3Jb.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    warning
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value should be 100 or less.

"POST suites" upload junit file request with invalid type success limit sets it to 0
    [Tags]    DB    EDIT
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-ok2.xml
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    warning=${P1C4S3Jb.warning}    success=XXX
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S3Jb.prefid}/campaigns/${P1C4S3Jb.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Response content should have x Items    ${resp.content}    1
    And Label item of list should be equal    ${resp.content}    "refid"    ${P1C4S3Jb.srefid}
    And Label item of list should be equal    ${resp.content}    "name"    ${P1C4S3Jb.name}
    And Label item of list should be equal    ${resp.content}    "warning"    ${P1C4S3Jb.warning}
    And Label item of list should be equal    ${resp.content}    "success"    0
    ${cposition} =    Evaluate    ${P1C4S3Jb.crefid} - 1
    ${sposition} =    Evaluate    ${P1C4S3Jb.srefid} - 1
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id =${P1C4S3Jb.pid} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition} and cir_suite.warning = ${P1C4S3Jb.warning} and cir_suite.success = 0 and cir_suite.passed=${P1C4S3Jb.passed} and cir_suite.failed=${P1C4S3Jb.failed} and cir_suite.errored=${P1C4S3Jb.errored} and cir_suite.skipped=${P1C4S3Jb.skipped} and cir_suite.disabled=${P1C4S3Jb.disabled}

"POST suites" upload junit file request with float type success limit round it
    [Tags]    DB    EDIT
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-ok2.xml
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    warning=${P1C4S3Jb.warning}    success=79.7
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S3Jb.prefid}/campaigns/${P1C4S3Jb.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    And Response content should have x Items    ${resp.content}    1
    And Label item of list should be equal    ${resp.content}    "refid"    ${P1C4S3Jb.srefid}
    And Label item of list should be equal    ${resp.content}    "name"    ${P1C4S3Jb.name}
    And Label item of list should be equal    ${resp.content}    "warning"    ${P1C4S3Jb.warning}
    And Label item of list should be equal    ${resp.content}    "success"    79
    ${cposition} =    Evaluate    ${P1C4S3Jb.crefid} - 1
    ${sposition} =    Evaluate    ${P1C4S3Jb.srefid} - 1
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id =${P1C4S3Jb.pid} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition} and cir_suite.warning = ${P1C4S3Jb.warning} and cir_suite.success = 79 and cir_suite.passed=${P1C4S3Jb.passed} and cir_suite.failed=${P1C4S3Jb.failed} and cir_suite.errored=${P1C4S3Jb.errored} and cir_suite.skipped=${P1C4S3Jb.skipped} and cir_suite.disabled=${P1C4S3Jb.disabled}

"POST suites" upload junit file request with success limit out of range returns HTTP "400" error
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-ok2.xml
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    warning=${P1C4S3Jb.warning}    success=120
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S3Jb.prefid}/campaigns/${P1C4S3Jb.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    success
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    This value should be 100 or less.

"POST suites" upload junit file request without file returns HTTP "400" error
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}    Content-Type=multipart/form-data
    &{data} =    And Create Dictionary    warning=${P1C4S3Jb.warning}    success=${P1C4S3Jb.success}
    ${resp}=    And Post Request    cir    /projects/${P1C4S3Jb.prefid}/campaigns/${P1C4S3Jb.crefid}/suites/junit    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    junitfile
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    A junit file must be specified.

"POST suites" upload junit file request with wrong file format returns HTTP "400" error
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/LICENSE.pdf
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary    warning=${P1C4S3Jb.warning}    success=${P1C4S3Jb.success}
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S3Jb.prefid}/campaigns/${P1C4S3Jb.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    400
    And Dictionary Should Contain Item    ${resp.json()[0]}    property_path    junitfile
    And Dictionary Should Contain Item    ${resp.json()[0]}    message    Please upload a valid XML file

"POST suites" upload junit file request with unknown project returns "404" error
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-ok1.xml
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/XXX/campaigns/${P1C4Ja.crefid}/suites/junit    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    404
    And Dictionary Should Contain Item    ${resp.json()}    code    404

"POST suites" upload junit file request with unknown campaign returns "404" error
    &{file} =    Create Dictionary    form_field=junitfile    path_file=${CURDIR}/../../files/junit-ok1.xml
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4Ja.prefid}/campaigns/0/suites/junit    ${file}    headers=${headers}    data=${data}
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
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id =${P2C3S1M.pid} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition} and cir_suite.warning = ${P2C3S1M.warning} and cir_suite.success = ${P2C3S1M.success} and cir_suite.passed=${P2C3S1M.passed} and cir_suite.failed=${P2C3S1M.failed} and cir_suite.errored=${P2C3S1M.errored} and cir_suite.skipped=${P2C3S1M.skipped} and cir_suite.disabled=${P2C3S1M .disabled}

"PUT suite limits" request could change campaign status from passed to warning
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=60    success=98
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    ${cposition} =    Evaluate    ${P2C3S1M.crefid} - 1
    Check If Exists In Database    select cir_campaign.id from cir_campaign where project_id = ${P2C3S1M.pid} and cir_campaign.position = ${cposition} and status = 2

"PUT suite limits" request could change campaign status from passed to failed
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=97    success=99
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    ${cposition} =    Evaluate    ${P2C3S1M.crefid} - 1
    Check If Exists In Database    select cir_campaign.id from cir_campaign where project_id = ${P2C3S1M.pid} and cir_campaign.position = ${cposition} and status = 4

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

"PUT suite limits" request without warning limit doesn't change warning limit value
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    success=${P2C3S1M.success}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    warning    ${P2C3S1.warning}
    And Dictionary Should Contain Item    ${resp.json()}    success    ${P2C3S1M.success}

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

"PUT suite limits" request without success limit doesn't change success limit value
    &{headers} =    When Create Dictionary    Content-Type=application/json    X-CIR-TKN=${P2.token}
    &{data} =    And Create Dictionary    warning=${P2C3S1M.warning}
    ${resp} =    And Put Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}/limits    data=${data}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    200
    And Dictionary Should Contain Item    ${resp.json()}    warning    ${P2C3S1M.warning}
    And Dictionary Should Contain Item    ${resp.json()}    success    ${P2C3S1.success}

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
    Check If Exists In Database    select id from cir_campaign where project_id =${P1.id} and position = ${cposition}
    Check If Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id =${P1.id} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition}
    Check If Exists In Database    select cir_test.id from cir_test, cir_suite, cir_campaign where project_id =${P1.id} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.id = suite_id and cir_suite.position = ${sposition}
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    When Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    204
    Check If Exists In Database    select id from cir_campaign where project_id =${P1.id} and position = ${cposition}
    Check If Not Exists In Database    select cir_suite.id from cir_suite, cir_campaign where project_id =${P1.id} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.position = ${sposition}
    Check If Not Exists In Database    select cir_test.id from cir_test, cir_suite, cir_campaign where project_id =${P1.id} and cir_campaign.position = ${cposition} and cir_campaign.id = campaign_id and cir_suite.id = suite_id and cir_suite.position = ${sposition}

"DELETE suites" request changes camapign status and updates tests count
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    ${resp} =    When Delete Request    cir    /projects/${P1C3S1D.prefid}/campaigns/${P1C3S1D.crefid}/suites/${P1C3S1D.srefid}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    204
    ${cposition} =    Evaluate    ${P1C3S1D.crefid} - 1
    Check If Exists In Database    select id from cir_campaign where project_id =${P1C3S1D.pid} and position = ${cposition} and status = 1 and passed = 13 and failed = 0 and errored = 0 and skipped = 0 and disabled = 0

"DELETE suites" request with deleting unique suite changes camapign status to warning and updates tests count
    [Tags]    EDIT    DB
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P2.token}
    ${resp} =    When Delete Request    cir    /projects/${P2C3S1M.prefid}/campaigns/${P2C3S1M.crefid}/suites/${P2C3S1M.srefid}    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    204
    ${cposition} =    Evaluate    ${P2C3S1M.crefid} - 1
    Check If Exists In Database    select id from cir_campaign where project_id =${P2C3S1M.pid} and position = ${cposition} and status = 2 and passed = 0 and failed = 0 and errored = 0 and skipped = 0 and disabled = 0

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
