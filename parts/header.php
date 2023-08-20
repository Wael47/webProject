<?php require_once "./parts/db.php";
session_start();
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <link rel="stylesheet" href="./css/websiteElement.css">
    <link rel="stylesheet" href="./css/layout.css">
    <title>Managing Football Teams</title>
</head>
<body>
    <header>
        <figure>
            <img src="./images/logo.png"
                 alt="Logo">
            <figcaption>Managing Football Teams</figcaption>
        </figure>
        <a href="../">About us</a>
        <?php if (isset($_SESSION['user'])):?>
        <figure>
            <img src="./images/userImage.png"
                 alt="user image">
            <figcaption><?= $_SESSION['user']['username']?></figcaption>
            <a href="./logout.php">Log out</a>
        </figure>
        <?php endif;?>
    </header>
    <div>
        <aside>
            <nav>
                <?php if(!isset($_SESSION['user'])):?>
                <a href="./index.php">Login</a>
                <a href="./dashboard.php">Dashboard</a>
                <?php else:?>
                    <a href="./dashboard.php">Dashboard</a>
                    <a href="./team-form.php">Create New Team</a>
                    <a href="./dashboard.php?search=my-team">Edit Team</a>
                <?php endif;?>
            </nav>
        </aside>