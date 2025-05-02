<?php
Require 'config/database.php';

if (isset($_POST['submit'])) {
    // get form data
    $username = filter_var($_POST['username'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $email = filter_var($_POST['email'], FILTER_SANITIZE_EMAIL);
    $createpassword = filter_var($_POST['createpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $confirmpassword = filter_var($_POST['confirmpassword'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    // validate input
    if (!$username) {
        $_SESSION['signup'] = "username required";
    } elseif (!$email) {
        $_SESSION['signup'] = "email required";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $_SESSION['signup'] = "email not valid";
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
            $user_check_query = "SELECT * FROM users WHERE username='$username' OR email='$email' LIMIT 1";
            $user_check_result = mysqli_query($connection, $user_check_query);
            if (mysqli_num_rows($user_check_result) > 0) {
                $_SESSION['signup'] = "username/email already exists";
            }
        }
    }
    // redirect back to signup page
    if(isset($_SESSION['signup'])) {
        header("location: signup.php");
        die();
    } else {
        // insert new user into users table
        $sql = "INSERT INTO product_variants (product_id, color, en_stock) VALUES (?, ?, ?)";
    
        $stmt = $connection->prepare($sql);
        if (!$stmt) {
            $_SESSION['variant'] = "SQL Error: " . $connection->error;
            header("Location: add-product-variant.php?id=" . $_POST['id']);
            exit;
        }
        
        $stmt->bind_param(
            "isi",
            $product_id, $color, $en_stock
        );

        $insert_user_query = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
        $insert_user_stmt = $connection->prepare($insert_user_query);
        if (!$insert_user_stmt) {
            $_SESSION['signup'] = "SQL Error: " . $connection->error;
            header("Location: signup.php");
            exit;
        }
        $insert_user_stmt->bind_param("sss", $username, $email, $hashed_password);
        // execute the statement
        if ($insert_user_stmt->execute()) {
            // redirect to login page
            $_SESSION['signup-success'] = "Registration successful. Please log in";
            header("location: login.php");
            die();
        } else {
            $_SESSION['signup'] = "SQL Error: " . $connection->error;
            header("Location: signup.php");
            exit;
        }
        // close statement
        $insert_user_stmt->close();
    }
   
} else {
    header("location: signup.php");
    die();
}


?>