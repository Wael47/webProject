<?php
require_once "./parts/db.php";

$sql = 'DELETE FROM teams WHERE id = :id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $_GET['id']);
$stmt->execute();

header('location: dashboard.php');

