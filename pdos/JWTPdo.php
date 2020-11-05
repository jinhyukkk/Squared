<?php

function isValidUser($userId, $pwd){
    $pdo = pdoSqlConnect();
    $query = "SELECT userId, pwd as hash FROM Users WHERE userId= '".$userId."' and pwd = '".$pwd."';";


    $st = $pdo->prepare($query);
    $st->execute([$userId, $pwd]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st=null;$pdo = null;

    return password_verify($pwd, $res[0]['hash']);

}
function getUserIdxByID($ID)
{
    $pdo = pdoSqlConnect();
    $query = "SELECT userIdx FROM Users WHERE userId = ?;";

    $st = $pdo->prepare($query);
    $st->execute([$ID]);
    $st->setFetchMode(PDO::FETCH_ASSOC);
    $res = $st->fetchAll();

    $st = null;
    $pdo = null;

    return $res[0]['userIdx'];
}