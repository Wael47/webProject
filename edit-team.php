<?php
require_once "./parts/db.php";
session_start();

if ($_SERVER['REQUEST_METHOD'] == "POST") {
    $errorMessages = [];
    unset($_SESSION['successMessage']);
    unset($_SESSION['teamParams']);
    unset($_SESSION['error']);

    $params = [
        ':id' => $_GET['id'],
        ':team_name' => trim($_POST['teamName']),
        ':skill_level' => $_POST['skillLevel'],
        ':game_day' => trim($_POST['gameDay'])
    ];

    if (trim($_POST['teamName']) == '') {
        $errorMessages['messageTeamName'] = '* Team Name is Required';
    }

    if ($_POST['skillLevel'] == '') {
        $errorMessages['messageSkillLevel'] = '* Skill Level is Required';
    }
    if (!is_numeric($_POST['skillLevel'])) {
        $errorMessages['messageSkillLevel'] = '* Skill Level must be a number';
    }
    if ($_POST['skillLevel'] < 1 || $_POST['skillLevel'] > 5) {
        $errorMessages['messageSkillLevel'] = '* Skill Level must be a 1-5 ';
    }

    if (trim($_POST['gameDay']) == '') {
        $errorMessages['messageGameDay'] = '* Game Day is Required';
    }

    if (!isset($errorMessages['messageTeamName']) && !isset($errorMessages['messageSkillLevel']) && !isset($errorMessages['messageGameDay'])) {

        $sql = 'update teams t
                set t.team_name = :team_name, t.skill_level = :skill_level, t.game_day = :game_day
                where t.id = :id';
        $stmt = $pdo->prepare($sql);
        $stmt->execute($params);
        $_SESSION['successMessage'] = 'The edit operation was successful!';
        header("location: dashboard.php");
        exit();
    }
    $_SESSION['error'] = $errorMessages;
    $_SESSION['teamParams'] = [
        'team_name' => trim($_POST['teamName']),
        'skill_level' => $_POST['skillLevel'],
        'game_day' => trim($_POST['gameDay'])
    ];
    header("location: team-form.php?id=$_GET[id]");
}
