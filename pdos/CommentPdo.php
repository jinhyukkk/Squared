<?php

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
       CASE WHEN likeState = 'L' then 'Like' when likeState = 'U' then 'unLike' else 'default' end as upDownState,
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
    $query = "UPDATE Comment SET isDeleted='Y' where webtoonIdx = $webtoonIdx and episodeIdx=$episodeIdx 
                and userIdx = $userIdxToken and commentIdx = $commentIdx and isDeleted='N';";
    $st = $pdo->prepare($query);
    $st->execute([$webtoonIdx, $episodeIdx, $commentIdx, $userIdxToken]);
    $st = null;
    $pdo = null;
}
// 이미 존재하는 UnLike
function isValidCommentUnLike($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select * from CommentLike where userIdx = $userIdxToken 
                                and webtoonIdx = $webtoonIdx and episodeIdx = $episodeIdx and commentIdx = $commentIdx
                                and isDeleted = 'N' and likeState = 'U') AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}

// 이미 존재하는 Like
function isValidCommentLike($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select * from CommentLike where userIdx = $userIdxToken 
                                and webtoonIdx = $webtoonIdx and episodeIdx = $episodeIdx and commentIdx = $commentIdx
                                and isDeleted = 'N' and likeState = 'L') AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}

// 존재하는 Like
function isExistsCommentLikeState($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select * from CommentLike where userIdx = $userIdxToken 
                                and webtoonIdx = $webtoonIdx and episodeIdx = $episodeIdx and commentIdx = $commentIdx and likeState = 'L') AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}
// 존재하는 UnLike
function isExistsCommentUnLikeState($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "SELECT EXISTS(select * from CommentLike where userIdx = $userIdxToken 
                                and webtoonIdx = $webtoonIdx and episodeIdx = $episodeIdx and commentIdx = $commentIdx and likeState = 'U') AS exist;";


    $st = $pdo->prepare($query);
    //    $st->execute([$param,$param]);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return intval($res[0]["exist"]);

}

//댓글 좋아요 등록
function registerCommentLike($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO CommentLike (webtoonIdx, episodeIdx, commentIdx, userIdx, likeState) 
                    VALUES ($webtoonIdx, $episodeIdx, $commentIdx, $userIdxToken, 'L');";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx]);

    $st = null;
    $pdo = null;

}

//댓글 싫어요 등록
function registerCommentUnLike($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "INSERT INTO CommentLike (webtoonIdx, episodeIdx, commentIdx, userIdx, likeState) 
                    VALUES ($webtoonIdx, $episodeIdx, $commentIdx, $userIdxToken, 'U');";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx]);

    $st = null;
    $pdo = null;

}

//현재 좋아요 상태
function currentCommentLikeStatus($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select IF(isDeleted='Y', '좋아요 취소', '좋아요') AS state from CommentLike where userIdx = $userIdxToken and webtoonIdx = $webtoonIdx 
                                                and episodeIdx = $episodeIdx and commentIdx = $commentIdx and likeState = 'L';";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]["state"];
}

//현재 싫어요 상태
function currentCommentUnLikeStatus($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx)
{
    $pdo = pdoSqlConnect();
    $query = "select IF(isDeleted='Y', '싫어요 취소', '싫어요') AS state from CommentLike where userIdx = $userIdxToken and webtoonIdx = $webtoonIdx 
                                                and episodeIdx = $episodeIdx and commentIdx = $commentIdx and likeState = 'U';";

    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx]);
    //    $st->execute();
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]["state"];
}

//댓글 좋아요 수정
function modifyCommentLike($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE CommentLike SET isDeleted = if(isDeleted = 'Y', 'N','Y') 
                        where webtoonIdx = $webtoonIdx and episodeIdx=$episodeIdx and userIdx =$userIdxToken and commentIdx=$commentIdx and likeState = 'L';";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx]);
    $st = null;
    $pdo = null;
}

//댓글 싫어요 수정
function modifyCommentUnLike($userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx){
    $pdo = pdoSqlConnect();
    $query = "UPDATE CommentLike SET isDeleted = if(isDeleted = 'Y', 'N','Y') 
                        where webtoonIdx = $webtoonIdx and episodeIdx=$episodeIdx and userIdx =$userIdxToken and commentIdx=$commentIdx and likeState = 'U';";
    $st = $pdo->prepare($query);
    $st->execute([$userIdxToken, $webtoonIdx, $episodeIdx, $commentIdx]);
    $st = null;
    $pdo = null;
}