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

//요일별 웹툰 업데이트순
function getWebtoons_Update($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select Webtoon.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade)                     as star,
       IF(isnull(nowUpdate.webtoonIdx), 'N', 'Y')                          as up,
       IF(isnull(CountEpisode.webtoonIdx), 'N', IF(episode < 7, 'Y', 'N')) as new,
       viewType,
       IF(rate = 19, 'Y', 'N')                                             as adult,
       rest
from Webtoon
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = Webtoon.webtoonIdx
         left outer join (select webtoonIdx
                          from Episode
                          where DATE(createdAt) = DATE(NOW())
                          group by webtoonIdx) nowUpdate on nowUpdate.webtoonIdx = Webtoon.webtoonIdx
         left outer join (select webtoonIdx, count(episodeIdx) as episode from Episode group by webtoonIdx) CountEpisode
                         on CountEpisode.webtoonIdx = Webtoon.webtoonIdx
         left outer join (select max(updatedAt) as recent, webtoonIdx from Episode group by webtoonIdx) recentUpdate
                         on recentUpdate.webtoonIdx = Webtoon.webtoonIdx
where week = ? and Webtoon.isDeleted = 'N' order by recentUpdate.recent desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
// 요일별 웹툰 인기순
function getWebtoons_Hot($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select Webtoon.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade)                     as star,
       IF(isnull(nowUpdate.webtoonIdx), 'N', 'Y')                          as up,
       IF(isnull(CountEpisode.webtoonIdx), 'N', IF(episode < 7, 'Y', 'N')) as new,
       viewType,
       IF(rate = 19, 'Y', 'N')                                             as adult,
       rest
from Webtoon
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = Webtoon.webtoonIdx
         left outer join (select webtoonIdx
                          from Episode
                          where DATE(createdAt) = DATE(NOW())
                          group by webtoonIdx) nowUpdate on nowUpdate.webtoonIdx = Webtoon.webtoonIdx
         left outer join (select webtoonIdx, count(episodeIdx) as episode from Episode group by webtoonIdx) CountEpisode
                         on CountEpisode.webtoonIdx = Webtoon.webtoonIdx
where week = ? and Webtoon.isDeleted = 'N' order by grade desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
// 요일별 웹툰 조회순
function getWebtoons_View($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade)                     as star,
       IF(isnull(nowUpdate.webtoonIdx), 'N', 'Y')                          as up,
       IF(isnull(CountEpisode.webtoonIdx), 'N', IF(episode < 7, 'Y', 'N')) as new,
       viewType,
       IF(rate = 19, 'Y', 'N')                                             as adult,
       rest
from Webtoon W
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = W.webtoonIdx
         left outer join (select webtoonIdx
                          from Episode
                          where DATE(createdAt) = DATE(NOW())
                          group by webtoonIdx) nowUpdate on nowUpdate.webtoonIdx = W.webtoonIdx
         left outer join (select webtoonIdx, count(episodeIdx) as episode from Episode group by webtoonIdx) CountEpisode
                         on CountEpisode.webtoonIdx = W.webtoonIdx
         left join (select webtoonIdx, count(userIdx) views from History group by webtoonIdx) V
                   on W.webtoonIdx = V.webtoonIdx
where week = ?
  and W.isDeleted = 'N'
order by V.views desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
// 요일별 웹툰 남성인기순
function getWebtoons_Male($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade)                     as star,
       IF(isnull(nowUpdate.webtoonIdx), 'N', 'Y')                          as up,
       IF(isnull(CountEpisode.webtoonIdx), 'N', IF(episode < 7, 'Y', 'N')) as new,
       viewType,
       IF(rate = 19, 'Y', 'N')                                             as adult,
       rest
from Webtoon W
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = W.webtoonIdx
         left outer join (select webtoonIdx
                          from Episode
                          where DATE(createdAt) = DATE(NOW())
                          group by webtoonIdx) nowUpdate on nowUpdate.webtoonIdx = W.webtoonIdx
         left outer join (select webtoonIdx, count(episodeIdx) as episode from Episode group by webtoonIdx) CountEpisode
                         on CountEpisode.webtoonIdx = W.webtoonIdx
         left join (select webtoonIdx, count(H.userIdx) views
                    from History H
                             left join Users U on H.userIdx = U.userIdx
                        where gender = 'M'
                    group by webtoonIdx) V
                   on W.webtoonIdx = V.webtoonIdx
