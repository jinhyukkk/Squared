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
        * API Name : 네이버 로그인 API
        * 마지막 수정 날짜 : 20.11.02
        */

        case "naverLogin" :
            http_response_code(200);

            if (!isset($req->accessToken)) {
                $res->isSuccess = FALSE;
                $res->code = 350;
                $res->message = "accessToken이 없습니다";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            } else {
                $accessToken = $req->accessToken;
            }
            $curl = 'curl -v -X GET https://openapi.naver.com/v1/nid/me -H "Authorization: Bearer ' . $accessToken . '"';
            $info = shell_exec($curl);
            $info_arr = json_decode($info, true);

            if ($info_arr["message"] != "success") {
                $res->isSuccess = FALSE;
                $res->code = 360;
                $res->message = "소셜로그인 실패 : " . $info_arr["message"];
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }

            $id = $info_arr["response"]["id"];
            $nickname = $info_arr["response"]["nickname"];
            $email = $info_arr["response"]["email"];
            $gender = $info_arr["response"]["gender"];
            $age = $info_arr["response"]["age"];

            //존재하는 email 바로 로그인
            if (isEmailExist($email)) {
                $userIdx = getUserIdxByID($id);  // JWTPdo.php 에 구현
                $jwt = getJWT($userIdx, JWT_SECRET_KEY); // function.php 에 구현

                $res->jwt = $jwt;
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "jwt 발급 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            } //          등록하고 로그인
            else {
                updateUser($id, $nickname, $email, $gender, $age);
                $userIdx = getUserIdxByID($id);  // JWTPdo.php 에 구현
                $jwt = getJWT($userIdx, JWT_SECRET_KEY); // function.php 에 구현

                $res->jwt = $jwt;
                $res->isSuccess = TRUE;
                $res->code = 100;
                $res->message = "jwt 발급 성공";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
