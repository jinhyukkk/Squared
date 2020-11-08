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
        * API No. 11
        * API Name : 하트 등록/취소 API
        * 마지막 수정 날짜 : 20.11.04
        */

        case "episodeHeart":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $webtoonIdx = $vars['webtoonId'];
            $episodeIdx = $vars['episodeId'];

            if (!is_numeric($webtoonIdx)){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_numeric($episodeIdx)){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidWebtoon($webtoonIdx)){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidEpisode($webtoonIdx, $episodeIdx)){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


            if (!isExistsHeart($userIdxToken, $webtoonIdx, $episodeIdx)){
                registerHeart($userIdxToken, $webtoonIdx, $episodeIdx);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = currentHeartStatus($userIdxToken, $webtoonIdx, $episodeIdx);
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else {
                modifyHeart($userIdxToken, $webtoonIdx, $episodeIdx);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = currentHeartStatus($userIdxToken, $webtoonIdx, $episodeIdx);
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        /*
        * API No. 12
        * API Name : 관심 등록/취소 API
        * 마지막 수정 날짜 : 20.11.04
        */

        case "registInterest":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $webtoonIdx = $vars['webtoonId'];

            if (!is_numeric($webtoonIdx)){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidWebtoon($webtoonIdx)){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isExistsInterest($userIdxToken, $webtoonIdx)){
                registNotice($userIdxToken, $webtoonIdx);
                $res->result=registInterest($userIdxToken, $webtoonIdx);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = currentInterestStatus($userIdxToken, $webtoonIdx);
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else {
                modifyInterest($userIdxToken, $webtoonIdx);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = currentInterestStatus($userIdxToken, $webtoonIdx);
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
        /*
        * API No. 13
        * API Name : 알림 등록/취소 API
        * 마지막 수정 날짜 : 20.11.04
        */

        case "registNotice":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            $webtoonIdx = $vars['webtoonId'];

            if (!is_numeric($webtoonIdx)){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isValidWebtoon($webtoonIdx)){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidInterest($userIdxToken, $webtoonIdx)){
                $res->isSuccess = FALSE;
                $res->code = 300;
                $res->message = "관심등록을 먼저 해야합니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isExistsNotice($userIdxToken, $webtoonIdx)){
                $res->isSuccess = FALSE;
                $res->code = 300;
                $res->message = "관심등록을 먼저 해야합니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            modifyNotice($userIdxToken, $webtoonIdx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = currentNoticeStatus($userIdxToken, $webtoonIdx);
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 18
        * API Name : 관심웹툰 조회 API
        * 마지막 수정 날짜 : 20.11.05
        */

        case "getInterested":
            http_response_code(200);

            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];

            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
                $res->isSuccess = FALSE;
                $res->code = 202;
                $res->message = "유효하지 않은 토큰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                addErrorLogs($errorLogs, $res, $req);
                return;
            }

            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

            if(!isExistsInterested($userIdxToken)){
                $res->count = 0;
            }
            else {
                $res->count = getInterestedCount($userIdxToken);
            }
            if(getInterested($userIdxToken)){
                $res->webtoonList = getInterested($userIdxToken);
            }
            else {
                $res->webtoonList = "관심웹툰 없음";
            }

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "관심웹툰 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
