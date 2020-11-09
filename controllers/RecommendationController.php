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
                or ($genre=="drama")
                or ($genre=="action")
                or ($genre=="fantasy"))){
                $res->isSuccess = FALSE;
                $res->code = 310;
                $res->message = "존재하지 않은 장르입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if($genre=="drama"){
                $genre = "드라마";
            }
            elseif($genre=="action"){
                $genre = "액션";
            }
            elseif($genre=="fantasy"){
                $genre = "판타지";
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
                    $res->count = recommendGenreCount($genre);
                    $res->webtoonList = recommendationHot($genre);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "추천 완결 웹툰 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                elseif($sort=="male"){
                    $res->count = recommendGenreCount($genre);
                    $res->webtoonList = recommendationMale($genre);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "추천 완결 웹툰 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                elseif($sort=="female"){
                    $res->count = recommendGenreCount($genre);
                    $res->webtoonList = recommendationFemale($genre);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "추천 완결 웹툰 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                elseif($sort=="update"){
                    $res->count = recommendGenreCount($genre);
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
         * API Name : 추천 완결 TOP10 API
         * 마지막 수정 날짜 : 20.11.08
         */
        case "top10":
            http_response_code(200);

            $choice = $_GET['choice'];

            if (!(($choice=="best")
                or ($choice=="related")
                or ($choice=="gender"))){
                $res->isSuccess = FALSE;
                $res->code = 320;
                $res->message = "존재하지 않은 TOP10 정보입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            if($choice=="best"){
                $res->text = "인기 수직 상승! 이번 주 급상승 TOP 10!";
                $res->webtoonList = popularity();
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "인기 top10 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif($choice=="related"){
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
                $title = top10Title($userIdxToken);

                if(mb_strlen($title, "UTF-8") > 12){
                    $res->text = mb_substr("$title 독자들이 좋아하는 추천완결", 0, 27, "UTF-8")."...";
                }
                else{
                    $res->text = "$title 독자들이 좋아하는 추천완결";
                }

                $res->webtoonList = related($title);
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "회원용 top10 조회 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            elseif($choice=="gender"){
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

                $gender = getGenderByUserIdx($userIdxToken);

                if($gender=='M'){
                    $res->text = "남성 독자님들이 이번 주 많이 본 추천완결 신작";
                    $res->webtoonList = bestByGender($gender);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "남성 top10 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                elseif($gender=='F'){
                    $res->text = "여성 독자님들이 이번 주 많이 본 추천완결 신작";
                    $res->webtoonList = bestByGender($gender);
                    $res->isSuccess = TRUE;
                    $res->code = 100;
                    $res->message = "여성 top10 조회 성공";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    break;
                }
                else{
                    $res->isSuccess = FALSE;
                    $res->code = 330;
                    $res->message = "올바르지 않은 성별입니다.";
                    echo json_encode($res, JSON_NUMERIC_CHECK);
                    return;
                }
            }


        /*
         * API No. 22
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