where week = ?
  and W.isDeleted = 'N'
order by V.views desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
// 요일별 웹툰 여성인기순
function getWebtoons_Female($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade)                     as star,
       IF(isnull(nowUpdate.webtoonIdx), 'N', 'Y')                          as up,
       IF(isnull(CountEpisode.webtoonIdx), 'N', IF(episode < 7, 'Y', 'N')) as new,
       viewType,
       IF(rate = 19, 'Y', 'N')                                             as adult,
       rest
from Webtoon W
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = W.webtoonIdx
         left outer join (select webtoonIdx
                          from Episode
                          where DATE(createdAt) = DATE(NOW())
                          group by webtoonIdx) nowUpdate on nowUpdate.webtoonIdx = W.webtoonIdx
         left outer join (select webtoonIdx, count(episodeIdx) as episode from Episode group by webtoonIdx) CountEpisode
                         on CountEpisode.webtoonIdx = W.webtoonIdx
         left join (select webtoonIdx, count(H.userIdx) views
                    from History H
                             left join Users U on H.userIdx = U.userIdx
                        where gender = 'F'
                    group by webtoonIdx) V
                   on W.webtoonIdx = V.webtoonIdx
where week = ?
  and W.isDeleted = 'N'
order by V.views desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$keyword]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//완결웹툰 인기순
function finishedWebtoons_Hot()
{
    $pdo = pdoSqlConnect();
    $query = "select Webtoon.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade)                     as star,
       viewType,
       IF(rate = 19, 'Y', 'N')                                             as adult,
       every24
from Webtoon
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = Webtoon.webtoonIdx
where complete='Y' and Webtoon.isDeleted = 'N' order by grade desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//완결웹툰 조회순
function finishedWebtoons_View()
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade)                     as star,
       viewType,
       IF(rate = 19, 'Y', 'N')                                             as adult,
       every24
from Webtoon W
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = W.webtoonIdx
         left join (select webtoonIdx, count(userIdx) views from History group by webtoonIdx) V
                   on W.webtoonIdx = V.webtoonIdx
where complete='Y' and W.isDeleted = 'N' order by V.views desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//완결웹툰 남성 조회순
function finishedWebtoons_Male()
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade) as star,
       viewType,
       IF(rate = 19, 'Y', 'N')                              as adult,
       every24
from Webtoon W
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = W.webtoonIdx
         left join (select webtoonIdx, count(H.userIdx) views
                    from History H
                             left join Users U on H.userIdx = U.userIdx
                    where gender = 'M'
                    group by webtoonIdx) V
                   on W.webtoonIdx = V.webtoonIdx
where complete = 'Y'
  and W.isDeleted = 'N'
order by V.views desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//완결웹툰 여성 조회순
function finishedWebtoons_Female()
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade) as star,
       viewType,
       IF(rate = 19, 'Y', 'N')                              as adult,
       every24
from Webtoon W
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = W.webtoonIdx
         left join (select webtoonIdx, count(H.userIdx) views
                    from History H
                             left join Users U on H.userIdx = U.userIdx
                    where gender = 'F'
                    group by webtoonIdx) V
                   on W.webtoonIdx = V.webtoonIdx
where complete = 'Y'
  and W.isDeleted = 'N'
