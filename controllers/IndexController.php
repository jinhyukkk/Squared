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
