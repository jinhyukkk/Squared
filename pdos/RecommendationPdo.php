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

// 추천 완결 관심 웹툰
function userComment($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select concat(userId, '님, 무료회차가 생겼어요!') as userComment from Users where userIdx = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['userComment'];
}
// 추천 완결 관심 웹툰
function interestedComplete($userIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx, W.title, W.thumbnailUrl, IF(rate = 19, 'Y', 'N') adult
from Interest I
         left join Webtoon W on I.webtoonIdx = W.webtoonIdx
where userIdx = $userIdx
  and complete = 'Y'
  and every24 = 'Y'
  and I.isDeleted = 'N'
  and W.isDeleted = 'N';";

    $st = $pdo->prepare($query);
    $st->execute([$userIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
// 추천 완결 개수
function recommendAllCount()
{
    $pdo = pdoSqlConnect();
    $query = "select count(W.webtoonIdx) as count
from Webtoon W where complete = 'Y' and every24 = 'Y' and isDeleted = 'N';";

    $st = $pdo->prepare($query);
    $st->execute([]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]["count"];
}

// 추천 완결 최신순
function recommendationUpdateAll()
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       title,
       thumbnailUrl,
       creator,
       subSummary                                                  as summary,
       concat('총', IF(isnull(totalEpisode), 0, totalEpisode), '화') as totalEpisode,
       IF(rate = 19, 'Y', 'N')                                     as adult,
       every24
from Webtoon W
         left join (select webtoonIdx, min(episodeIdx), count(episodeIdx) as totalEpisode, createdAt
                    from Episode
                    group by webtoonIdx) E
                   on W.webtoonIdx = E.webtoonIdx
where complete = 'Y'
  and isDeleted = 'N' and every24 = 'Y'
order by E.createdAt desc;";

    $st = $pdo->prepare($query);
    $st->execute([]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// 추천 완결 인기순
function recommendationHotAll()
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       title,
       thumbnailUrl,
       creator,
       subSummary                                                  as summary,
       concat('총', IF(isnull(totalEpisode), 0, totalEpisode), '화') as totalEpisode,
       IF(rate = 19, 'Y', 'N')                                     as adult,
       every24
from Webtoon W
         left join (select webtoonIdx, min(episodeIdx), count(episodeIdx) as totalEpisode, createdAt
                    from Episode
                    group by webtoonIdx) E
                   on W.webtoonIdx = E.webtoonIdx
left join (select webtoonIdx, count(userIdx) views from History group by webtoonIdx) V on W.webtoonIdx=V.webtoonIdx
where complete = 'Y'
  and isDeleted = 'N' and every24 = 'Y'
order by V.views desc;";

    $st = $pdo->prepare($query);
    $st->execute([]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
// 추천 완결 남자인기순
function recommendationMaleAll()
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       title,
       thumbnailUrl,
       creator,
       subSummary                                                  as summary,
       concat('총', IF(isnull(totalEpisode), 0, totalEpisode), '화') as totalEpisode,
       IF(rate = 19, 'Y', 'N')                                     as adult,
       every24
from Webtoon W
         left join (select webtoonIdx, min(episodeIdx), count(episodeIdx) as totalEpisode, createdAt
                    from Episode
                    group by webtoonIdx) E
                   on W.webtoonIdx = E.webtoonIdx
         left join (select webtoonIdx, count(H.userIdx) views
                    from History H
                             join (select * from Users where gender = 'M') U on H.userIdx = U.userIdx
                    group by webtoonIdx) M on M.webtoonIdx=W.webtoonIdx
where complete = 'Y'
  and W.isDeleted = 'N'
  and every24 = 'Y' order by M.views desc;";

    $st = $pdo->prepare($query);
    $st->execute([]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// 추천 완결 여자인기순
function recommendationFemaleAll()
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       title,
       thumbnailUrl,
       creator,
       subSummary                                                  as summary,
       concat('총', IF(isnull(totalEpisode), 0, totalEpisode), '화') as totalEpisode,
       IF(rate = 19, 'Y', 'N')                                     as adult,
       every24
from Webtoon W
         left join (select webtoonIdx, min(episodeIdx), count(episodeIdx) as totalEpisode, createdAt
                    from Episode
                    group by webtoonIdx) E
                   on W.webtoonIdx = E.webtoonIdx
         left join (select webtoonIdx, count(H.userIdx) views
                    from History H
                             join (select * from Users where gender = 'F') U on H.userIdx = U.userIdx
                    group by webtoonIdx) F on F.webtoonIdx=W.webtoonIdx
where complete = 'Y'
  and W.isDeleted = 'N'
  and every24 = 'Y' order by F.views desc;";

    $st = $pdo->prepare($query);
    $st->execute([]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// 추천 완결 장르 구분 최신순
function recommendationUpdate($genre)
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       title,
       thumbnailUrl,
       creator,
       subSummary                                                  as summary,
       concat('총', IF(isnull(totalEpisode), 0, totalEpisode), '화') as totalEpisode,
       IF(rate = 19, 'Y', 'N')                                     as adult,
       every24
from Webtoon W
         left join (select webtoonIdx, min(episodeIdx), count(episodeIdx) as totalEpisode, createdAt
                    from Episode
                    group by webtoonIdx) E
                   on W.webtoonIdx = E.webtoonIdx
where genre = ?
  and complete = 'Y'
  and isDeleted = 'N' and every24 = 'Y'
order by E.createdAt desc;";

    $st = $pdo->prepare($query);
    $st->execute([$genre]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}