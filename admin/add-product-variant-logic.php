<?php
require_once 'config/database.php';

if (isset($_POST['variant_submit']) && isset($_POST['id'])) {
    $product_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $color = filter_var($_POST['color'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);


    // validate input
    if ($color === 'null') {
        $_SESSION['variant'] = "Color is required";
    } else {

        // Prepare SQL Update
        $sql = "INSERT INTO products_variants (product_id,color) VALUES (?,?)";
    
        $stmt = $connection->prepare($sql);
        $stmt->bind_param(
            "is",
            $product_id, $color
        );
    
        if ($stmt->execute()) {
            $_SESSION['variant-success'] = "Product variant successfully created.";
            header('Location: index.php');
        } else {
            $_SESSION['variant'] = "Error occurred while creating product variant.";
            header("Location: add-product-variant.php");
        }
        exit;
    }

    if (isset($_SESSION['variant'])) {
        header("Location: add-product-variant.php");
        exit;
    }
}
?>