order by V.views desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//완결웹툰 업데이트순
function finishedWebtoons_Update()
{
    $pdo = pdoSqlConnect();
    $query = "select Webtoon.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade)                     as star,
       viewType,
       IF(rate = 19, 'Y', 'N')                                             as adult,
       every24
from Webtoon
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = Webtoon.webtoonIdx
         left outer join (select max(updatedAt) as recent, webtoonIdx from Episode group by webtoonIdx) recentUpdate
                         on recentUpdate.webtoonIdx = Webtoon.webtoonIdx
where complete='Y' and Webtoon.isDeleted = 'N' order by recentUpdate.recent desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//신작 웹툰 인기순
function newWebtoons_Hot()
{
    $pdo = pdoSqlConnect();
    $query = "select Webtoon.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       case
           when isnull(week) then '요일정보 없음'
           when week = 'mon' then '월'
           when week = 'tue' then '화'
           when week = 'wed' then '수'
           when week = 'thur' then '목'
           when week = 'fri' then '금'
           when week = 'sat' then '토'
           when week = 'sun' then '일' end              as week,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade) as star,
       IF(isnull(nowUpdate.webtoonIdx), 'N', 'Y')      as up,
       viewType,
       IF(rate = 19, 'Y', 'N')                         as adult,
       rest
from Webtoon
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = Webtoon.webtoonIdx
         left outer join (select webtoonIdx
                          from Episode
                          where DATE(createdAt) = DATE(NOW())
                          group by webtoonIdx) nowUpdate on nowUpdate.webtoonIdx = Webtoon.webtoonIdx
         left outer join (select webtoonIdx, count(episodeIdx) as episode from Episode group by webtoonIdx) CountEpisode
                         on CountEpisode.webtoonIdx = Webtoon.webtoonIdx
where (isnull(CountEpisode.webtoonIdx) or episode < 7) and Webtoon.isDeleted = 'N' and Webtoon.complete = 'N' order by grade desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//신작 웹툰 조회순
function newWebtoons_View()
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       case
           when isnull(week) then '요일정보 없음'
           when week = 'mon' then '월'
           when week = 'tue' then '화'
           when week = 'wed' then '수'
           when week = 'thur' then '목'
           when week = 'fri' then '금'
           when week = 'sat' then '토'
           when week = 'sun' then '일' end              as week,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade) as star,
       IF(isnull(nowUpdate.webtoonIdx), 'N', 'Y')      as up,
       viewType,
       IF(rate = 19, 'Y', 'N')                         as adult,
       rest
from Webtoon W
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = W.webtoonIdx
         left outer join (select webtoonIdx
                          from Episode
                          where DATE(createdAt) = DATE(NOW())
                          group by webtoonIdx) nowUpdate on nowUpdate.webtoonIdx = W.webtoonIdx
         left outer join (select webtoonIdx, count(episodeIdx) as episode from Episode group by webtoonIdx) CountEpisode
                         on CountEpisode.webtoonIdx = W.webtoonIdx
         left join (select webtoonIdx, count(userIdx) views from History group by webtoonIdx) V
                   on W.webtoonIdx = V.webtoonIdx
where (isnull(CountEpisode.webtoonIdx) or episode < 7) and W.isDeleted = 'N' and W.complete = 'N'
order by V.views desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//신작 웹툰 남성 인기순
function newWebtoons_Male()
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       case
           when isnull(week) then '요일정보 없음'
           when week = 'mon' then '월'
           when week = 'tue' then '화'
           when week = 'wed' then '수'
           when week = 'thur' then '목'
           when week = 'fri' then '금'
           when week = 'sat' then '토'
           when week = 'sun' then '일' end              as week,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade) as star,
       IF(isnull(nowUpdate.webtoonIdx), 'N', 'Y')      as up,
       viewType,
       IF(rate = 19, 'Y', 'N')                         as adult,
       rest
from Webtoon W
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = W.webtoonIdx
         left outer join (select webtoonIdx
                          from Episode
                          where DATE(createdAt) = DATE(NOW())
                          group by webtoonIdx) nowUpdate on nowUpdate.webtoonIdx = W.webtoonIdx
         left outer join (select webtoonIdx, count(episodeIdx) as episode from Episode group by webtoonIdx) CountEpisode
                         on CountEpisode.webtoonIdx = W.webtoonIdx
         left join (select webtoonIdx, count(H.userIdx) views
                    from History H
                             left join Users U on H.userIdx = U.userIdx
                        where gender = 'M'
                    group by webtoonIdx) V
                   on W.webtoonIdx = V.webtoonIdx
where (isnull(CountEpisode.webtoonIdx) or episode < 7)
  and W.isDeleted = 'N'
  and W.complete = 'N'
order by V.views desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//신작 웹툰 여성 인기순
function newWebtoons_Female()
{
    $pdo = pdoSqlConnect();
    $query = "select W.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       case
           when isnull(week) then '요일정보 없음'
           when week = 'mon' then '월'
           when week = 'tue' then '화'
           when week = 'wed' then '수'
           when week = 'thur' then '목'
           when week = 'fri' then '금'
           when week = 'sat' then '토'
           when week = 'sun' then '일' end              as week,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade) as star,
       IF(isnull(nowUpdate.webtoonIdx), 'N', 'Y')      as up,
       viewType,
       IF(rate = 19, 'Y', 'N')                         as adult,
       rest
