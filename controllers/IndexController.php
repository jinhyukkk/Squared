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

//            if (!isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
//                $res->isSuccess = FALSE;
//                $res->code = 202;
//                $res->message = "유효하지 않은 토큰입니다.";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                addErrorLogs($errorLogs, $res, $req);
//                return;
//            }
//
//            $jwt = $_SERVER['HTTP_X_ACCESS_TOKEN'];
//
//            if (!isValidJWT($jwt, JWT_SECRET_KEY)) { // function.php 에 구현
//                $res->isSuccess = FALSE;
//                $res->code = 202;
//                $res->message = "유효하지 않은 토큰입니다.";
//                echo json_encode($res, JSON_NUMERIC_CHECK);
//                addErrorLogs($errorLogs, $res, $req);
//                return;
//            }
//
//            $userIdxToken = getDataByJWToken($jwt, JWT_SECRET_KEY)->userIdx;

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
            $res->result = new stdClass();
            $res->result->thumbnailUrl = getWebtoonDetail($webtoonIdx)["subThumbnailUrl"];
            $res->result->color = getWebtoonDetail($webtoonIdx)["color"];
            $res->result->title = getWebtoonDetail($webtoonIdx)["title"];
            $res->result->creator = getWebtoonDetail($webtoonIdx)["creator"];
            $res->result->week = getWebtoonDetail($webtoonIdx)["week"];
            $res->result->summary = getWebtoonDetail($webtoonIdx)["summary"];
            $res->result->isInterested = getWebtoonDetail($webtoonIdx)["isInterested"];
            $res->result->Notice = getWebtoonDetail($webtoonIdx)["Notice"];

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
            $res->result = new stdClass();
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

        /*
        * API No. 6
        * API Name : 댓글 등록 API
        * 마지막 수정 날짜 : 20.11.04
        */

        case "postComment":
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

            $webtoonIdx = $_GET['webtoonId'];
            $episodeIdx = $_GET['episodeId'];


            if (empty($req->content)) {
                $res->isSuccess = FALSE;
                $res->code = 250;
                $res->message = "올바른 댓글 형식이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_string($req->content)){
                $res->isSuccess = FALSE;
                $res->code = 250;
                $res->message = "올바른 댓글 형식이 아닙니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
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
            postComment($webtoonIdx, $episodeIdx, $userIdxToken, $req->content);
            $res->webtoonIdx = $webtoonIdx;
            $res->episodeIdx = $episodeIdx;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "댓글 등록 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 7
        * API Name : 댓글 조회 API
        * 마지막 수정 날짜 : 20.11.04
        */

        case "getComment":
            http_response_code(200);
            // 회원
            if (isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){

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

                $res->result = new stdClass();
                $res->result->commentCount = getCommentCount($webtoonIdx, $episodeIdx)["commentCount"];
                if (getCommentUser($webtoonIdx, $episodeIdx, $userIdxToken)==[]){
                    $res->result->comment = "댓글 없음";
                }
                else{
                    $res->result->comment = getCommentUser($webtoonIdx, $episodeIdx, $userIdxToken);
                }
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "댓글 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            // 비회원
            else {
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

                $res->result = new stdClass();
                $res->result->commentCount = getCommentCount($webtoonIdx, $episodeIdx)["commentCount"];
                if (getComment($webtoonIdx, $episodeIdx)==[]){
                    $res->result->comment = "댓글 없음";
                }
                else{
                    $res->result->comment = getComment($webtoonIdx, $episodeIdx);
                }
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "댓글 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        /*
        * API No. 8
        * API Name : 댓글 삭제 API
        * 마지막 수정 날짜 : 20.11.04
        */
        case "deleteComment":
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

            $webtoonIdx = $_GET['webtoonId'];
            $episodeIdx = $_GET['episodeId'];
            $commentIdx = $_GET['commentId'];

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
            if (!is_numeric($commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 260;
                $res->message = "존재하지 않은 댓글입니다.";
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
            if (!isValidComment($webtoonIdx, $episodeIdx, $commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 260;
                $res->message = "존재하지 않은 댓글입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if ($userIdxToken != getUserIdxComment($webtoonIdx, $episodeIdx, $commentIdx)){
                $res->isSuccess = false;
                $res->code = 270;
                $res->message = "권한이 없는 유저입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            deleteComment($webtoonIdx, $episodeIdx, $commentIdx, $userIdxToken);
            $res->result = "$webtoonIdx-$episodeIdx-$commentIdx 댓글 삭제";
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "댓글 삭제 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 9
        * API Name : 댓글 좋아요 등록/취소 API
        * 마지막 수정 날짜 : 20.11.04
        */

        case "commentLike":
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
            $commentIdx = $vars['commentId'];

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
            if (!is_numeric($commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 260;
                $res->message = "존재하지 않은 댓글입니다.";
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
            if (!isValidComment($webtoonIdx, $episodeIdx, $commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 260;
                $res->message = "존재하지 않은 댓글입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (isValidCommentUnLike($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 280;
                $res->message = "이미 '싫어요'를 누르셨습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isExistsCommentLikeState($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx)){
                registerCommentLike($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx);
                $res->result = currentCommentLikeStatus($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "댓글 좋아요 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else {
                modifyCommentLike($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx);
                $res->result = currentCommentLikeStatus($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "댓글 좋아요 수정 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        /*
        * API No. 10
        * API Name : 댓글 싫어요 등록/취소 API
        * 마지막 수정 날짜 : 20.11.04
        */

        case "commentUnLike":
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
            $commentIdx = $vars['commentId'];

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
            if (!is_numeric($commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 260;
                $res->message = "존재하지 않은 댓글입니다.";
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
            if (!isValidComment($webtoonIdx, $episodeIdx, $commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 260;
                $res->message = "존재하지 않은 댓글입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (isValidCommentLike($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 290;
                $res->message = "이미 '좋아요'를 누르셨습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if (!isExistsCommentUnLikeState($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx)){
                registerCommentUnLike($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx);
                $res->result = currentCommentUnLikeStatus($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "댓글 싫어요 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else {
                modifyCommentUnLike($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx);
                $res->result = currentCommentUnLikeStatus($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "댓글 싫어요 수정 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

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
        * API No. 14
        * API Name : 임시저장 API
        * 마지막 수정 날짜 : 20.11.05
        */

        case "registerStorage":
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

            registerStorage($userIdxToken, $webtoonIdx, $episodeIdx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "임시저장 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 15
        * API Name : 임시저장 조회 API
        * 마지막 수정 날짜 : 20.11.05
        */

        case "getStorage":
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

            $res->result = new stdClass();
            $res->result->count = getStorageCount($userIdxToken);
            $res->result->webtoonList = getStorage($userIdxToken);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "임시저장 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

        /*
        * API No. 16
        * API Name : 임시저장 상세조회 API
        * 마지막 수정 날짜 : 20.11.05
        */

        case "getStorageDetail":
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

            if (!isValidStorage($userIdxToken, $webtoonIdx)){
                $res->isSuccess = FALSE;
                $res->code = 310;
                $res->message = "임시저장하지 않은 웹툰입니다.";
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

            $res->result = new stdClass();
            $res->result->webtoonTitle = getWebtoonTitle($userIdxToken, $webtoonIdx);
            $res->result->episodeList = getStorageDetail($userIdxToken, $webtoonIdx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "임시저장 상세 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;
        /*
        * API No. 17
        * API Name : 임시저장 삭제 API
        * 마지막 수정 날짜 : 20.11.05
        */

        case "deleteStorage":
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

            if (!isValidStorage($userIdxToken, $webtoonIdx)){
                $res->isSuccess = FALSE;
                $res->code = 310;
                $res->message = "임시저장하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isValidStorageEpisode($userIdxToken, $webtoonIdx, $episodeIdx)){
                $res->isSuccess = FALSE;
                $res->code = 310;
                $res->message = "임시저장하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            deleteStorage($userIdxToken, $webtoonIdx, $episodeIdx);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "임시저장 삭제 성공";
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

            $res->result = new stdClass();
            $res->result->count = getInterestedCount($userIdxToken);
            $res->result->webtoonList = getInterested($userIdxToken);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "관심웹툰 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;


        /*
        * API No. 4
        * API Name : 네이버 로그인 API
        * 마지막 수정 날짜 : 20.11.02
        */

        case "naverLogIn" :
            http_response_code(200);

            if(!isset($req->accessToken)){
                $res->isSuccess = FALSE;
                $res->code = 810;
                $res->message = "accessToken은 null일 수 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else{$accessToken=$req->accessToken;}
            $curl = 'curl -v -X GET https://openapi.naver.com/v1/nid/me -H "Authorization: Bearer ' . $accessToken. '"';
            $info = shell_exec($curl);
            $info_arr = json_decode($info,true);
            ///echo json_encode($info_arr);

            if($info_arr["message"]!="success"){
                $res->isSuccess = FALSE;
                $res->code = 700;
                $res->message = "소셜로그인 실패 : ".$info_arr["message"];
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $email = $info_arr["response"]["email"];

            if (!isEmailExist($email)) {
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "존재하지 않는 이메일입니다. 회원가입해주세요";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $userId = getUserIdxByID($email);
            userConnect($userId);
            $jwt = getJWT($userId, JWT_SECRET_KEY);
            $res->result = new \stdClass();
            $res->result->jwt = $jwt;
            $res->isSuccess = TRUE;
            $res->code = 100; //성공 code
            $res->message = "네이버 로그인 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;



        case "naverCreateUser" :
            http_response_code(200);
            if(!isset($req->accessToken)){
                $res->isSuccess = FALSE;
                $res->code = 810;
                $res->message = "accessToken은 null일 수 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }else{$accessToken=$req->accessToken;}
            $curl = 'curl -v -X GET https://openapi.naver.com/v1/nid/me -H "Authorization: Bearer ' . $accessToken. '"';
            $info = shell_exec($curl);
            $info_arr = json_decode($info,true);
            if($info_arr["message"]!="success"){
                $res->isSuccess = FALSE;
                $res->code = 700;
                $res->message = "소셜로그인 실패 : ".$info_arr["message"];
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $email = $info_arr["response"]["email"];
            //email validation
            if (isEmailExist($email)) {
                $res->isSuccess = FALSE;
                $res->code = 400; //존재하지 않는 userId
                $res->message = "이미 존재하는 이메일입니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            //회원가입처리
            if(!isset($req->nickname)){
                $res->isSuccess = FALSE;
                $res->code = 820;
                $res->message = "nickname은 null일 수 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else{$nickname = $req->nickname;}
            //nickname validation
            if (isNicknameUsed($nickname)) {
                $res->isSuccess = FALSE;
                $res->code = 400;
                $res->message = "이미 사용된 닉네임입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $userId = naverSignUp($email, $nickname);
            $jwt = getJWT($userId, JWT_SECRET_KEY);
            $res->result = new \stdClass();
            $res->result->jwt = $jwt;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "네이버 회원가입 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

            createUser($req->userId, $accessToken, $req->gender, $req->birth);
            $userIdx = getUserIdxByEmail($req->userEmail);
            $jwt = getJWT($userIdx, JWT_SECRET_KEY);

            $res->jwt = $jwt;
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "회원가입 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;

//        /*
//        * API No. 4
//        * API Name : 네이버 로그인 API
//        * 마지막 수정 날짜 : 20.11.02
//        */
//        case "stateCode" :
//            http_response_code(200);
//
//            $client_id = "OjQ_dYqRjIPbtwjbrBmU";
//            $redirectURI = urlencode("https://prod.doong.shop/callback");
//            $state = generate_state();
//            $apiURL = "https://nid.naver.com/oauth2.0/authorize?response_type=code&client_id=".$client_id."&redirect_uri=".$redirectURI."&state=".$state;
//            $data = array(
//                'test' => 'test'
//            );
//            $ch = curl_init();                                 //curl 초기화
//            curl_setopt($ch, CURLOPT_URL, $apiURL);               //URL 지정하기
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);    //요청 결과를 문자열로 반환
//            curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);      //connection timeout 10초
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);   //원격 서버의 인증서가 유효한지 검사 안함
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);       //POST data
//            curl_setopt($ch, CURLOPT_POST, true);              //true시 post 전송
//
//            $response = curl_exec($ch);
//            curl_close($ch);
//
//            return $response;
//
//            $res->result = $response;
//            $res->isSuccess = TRUE;
//            $res->code = 100;
//            $res->message = "네이버 accessToken 얻어오기 성공";
//            echo json_encode($res, JSON_NUMERIC_CHECK);
//
//            break;
//        /*
//        * API No. 4
//        * API Name : 네이버 로그인 API
//        * 마지막 수정 날짜 : 20.11.02
//        */
//        case "naverCallback" :
//            http_response_code(200);
//            session_start();
//            $client_id = "OjQ_dYqRjIPbtwjbrBmU";
//            $client_secret = "cYEUVqT67x";
//            $code = $_GET["code"];
//            $state = $_GET["state"];
//            $redirectURI = urlencode("https://prod.doong.shop/callback");
//            $url = "https://nid.naver.com/oauth2.0/token?grant_type=authorization_code&client_id=".$client_id."&client_secret=".$client_secret."&redirect_uri=".$redirectURI."&code=".$code."&state=".$state;
//            $is_post = false;
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL, $url);
//            curl_setopt($ch, CURLOPT_POST, $is_post);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            $headers = array();
//            $response = curl_exec ($ch);
//            $status_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
////            echo "status_code:".$status_code."";
//            curl_close ($ch);
//            $accessToken = json_decode($response,true)["access_token"];
//            $res->result = new \stdClass();
//            $res->result->accessToken = $accessToken;
//            $res->isSuccess = TRUE;
//            $res->code = 100;
//            $res->message = "네이버 accessToken 얻어오기 성공";
//            echo json_encode($res, JSON_NUMERIC_CHECK);
//
//            break;
//


    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
