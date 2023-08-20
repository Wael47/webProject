<?php require_once './parts/header.php';

$successMessage = $_SESSION['successMessage'] ?? '';
unset($_SESSION['successMessage']);

//To prevent entry if he is not logged and redirect to index
if (!isset($_SESSION['user'])) {
    header('location: index.php');
    exit();
}

//to fill data if team id in URL
if (isset($_GET['id'])) {
    $sql = 'select * from teams t where id = :id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    $teamData = $stmt->fetch();
    // is team not exists redirect to dashboard
    if (!$teamData) {
        header('location: dashboard.php');
        exit();
    }
    // if the user enter not the creator of team redirect to dashboard
    if ($_SESSION['user']['id'] != $teamData['user_id']) {
        header('location: dashboard.php');
        exit();
    }
    $errorMessages = $_SESSION['error'] ?? [] ;
    $teamData = $_SESSION['teamParams'] ?? $teamData;
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $teamData = [
        'team_name' => $_POST['teamName'],
        'skill_level' => $_POST['skillLevel'],
        'game_day' => $_POST['gameDay']
    ];

    $errorMessages = [];
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

        $sql = 'insert into teams(user_id, team_name, skill_level, game_day) 
                values (:user_id, :team_name, :skill_level, :game_day)';
        $params[':user_id'] = $_SESSION['user']['id'];
        $stmt = $pdo->prepare($sql);
        $params[':team_name'] = trim($_POST['teamName']);
        $params[':skill_level'] = $_POST['skillLevel'];
        $params[':game_day'] = trim($_POST['gameDay']);
        $stmt->execute($params);
        $teamData = [];
        $_SESSION['successMessage'] = 'The add operation was successful!';
        header('location: dashboard.php');
        exit();
    }
}
?>
    <main>
        <?php if (isset($successMessage)):?>
            <div class="form-success">
                <?=$successMessage?>
            </div>
        <?php endif;?>
        <h1><?= !isset($_GET['id']) ? 'New Team' : 'Edit Team' ?></h1>
        <a href="dashboard.php">dashboard</a>
        <form action="<?= isset($_GET['id']) ? "./edit-team.php?id=$_GET[id]" : $_SERVER['PHP_SELF'] ?>" method="post">
            <table>
                <tbody>
                <tr>
                    <td><label for="teamName">Team Name</label></td>
                    <td>
                        <input type="text" name="teamName" id="teamName" value="<?= $teamData['team_name'] ?? '' ?>">
                        <?php if (isset($errorMessages['messageTeamName'])): ?>
                            <div class="form-error">
                                <?= $errorMessages['messageTeamName'] ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="skillLevel">Skill Level(1-5)</label></td>
                    <td>
                        <input type="number" name="skillLevel" id="skillLevel"
                               value="<?= $teamData['skill_level'] ?? 1 ?>" min="1" max="5">
                        <?php if (isset($errorMessages['messageSkillLevel'])): ?>
                            <div class="form-error">
                                <?= $errorMessages['messageSkillLevel'] ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="gameDay">Game Day</label></td>
                    <td>
                        <input type="text" name="gameDay" id="gameDay" value="<?= $teamData['game_day'] ?? '' ?>">
                        <?php if (isset($errorMessages['messageGameDay'])): ?>
                            <div class="form-error">
                                <?= $errorMessages['messageGameDay'] ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" value="Submit">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
        <?php if (isset($_GET['id'])):?>
            <a href="delete-team.php?id=<?=$_GET['id']?>">Delete Team</a>
        <?php endif;?>
    </main>
<?php require_once './parts/footer.php' ?>