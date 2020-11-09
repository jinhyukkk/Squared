<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (object)array();
header('Content-Type: json');
$req = json_decode(file_get_contents("php://input"));
try {
    addAccessLogs($accessLogs, $req);
    switch ($handler) {
        case "index":
            echo "API Server";
            break;
        case "ACCESS_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/access.log");
            break;
        case "ERROR_LOGS":
            //            header('content-type text/html charset=utf-8');
            header('Content-Type: text/html; charset=UTF-8');
            getLogs("./logs/errors.log");
            break;
        /*
        * API No. 24
        * API Name : 검색 API
        * 마지막 수정 날짜 : 20.11.09
        */

        case "search":
            http_response_code(200);

            $keyword = $_GET['keyword'];
            $searchType = $_GET['searchType'];

            if (!isset($keyword)){
                $res->isSuccess = FALSE;
                $res->code = 390;
                $res->message = "키워드를 입력해주세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!(($searchType=="all") or ($searchType=="webtoon") or ($searchType=="challenge"))){
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "검색형식이 맞지 않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if($searchType=="all"){
                if(searchWebtoonLimit($keyword) and searchChallengeLimit($keyword)){

                    $res->webtoonCount = searchWebtoonCount($keyword);
                    $res->webtoonList = searchWebtoonLimit($keyword);

                    $res->bestChallengeCount = searchChallengeCount($keyword);
                    $res->bestChallenge = searchChallengeLimit($keyword);

                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "전체 검색 성공";
                    echo json_encode($res);
                    break;
                }
                elseif(searchWebtoonLimit($keyword)){
                    $res->webtoonCount = searchWebtoonCount($keyword);
                    $res->webtoonList = searchWebtoonLimit($keyword);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "전체 검색 성공";
                    echo json_encode($res);
                    break;
                }
                elseif(searchWebtoonLimit($keyword)){
                    $res->bestChallengeCount = searchChallengeCount($keyword);
                    $res->bestChallenge = searchChallengeLimit($keyword);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "전체 검색 성공";
                    echo json_encode($res);
                    break;
                }
                else{
                    $res->isSuccess = TRUE;
                    $res->code = 110;
                    $res->message = "검색 결과가 없습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }
            elseif($searchType=="webtoon"){
                if(searchWebtoon($keyword)){
                    $res->webtoonCount = searchWebtoonCount($keyword);
                    $res->webtoonList = searchWebtoon($keyword);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "웹툰 검색 성공";
                    echo json_encode($res);
                    break;
                }
                else{
                    $res->isSuccess = TRUE;
                    $res->code = 110;
                    $res->message = "검색 결과가 없습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }
            elseif($searchType=="challenge"){
                if(searchChallenge($keyword)){
                    $res->bestChallengeCount = searchChallengeCount($keyword);
                    $res->bestChallenge = searchChallenge($keyword);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "베스트도전 검색 성공";
                    echo json_encode($res);
                    break;
                }
                else{
                    $res->isSuccess = TRUE;
                    $res->code = 110;
                    $res->message = "검색 결과가 없습니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
