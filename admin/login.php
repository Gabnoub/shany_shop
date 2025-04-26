<?php
// session_start();
Require 'config/constants.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css">
    <title>Shany LogIn Page</title>
    <script src="../JS/script.js" defer></script>
</head>
<body>
    <section class="form__section login">
        <h2>Login</h2>
        <?php if(isset($_SESSION['signup-success'])) : ?>
            <div class="alert__message success">
                <p>
                    <?= $_SESSION['signup-success'];
                    unset($_SESSION['signup-success']);
                    ?>
                </p>
            </div>
        <?php endif ?>
        <?php if(isset($_SESSION['login'])) : ?>
            <div class="alert__message">
                <p>
                    <?= $_SESSION['login'];
                    unset($_SESSION['login']);
                    ?>
                </p>
            </div>
        <?php endif ?>
        <form action="<?= ROOT_URL ?>admin/login-logic.php" method="POST">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="password" placeholder="Password">
            <button type="submit" name="submit" class="sub__btn">Login</button>
        </form>
    </section>
</body>
</html>