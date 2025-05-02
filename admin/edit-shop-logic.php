<?php
require 'config/database.php';

if (isset($_POST['shop_submit'])) {
    // Sanitize Inputs
    $promo = filter_var($_POST['promo'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $phone_contact = filter_var($_POST['phone_contact'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dec_title = filter_var($_POST['decouvrir_title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $dec_text = filter_var($_POST['decouvrir_text'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_1 = filter_var($_POST['category_1'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_2 = filter_var($_POST['category_2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_3 = filter_var($_POST['category_3'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_4 = filter_var($_POST['category_4'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_text_1 = filter_var($_POST['category_text_1'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_text_2 = filter_var($_POST['category_text_2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_text_3 = filter_var($_POST['category_text_3'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $category_text_4 = filter_var($_POST['category_text_4'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $text_story = filter_var($_POST['text_story'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $title_info_1 = filter_var($_POST['title_info_1'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $text_info_1 = filter_var($_POST['text_info_1'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $title_info_2 = filter_var($_POST['title_info_2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $text_info_2 = filter_var($_POST['text_info_2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $title_info_3 = filter_var($_POST['title_info_3'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $text_info_3 = filter_var($_POST['text_info_3'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $title_lif = filter_var($_POST['title_lif'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $image_car_1 = $_FILES['image_car_1'];
    $image_car_2 = $_FILES['image_car_2'];
    $image_car_3 = $_FILES['image_car_3'];
    $image_lif_1 = $_FILES['image_lif_1'];
    $image_lif_2 = $_FILES['image_lif_2'];
    $image_lif_3 = $_FILES['image_lif_3'];
    $image_story = $_FILES['image_story'];
    $id = intval(1);

    // check inputs
    if (empty($promo)) {
        $_SESSION['shop'] = "promotion text is required"; 
    } elseif (empty($dec_title)) {
        $_SESSION['shop'] = "Decouvrir title is required"; 
    } elseif (empty($dec_text)) {
        $_SESSION['shop'] = "Decouvrir text is required";   
    } elseif (empty($phone_contact)) {
        $_SESSION['shop'] = "Phone contact is required";      
    } else {


    // Bilder
    // $time = time();
    $images = [$_FILES['image_car_1'], $_FILES['image_car_2'], $_FILES['image_car_3'], $_FILES['image_lif_1'],$_FILES['image_lif_2'], $_FILES['image_lif_3'], $_FILES['image_story']];
    // $image_names[] = '';

    // Previous images from form
    $cur_images = [
        $_POST['current_image_car_1'], 
        $_POST['current_image_car_2'], 
        $_POST['current_image_car_3'], 
        $_POST['current_image_lif_1'],
        $_POST['current_image_lif_2'],  
        $_POST['current_image_lif_3'],  
        $_POST['current_image_story']
    ];


    $upload_folder = __DIR__ . '/images/';
    if (!is_dir($upload_folder)) {
        mkdir($upload_folder, 0755, true);
    }
    
    $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];
    
    for ($i = 0; $i < 7; $i++) {
        if (!empty($images[$i]['name'])) {
            $extension = strtolower(pathinfo($images[$i]['name'], PATHINFO_EXTENSION));
            $size = $images[$i]['size'];
    
            if (!in_array($extension, $allowed_exts)) {
                $_SESSION['shop'] = "Image " . ($i + 1) . " has an unsupported format. Only jpg, jpeg, png, webp allowed.";
                header("Location: edit-shop.php");
                exit;
            }
    
            if ($size > 1000000) {
                $_SESSION['shop'] = "Image " . ($i + 1) . " is too large. Max allowed size is 1MB.";
                header("Location: edit-shop.php");
                exit;
            }
    
            // If valid, proceed with upload
            $new_image_name = time() . '-' . preg_replace("/[^a-zA-Z0-9\.\-_]/", "", $images[$i]['name']);
            move_uploaded_file($images[$i]['tmp_name'], $upload_folder . $new_image_name);
    
            // Remove old image if exists
            if (!empty($cur_images[$i]) && file_exists($upload_folder . $cur_images[$i])) {
                unlink($upload_folder . $cur_images[$i]);
            }
    
            $cur_images[$i] = $new_image_name;
        }
    }
    
        // Fetch shop_infos from database
        $check_shop_infos = "SELECT * FROM shop_infos WHERE id = ?";
        $query_shop_infos = $connection->prepare($check_shop_infos);
        if (!$query_shop_infos) {
            $_SESSION['shop'] = "SQL Error: " . $connection->error;
            header("Location: edit-shop.php");
            exit;
        } else {
            $query_shop_infos->bind_param("i", $id);
            $query_shop_infos->execute();
            $shop_infos_result = $query_shop_infos->get_result();
            if ($shop_infos_result->num_rows > 0) {
                $sql = "UPDATE shop_infos SET
                    promo = ?, decouvrir_title = ?, decouvrir_text = ?, image_car_1 = ?, image_car_2 = ?, image_car_3 = ?, image_lif_1 = ?, image_lif_2 = ?,
                    image_lif_3 = ?, image_story = ?, category_1 = ?, category_2 = ?, category_3 = ?, category_4 = ?,
                    category_text_1 = ?, category_text_2 = ?, category_text_3 = ?, category_text_4 = ?, text_story = ?,
                    text_info_1 = ?, text_info_2 = ?, text_info_3 = ?, title_info_1 = ?, title_info_2 = ?, title_info_3 = ?,
                    title_lif = ?, phone_contact = ?
                    WHERE id = ?";
                $stmt = $connection->prepare($sql);
                if (!$stmt) {
                    $_SESSION['shop'] = "SQL Error: " . $connection->error;
                    header("Location: edit-shop.php");
                    exit;
                }
                $stmt->bind_param("sssssssssssssssssssssssssssi",
                    $promo, $dec_title, $dec_text, $cur_images[0], $cur_images[1], $cur_images[2], $cur_images[3], $cur_images[4],
                    $cur_images[5], $cur_images[6], $category_1, $category_2, $category_3, $category_4, $category_text_1, 
                    $category_text_2, $category_text_3, $category_text_4, $text_story, $text_info_1, $text_info_2, $text_info_3,
                    $title_info_1, $title_info_2, $title_info_3, $title_lif, $phone_contact,
                    $id
                );
            } else {
                $sql = "INSERT INTO shop_infos (
                    promo, decouvrir_title, decouvrir_text, image_car_1, image_car_2, image_car_3, 
                    image_lif_1, image_lif_2, image_lif_3, image_story, 
                    category_1, category_2, category_3, category_4,
                    category_text_1, category_text_2, category_text_3, category_text_4,
                    text_story, text_info_1, text_info_2, text_info_3,
                    title_info_1, title_info_2, title_info_3, title_lif, phone_contact
                    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)"; 
                $stmt = $connection->prepare($sql);
                if (!$stmt) {
                    $_SESSION['shop'] = "SQL Error: " . $connection->error;
                    header("Location: edit-shop.php");
                    exit;
                }
                $stmt->bind_param("ssssssssssssssssssssssssss",
                    $promo, $dec_title, $dec_text, $cur_images[0], $cur_images[1], $cur_images[2], $cur_images[3], $cur_images[4],
                    $cur_images[5], $cur_images[6], $category_1, $category_2, $category_3, $category_4, $category_text_1, 
                    $category_text_2, $category_text_3, $category_text_4, $text_story, $text_info_1, $text_info_2, $text_info_3,
                    $title_info_1, $title_info_2, $title_info_3, $title_lif, $phone_contact
                );
            }
        }
        if ($stmt->execute()) {
            $_SESSION['shop'] = "Shop infos successfully updated.";
            header('Location: edit-shop.php');
        } else {
            $_SESSION['shop'] = "Error occurred while updating shop.";
            header("Location: edit-shop.php");
        }
        exit;
    }
    if (isset($_SESSION['shop'])) {
        header("Location: edit-shop.php");
        exit;
    }
}

?>