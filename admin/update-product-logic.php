<?php
require_once 'config/database.php';

if (isset($_POST['edit_submit']) && isset($_POST['id'])) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $category = filter_var($_POST['category'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
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
    $slug = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $title);
    $catslug = $cat_slug[$category];



    // validate input
    if ($category === 'null') {
        $_SESSION['edit'] = "Category is required"; 
    } elseif ($en_stock === 'null') {
        $_SESSION['edit'] = "Stock status is required";
    } elseif (!$title) {
        $_SESSION['edit'] = "Title is required";
    } elseif (!$description1) {
        $_SESSION['edit'] = "Description is required";
    } elseif (!$price) {
        $_SESSION['edit'] = "Price is required";
    } else {

        // Calculate final price
        $final_price = is_numeric($discount) ? $price - $discount : $price;
        $final_price = max($final_price, 0);

        // Process uploaded images
        $image1 = $_FILES['image1'];
        $image2 = $_FILES['image2'];
        $image3 = $_FILES['image3'];
        $image4 = $_FILES['image4'];
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
        
        for ($i = 0; $i < 4; $i++) {
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
        

        // Prepare SQL Update
        $sql = "UPDATE products SET
            category = ?, en_stock = ?, title = ?, material = ?, color = ?, size = ?,
            description1 = ?, bulletpoint1 = ?, bulletpoint2 = ?, bulletpoint3 = ?, bulletpoint4 = ?, description2 = ?,
            image1 = ?, image2 = ?, image3 = ?, image4 = ?, price = ?, discount = ?, final_price = ?, slug = ?, cat_slug = ?
            WHERE id = ?" ;

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iissssssssssssssiiissi",
            $category, $en_stock, $title, $material, $color, $size,
            $description1, $bulletpoint1, $bulletpoint2, $bulletpoint3, $bulletpoint4, $description2,
            $cur_images[0], $cur_images[1], $cur_images[2], $cur_images[3],
            $price, $discount, $final_price, $slug, $catslug,
            $id
        );

        if ($stmt->execute()) {
            $_SESSION['add-success'] = "Product successfully updated.";
            header('Location: index.php');
        } else {
            $_SESSION['edit'] = "Error occurred while updating product.";
            header("Location: edit-product.php?id=" . urlencode($id));
        }
        exit;
    }

    if (isset($_SESSION['edit'])) {
        header("Location: edit-product.php?id=" . urlencode($id));
        exit;
    }
}
?>
