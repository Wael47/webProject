<?php
require_once "./parts/db.php";
session_start();
if ($_SERVER['REQUEST_METHOD'] == 'POST'){

    unset($_SESSION['error']);

    if (trim($_POST['playerName']) == '') {
        $_SESSION['error'] = '* Player Name is Required';
    }else{
        $sql = 'insert into players(player_name, team_id) VALUES (:player_name, :team_id)';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':player_name', $_POST['playerName']);
        $stmt->bindValue(':team_id', $_GET['id']);
        $stmt->execute();
    }

    header("location: details.php?id=$_GET[id]");
    exit();
}