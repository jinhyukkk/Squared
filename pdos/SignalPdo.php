<?php
// CREATE
//    function addMaintenance($message){
//        $pdo = pdoSqlConnect();
//        $query = "INSERT INTO MAINTENANCE (MESSAGE) VALUES (?);";
//
//        $st = $pdo->prepare($query);
//        $st->execute([$message]);
//
//        $st = null;
//        $pdo = null;
//
//    }
// UPDATE
//    function updateMaintenanceStatus($message, $status, $no){
//        $pdo = pdoSqlConnect();
//        $query = "UPDATE MAINTENANCE
//                        SET MESSAGE = ?,
//                            STATUS  = ?
//                        WHERE NO = ?";
//
//        $st = $pdo->prepare($query);
//        $st->execute([$message, $status, $no]);
//        $st = null;
//        $pdo = null;
//    }
// RETURN BOOLEAN
//    function isRedundantEmail($email){
//        $pdo = pdoSqlConnect();
//        $query = "SELECT EXISTS(SELECT * FROM USER_TB WHERE EMAIL= ?) AS exist;";
//
//
//        $st = $pdo->prepare($query);
//        //    $st->execute([$param,$param]);
//        $st->execute([$email]);
//        $st->setFetchMode(PDO::FETCH_ASSOC);
//        $res = $st->fetchAll();
//
//        $st=null;$pdo = null;
//
//        return intval($res[0]["exist"]);
//
//    }
////READ
//function getUserDetail($userIdx)
//{
//    $pdo = pdoSqlConnect();
//    $query = "select * from Users where userIdx = ?;";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$userIdx]);
//    //    $st->execute();
//    $st->setFetchMode(PDO::FETCH_ASSOC);
//    $res = $st->fetchAll();
//
//    $st = null;
//    $pdo = null;
//
//    return $res[0];
//}

// 존재하는 heart
function isExistsHeart($userIdxToken, $webtoonIdx, $episodeIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select * from Heart where userIdx = $userIdxToken and webtoonIdx = $webtoonIdx and episodeIdx = $episodeIdx) AS exist;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}

//heart 등록
function registerHeart($userIdxToken, $webtoonIdx, $episodeIdx){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO Heart (webtoonIdx, episodeIdx, userIdx) VALUES ($webtoonIdx, $episodeIdx, $userIdxToken);";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx]);

    $st = null;
    $pdo = null;

}

//현재 하트 상태
function currentHeartStatus($userIdxToken, $webtoonIdx, $episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select IF(isDeleted='Y', '취소되었습니다.', '하트 등록') AS state from Heart 
                where userIdx = $userIdxToken and webtoonIdx = $webtoonIdx and episodeIdx = $episodeIdx;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]["state"];
}

//하트 수정
function modifyHeart($userIdxToken, $webtoonIdx, $episodeIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE Heart SET isDeleted = if(isDeleted = 'Y', 'N','Y') 
                        where webtoonIdx = $webtoonIdx and episodeIdx=$episodeIdx and userIdx =$userIdxToken;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx]);
    $st = null;
    $pdo = null;
}
// 존재하는 Interest
function isExistsInterest($userIdxToken, $webtoonIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select * from Interest where userIdx = $userIdxToken and webtoonIdx = $webtoonIdx) AS exist;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken, $webtoonIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}
//Interest 등록
function registInterest($userIdxToken, $webtoonIdx){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO Interest (webtoonIdx, userIdx) VALUES ($webtoonIdx, $userIdxToken);";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx]);

    $st = null;
    $pdo = null;

    return "관심 등록";
}
//Notice 등록
function registNotice($userIdxToken, $webtoonIdx){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO Notice (webtoonIdx, userIdx) VALUES ($webtoonIdx, $userIdxToken);";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx]);

    $st = null;
    $pdo = null;

}
//현재 관심 상태
function currentInterestStatus($userIdxToken, $webtoonIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select IF(isDeleted='Y', '관심웹툰이 해제되었습니다.', '관심웹툰으로 등록되었어요. 새롭게 생겼거나, 놓치고 계신 무료 회차 알림을 드려요.') AS state from Interest 
                where userIdx = $userIdxToken and webtoonIdx = $webtoonIdx;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]["state"];
}
//관심 수정
function modifyInterest($userIdxToken, $webtoonIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE Interest as I, Notice as A
SET I.isDeleted = if(I.isDeleted = 'Y', 'N', 'Y'),
    A.isDeleted = I.isDeleted
where I.webtoonIdx = $webtoonIdx
  and I.userIdx = $userIdxToken
  and A.webtoonIdx = $webtoonIdx
  and A.userIdx = $userIdxToken;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx]);
    $st = null;
    $pdo = null;
}
// 등록되어있는 Interest
function isValidInterest($userIdxToken, $webtoonIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select * from Interest where userIdx = $userIdxToken and webtoonIdx = $webtoonIdx and isDeleted = 'N') AS exist;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken, $webtoonIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}
// 존재하는 Notice
function isExistsNotice($userIdxToken, $webtoonIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select * from Notice where userIdx = $userIdxToken and webtoonIdx = $webtoonIdx) AS exist;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken, $webtoonIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}
//알림 수정
function modifyNotice($userIdxToken, $webtoonIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE Notice SET isDeleted = if(isDeleted = 'Y', 'N', 'Y')
                    where webtoonIdx = $webtoonIdx and userIdx = $userIdxToken;";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx]);
    $st = null;
    $pdo = null;
}
//현재 알림 상태
function currentNoticeStatus($userIdxToken, $webtoonIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select IF(isDeleted='Y', '알림 해제', '알림 등록') AS state from Notice 
                where userIdx = $userIdxToken and webtoonIdx = $webtoonIdx;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]["state"];
}

//관심웹툰 조회
function getInterested($userIdxToken)
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       title,
       thumbnailUrl,
       IF(isnull(E.webtoonIdx), 'N', 'Y')                                                 as upSign,
       IF(lastUpdate.updatedAt, date_format(lastUpdate.updatedAt, '%y.%c.%d'), '에피소드 없음') as updatedAt,
       IF(isnull(N.isDeleted), 'N', if(N.isDeleted = 'N', 'Y', 'N'))                      as isNotice
from Interest I
         join Webtoon W on I.webtoonIdx = W.webtoonIdx
         left outer join (select * from Notice where userIdx = $userIdxToken) N on I.webtoonIdx = N.webtoonIdx
         left outer join (select webtoonIdx
                          from Episode
                          where DATE(createdAt) = DATE(NOW())
                          group by webtoonIdx) E on E.webtoonIdx = W.webtoonIdx
         left join (select webtoonIdx, max(updatedAt) updatedAt from Episode group by webtoonIdx) lastUpdate
                   on lastUpdate.webtoonIdx = I.webtoonIdx
where I.userIdx = $userIdxToken
  and I.isDeleted = 'N';";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// 존재하는 관심웹툰
function isExistsInterested($userIdxToken){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select count(webtoonIdx) as count from Interest 
                where userIdx = $userIdxToken and isDeleted = 'N' group by userIdx) AS exist;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}
//관심웹툰 개수 조회
function getInterestedCount($userIdxToken)
{
    $pdo = pdoSqlConnect();
    $query = "select concat('전체 ', count(webtoonIdx)) as count from Interest 
                where userIdx = $userIdxToken and isDeleted = 'N' group by userIdx;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]["count"];
}