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

// RETURN BOOLEAN
    function isEmailExist($email){
        $pdo = pdoSqlConnect();
        $query = "SELECT EXISTS(SELECT * FROM Users WHERE email= ?) AS exist;";


        $st = $pdo->prepare($query);
        //    $st->execute([$param,$param]);
        $st->execute([$email]);
        $st->setFetchMode(PDO::FETCH_ASSOC);
        $res = $st->fetchAll();

        $st=null;$pdo = null;

        return intval($res[0]["exist"]);

    }

// UPDATE User
    function updateUser($id, $nickname, $email, $gender, $age){
        $pdo = pdoSqlConnect();
        $query = "INSERT INTO Users (userId, nickName, email, gender, age)
                             VALUES ('".$id."', '".$nickname."', '".$email."', '".$gender."', '".$age."')";

        $st = $pdo->prepare($query);
        $st->execute([$id, $nickname, $email, $gender, $age]);
        $st = null;
        $pdo = null;
    }