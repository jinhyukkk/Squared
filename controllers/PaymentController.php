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

        case "payment" :
            http_response_code(200);

            require_once('../autoload.php');
            spl_autoload_register('BootpayAutoload');

            use Bootpay\Rest\BootpayApi;

            $receiptId = '[[ receipt_id ]]';

            $bootpay = BootpayApi::setConfig(
                "5face2878f075100207ddc6e",
                "tdKsHWqxQbIpJVU/t+rH8BD6TFDgkKC93behWKhoQJA="
            );

            $response = $bootpay->requestAccessToken();

// Token이 발행되면 그 이후에 verify 처리 한다.
            if ($response->status === 200) {
                $result = $bootpay->verify($receiptId);
                // 원래 주문했던 금액이 일치하는가?
                // 그리고 결제 상태가 완료 상태인가?
                if ($result->data->price === price && $result->data->status === 1) {
                    // TODO: 이곳이 상품 지급 혹은 결제 완료 처리를 하는 로직으로 사용하면 됩니다.
                }
            }

//            $curl = "-X POST https://api.bootpay.co.kr/request/token";
//                curl -H "Content-Type: application/json" \
//            -d '{"application_id": "5face2878f075100207ddc6e", "private_key": "tdKsHWqxQbIpJVU/t+rH8BD6TFDgkKC93behWKhoQJA="}' \


    }
} catch (\Exception $e) {
    return getSQLErrorException($errorLogs, $e, $req);
}
