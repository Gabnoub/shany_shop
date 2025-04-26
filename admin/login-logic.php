<?php
Require 'config/database.php';

if (isset($_POST['submit'])) {
    // get form data
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $password = filter_var($_POST['password'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    if (!$username) {
        $_SESSION['login'] = "username required";
    } elseif (!$password) {
        $_SESSION['login'] = "password required";
    }
    else {
        //fetch user from DB
        $fetch_user_query = "SELECT * FROM users WHERE username='$username'";
        $fetch_user_result = mysqli_query($connection, $fetch_user_query);

        if (mysqli_num_rows($fetch_user_result) === 1) {
            // convert the record into assoc array
            $user_record = mysqli_fetch_assoc($fetch_user_result);
            $db_password = $user_record['password'];
            // compare form password with database password
            if (password_verify($password, $db_password)) {
                // log In
                $_SESSION['user_id'] = $user_record['id'];
                $_SESSION['username'] = $user_record['username'];
                header('location: index.php');
                die();
            } else {
                $_SESSION['login'] = "please check your username and/or password";
            }
        } else {
            $_SESSION['login'] = "user not found";
        }
    }
    header("location: login.php");
    die();
} else {
    header("location: login.php");
    die();
}


?>