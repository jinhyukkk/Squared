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
         * API No. 1
         * API Name : 웹툰 조회 API
         * 마지막 수정 날짜 : 20.11.02
         */
        case "getWebtoons":
            http_response_code(200);

            $week = $_GET['week'];
            $sort = $_GET['sort'];

            if (!(($week=="mon")
                or ($week=="tue")
                or ($week=="wed")
                or ($week=="thur")
                or ($week=="fri")
                or ($week=="sat")
                or ($week=="sun"))){
                $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "존재하지 않은 요일입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!(($sort=="hot")
                or ($sort=="view")
                or ($sort=="male")
                or ($sort=="female")
                or ($sort=="update"))){
                $res->isSuccess = FALSE;
                $res->code = 230;
                $res->message = "존재하지 않은 정렬입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if($sort=="hot"){
                $res->result = getWebtoons_Hot($week);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "웹툰 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
//            if($sort=="view"){
//                $res->result = getWebtoons_View($week);
//                $res->isSuccess = TRUE;
//                $res->code = 100;
//                $res->message = "웹툰 조회 성공";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }
//            if($sort=="male"){
//                $res->result = getWebtoons_Male($week);
//                $res->isSuccess = TRUE;
//                $res->code = 100;
//                $res->message = "웹툰 조회 성공";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }
//            if($sort=="female"){
//                $res->result = getWebtoons_Female($week);
//                $res->isSuccess = TRUE;
//                $res->code = 100;
//                $res->message = "웹툰 조회 성공";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }
            if($sort=="update"){
                $res->result = getWebtoons_Update($week);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "웹툰 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }


        /*
         * API No. 2
         * API Name : 완결웹툰 조회 API
         * 마지막 수정 날짜 : 20.11.02
         */
        case "finishedWebtoons":
            http_response_code(200);

            $sort = $_GET['sort'];

            if (!(($sort=="hot")
                or ($sort=="view")
                or ($sort=="male")
                or ($sort=="female")
                or ($sort=="update"))){
                $res->isSuccess = FALSE;
                $res->code = 230;
                $res->message = "존재하지 않은 정렬입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if($sort=="hot"){
                $res->result = finishedWebtoons_Hot();
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "웹툰 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
//            if($sort=="view"){
//                $res->result = finishedWebtoons_View();
//                $res->isSuccess = TRUE;
//                $res->code = 100;
//                $res->message = "웹툰 조회 성공";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }
//            if($sort=="male"){
//                $res->result = finishedWebtoons_Male();
//                $res->isSuccess = TRUE;
//                $res->code = 100;
//                $res->message = "웹툰 조회 성공";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }
//            if($sort=="female"){
//                $res->result = finishedWebtoons_Female();
//                $res->isSuccess = TRUE;
//                $res->code = 100;
//                $res->message = "웹툰 조회 성공";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }
            if($sort=="update"){
                $res->result = finishedWebtoons_Update();
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "웹툰 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        /*
         * API No. 3
         * API Name : 신작 웸툰 조회 API
         * 마지막 수정 날짜 : 20.11.02
         */
        case "newWebtoons":
            http_response_code(200);

            $sort = $_GET['sort'];

            if (!(($sort=="hot")
                or ($sort=="view")
                or ($sort=="male")
                or ($sort=="female")
                or ($sort=="update"))){
                $res->isSuccess = FALSE;
                $res->code = 230;
                $res->message = "존재하지 않은 정렬입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if($sort=="hot"){
                $res->result = newWebtoons_Hot();
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "웹툰 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
//            if($sort=="view"){
//                $res->result = newWebtoons_View();
//                $res->isSuccess = TRUE;
//                $res->code = 100;
//                $res->message = "웹툰 조회 성공";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }
//            if($sort=="male"){
//                $res->result = newWebtoons_Male();
//                $res->isSuccess = TRUE;
//                $res->code = 100;
//                $res->message = "웹툰 조회 성공";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }
//            if($sort=="female"){
//                $res->result = newWebtoons_Female();
//                $res->isSuccess = TRUE;
//                $res->code = 100;
//                $res->message = "웹툰 조회 성공";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                break;
//            }
            if($sort=="update"){
                $res->result = newWebtoons_Update();
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "웹툰 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        /*
        * API No. 4
        * API Name : 웸툰 상세 조회 API
        * 마지막 수정 날짜 : 20.11.03
        */
        case "webtoonList":
            http_response_code(200);

            $webtoonIdx = $_GET['webtoonId'];

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

            $res->result->thumbnailUrl = getWebtoonDetail($webtoonIdx)["subThumbnailUrl"];
            $res->result->color = getWebtoonDetail($webtoonIdx)["color"];
            $res->result->title = getWebtoonDetail($webtoonIdx)["title"];
            $res->result->creator = getWebtoonDetail($webtoonIdx)["creator"];
            $res->result->week = getWebtoonDetail($webtoonIdx)["week"];
            $res->result->summary = getWebtoonDetail($webtoonIdx)["summary"];
            $res->result->isInterested = getWebtoonDetail($webtoonIdx)["isInterested"];
            $res->result->alarm = getWebtoonDetail($webtoonIdx)["alarm"];

            if(!(getWebtoonList($webtoonIdx))){
                $res->result->episode = "에피소드 없음";
            }
            else{
                $res->result->episode = getWebtoonList($webtoonIdx);
            }

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "웹툰 상세 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 5
        * API Name : 웸툰 회차별 상세 조회 API
        * 마지막 수정 날짜 : 20.11.03
        */
        case "episodeView":
            http_response_code(200);

            $webtoonIdx = $_GET['webtoonId'];
            $episodeIdx = $_GET['episodeId'];

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

            $res->result->episodeIdx = episodeView($webtoonIdx, $episodeIdx)["episodeIdx"];
            $res->result->title = episodeView($webtoonIdx, $episodeIdx)["title"];
            $res->result->heartStatus = episodeView($webtoonIdx, $episodeIdx)["heartStatus"];
            $res->result->heartCount = episodeView($webtoonIdx, $episodeIdx)["heartCount"];
            $res->result->commentCount = episodeView($webtoonIdx, $episodeIdx)["commentCount"];
            $res->result->contentsUrl = episodeContents($webtoonIdx, $episodeIdx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "웹툰 회차 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}

