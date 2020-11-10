<?php

require './pdos/DatabasePdo.php';
require './pdos/IndexPdo.php';
require './pdos/WebtoonPdo.php';
require './pdos/CommentPdo.php';
require './pdos/SignalPdo.php';
require './pdos/StoragePdo.php';
require './pdos/JWTPdo.php';
require './pdos/RecommendationPdo.php';
require './pdos/AdvertisingPdo.php';
require './pdos/SearchPdo.php';
require './vendor/autoload.php';

use \Monolog\Logger as Logger;
use Monolog\Handler\StreamHandler;

date_default_timezone_set('Asia/Seoul');
ini_set('default_charset', 'utf8mb4');

//에러출력하게 하는 코드
error_reporting(E_ALL); ini_set("display_errors", 1);

//Main Server API
$dispatcher = FastRoute\simpleDispatcher(function (FastRoute\RouteCollector $r) {


    /* ******************   Test   ****************** */
    $r->addRoute('GET', '/', ['IndexController', 'index']);
    $r->addRoute('GET', '/webtoon', ['WebtoonController', 'getWebtoons']);
    $r->addRoute('GET', '/webtoon/{webtoonId}', ['WebtoonController', 'webtoonList']);
    $r->addRoute('GET', '/webtoon/{webtoonId}/episode/{episodeId}', ['WebtoonController', 'episodeView']);
    $r->addRoute('POST', '/comment', ['CommentController', 'postComment']);
    $r->addRoute('GET', '/comment', ['CommentController', 'getComment']);
    $r->addRoute('DELETE', '/comment', ['CommentController', 'deleteComment']);
    $r->addRoute('POST', '/webtoon/{webtoonId}/episode/{episodeId}/comment/{commentId}/like', ['CommentController', 'commentLike']);
    $r->addRoute('POST', '/webtoon/{webtoonId}/episode/{episodeId}/comment/{commentId}/unLike', ['CommentController', 'commentUnLike']);
    $r->addRoute('POST', '/webtoon/{webtoonId}/episode/{episodeId}/heart', ['SignalController', 'episodeHeart']);
    $r->addRoute('POST', '/webtoon/{webtoonId}/interest', ['SignalController', 'registInterest']);
    $r->addRoute('POST', '/webtoon/{webtoonId}/notice', ['SignalController', 'registNotice']);
    $r->addRoute('POST', '/storage', ['StorageController', 'registerStorage']);
    $r->addRoute('GET', '/storage', ['StorageController', 'getStorage']);
    $r->addRoute('GET', '/storage/webtoon/{webtoonId}', ['StorageController', 'getStorageDetail']);
    $r->addRoute('DELETE', '/storage/webtoon/{webtoonId}/episode/{episodeId}', ['StorageController', 'deleteStorage']);
    $r->addRoute('DELETE', '/storage/webtoon/{webtoonId}/expiration', ['StorageController', 'deleteExpiration']);
    $r->addRoute('GET', '/interested', ['SignalController', 'getInterested']);
    $r->addRoute('GET', '/recentlyView', ['WebtoonController', 'recentlyView']);
    $r->addRoute('GET', '/recommendation', ['RecommendationController', 'recommendation']);
    $r->addRoute('GET', '/recommendation/top10', ['RecommendationController', 'top10']);
    $r->addRoute('GET', '/recommendation/interested', ['RecommendationController', 'freeRounds']);
    $r->addRoute('GET', '/advertising', ['AdvertisingController', 'advertising']);
    $r->addRoute('GET', '/search', ['SearchController', 'search']);


//    $r->addRoute('GET', '/users/{userIdx}', ['IndexController', 'getUserDetail']);
//    $r->addRoute('POST', '/user', ['IndexController', 'createUser']); // 비밀번호 해싱 예시 추가

    /* ******************   JWT   ****************** */
    $r->addRoute('POST', '/jwt', ['JWTController', 'createJwt']);   // JWT 생성: 로그인
    $r->addRoute('POST', '/autoLogin', ['JWTController', 'validateJwt']);  // JWT 유효성 검사
    $r->addRoute('POST', '/naverLogin', ['IndexController', 'naverLogin']);



//    $r->addRoute('GET', '/users', 'get_all_users_handler');
//    // {id} must be a number (\d+)
//    $r->addRoute('GET', '/user/{id:\d+}', 'get_user_handler');
//    // The /{title} suffix is optional
//    $r->addRoute('GET', '/articles/{id:\d+}[/{title}]', 'get_article_handler');
});

// Fetch method and URI from somewhere
$httpMethod = $_SERVER['REQUEST_METHOD'];
$uri = $_SERVER['REQUEST_URI'];

// Strip query string (?foo=bar) and decode URI
if (false !== $pos = strpos($uri, '?')) {
    $uri = substr($uri, 0, $pos);
}
$uri = rawurldecode($uri);

$routeInfo = $dispatcher->dispatch($httpMethod, $uri);

// 로거 채널 생성
$accessLogs = new Logger('ACCESS_LOGS');
$errorLogs = new Logger('ERROR_LOGS');
// log/your.log 파일에 로그 생성. 로그 레벨은 Info
$accessLogs->pushHandler(new StreamHandler('logs/access.log', Logger::INFO));
$errorLogs->pushHandler(new StreamHandler('logs/errors.log', Logger::ERROR));
// add records to the log
//$log->addInfo('Info log');
// Debug 는 Info 레벨보다 낮으므로 아래 로그는 출력되지 않음
//$log->addDebug('Debug log');
//$log->addError('Error log');

switch ($routeInfo[0]) {
    case FastRoute\Dispatcher::NOT_FOUND:
        // ... 404 Not Found
        echo "404 Not Found";
        break;
    case FastRoute\Dispatcher::METHOD_NOT_ALLOWED:
        $allowedMethods = $routeInfo[1];
        // ... 405 Method Not Allowed
        echo "405 Method Not Allowed";
        break;
    case FastRoute\Dispatcher::FOUND:
        $handler = $routeInfo[1];
        $vars = $routeInfo[2];

        switch ($routeInfo[1][0]) {
            case 'IndexController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/IndexController.php';
                break;
            case 'JWTController':
                $handler = $routeInfo[1][1];
                $vars = $routeInfo[2];
                require './controllers/JWTController.php';
                break;
            case 'WebtoonController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/WebtoonController.php';
                break;
            case 'CommentController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/CommentController.php';
                break;
            case 'SignalController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/SignalController.php';
                break;
            case 'StorageController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/StorageController.php';
                break;
            case 'RecommendationController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/RecommendationController.php';
                break;
            case 'AdvertisingController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/AdvertisingController.php';
                break;
            case 'SearchController':
                $handler = $routeInfo[1][1]; $vars = $routeInfo[2];
                require './controllers/SearchController.php';
                break;

        }

        break;
}
