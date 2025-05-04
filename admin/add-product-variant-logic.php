<?php
require_once 'config/database.php';

if (isset($_POST['variant_submit']) && isset($_POST['id'] )) {
    $product_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $color =  filter_var($_POST['color'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $en_stock = filter_var($_POST['en_stock'], FILTER_SANITIZE_NUMBER_INT); 
    $image1 = $_FILES['image1'] ?? null;

    
    if (isset($_POST['product_color'])) {
       $product_color= $_POST['product_color'];
    }

    // validate input
    if ($color === 'null') {
        $_SESSION['variant'] = "Color is required";
    } elseif ($en_stock === 'null') {
        $_SESSION['variant'] = "stock status is required";
    } elseif (html_entity_decode($color) === $product_color) {
        $_SESSION['variant'] = "Variant already exists";
    } elseif (!$image1['name']) {
        $_SESSION['add'] = "Image 1 is required";
    } else {
        $image2 = $_FILES['image2'] ?? null;
        $image3 = $_FILES['image3'] ?? null;
        $image4 = $_FILES['image4'] ?? null;

    // Create an array of images
    $time = time();
    $images = [$image1, $image2, $image3, $image4];
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
    

        // Fetch product variant from database
        $sql_var = "SELECT * FROM product_variants WHERE product_id = ? AND color = ?";
        $stmt_var = $connection->prepare($sql_var);
        if (!$stmt_var) {
            $_SESSION['variant'] = "SQL Error: " . $connection->error;
            header("Location: add-product-variant.php?id=" . $_POST['id']);
            exit;
        }
        
            // $stmt_var->bind_param("i", $id);
            $stmt_var->bind_param("is", $product_id, $color);
            $stmt_var->execute();
            $stmt_result = $stmt_var->get_result();
            if ($stmt_result->num_rows > 0) {
                // Variant already exists
                $_SESSION['variant'] = "Variant already exists";
                header("Location: add-product-variant.php?id=" . $_POST['id']);
                exit;
            } else {
                $sql = "INSERT INTO product_variants (product_id, color, en_stock, image1, image2, image3, 
                        image4) VALUES (?, ?, ?, ?, ?, ?, ?)";
    
                $stmt = $connection->prepare($sql);
                if (!$stmt) {
                    $_SESSION['variant'] = "SQL Error: " . $connection->error;
                    header("Location: add-product-variant.php?id=" . $_POST['id']);
                    exit;
                }
                
                $stmt->bind_param(
                    "isissss",
                    $product_id, $color, $en_stock, $image_names[1], $image_names[2], $image_names[3], 
                    $image_names[4]
                );
            }
        


        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['variant-success'] = "Product variant successfully created.";
            header('Location: index.php');
            die();
        } else {
            $_SESSION['variant'] = "Error occurred while creating product variant.";
            header("Location: add-product-variant.php?id=" . $_POST['id']);
            exit;
        }
        exit;
    }

    if (isset($_SESSION['variant'])) {
        header("Location: add-product-variant.php?id=" . $_POST['id']);
        exit;
    }
}
?>
