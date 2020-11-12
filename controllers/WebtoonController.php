<?php
require 'function.php';

const JWT_SECRET_KEY = "TEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEYTEST_KEY";

$res = (Object)Array();
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

            if (!isset($_GET['category'])){
                $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "잘못된 구분입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isset($_GET['sort'])){
                $res->isSuccess = FALSE;
                $res->code = 230;
                $res->message = "존재하지 않은 정렬입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $category = $_GET['category'];
            $sort = $_GET['sort'];

            if (!(($category=="mon")
                or ($category=="tue")
                or ($category=="wed")
                or ($category=="thur")
                or ($category=="fri")
                or ($category=="sat")
                or ($category=="sun")
                or ($category=="new")
                or ($category=="finish"))){
                $res->isSuccess = FALSE;
                $res->code = 220;
                $res->message = "잘못된 구분입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif (!(($sort=="hot")
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
            if (($category=="mon")
                or ($category=="tue")
                or ($category=="wed")
                or ($category=="thur")
                or ($category=="fri")
                or ($category=="sat")
                or ($category=="sun")){
                if ($sort == "hot") {
                    $res->result = getWebtoons_Hot($category);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "요일별 웹툰 인기순 조회 성공";
                    echo json_encode($res);
                    break;
                }
                if($sort=="view"){
                    $res->result = getWebtoons_View($category);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "요일별 웹툰 조회순 조회 성공";
                    echo json_encode($res);
                    break;
                }
                if($sort=="male"){
                    $res->result = getWebtoons_Male($category);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "요일별 웹툰 남성 인기순 조회 성공";
                    echo json_encode($res);
                    break;
                }
                if($sort=="female"){
                    $res->result = getWebtoons_Female($category);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "요일별 웹툰 여성 인기순 조회 성공";
                    echo json_encode($res);
                    break;
                }
                if ($sort == "update") {
                    $res->result = getWebtoons_Update($category);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "요일별 웹툰 업데이트순 조회 성공";
                    echo json_encode($res);
                    break;
                }
            }
            elseif ($category=="new"){
                if($sort=="hot"){
                    $res->result = newWebtoons_Hot();
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "신작 웹툰 인기순 조회 성공";
                    echo json_encode($res);
                    break;
                }
                if($sort=="view"){
                    $res->result = newWebtoons_View();
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "신작 웹툰 조회순 조회 성공";
                    echo json_encode($res);
                    break;
                }
                if($sort=="male"){
                    $res->result = newWebtoons_Male();
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "신작 웹툰 남성 인기순 조회 성공";
                    echo json_encode($res);
                    break;
                }
                if($sort=="female"){
                    $res->result = newWebtoons_Female();
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "신작 웹툰 여성 인기순 조회 성공";
                    echo json_encode($res);
                    break;
                }
                if($sort=="update"){
                    $res->result = newWebtoons_Update();
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "신작 웹툰 업데이트순 조회 성공";
                    echo json_encode($res);
                    break;
                }
            }
            elseif ($category=="finish"){
                if($sort=="hot"){
                    $res->result = finishedWebtoons_Hot();
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "완결 웹툰 인기순 조회 성공";
                    echo json_encode($res);
                    break;
                }
                if($sort=="view"){
                    $res->result = finishedWebtoons_View();
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "완결 웹툰 조회순 조회 성공";
                    echo json_encode($res);
                    break;
                }
                if($sort=="male"){
                    $res->result = finishedWebtoons_Male();
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "완결 웹툰 남성 인기순 조회 성공";
                    echo json_encode($res);
                    break;
                }
                if($sort=="female"){
                    $res->result = finishedWebtoons_Female();
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "완결 웹툰 여성 인기순 조회 성공";
                    echo json_encode($res);
                    break;
                }
                if($sort=="update"){
                    $res->result = finishedWebtoons_Update();
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "완결 웹툰 업데이트순 조회 성공";
                    echo json_encode($res);
                    break;
                }
            }

        /*
        * API No. 2
        * API Name : 웸툰 상세 조회 API
        * 마지막 수정 날짜 : 20.11.03
        */

        case "webtoonList":
            http_response_code(200);

            if (!isset($vars['webtoonId'])){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

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
            //회원
            if(isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){

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
                $res->result->thumbnailUrl = getWebtoonDetail($webtoonIdx, $userIdxToken)["subThumbnailUrl"];
                $res->result->color = getWebtoonDetail($webtoonIdx, $userIdxToken)["color"];
                $res->result->title = getWebtoonDetail($webtoonIdx, $userIdxToken)["title"];
                $res->result->creator = getWebtoonDetail($webtoonIdx, $userIdxToken)["creator"];
                $res->result->week = getWebtoonDetail($webtoonIdx, $userIdxToken)["week"];
                $res->result->summary = getWebtoonDetail($webtoonIdx, $userIdxToken)["summary"];
                $res->result->isInterested = getWebtoonDetail($webtoonIdx, $userIdxToken)["isInterested"];
                $res->result->Notice = getWebtoonDetail($webtoonIdx, $userIdxToken)["Notice"];

                if(!(getWebtoonList($webtoonIdx, $userIdxToken))){
                    $res->result->episode = "에피소드 없음";
                }
                else{
                    $res->result->episode = getWebtoonList($webtoonIdx, $userIdxToken);
                }
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "웹툰 상세 조회 성공";
                echo json_encode($res);
                break;
            }
            //비회원
            else{
                $res->result = new stdClass();
                $res->result->thumbnailUrl = getWebtoonDetailNonMember($webtoonIdx)["subThumbnailUrl"];
                $res->result->color = getWebtoonDetailNonMember($webtoonIdx)["color"];
                $res->result->title = getWebtoonDetailNonMember($webtoonIdx)["title"];
                $res->result->creator = getWebtoonDetailNonMember($webtoonIdx)["creator"];
                $res->result->week = getWebtoonDetailNonMember($webtoonIdx)["week"];
                $res->result->summary = getWebtoonDetailNonMember($webtoonIdx)["summary"];
                $res->result->isInterested = getWebtoonDetailNonMember($webtoonIdx)["isInterested"];
                $res->result->Notice = getWebtoonDetailNonMember($webtoonIdx)["Notice"];

                if(!(getWebtoonListNonMember($webtoonIdx))){
                    $res->result->episode = "에피소드 없음";
                }
                else{
                    $res->result->episode = getWebtoonListNonMember($webtoonIdx);
                }
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "웹툰 상세 조회 성공";
                echo json_encode($res);
                break;
            }

        /*
        * API No. 3
        * API Name : 웸툰 회차별 상세 조회 API
        * 마지막 수정 날짜 : 20.11.03
        */
        case "episodeView":
            http_response_code(200);

            if (!isset($vars['webtoonId'])){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!isset($vars['episodeId'])){
                $res->isSuccess = FALSE;
                $res->code = 240;
                $res->message = "존재하지 않은 웹툰입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

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

            if(isset($_SERVER['HTTP_X_ACCESS_TOKEN'])){
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
                $res->result->episodeIdx = episodeView($webtoonIdx, $episodeIdx)["episodeIdx"];
                $res->result->title = episodeView($webtoonIdx, $episodeIdx)["title"];
                $res->result->heartStatus = episodeView($webtoonIdx, $episodeIdx)["heartStatus"];
                $res->result->heartCount = episodeView($webtoonIdx, $episodeIdx)["heartCount"];
                $res->result->commentCount = episodeView($webtoonIdx, $episodeIdx)["commentCount"];
                if(episodeContents($webtoonIdx, $episodeIdx)){
                    $res->result->contentsUrl = episodeContents($webtoonIdx, $episodeIdx);
                }
                else {
                    $res->result->contentsUrl = "웹툰 내용 없음";
                }
                if(isExistViewPoint($userIdxToken, $webtoonIdx, $episodeIdx)){
                    updateViewPoint($userIdxToken, $webtoonIdx, $episodeIdx);
                }
                else{
                    creatViewPoint($userIdxToken, $webtoonIdx, $episodeIdx);
                }
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "웹툰 회차 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            else{
                $res->result = new stdClass();
                $res->result->episodeIdx = episodeView($webtoonIdx, $episodeIdx)["episodeIdx"];
                $res->result->title = episodeView($webtoonIdx, $episodeIdx)["title"];
                $res->result->heartStatus = episodeView($webtoonIdx, $episodeIdx)["heartStatus"];
                $res->result->heartCount = episodeView($webtoonIdx, $episodeIdx)["heartCount"];
                $res->result->commentCount = episodeView($webtoonIdx, $episodeIdx)["commentCount"];
                if(episodeContents($webtoonIdx, $episodeIdx)){
                    $res->result->contentsUrl = episodeContents($webtoonIdx, $episodeIdx);
                }
                else {
                    $res->result->contentsUrl = "웹툰 내용 없음";
                }

                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "웹툰 회차 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

        /*
        * API No. 19
        * API Name : 최근 본 웸툰 조회 API
        * 마지막 수정 날짜 : 20.11.08
        */

        case "recentlyView":
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

            if(!isExistsRecentlyView($userIdxToken)){
                $res->count = 0;
            }
            else {
                $res->count = getRecentlyViewCount($userIdxToken);
            }
            if(getRecentlyView($userIdxToken)){
                $res->webtoonList = getRecentlyView($userIdxToken);
            }
            else {
                $res->webtoonList = "최근 본 웹툰 없음";
            }

            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "최근 본 웹툰 조회 성공";
            echo json_encode($res);
            break;

        /*
        * API No. 24
        * API Name : 웹툰 등록 API
        * 마지막 수정 날짜 : 20.11.11
        */

        case "postEpisode":
            http_response_code(200);

//            $ch = curl_init("https://fcm.googleapis.com/fcm/send");
//            $header = array("Content-Type:application/json",
//                "Authorization:key=AAAA2fAMhU0:APA91bFSj2HtVQPQdY3zR2N-tDxa6LRthCfMvjLr-u_25-5l9pbhkawCz_o0A1YEufYqIFOEuCLGGXH-3ka8nC9bwBja6FLFw2iOQLBEMv3kAUbBvSf9Tw7kBisBjVJQ_cozdJe-RJlz");
//            $data = json_encode(array(
//                "to" => "핸드폰 or 에뮬로 실행하고 로그에 나온 장문의 토큰 문자열",
//                "notification" => array(
//                    "title"   => '웹툰 등록',
//                    "message" => "새로운 웹툰이 추가 되었어요!")
//            ));
//            curl_setopt($ch, CURLOPT_HTTPHEADER, $header);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//            curl_setopt($ch, CURLOPT_POST, 1);
//            curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
//            curl_exec($ch);



//            $tokens = array();
//            $tokens[0] = 'token1';
//            $tokens[1] = 'token2';
//
//// 헤더 부분
//            $key = "AAAA2fAMhU0:APA91bFSj2HtVQPQdY3zR2N-tDxa6LRthCfMvjLr-u_25-5l9pbhkawCz_o0A1YEufYqIFOEuCLGGXH-3ka8nC9bwBja6FLFw2iOQLBEMv3kAUbBvSf9Tw7kBisBjVJQ_cozdJe-RJlz";
//            $headers = array(
//                'Content-Type:application/json',
//                'Authorization:key='.$key
//            );
//
////// 발송 내용
////            $arr   = array();
////            $arr['notification'] = array();
////            $arr['notification']['title'] = '제목';
////            $arr['notification']['body'] = '내용';
////            $arr['notification']['sound'] = 'default';
////            $arr['notification']['badge'] = '1';
////            $arr['notification']['tag'] = '1';  // 개별로 보낼때 이름을 다르게
////            $arr['notification']['priority'] = 'high';  // 안드로이드 8이상 추가
////            $arr['notification']['content_available'] = true;  // 안드로이드 8이상 추가
//
//            $arr['data'] = array();
//            $arr['data']['message'] = '새로운 웹툰이 나왔어요!'; // 내부에서 받을 시
//
//            $arr['registration_ids'] = array();
//            $arr['registration_ids'] = $tokens;
//
//            $ch = curl_init();
//            curl_setopt($ch, CURLOPT_URL,    'https://fcm.googleapis.com/fcm/send');
//            curl_setopt($ch, CURLOPT_POST, true);
//            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
//            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
//            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
//            curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($arr));
//            $response = curl_exec($ch);
//            curl_close($ch);
//
//// 푸쉬 전송에 대한 결과를 수신
//            $obj = json_decode($response);
//
//// 전송 성공 및 실패한 갯수
//            $suc_cnt = $obj->success;
//            $fail_cnt = $obj->failure;
//
//            echo $suc_cnt.' --- '.$fail_cnt;



            $tokens = array();
            $tokens[0] = "디바이스 토큰을 여기다 넣으세요";


            $myMessage = "Message Test";
            if ($myMessage == ""){
                $myMessage = "Newly registered.";
            }

            $message = array("message" => $myMessage);

            $url = 'https://fcm.googleapis.com/fcm/send';
            $fields = array(
                'registration_ids' => $tokens,
                'data' => $message
            );
            $key = "AAAA2fAMhU0:APA91bFSj2HtVQPQdY3zR2N-tDxa6LRthCfMvjLr-u_25-5l9pbhkawCz_o0A1YEufYqIFOEuCLGGXH-3ka8nC9bwBja6FLFw2iOQLBEMv3kAUbBvSf9Tw7kBisBjVJQ_cozdJe-RJlz";
            $headers = array(
                'Authorization:key =' . $key,
                'Content-Type: application/json'
            );

            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt ($ch, CURLOPT_SSL_VERIFYHOST, 0);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($fields));
            $result = curl_exec($ch);

            if ($result === FALSE) {
                die('Curl failed: ' . curl_error($ch));
            }
            curl_close($ch);
            echo $result;
//            $message_status = send_notification($tokens, $message);
//            echo $message_status;

            $webtoonIdx = $req->webtoonIdx;
            $episodeIdx = $req->episodeIdx;
            $title = $req->title;
            $thumbnailUrl = $req->thumbnailUrl;
            $words = $req->words;

            $res->result = getNoticeUser($webtoonIdx);
            postEpisode($webtoonIdx, $episodeIdx, $title, $thumbnailUrl, $words);
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "웹툰 등록 성공";
            echo json_encode($res);
            break;


    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
