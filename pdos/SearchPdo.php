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
//
//웹툰 검색
function searchWebtoon($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), '0.00', format(StarGrade.grade, 2)) as star,
       viewType,
       every24,
       IF(rate = 19, 'Y', 'N')                                         as adult,
       cookie,
       complete
from Webtoon W
         left outer join (select webtoonIdx, episodeIdx, format(AVG(grade), 2) as grade
                          from Star
                          group by episodeIdx) StarGrade
                         on StarGrade.webtoonIdx = W.webtoonIdx
where isDeleted = 'N' and webtoonType='웹툰'
  and (title like '%$keyword%' or creator like '%$keyword%');";

    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//웹툰 검색 결과 개수
function searchWebtoonCount($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select count(webtoonIdx) count
from Webtoon
where isDeleted = 'N' and webtoonType='웹툰'
  and (title like '%$keyword%' or creator like '%$keyword%');";

    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['count'];
}
//베스트 도전 검색
function searchChallenge($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), '0.00', format(StarGrade.grade, 2)) as star,
       promotion,
       complete
from Webtoon W
         left outer join (select webtoonIdx, episodeIdx, format(AVG(grade), 2) as grade
                          from Star
                          group by episodeIdx) StarGrade
                         on StarGrade.webtoonIdx = W.webtoonIdx
where isDeleted = 'N'
  and webtoonType = '베스트도전'
  and (title like '%$keyword%' or creator like '%$keyword%');";

    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//베스트도전 검색 결과 개수
function searchChallengeCount($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select count(webtoonIdx) count
from Webtoon
where isDeleted = 'N'
  and webtoonType = '베스트도전'
  and (title like '%$keyword%' or creator like '%$keyword%');";

    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['count'];
}
//웹툰 검색 limit5
function searchWebtoonLimit($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), '0.00', format(StarGrade.grade, 2)) as star,
       viewType,
       every24,
       IF(rate = 19, 'Y', 'N')                                         as adult,
       cookie,
       complete
from Webtoon W
         left outer join (select webtoonIdx, episodeIdx, format(AVG(grade), 2) as grade
                          from Star
                          group by episodeIdx) StarGrade
                         on StarGrade.webtoonIdx = W.webtoonIdx
where isDeleted = 'N' and webtoonType='웹툰'
  and (title like '%$keyword%' or creator like '%$keyword%') limit 5;";

    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//베스트 도전 검색 limit5
function searchChallengeLimit($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), '0.00', format(StarGrade.grade, 2)) as star,
       promotion,
       complete
from Webtoon W
         left outer join (select webtoonIdx, episodeIdx, format(AVG(grade), 2) as grade
                          from Star
                          group by episodeIdx) StarGrade
                         on StarGrade.webtoonIdx = W.webtoonIdx
where isDeleted = 'N'
  and webtoonType = '베스트도전'
  and (title like '%$keyword%' or creator like '%$keyword%') limit 5;";

    $st = $pdo->prepare($query);
    $st->execute([$keyword]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}