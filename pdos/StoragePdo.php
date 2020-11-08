<?php

// 임시저장
function registerStorage($userIdxToken, $webtoonIdx, $episodeIdx){
$pdo = pdoSqlConnect();
$query = "INSERT INTO Storage (userIdx, webtoonIdx, episodeIdx) VALUES ($userIdxToken, $webtoonIdx, $episodeIdx);";

$st = $pdo->prepare($query);
$st->execute([$userIdxToken, $webtoonIdx, $episodeIdx]);

$st = null;
$pdo = null;

}

//임시저장 조회
function getStorage($userIdxToken)
{
$pdo = pdoSqlConnect();
$query = "select distinct W.webtoonIdx, W.title, W.thumbnailUrl, W.creator
from Storage
left outer join Webtoon W on W.webtoonIdx = Storage.webtoonIdx
where userIdx = $userIdxToken and W.isDeleted = 'N';";

$st = $pdo->prepare($query);
//    $st->execute([$param,$param]);
$st->execute([$userIdxToken]);
$st->setFetchMode(PDO::FETCH_ASSOC);
$res = $st->fetchAll();

$st = null;
$pdo = null;

return $res;
}

//임시저장 개수 조회
function getStorageCount($userIdxToken)
{
$pdo = pdoSqlConnect();
$query = "select concat('전체 ', count(S.webtoonIdx)) as count
from (select distinct W.webtoonIdx, W.title, W.thumbnailUrl, W.creator
from Storage
left outer join Webtoon W on W.webtoonIdx = Storage.webtoonIdx
where userIdx = $userIdxToken and W.isDeleted = 'N') S";

$st = $pdo->prepare($query);
//    $st->execute([$param,$param]);
$st->execute([$userIdxToken]);
$st->setFetchMode(PDO::FETCH_ASSOC);
$res = $st->fetchAll();

$st = null;
$pdo = null;

return $res[0]["count"];
}
//임시저장 제목 조회
function getWebtoonTitle($userIdxToken, $webtoonIdx)
{
$pdo = pdoSqlConnect();
$query = "select distinct title
from Storage
join Webtoon W on Storage.webtoonIdx = W.webtoonIdx
where userIdx = $userIdxToken
and Storage.webtoonIdx = $webtoonIdx
and Storage.isDeleted = 'N';";

$st = $pdo->prepare($query);
//    $st->execute([$param,$param]);
$st->execute([$userIdxToken, $webtoonIdx]);
$st->setFetchMode(PDO::FETCH_ASSOC);
$res = $st->fetchAll();

$st = null;
$pdo = null;

return $res[0]['title'];
}
// 존재하는 임시저장
function isValidStorage($userIdxToken, $webtoonIdx){
$pdo = pdoSqlConnect();
$query = "SELECT EXISTS(select * from Storage where userIdx = $userIdxToken and webtoonIdx = $webtoonIdx and isDeleted = 'N') AS exist;";

$st = $pdo->prepare($query);
//    $st->execute([$param,$param]);
$st->execute([$userIdxToken, $webtoonIdx]);
$st->setFetchMode(PDO::FETCH_ASSOC);
$res = $st->fetchAll();

$st=null;$pdo = null;

return intval($res[0]["exist"]);

}

//임시저장 상세조회
function getStorageDetail($userIdxToken, $webtoonIdx)
{
$pdo = pdoSqlConnect();
$query = "select S.episodeIdx,
E.title,
E.thumbnailUrl,
IF(timediff(DATE_ADD(S.createdAt, INTERVAL 48 HOUR), now()) < 0, '저장기간만료',
concat('만료 ', substring(timediff(DATE_ADD(S.createdAt, INTERVAL 48 HOUR), now()), 1, 2), '시간 ',
substring(timediff(DATE_ADD(S.createdAt, INTERVAL 48 HOUR), now()), 4, 2), '분 남음')) as expirationTime
from Storage S
join Episode E on S.episodeIdx = E.episodeIdx
where S.userIdx = $userIdxToken
and S.webtoonIdx = $webtoonIdx
and S.isDeleted = 'N'
order by episodeIdx desc;";

$st = $pdo->prepare($query);
//    $st->execute([$param,$param]);
$st->execute([$userIdxToken, $webtoonIdx]);
$st->setFetchMode(PDO::FETCH_ASSOC);
$res = $st->fetchAll();

$st = null;
$pdo = null;

return $res;
}
// 존재하는 임시저장 회차
function isValidStorageEpisode($userIdxToken, $webtoonIdx, $episodeIdx){
$pdo = pdoSqlConnect();
$query = "SELECT EXISTS(select * from Storage where userIdx = $userIdxToken
and webtoonIdx = $webtoonIdx and episodeIdx = $episodeIdx and isDeleted = 'N') AS exist;";

$st = $pdo->prepare($query);
//    $st->execute([$param,$param]);
$st->execute([$userIdxToken, $webtoonIdx, $episodeIdx]);
$st->setFetchMode(PDO::FETCH_ASSOC);
$res = $st->fetchAll();

$st=null;$pdo = null;

return intval($res[0]["exist"]);

}
//임시저장 삭제
function deleteStorage($userIdxToken, $webtoonIdx, $episodeIdx){
$pdo = pdoSqlConnect();
$query = "UPDATE Storage SET isDeleted='Y' where webtoonIdx = $webtoonIdx and episodeIdx=$episodeIdx
and userIdx =$userIdxToken and isDeleted='N';";
$st = $pdo->prepare($query);
$st->execute([$userIdxToken, $webtoonIdx, $episodeIdx]);
$st = null;
$pdo = null;
}