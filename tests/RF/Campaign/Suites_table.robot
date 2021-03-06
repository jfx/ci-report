*** Settings ***
Test Setup        Setup
Test Teardown     Teardown
Resource          Function/campaign.txt

*** Test Cases ***
Table should have a header
    When I go to campaign page    &{P1C4}
    Then The table header should contain values    t-suite    \#Id    Name    %    Passed    Failed
    ...    Errored    Skipped    Disabled    Duration    Time    Document

Table should list all suites
    When I go to campaign page    &{P1C4}
    Then The table should contain X rows    t-suite    2
    And The table column should contain values    t-suite    1    \#1    \#2

The first row should be the first suite
    When I go to campaign page    &{P1C4}
    Then The table row should contain values of suite    1    &{P1C4S1}

Click on #Id should go to suite page
    Given I go to campaign page    &{P1C4}
    When Click Link    a-suite-${P1C4S1.srefid}
    Then I should be on suite page    &{P1C4S1}

Check failed value cell
    When I go to campaign page    &{P2C3}
    Then I check table cell value    t-suite    1    5    ${P2C3S1.failed}

Check errored value cell
    When I go to campaign page    &{P4C2}
    Then I check table cell value    t-suite    1    6    ${P4C2S1.errored}

Check skipped value cell
    When I go to campaign page    &{P2C3}
    Then I check table cell value    t-suite    1    7    ${P2C3S1.skipped}

Check disabled value cell
    When I go to campaign page    &{P5C4}
    Then I check table cell value    t-suite    1    8    ${P5C4S1.disabled}

A row suite with more or equal 95% should have green background color
    When I go to campaign page    &{P1C4}
    Then Page Should Contain Element    xpath=//tr[@id="tr-suite-${P1C4S1.srefid}" and @class="table-success"]

A row suite between 80% and 95% should have yellow background color
    When I go to campaign page    &{P3C3}
    Then Page Should Contain Element    xpath=//tr[@id="tr-suite-${P3C3S1.srefid}" and @class="table-warning"]

A row suite under 80% should have red background color
    When I go to campaign page    &{P4C3}
    Then Page Should Contain Element    xpath=//tr[@id="tr-suite-${P4C3S1.srefid}" and @class="table-danger"]

A row suite with attached document should have a link to download it
    When I go to campaign page    &{P1C4}
    Then Element Should Be Visible    a-download-${P1C4S1.srefid}

A row suite with attached document could download it
    When I go to campaign page    &{P1C4}
    Then Click link    a-download-${P1C4S1.srefid}

A row suite without attached document should not have a link to download it
    When I go to campaign page    &{P1C4}
    Then Element Should Not Be Visible    a-download-${P1C4S2.srefid}

Attach and remove document of a suite
    [Tags]    API    EDIT
    When I go to campaign page    &{P1C4}
    Then Element Should Not Be Visible    a-download-${P1C4S2.srefid}
    &{file} =    Create Dictionary    form_field=zipfile    path_file=${CURDIR}/../../files/zipfile-ok.zip
    &{headers} =    When Create Dictionary    X-CIR-TKN=${P1.token}
    &{data} =    And Create Dictionary
    ${resp}=    Post Request With File Upload    ${API_URL}/projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}/doc/zip    ${file}    headers=${headers}    data=${data}
    Then Should Be Equal As Strings    ${resp.status_code}    201
    When I go to campaign page    &{P1C4}
    Then Element Should Be Visible    a-download-${P1C4S2.srefid}
    ${resp} =    When Delete Request    cir    /projects/${P1C4S2.prefid}/campaigns/${P1C4S2.crefid}/suites/${P1C4S2.srefid}/doc/zip    headers=${headers}
    Then Should Be Equal As Strings    ${resp.status_code}    204
    When I go to campaign page    &{P1C4}
    Then Element Should Not Be Visible    a-download-${P1C4S2.srefid}

A row suite with no test should have grey background color
    When I go to campaign page    &{P8C1S1}
    Then Page Should Contain Element    xpath=//tr[@id="tr-suite-${P8C1S1.srefid}" and @class="table-secondary"]

A campaign without suite should display "No suite"
    When I go to campaign page    &{P7C1}
    Then Page Should Contain    No suite
