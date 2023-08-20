<?php require_once './parts/header.php';
$sql = 'select t.*, COUNT(p.id) as "number_players"  
        FROM teams t 
        left join players p 
        on t.id = p.team_id
        where t.id = :id';
$stmt = $pdo->prepare($sql);
$stmt->bindValue(':id', $_GET['id']);
$stmt->execute();
$team = $stmt->fetch();

$teamPlayers =[];
if ($team['number_players']){
    $sql = 'select * from players p where p.team_id = :id;';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':id', $_GET['id']);
    $stmt->execute();
    $teamPlayers = $stmt->fetchAll();
}

$error = $_SESSION['error'] ?? '';
unset($_SESSION['error']);
?>
<main>
    <h1><?= $team['team_name']?></h1>
    <a href="dashboard.php">dashboard</a>

    <table>
        <tbody>
        <tr>
            <td>Team Name:</td>
            <td><?= $team['team_name']?></td>
        </tr>
        <tr>
            <td>Skill Level:</td>
            <td><?= $team['skill_level']?></td>
        </tr>
        <tr>
            <td>Game Day:</td>
            <td><?= $team['game_day']?></td>
        </tr>
        </tbody>
    </table>

    <table>
        <caption>Players</caption>
        <?php foreach ($teamPlayers as $player):?>
        <tr><td><?= $player['player_name']?></td></tr>
        <?php endforeach;?>
    </table>
    <?php if (isset($_SESSION['user']) && $team['user_id'] == $_SESSION['user']['id'] && count($teamPlayers) < 9):?>
    <form action="add-player.php?id=<?=$_GET['id']?>" method="post">
        <table>
            <caption>Add Player</caption>
            <tr>
                <td><label for="playerName">Player Name</label></td>
                <td>
                    <input type="text" name="playerName" id="playerName">
                    <?php if ($error): ?>
                        <div class="form-error">
                            <?= $error; ?>
                        </div>
                    <?php endif; ?>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <input type="submit" value="Add">
                </td>
            </tr>
        </table>
    </form>
    <?php elseif(isset($_SESSION['user']) && $team['user_id'] == $_SESSION['user']['id'] && count($teamPlayers) > 9):?>
    <div class="form-error">
        The team is full, you cannot add more
    </div>
    <?php endif;?>
    <?php if (isset($_SESSION['user']) && $team['user_id'] == $_SESSION['user']['id']):?>
    <a href="team-form.php?id=<?= $team['id']?>">Edit</a>
    <br>
    <a href="delete-team.php?id=<?= $team['id']?>">Delete</a>
    <?php endif;?>
</main>
<?php require_once './parts/footer.php' ?>