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
//INSERT
//function createUser($ID, $pwd, $name)
//{
//    $pdo = pdoSqlConnect();
//    $query = "INSERT INTO Users (ID, pwd, name) VALUES (?,?,?);";
//
//    $st = $pdo->prepare($query);
//    $st->execute([$ID, $pwd, $name]);
//
//    $st = null;
//    $pdo = null;
//
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
//state 발급
function generate_state()
{
    $mt = microtime();
    $rand = mt_rand();
    return md5($mt . $rand);
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
       IF(exists(select * from Alarm where webtoonIdx=$webtoonIdx and userIdx=1 and isDeleted='N'), 'Y', 'N') as alarm
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

//존재하는 댓글 Valid
function isValidComment($webtoonIdx, $episodeIdx, $commentIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select EXISTS(select * from Comment 
                            where webtoonIdx = $webtoonIdx and episodeIdx = $episodeIdx 
                            and commentIdx = $commentIdx and isDeleted = 'N') exist;";

    $st = $pdo->prepare($query);
    $st->execute([$webtoonIdx, $episodeIdx, $commentIdx]);
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
// 댓글 등록
function postComment($webtoonIdx, $episodeIdx, $userIdxToken, $content){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO Comment (webtoonIdx, episodeIdx, userIdx, content) VALUES ($webtoonIdx, $episodeIdx, $userIdxToken, '$content');";

    $st = $pdo->prepare($query);
    $st->execute([$webtoonIdx, $episodeIdx, $userIdxToken, $content]);

    $st = null;
    $pdo = null;

}

//댓글 조회 비회원
function getComment($webtoonIdx, $episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select if(isnull(Users.nickName), 
                        (select concat(left(userId, char_length(userId) / 2), 
                        repeat('*', char_length(userId) - char_length(left(userId, char_length(userId) / 2)))) from Users), 
                        Users.nickName) as name,
       date_format(Comment.updatedAt, '%Y-%c-%d %H:%i') as updatedTime,
       content,
       if(isnull(CL.countLike), 0, CL.countLike)        as upCount,
       if(isnull(CUL.countUnLike), 0, CUL.countUnLike)  as downCount
from Comment
         join Users on Users.userIdx = Comment.userIdx
         left outer join (select commentIdx, count(userIdx) as countLike
                          from CommentLike
                          where webtoonIdx = $webtoonIdx
                            and episodeIdx = $episodeIdx
                            and likeState = 'L'
                            and isDeleted = 'N'
                          group by commentIdx) CL on CL.commentIdx = Comment.commentIdx
         left outer join (select commentIdx, count(userIdx) as countUnLike
                          from CommentLike
                          where webtoonIdx = $webtoonIdx
                            and episodeIdx = $episodeIdx
                            and likeState = 'U'
                            and isDeleted = 'N'
                          group by commentIdx) CUL on CUL.commentIdx = Comment.commentIdx
where webtoonIdx = $webtoonIdx
  and episodeIdx = $episodeIdx and Comment.isDeleted = 'N';";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$webtoonIdx, $episodeIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}
//댓글 조회 회원용
function getCommentUser($webtoonIdx, $episodeIdx, $userIdxToken)
{
    $pdo = pdoSqlConnect();
    $query = "select if(isnull(Users.nickName), 
                        (select concat(left(userId, char_length(userId) / 2), 
                        repeat('*', char_length(userId) - char_length(left(userId, char_length(userId) / 2)))) from Users), 
                        Users.nickName) as name,
       date_format(Comment.updatedAt, '%Y-%c-%d %H:%i') as updatedTime,
       content,
       if(isnull(CL.countLike), 0, CL.countLike)        as upCount,
       if(isnull(CUL.countUnLike), 0, CUL.countUnLike)  as downCount,
       CASE WHEN likeState = 'U' then 'up' when likeState = 'D' then 'down' else 'default' end as upDownState,
       IF(Comment.useridx = $userIdxToken, 'Y', 'N') as isUsers
from Comment
         join Users on Users.userIdx = Comment.userIdx
         left outer join (select commentIdx, count(userIdx) as countLike
                          from CommentLike
                          where webtoonIdx = $webtoonIdx
                            and episodeIdx = $episodeIdx
                            and likeState = 'L'
                            and isDeleted = 'N'
                          group by commentIdx) CL on CL.commentIdx = Comment.commentIdx
         left outer join (select commentIdx, count(userIdx) as countUnLike
                          from CommentLike
                          where webtoonIdx = $webtoonIdx
                            and episodeIdx = $episodeIdx
                            and likeState = 'U'
                            and isDeleted = 'N'
                          group by commentIdx) CUL on CUL.commentIdx = Comment.commentIdx
         left outer join (select commentIdx, likeState
                          from CommentLike
                          where webtoonIdx = ?
                            and episodeIdx = ?
                            and userIdx = ?
                            and isDeleted = 'N') LS on LS.commentIdx = Comment.commentIdx
where webtoonIdx = $webtoonIdx
  and episodeIdx = $episodeIdx and Comment.isDeleted = 'N';";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$webtoonIdx, $episodeIdx, $userIdxToken]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res;
}

//댓글 개수 조회
function getCommentCount($webtoonIdx, $episodeIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select format(count(commentIdx), 0) as commentCount
from Comment
where webtoonIdx = $webtoonIdx
  and episodeIdx = $episodeIdx and isDeleted = 'N';";

    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$webtoonIdx, $episodeIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0];
}
//댓글 유저인덱스 조회
function getUserIdxComment($webtoonIdx, $episodeIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT userIdx FROM Comment WHERE webtoonIdx = $webtoonIdx and episodeIdx=$episodeIdx and commentIdx=$commentIdx;";
    $st = $pdo->prepare($query);
    $st->execute([$webtoonIdx, $episodeIdx, $commentIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();
    $st = null;
    $pdo = null;
    return $res[0]["userIdx"];
}

//댓글 삭제
function deleteComment($webtoonIdx, $episodeIdx, $commentIdx, $userIdxToken){
    $pdo = pdoSqlConnect();
    $query = "UPDATE Comment SET isDeleted='Y' where webtoonIdx = $webtoonIdx and episodeIdx=$episodeIdx and userIdx =$userIdxToken and isDeleted='N';";
    $st = $pdo->prepare($query);
    $st->execute([$webtoonIdx, $episodeIdx, $commentIdx, $userIdxToken]);
    $st = null;
    $pdo = null;
}
