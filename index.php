<?php require_once './parts/header.php';

if (isset($_SESSION['user'])) {
    header('location: dashboard.php');
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $errorMessages = [];
    if ($_POST['submit'] == 'Register') {
        $errorMessages = validateRegisterForm($pdo);
        if ($errorMessages['isValid']) {
            $sql = 'INSERT INTO users (username, email, _password) VALUES (:username,:email,:password)';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':username', trim($_POST['username']));
            $stmt->bindValue(':email', trim($_POST['email']));
            $stmt->bindValue(':password', $_POST['password']);
            $stmt->execute();

            header('location: index.php');
            exit();
        }

    } elseif ($_POST['submit'] == 'Login') {

        if (trim($_POST['email']) == '') {
            $errorMessages['messageEmailLogin'] = '* Email is Required';
        }

        if ($_POST['password'] == '') {
            $errorMessages['messagePasswordLogin'] = '* Password is Required';
        }
        if (!isset($errorMessages['messageEmailLogin']) && !isset($errorMessages['messagePasswordLogin'])) {
            $sql = 'select * from users u where u.email = :email and u._password = :password';
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':email', $_POST['email']);
            $stmt->bindValue(':password', $_POST['password']);
            $stmt->execute();
            $user = $stmt->fetch();
            if ($user) {
                $_SESSION['user'] = $user;
                header('location: dashboard.php');
                exit();
            }
            $errorMessages['messagePasswordLogin'] = '* Invalid username or password';
        }
    }
}
function validateRegisterForm($pdo): array
{
    $errorMessages = [];
    if (trim($_POST['username']) == '') {
        $errorMessages['messageUsername'] = '* User Name is Required';
    }

    if (trim($_POST['email']) == '') {
        $errorMessages['messageEmail'] = '* Email is Required';
    } else {
        $sql = 'select * from users u where u.email = :email';
        $stmt = $pdo->prepare($sql);
        $stmt->bindValue(':email', trim($_POST['email']));
        $stmt->execute();
        if ($stmt->fetchAll()) {
            $errorMessages['messageEmail'] = "* this email {$_POST['email']} is exists";
        }
    }

    if (strlen(trim($_POST['password'])) < 8) {
        $errorMessages['messagePassword'] = '* the password must be at least 8 characters long';
    } else {
        if ($_POST['password'] != $_POST['confirmPw']) {
            $errorMessages['messageConfirmPassword'] = '* Password is not equal Confirm Password';
        }
    }

    if (!$errorMessages) {
        $errorMessages['isValid'] = true;
    } else {
        $errorMessages['isValid'] = false;
    }
    return $errorMessages;
}

?>
    <main>
        <h1> Welcome <?= $_SESSION['user']['username'] ?? '' ?></h1>
        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <table>
                <caption>Register</caption>
                <tbody>
                <tr>
                    <td><label for="userNameInput">User Name: </label></td>
                    <td>
                        <input type="text" name="username" id="userNameInput" value="<?= $_POST['username'] ?? '' ?>" class="required">
                        <?php if (isset($errorMessages['messageUsername'])): ?>
                            <div class="form-error">
                                <?= $errorMessages['messageUsername'] ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td><label for="emailInput">Email: </label></td>
                    <td>
                        <input type="email" name="email" id="emailInput"
                               value="<?= (($_POST['submit'] ?? '') == 'Register') ? $_POST['email'] : '' ?>" class="required">
                        <?php if (isset($errorMessages['messageEmail'])): ?>
                            <div class="form-error">
                                <?= $errorMessages['messageEmail'] ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td><label for="passwordInput">Password: </label></td>
                    <td>
                        <input type="password" name="password" id="passwordInput" class="required">
                        <?php if (isset($errorMessages['messagePassword'])): ?>
                            <div class="form-error">
                                <?= $errorMessages['messagePassword'] ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>

                <tr>
                    <td><label for="confirmPwInput">Confirm PW: </label></td>
                    <td>
                        <input type="password" name="confirmPw" id="confirmPwInput" class="required">
                        <?php if (isset($errorMessages['messageConfirmPassword'])): ?>
                            <div class="form-error">
                                <?= $errorMessages['messageConfirmPassword'] ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Register">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>

        <form action="<?= $_SERVER['PHP_SELF'] ?>" method="post">
            <table>
                <caption>Log in</caption>
                <tbody>
                <tr>
                    <td><label for="loginEmail">Email: </label></td>
                    <td>
                        <input type="email" name="email" id="loginEmail"
                               value="<?= (($_POST['submit'] ?? '') == 'Login') ? $_POST['email'] : '' ?>">
                        <?php if (isset($errorMessages['messageEmailLogin'])): ?>
                            <div class="form-error">
                                <?= $errorMessages['messageEmailLogin'] ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td><label for="loginPassword">Password: </label></td>
                    <td>
                        <input type="password" name="password" id="loginPassword">
                        <?php if (isset($errorMessages['messagePasswordLogin'])): ?>
                            <div class="form-error">
                                <?= $errorMessages['messagePasswordLogin'] ?>
                            </div>
                        <?php endif; ?>
                    </td>
                </tr>
                <tr>
                    <td colspan="2">
                        <input type="submit" name="submit" value="Login">
                    </td>
                </tr>
                </tbody>
            </table>
        </form>
    </main>
<?php require_once './parts/footer.php' ?>