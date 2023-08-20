<?php require_once './parts/header.php';
if (isset($_GET['search']) && $_GET['search']=='my-team'){
    $sql = 'select t.* FROM teams t where t.user_id = :user_id';
    $stmt = $pdo->prepare($sql);
    $stmt->bindValue(':user_id',$_SESSION['user']['id']);
}else{
    $sql = 'select t.* FROM teams t';
    $stmt = $pdo->prepare($sql);
}
$stmt->execute();
$teams = $stmt->fetchAll();

?>
    <main>
        <h1> Welcome <?= $_SESSION['user']['username'] ?? '' ?></h1>

        <?php
        if (isset($_GET['search']) && $_GET['search']=='my-team'){
            echo ' <h2> Your teams </h2>';
        }
        ?>
            <table>
                <thead>
                <tr>
                    <th>Team Name</th>
                    <th>Skill Level (1-5)</th>
                    <th>Players</th>
                    <th>Game Day</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($teams as $team):
                    $sql = 'select count(p.id) as "number_players" FROM players p where p.team_id = :team_id';
                    $stmt = $pdo->prepare($sql);
                    $stmt->bindValue(':team_id', $team['id']);
                    $stmt->execute();
                    $playerCount = $stmt->fetch();
                    ?>
                    <tr>
                        <td><a href="details.php?id=<?= $team['id'] ?>"> <?= $team['team_name'] ?> </a></td>
                        <td> <?= $team['skill_level'] ?></td>
                        <td> <?= $playerCount['number_players'] ?? 0 ?>/9</td>
                        <td> <?= $team['game_day'] ?></td>
                    </tr>
                <?php endforeach; ?>
                <?php if (isset($_SESSION['user'])): ?>
                <tr>
                    <td colspan="5">
                        <a href="./team-form.php">
                            <button>Create New Team</button>
                        </a>
                    </td>
                </tr>
                <?php endif; ?>
                </tbody>
            </table>
            <div>



            </div>
    </main>
<?php require_once './parts/footer.php' ?>