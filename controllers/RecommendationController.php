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
         * API No. 20
         * API Name : 추천 완결 API
         * 마지막 수정 날짜 : 20.11.08
         */
        case "recommendation":
            http_response_code(200);

            $sort = $_GET['sort'];
            $genre = $_GET['genre'];

            if (!(($sort=="hot")
                or ($sort=="male")
                or ($sort=="female")
                or ($sort=="update"))){
                $res->isSuccess = FALSE;
                $res->code = 230;
                $res->message = "존재하지 않은 정렬입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!(($genre=="all")
                or ($genre=="드라마")
                or ($genre=="액션")
                or ($genre=="판타지"))){
                $res->isSuccess = FALSE;
                $res->code = 310;
                $res->message = "존재하지 않은 장르입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if($genre=="all"){
                if($sort=="hot"){
                    $res->count = recommendAllCount();
                    $res->webtoonList = recommendationHotAll();
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "추천 완결 웹툰 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                elseif($sort=="male"){
                    $res->count = recommendAllCount();
                    $res->webtoonList = recommendationMaleAll();
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "추천 완결 웹툰 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                elseif($sort=="female"){
                    $res->count = recommendAllCount();
                    $res->webtoonList = recommendationFemaleAll();
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "추천 완결 웹툰 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                elseif($sort=="update"){
                    $res->count = recommendAllCount();
                    $res->webtoonList = recommendationUpdateAll();
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "추천 완결 웹툰 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }
            else{
                if($sort=="hot"){
                    $res->webtoonList = recommendationHot($genre);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "추천 완결 웹툰 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                elseif($sort=="male"){
                    $res->webtoonList = recommendationMale($genre);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "추천 완결 웹툰 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                elseif($sort=="female"){
                    $res->webtoonList = recommendationFemale($genre);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "추천 완결 웹툰 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                elseif($sort=="update"){
                    $res->webtoonList = recommendationUpdate($genre);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "추천 완결 웹툰 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
            }




        /*
         * API No. 21
         * API Name : 관심 웹툰 추천 완결 API
         * 마지막 수정 날짜 : 20.11.08
         */
        case "freeRounds":
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
            $res->userComment = userComment($userIdxToken);
            $res->my = interestedComplete($userIdxToken);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "관심 완결 웹툰 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
