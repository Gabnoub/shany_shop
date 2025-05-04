<?php
require_once 'config/database.php';

if (isset($_POST['variant_submit']) && isset($_POST['id'] )) {
    $id = (int) $_POST['id'];
    $en_stock = filter_var($_POST['en_stock'], FILTER_SANITIZE_NUMBER_INT);

    // validate input
    if ($en_stock === 'null') {
        $_SESSION['variant'] = "Stock status is required";
    } else {

        
        // Process uploaded images
        $image1 = $_FILES['image1'] ?? null;
        $image2 = $_FILES['image2'] ?? null;
        $image3 = $_FILES['image3'] ?? null;
        $image4 = $_FILES['image4'] ?? null;


        // Create an array of images
        $images = [$image1, $image2, $image3, $image4];

        // Previous images from form
        $cur_images = [
            $_POST['current_image1'], 
            $_POST['current_image2'], 
            $_POST['current_image3'], 
            $_POST['current_image4'] 
        ];

        $upload_folder = __DIR__ . '/images/';
        if (!is_dir($upload_folder)) {
            mkdir($upload_folder, 0755, true);
        }
        
        $allowed_exts = ['jpg', 'jpeg', 'png', 'webp'];

        // Validate and upload images
        // get number of images
        $num_images = count($images);
        for ($i = 0; $i < $num_images; $i++) {
            if (!empty($images[$i]['name'])) {
                $extension = strtolower(pathinfo($images[$i]['name'], PATHINFO_EXTENSION));
                $size = $images[$i]['size'];
        
                if (!in_array($extension, $allowed_exts)) {
                    $_SESSION['edit'] = "Image " . ($i + 1) . " has an unsupported format. Only jpg, jpeg, png, webp allowed.";
                    header("Location: edit-product.php?id=" . urlencode($id));
                    exit;
                }
        
                if ($size > 1000000) {
                    $_SESSION['edit'] = "Image " . ($i + 1) . " is too large. Max allowed size is 1MB.";
                    header("Location: edit-product.php?id=" . urlencode($id));
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
        
        // Proceed with updating stock status of the variant
        $sql = "UPDATE product_variants SET en_stock = ?, image1 = ?, image2 = ?, image3 = ?, image4 = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        if (!$stmt) {
            $_SESSION['variant'] = "SQL Error: " . $connection->error;
            header("Location: edit-product-variant.php?id=" . $_POST['id']);
            exit;
        }
        
        $stmt->bind_param(
            "issssi",
            $en_stock, $cur_images[0], $cur_images[1], $cur_images[2], $cur_images[3], $id
        );
    
        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['variant-success'] = "Product variant successfully updated.";
            header("Location: index.php");
            die();
        } else {
            $_SESSION['variant'] = "Error occurred while creating product variant.";
            header("Location: edit-product-variant.php?id=" . $_POST['id']);
            exit;
        }
        exit;
    }

    if (isset($_SESSION['variant'])) {
        header("Location: edit-product-variant.php?id=" . $_POST['id']);
        exit;
    }
}
?>