from Webtoon W
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = W.webtoonIdx
         left outer join (select webtoonIdx
                          from Episode
                          where DATE(createdAt) = DATE(NOW())
                          group by webtoonIdx) nowUpdate on nowUpdate.webtoonIdx = W.webtoonIdx
         left outer join (select webtoonIdx, count(episodeIdx) as episode from Episode group by webtoonIdx) CountEpisode
                         on CountEpisode.webtoonIdx = W.webtoonIdx
         left join (select webtoonIdx, count(H.userIdx) views
                    from History H
                             left join Users U on H.userIdx = U.userIdx
                        where gender = 'F'
                    group by webtoonIdx) V
                   on W.webtoonIdx = V.webtoonIdx
where (isnull(CountEpisode.webtoonIdx) or episode < 7)
  and W.isDeleted = 'N'
  and W.complete = 'N'
order by V.views desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//신작 웹툰 업데이트순
function newWebtoons_Update()
{
    $pdo = pdoSqlConnect();
    $query = "select Webtoon.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       case
           when isnull(week) then '요일정보 없음'
           when week = 'mon' then '월'
           when week = 'tue' then '화'
           when week = 'wed' then '수'
           when week = 'thur' then '목'
           when week = 'fri' then '금'
           when week = 'sat' then '토'
           when week = 'sun' then '일' end              as week,
       IF(isnull(StarGrade.grade), '0.00', StarGrade.grade) as star,
       IF(isnull(nowUpdate.webtoonIdx), 'N', 'Y')      as up,
       viewType,
       IF(rate = 19, 'Y', 'N')                         as adult,
       rest
from Webtoon
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = Webtoon.webtoonIdx
         left outer join (select webtoonIdx
                          from Episode
                          where DATE(createdAt) = DATE(NOW())
                          group by webtoonIdx) nowUpdate on nowUpdate.webtoonIdx = Webtoon.webtoonIdx
         left outer join (select webtoonIdx, count(episodeIdx) as episode from Episode group by webtoonIdx) CountEpisode
                         on CountEpisode.webtoonIdx = Webtoon.webtoonIdx
         left outer join (select max(updatedAt) as recent, webtoonIdx from Episode group by webtoonIdx) recentUpdate
                         on recentUpdate.webtoonIdx = Webtoon.webtoonIdx
where (isnull(CountEpisode.webtoonIdx) or episode < 7) and Webtoon.isDeleted = 'N' and Webtoon.complete = 'N' order by recentUpdate.recent desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}


//존재하는 웹툰 Valid
function isValidWebtoon($webtoonId)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from Webtoon where webtoonIdx = ? and isDeleted = 'N') exist;";

    $st = $pdo->prepare($query);
    $st->execute([$webtoonId]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}

