<?php
try {
    $protocol = 'mysql';
    $host = 'localhost';
    $db = 'football_teams';
    $username = 'root' ;
    $password = '';

    $pdo = new PDO("$protocol:host=$host;dbname=$db", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

} catch (Exception $e) {
    //throw $e;
    var_dump($e->getmessage());
    die();
}
