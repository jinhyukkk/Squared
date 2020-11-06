<?php

//요일별 웹툰 업데이트순
function getWebtoons_Update($keyword)
{
    $pdo = pdoSqlConnect();
    $query = "select Webtoon.webtoonIdx,
       thumbnailUrl,
       title,
       creator,
       IF(isnull(StarGrade.grade), 0, StarGrade.grade)                     as star,
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
where week = ? order by recentUpdate.recent desc;";

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
       IF(isnull(StarGrade.grade), 0, StarGrade.grade)                     as star,
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
where week = ? order by grade desc;";

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
       IF(isnull(StarGrade.grade), 0, StarGrade.grade)                     as star,
       viewType,
       IF(rate = 19, 'Y', 'N')                                             as adult,
       every24
from Webtoon
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = Webtoon.webtoonIdx
         left outer join (select max(updatedAt) as recent, webtoonIdx from Episode group by webtoonIdx) recentUpdate
                         on recentUpdate.webtoonIdx = Webtoon.webtoonIdx
where complete='Y'
order by grade desc;";

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
       IF(isnull(StarGrade.grade), 0, StarGrade.grade)                     as star,
       viewType,
       IF(rate = 19, 'Y', 'N')                                             as adult,
       every24
from Webtoon
         left outer join (select webtoonIdx, format(AVG(grade), 2) as grade from Star group by webtoonIdx) StarGrade
                         on StarGrade.webtoonIdx = Webtoon.webtoonIdx
         left outer join (select max(updatedAt) as recent, webtoonIdx from Episode group by webtoonIdx) recentUpdate
                         on recentUpdate.webtoonIdx = Webtoon.webtoonIdx
where complete='Y'
order by recentUpdate.recent desc;";

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
       IF(isnull(StarGrade.grade), 0, StarGrade.grade) as star,
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
where isnull(CountEpisode.webtoonIdx)
   or episode < 7
order by grade desc;";

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
       IF(isnull(StarGrade.grade), 0, StarGrade.grade) as star,
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
where isnull(CountEpisode.webtoonIdx)
   or episode < 7
order by recentUpdate.recent desc;";

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
    $query = "select EXISTS(select * from Webtoon where webtoonIdx = ?) exist;";

    $st = $pdo->prepare($query);
    $st->execute([$webtoonId]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['exist'];
}

//웹툰 상세조회
function getWebtoonDetail($webtoonIdx)
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
       IF(exists(select * from Interest where webtoonIdx=$webtoonIdx and userIdx=1 and isDeleted='N'), 'Y', 'N') as isInterested,
       IF(exists(select * from Notice where webtoonIdx=$webtoonIdx and userIdx=1 and isDeleted='N'), 'Y', 'N') as Notice
from Webtoon
where webtoonIdx = $webtoonIdx;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$webtoonIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

//웹툰 리스트 상세조회
function getWebtoonList($webtoonIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select Episode.episodeIdx,
       thumbnailUrl,
       title,
       IF(DATE(Episode.createdAt) = DATE(NOW()), 'Y', 'N')             as up,
       IF(isnull(StarGrade.grade), '0.00', format(StarGrade.grade, 2)) as star,
       date_format(updatedAt, '%y.%c.%d')                              as updatedDate,
       IF(isnull(music), 'N', 'Y') as music
from Episode
         left outer join (select webtoonIdx, episodeIdx, format(AVG(grade), 2) as grade
                          from Star
                          group by episodeIdx) StarGrade
                         on StarGrade.episodeIdx = Episode.episodeIdx
where Episode.webtoonIdx = ?
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
    $query = "select EXISTS(select * from Episode where webtoonIdx = $webtoonIdx and episodeIdx = $episodeIdx) exist;";

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
  and episodeIdx = $episodeIdx;";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$webtoonIdx, $episodeIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}

//웹툰 회차별 상세조회
function episodeContents($webtoonIdx, $episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select contentsUrl
from EpisodeContents
where webtoonIdx = ?
  and episodeIdx = ?
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