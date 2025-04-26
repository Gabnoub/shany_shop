<?php
Require 'config/database.php';

if (isset($_POST['submit'])) {
    // get form data
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $createpassword = filter_var($_POST['createpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmpassword = filter_var($_POST['confirmpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    // validate input
    if (!$username) {
        $_SESSION['signup'] = "username required";
    } elseif (!$createpassword) {
        $_SESSION['signup'] = "create a password";
    } elseif (!$confirmpassword) {
        $_SESSION['signup'] = "confirm your password";
    } else {
        //check if passwords do not match
        if ($createpassword !== $confirmpassword) {
            $_SESSION['signup'] = "passwords do not match";
        } else {
            // hash password
            $hashed_password = password_hash($createpassword, PASSWORD_DEFAULT);
            // check if user already exists
            $user_check_query = "SELECT * FROM users WHERE username='$username'";
            $user_check_result = mysqli_query($connection, $user_check_query);
            if (mysqli_num_rows($user_check_result) > 0) {
                $_SESSION['signup'] = "username already exists";
            }
        }
    }
    // redirect back to signup page
    if(isset($_SESSION['signup'])) {
        header("location: signup.php");
        die();
    } else {
        // insert new user into users table
        $insert_user_query = "INSERT INTO users (username, password) VALUES ('$username','$hashed_password')";
        $insert_user_result = mysqli_query($connection, $insert_user_query);
        if (!mysqli_errno($connection)) {
            // redirect to login page
            $_SESSION['signup-success'] = "Registration successful. Please log in";
            header("location: login.php");
            die();
        } else {
            $_SESSION['signup'] = "Registration not successful. Please try again";
            header("location: signup.php");
            die();
        }
    }
   
} else {
    header("location: signup.php");
    die();
}


?>