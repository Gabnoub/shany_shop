<?php
require_once 'config/database.php';

if (isset($_POST['variant_submit']) && isset($_POST['id'] )) {
    $id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $color =  filter_var($_POST['color'], FILTER_SANITIZE_FULL_SPECIAL_CHARS); 
    $en_stock = (int) $_POST['en_stock'];
    $product_id= $_POST['product_id'];

    // validate input
    if ($color === 'null') {
        $_SESSION['variant'] = "Color is required";
    } elseif ($en_stock === 'null') {
        $_SESSION['variant'] = "Stock status is required";
    } else {
    // Check if product variant already exists
    $sql_check = "SELECT * FROM product_variants WHERE product_id = ? AND color = ?";
    $stmt_check = $connection->prepare($sql_check);
    if (!$stmt_check) {
        $_SESSION['variant'] = "SQL Error: " . $connection->error;
        header("Location: edit-product-variant.php?id=" . $_POST['id']);
        exit;
    }
    $stmt_check->bind_param("is", $product_id, $color);
    $stmt_check->execute();
    $stmt_result = $stmt_check->get_result();
    // fetch product color from database
    $sql_product = "SELECT color FROM products WHERE id = ?";
    $stmt_product = $connection->prepare($sql_product);
    if (!$stmt_product) {
        $_SESSION['variant'] = "SQL Error: " . $connection->error;
        header("Location: edit-product-variant.php?id=" . $_POST['id']);
        exit;
    }
    $stmt_product->bind_param("i", $product_id);
    $stmt_product->execute();
    $stmt_product->bind_result($product_color);
    $stmt_product->fetch();
    $stmt_product->close();
    // Check if the color is the same as the product color
    if ($color === $product_color) {
        $_SESSION['variant'] = "Variant already exists";
        header("Location: edit-product-variant.php?id=" . $_POST['id']);
        exit;
    }
    if ($stmt_result->num_rows > 0) {
        // Variant already exists
        $_SESSION['variant'] = "Variant already exists";
        header("Location: edit-product-variant.php?id=" . $_POST['id']);
        exit;
    } else {
        // Proceed with inserting the new variant
        $sql = "UPDATE product_variants SET color = ?,  en_stock = ? WHERE id = ?";
        $stmt = $connection->prepare($sql);
        if (!$stmt) {
            $_SESSION['variant'] = "SQL Error: " . $connection->error;
            header("Location: edit-product-variant.php?id=" . $_POST['id']);
            exit;
        }
        
        $stmt->bind_param(
            "sii",
            $color, $en_stock, $id
        );
    }
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