//웹툰 상세조회 - 회원용
function getWebtoonDetail($webtoonIdx, $userIdxToken)
{
    $pdo = pdoSqlConnect();
    $query = "select subThumbnailUrl,
       color,
       title,
       creator,
       case
           when isnull(week) then '요일정보 없음'
           when week = 'mon' then '월요웹툰'
           when week = 'tue' then '화요웹툰'
           when week = 'wed' then '수요웹툰'
           when week = 'thur' then '목요웹툰'
           when week = 'fri' then '금요웹툰'
           when week = 'sat' then '토요웹툰'
           when week = 'sun' then '일요웹툰' end as week,
       summary,
       IF(exists(select * from Interest where webtoonIdx=$webtoonIdx and userIdx=$userIdxToken and isDeleted='N'), 'Y', 'N') as isInterested,
       IF(exists(select * from Notice where webtoonIdx=$webtoonIdx and userIdx=$userIdxToken and isDeleted='N'), 'Y', 'N') as Notice
from Webtoon
where webtoonIdx = $webtoonIdx and Webtoon.isDeleted = 'N';";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$webtoonIdx, $userIdxToken]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

//웹툰 리스트 상세조회 - 회원용
function getWebtoonList($webtoonIdx, $userIdxToken)
{
    $pdo = pdoSqlConnect();
    $query = "select Episode.episodeIdx,
       thumbnailUrl,
       title,
       IF(DATE(Episode.createdAt) = DATE(NOW()), 'Y', 'N')             as up,
       IF(isnull(StarGrade.grade), '0.00', format(StarGrade.grade, 2)) as star,
       date_format(Episode.updatedAt, '%y.%c.%d')                      as updatedDate,
       IF(isnull(music), 'N', 'Y')                                     as music,
       IF(S.createdAt, IF(timediff(DATE_ADD(S.createdAt, INTERVAL 48 HOUR), now()) < 0, '저장기간만료', '임시저장됨'),
          '표시안함')                                                      as isSaved,
       IF(H.episodeIdx, 'Y', 'N')                                     as isSaw
from Episode
         left outer join (select webtoonIdx, episodeIdx, format(AVG(grade), 2) as grade
                          from Star
                          group by episodeIdx) StarGrade
                         on StarGrade.episodeIdx = Episode.episodeIdx
         left outer join (select * from Storage where userIdx = $userIdxToken and isDeleted = 'N') S on Episode.episodeIdx = S.episodeIdx
         left join (select * from History where userIdx = $userIdxToken and webtoonIdx = $webtoonIdx) H on Episode.episodeIdx = H.episodeIdx
where Episode.webtoonIdx = $webtoonIdx
order by episodeIdx desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$webtoonIdx, $userIdxToken]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//웹툰 상세조회 - 비회원용
function getWebtoonDetailNonMember($webtoonIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select subThumbnailUrl,
       color,
       title,
       creator,
       case
           when isnull(week) then '요일정보 없음'
           when week = 'mon' then '월요웹툰'
           when week = 'tue' then '화요웹툰'
           when week = 'wed' then '수요웹툰'
           when week = 'thur' then '목요웹툰'
           when week = 'fri' then '금요웹툰'
           when week = 'sat' then '토요웹툰'
           when week = 'sun' then '일요웹툰' end as week,
       summary,
       'N' as isInterested,
       'N' as Notice
from Webtoon
where webtoonIdx = $webtoonIdx and Webtoon.isDeleted = 'N';";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$webtoonIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

//웹툰 리스트 상세조회 - 비회원용
function getWebtoonListNonMember($webtoonIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select Episode.episodeIdx,
       thumbnailUrl,
       title,
       IF(DATE(Episode.createdAt) = DATE(NOW()), 'Y', 'N')             as up,
       IF(isnull(StarGrade.grade), '0.00', format(StarGrade.grade, 2)) as star,
       date_format(Episode.updatedAt, '%y.%c.%d')                      as updatedDate,
       IF(isnull(music), 'N', 'Y')                                     as music,
       '표시안함'                                                      as isSaved,
       'N'                                     as isSaw
from Episode
         left outer join (select webtoonIdx, episodeIdx, format(AVG(grade), 2) as grade
                          from Star
                          group by episodeIdx) StarGrade
                         on StarGrade.episodeIdx = Episode.episodeIdx
where Episode.webtoonIdx = $webtoonIdx
order by episodeIdx desc;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$webtoonIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//존재하는 에피소드 Valid
function isValidEpisode($webtoonIdx, $episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from Episode where webtoonIdx = $webtoonIdx 
                                                    and episodeIdx = $episodeIdx 
                                                    and isDeleted = 'N') exist;";

    $st = $pdo->prepare($query);
    $st->execute([$webtoonIdx, $episodeIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}

//웹툰 회차별 상세조회
function episodeView($webtoonIdx, $episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select episodeIdx,
       title,
       (select format(count(userIdx), 0) as heartCount
        from Heart
        where webtoonIdx = $webtoonIdx and episodeIdx = $episodeIdx) as heartCount,
       (select format(count(commentIdx), 0) as commentCount
        from Comment
        where webtoonIdx = $webtoonIdx and episodeIdx = $episodeIdx) as commentCount,
       IF(exists(select *
                 from Heart
                 where webtoonIdx = $webtoonIdx and episodeIdx = $episodeIdx and userIdx = 1 and isDeleted = 'N'), 'Y',
          'N')                                                       as heartStatus
from Episode
where webtoonIdx = $webtoonIdx
  and episodeIdx = $episodeIdx 
  and isDeleted = 'N';";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$webtoonIdx, $episodeIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

//웹툰 회차별 내용 상세조회
function episodeContents($webtoonIdx, $episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select contentsUrl
from EpisodeContents
where webtoonIdx = $webtoonIdx
  and episodeIdx = $episodeIdx
order by contentsUrl;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$webtoonIdx, $episodeIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
// 존재하는 viewPoint
function isExistViewPoint($userIdxToken, $webtoonIdx, $episodeIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(SELECT * FROM History WHERE userIdx = $userIdxToken 
                                                and webtoonIdx = $webtoonIdx and episodeIdx = $episodeIdx
                                                and isDeleted = 'N') AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}
// CREATE viewPoint
    function creatViewPoint($userIdxToken, $webtoonIdx, $episodeIdx){
        $pdo = pdoSqlConnect();
        $query = "INSERT INTO History (userIdx, webtoonIdx, episodeIdx) VALUES ($userIdxToken, $webtoonIdx, $episodeIdx);";

        $st = $pdo->prepare($query);
        $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx]);

        $st = null;
        $pdo = null;

    }
// UPDATE viewPoint
    function updateViewPoint($userIdxToken, $webtoonIdx, $episodeIdx){
        $pdo = pdoSqlConnect();
        $query = "UPDATE History
                        SET episodeIdx = IF(episodeIdx < $episodeIdx, $episodeIdx, episodeIdx), updatedAt = now()
                        WHERE userIdx = $userIdxToken and webtoonIdx = $webtoonIdx and isDeleted = 'N';";

        $st = $pdo->prepare($query);
        $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx]);
        $st = null;
        $pdo = null;
    }
//최근 본 웹툰 조회
function getRecentlyView($userIdxToken)
{
    $pdo = pdoSqlConnect();
    $query = "select H.webtoonIdx,
       title,
       thumbnailUrl,
       IF(datediff(now(), H.updatedAt) = 0, '오늘', concat(datediff(now(), H.updatedAt), '일전')) as lastDate,
       concat(MAX(episodeIdx), '화 이어보기')                                                  as viewPoint,
       IF(isnull(CountEpisode.webtoonIdx), 'N', IF(episode < 7, 'Y', 'N'))                    as new,
       viewType,
       complete                                                                               as isFinished,
       every24
from History H
         join Webtoon W on H.webtoonIdx = W.webtoonIdx
         left outer join (select webtoonIdx, count(episodeIdx) as episode from Episode group by webtoonIdx) CountEpisode
                         on CountEpisode.webtoonIdx = H.webtoonIdx
where userIdx = 1 and H.isDeleted = 'N' group by H.webtoonIdx;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

// 존재하는 최근 본 웹툰
function isExistsRecentlyView($userIdxToken){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select * from History 
                where userIdx = $userIdxToken and isDeleted = 'N' group by userIdx) AS exist;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}
//최근 본 웹툰 개수 조회
function getRecentlyViewCount($userIdxToken)
{
    $pdo = pdoSqlConnect();
    $query = "select count(webtoonIdx) as count from (select H.webtoonIdx,
       title,
       thumbnailUrl,
       IF(datediff(now(), H.updatedAt) = 0, '오늘', concat(datediff(now(), H.updatedAt), '일전')) as lastDate,
       concat(MAX(episodeIdx), '화 이어보기')                                                  as viewPoint,
       IF(isnull(CountEpisode.webtoonIdx), 'N', IF(episode < 7, 'Y', 'N'))                    as new,
       viewType,
       complete                                                                               as isFinished,
       every24
from History H
         join Webtoon W on H.webtoonIdx = W.webtoonIdx
         left outer join (select webtoonIdx, count(episodeIdx) as episode from Episode group by webtoonIdx) CountEpisode
                         on CountEpisode.webtoonIdx = H.webtoonIdx
where userIdx = $userIdxToken and H.isDeleted = 'N' group by H.webtoonIdx) H";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]["count"];
}
// 에피소드 등록
    function postEpisode($webtoonIdx, $episodeIdx, $title, $thumbnailUrl, $words){
        $pdo = pdoSqlConnect();
        $query = "INSERT INTO Episode (webtoonIdx, episodeIdx, title, thumbnailUrl, words) VALUES ($webtoonIdx, $episodeIdx, '".$title."', '".$thumbnailUrl."', '".$words."');";

        $st = $pdo->prepare($query);
        $st->execute([$webtoonIdx, $episodeIdx, $title, $thumbnailUrl, $words]);

        $st = null;
        $pdo = null;

    }
//FCM
function send_notification ($tokens, $message)
{
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
    return $result;
}

//READ
function getNoticeUser($webtoonIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select concat(userIdx, '번 유저에게 메세지 전송') massage from Notice where webtoonIdx = $webtoonIdx;";

    $st = $pdo->prepare($query);
    $st->execute([$webtoonIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}