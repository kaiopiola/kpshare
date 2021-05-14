<?php
$servername = "localhost";
$username = "user";
$password = "pass";
$dbname = "dbname";

try {
    $pdo = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo $e->getMessage();
}
date_default_timezone_set("America/Sao_Paulo");


function FetchAll($sql)
{
    global $pdo;
    try {
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $count = $statement->rowCount();
        $select = $statement->fetchAll(\PDO::FETCH_OBJ);
    } catch (\PDOException $e) {
        echo $e->getMessage();
    }
    return $select;
}

function FetchRow($sql)
{
    global $pdo;
    try {
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $count = $statement->rowCount();
        $select = $statement->fetch(\PDO::FETCH_OBJ);
    } catch (\PDOException $e) {
        echo $e->getMessage();
    }
    return $select;
}

function Query($sql)
{
    global $pdo;

    try {
        $statement = $pdo->prepare($sql);
        $statement->execute();
        $result['id'] = $pdo->lastInsertId();
        $result['status'] = 200;
    } catch (\PDOException $e) {
        $result['msg'] = $e->getMessage();
        $result['status'] = 400;
    }
    return $result;
}
