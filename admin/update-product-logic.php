<?php
require_once 'config/database.php';

if (isset($_POST['edit_submit']) && isset($_POST['id'])) {
    $id = (int) htmlspecialchars ($_POST['id']);
    $category = htmlspecialchars($_POST['category']);
    $en_stock = htmlspecialchars($_POST['en_stock']);
    $title = htmlspecialchars($_POST['title']);
    $article_number = htmlspecialchars($_POST['article_number']);
    $material = htmlspecialchars($_POST['material']);
    $color = htmlspecialchars($_POST['color']);
    $size = htmlspecialchars($_POST['size']);
    $description1 = htmlspecialchars($_POST['description1']);
    $bulletpoint1 = htmlspecialchars($_POST['bulletpoint1']);
    $bulletpoint2 = htmlspecialchars($_POST['bulletpoint2']);
    $bulletpoint3 = htmlspecialchars($_POST['bulletpoint3']);
    $bulletpoint4 = htmlspecialchars($_POST['bulletpoint4']);
    $description2 = htmlspecialchars($_POST['description2']);
    $price = htmlspecialchars($_POST['price']);
    $discount = htmlspecialchars($_POST['discount']);
    $slug = preg_replace('/[^a-zA-Z0-9\-_]/', '-', $title);
    $catslug = $cat_slug[$category];


// check if product name already exists
$product_check_query = "SELECT * FROM products WHERE slug = ? AND id != ?";
// prepare statement
$stmt_prd = $connection->prepare($product_check_query);
// bind parameters
$stmt_prd->bind_param("si", $slug, $id);
// execute statement
$stmt_prd->execute();
// get result
$product_check_result = $stmt_prd->get_result();



    // validate input
    if ($category === 'null') {
        $_SESSION['edit'] = "Category is required"; 
    } elseif ($en_stock === 'null') {
        $_SESSION['edit'] = "Stock status is required";
    } elseif ($color === 'null') {
        $_SESSION['edit'] = "Color is required";
    } elseif (!$title) {
        $_SESSION['edit'] = "Title is required";
    } elseif (!$article_number) {
        $_SESSION['edit'] = "Article number is required"; 
    } elseif (!$description1) {
        $_SESSION['edit'] = "Description is required";
    } elseif (!$price) {
        $_SESSION['edit'] = "Price is required";
    } elseif (mysqli_num_rows($product_check_result) > 0) {
        $_SESSION['edit'] = "product name already exists";
    } else {
        // Calculate final price
        $final_price = is_numeric($discount) ? $price - $discount : $price;
        $final_price = max($final_price, 0);

        // Process uploaded images
        $image1 = $_FILES['image1'] ?? null;
        $image2 = $_FILES['image2'] ?? null;
        $image3 = $_FILES['image3'] ?? null;
        $image4 = $_FILES['image4'] ?? null;
        $image5 = $_FILES['image5'] ?? null;
        $image6 = $_FILES['image6'] ?? null;
        $image7 = $_FILES['image7'] ?? null;
        $image8 = $_FILES['image8'] ?? null;
        $image9 = $_FILES['image9'] ?? null;

        // Create an array of images
        $images = [$image1, $image2, $image3, $image4, $image5, $image6, $image7, $image8, $image9];

        // Previous images from form
        $cur_images = [
            $_POST['current_image1'], 
            $_POST['current_image2'], 
            $_POST['current_image3'], 
            $_POST['current_image4'], 
            $_POST['current_image5'], 
            $_POST['current_image6'],
            $_POST['current_image7'], 
            $_POST['current_image8'], 
            $_POST['current_image9']
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
        

        // Prepare SQL Update
        $sql = "UPDATE products SET
            category = ?, en_stock = ?, 
            title = ?, article_number = ?, material = ?, color = ?, size = ?,
            description1 = ?, bulletpoint1 = ?, bulletpoint2 = ?, bulletpoint3 = ?, bulletpoint4 = ?, description2 = ?,
            image1 = ?, image2 = ?, image3 = ?, 
            image4 = ?, image5 = ?, image6 = ?, 
            image7 = ?, image8 = ?, image9 = ?, 
            price = ?, discount = ?, final_price = ?, slug = ?, cat_slug = ?
            WHERE id = ?" ;

        $stmt = $connection->prepare($sql);
        $stmt->bind_param("iissssssssssssssssssssiiissi",
            $category, $en_stock, 
            $title, $article_number, $material, $color, $size,
            $description1, $bulletpoint1, $bulletpoint2, $bulletpoint3, $bulletpoint4, $description2,
            $cur_images[0], $cur_images[1], $cur_images[2], 
            $cur_images[3], $cur_images[4], $cur_images[5],
            $cur_images[6], $cur_images[7], $cur_images[8], 
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
