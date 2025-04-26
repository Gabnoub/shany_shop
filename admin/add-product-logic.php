<?php
require 'config/database.php';

if (isset($_POST['add_submit'])) {
    // Sanitize Inputs
    $category = intval(filter_var($_POST['category'], FILTER_SANITIZE_FULL_SPECIAL_CHARS));
    $en_stock = filter_var($_POST['en_stock'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $title = filter_var($_POST['title'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $material = filter_var($_POST['material'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $color = filter_var($_POST['color'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $size = filter_var($_POST['size'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description1 = filter_var($_POST['description1'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $bulletpoint1 = filter_var($_POST['bulletpoint1'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $bulletpoint2 = filter_var($_POST['bulletpoint2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $bulletpoint3 = filter_var($_POST['bulletpoint3'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $bulletpoint4 = filter_var($_POST['bulletpoint4'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $description2 = filter_var($_POST['description2'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $price = filter_var($_POST['price'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $discount = filter_var($_POST['discount'], FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    $image1 = $_FILES['image1'];
    $slug = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $title);
    $catslug = $cat_slug[$category];

    // if (!$title || !$price || !$description1 || $category === 'null' || $en_stock === 'null') {
    //     $_SESSION['add'] = "please fill in all required fields";
    //     $_SESSION['add-data'] = $_POST;
    //     header("location: addproduct.php");
    //     exit;
    // }
    // validate input
    if ($category === 'null') {
        $_SESSION['add'] = "Category is required"; 
    } elseif ($en_stock === 'null') {
        $_SESSION['add'] = "Stock status is required";
    } elseif (!$title) {
        $_SESSION['add'] = "Title is required";
    } elseif (!$description1) {
        $_SESSION['add'] = "Description 1 is required";
    } elseif (!$price) {
        $_SESSION['add'] = "Price is required";
    } elseif (!$image1['name']) {
        $_SESSION['add'] = "Image 1 is required";
    } else {

    // Finaler Preis
    $final_price = is_numeric($discount) ? $price - $discount : $price;
    $final_price = max($final_price, 0);

    // Bilder
    $time = time();
    $images = [$_FILES['image1'], $_FILES['image2'], $_FILES['image3'], $_FILES['image4']];
    $image_names[] = '';


    $upload_folder = __DIR__ . '/images/';
    if (!is_dir($upload_folder)) mkdir($upload_folder, 0755, true);

    foreach ($images as $index => $img) {
        if (!empty($img['name'])) {
            $ext = strtolower(pathinfo($img['name'], PATHINFO_EXTENSION));
            $allowed = ['jpg', 'jpeg', 'png', 'webp'];
            if (in_array($ext, $allowed) && $img['size'] < 1000000) {
                $img_name = $time . '-' . basename($img['name']);
                move_uploaded_file($img['tmp_name'], $upload_folder . $img_name);
                $image_names[] = $img_name;
            } else {
                $_SESSION['add'] = "invalid image format" . ($index + 1);
                header("location: addproduct.php");
                exit;
            }
        } else {
            $image_names[] = null;
        }
    }
    


    // Prepared SQL
    $sql = "INSERT INTO products (
        category, en_stock, title, material, color, size,
        description1, bulletpoint1, bulletpoint2, bulletpoint3, bulletpoint4,
        description2, image1, image2, image3, image4, price, discount, final_price, slug, cat_slug
    ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)";

    $stmt = $connection->prepare($sql);
    $stmt->bind_param(
        "iissssssssssssssiiiss",
        $category, $en_stock, $title, $material, $color, $size,
        $description1, $bulletpoint1, $bulletpoint2, $bulletpoint3, $bulletpoint4,
        $description2,
        $image_names[1], $image_names[2], $image_names[3], $image_names[4],
        $price, $discount, $final_price, $slug, $catslug
    );
    

    if ($stmt->execute()) {
        $_SESSION['add-success'] = "Product successfully added";
        header("location: index.php");
    } else {
        $_SESSION['add'] = "Error during DB transferring " . $stmt->error;
        $_SESSION['add-data'] = $_POST;
        header("location: addproduct.php");
    }

    exit;
    }
    if (isset($_SESSION['add'])) {
        header("Location: addproduct.php");
        exit;
    }
}

?>