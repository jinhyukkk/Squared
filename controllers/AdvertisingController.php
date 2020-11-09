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
        * API No. 23
        * API Name : 광고 API
        * 마지막 수정 날짜 : 20.11.09
        */

        case "advertising":
            http_response_code(200);

            $page = $_GET['page'];

            if (!is_numeric($page)){
                $res->isSuccess = FALSE;
                $res->code = 370;
                $res->message = "잘못된 페이지입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if (!(0<$page and $page<11)){
                $res->isSuccess = FALSE;
                $res->code = 370;
                $res->message = "잘못된 페이지입니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            if(getAdvertising($page)){
                $res->result = getAdvertising($page);
            }
            else{
                $res->isSuccess = FALSE;
                $res->code = 380;
                $res->message = "광고 정보가 없습니다.";
                echo json_encode($res, JSON_NUMERIC_CHECK);
                break;
            }
            $res->isSuccess = TRUE;
            $res->code = 100;
            $res->message = "광고 조회 성공";
            echo json_encode($res, JSON_NUMERIC_CHECK);
            break;



    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
