<?php
// session_start();
Require 'config/constants.php';


?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/style.css?v1">
    <title>Shany LogIn Page</title>
    <script src="../JS/script.js" defer></script>
</head>
<body>
    <section class="form__section signup">
        <h2>Signup</h2>
        <?php if(isset($_SESSION['signup'])) : ?>
            <div class="alert__message">
                <p>
                    <?= $_SESSION['signup'];
                    unset($_SESSION['signup']);
                    ?>
                </p>
            </div>
        <?php endif ?>
        <form action="<?= ROOT_URL ?>admin/signup-logic.php"  enctype="multipart/form-data" method="POST">
            <input type="text" name="username" placeholder="Username">
            <input type="password" name="createpassword" placeholder="Create Password">
            <input type="password" name="confirmpassword" placeholder="Confirm Password">
            <button type="submit" name="submit" class="sub__btn">Signup</button>
        </form>
    </section>
</body>
</html>