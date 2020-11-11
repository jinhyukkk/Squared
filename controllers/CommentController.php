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
        * API Name : 댓글 좋아요, 싫어요 등록/취소 API
        * 마지막 수정 날짜 : 20.11.11
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

            if (!isset($req->webtoonId)){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isset($req->episodeId)){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isset($req->commentId)){
                $res->isSuccess = FALSE;
                $res->code = 260;
                $res->message = "존재하지 않은 댓글입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $webtoonIdx = $req->webtoonId;
            $episodeIdx = $req->episodeId;
            $commentIdx = $req->commentId;
            $choice = $req->choice;

            if (!is_integer($webtoonIdx)){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_integer($episodeIdx)){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_integer($commentIdx)){
                $res->isSuccess = FALSE;
                $res->code = 260;
                $res->message = "존재하지 않은 댓글입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!is_string($choice)){
                $res->isSuccess = FALSE;
                $res->code = 410;
                $res->message = "신호 형식이 옳지 않습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!(($choice=='좋아요') or ($choice=='싫어요'))){
                $res->isSuccess = FALSE;
                $res->code = 410;
                $res->message = "신호 형식이 옳지 않습니다.";
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
            
            if ($choice=='좋아요'){
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
            }
            elseif ($choice=='싫어요'){
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
            }


    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
