<?php
require_once 'config/database.php';

if (isset($_POST['variant_submit']) && isset($_POST['id'] )) {
    $product_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $color =  $_POST['color'];
    $en_stock = filter_var($_POST['en_stock'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $id = 1;
    echo $_POST['product_color'];
    echo $color;
    if (isset($_POST['product_color'])) {
       $product_color= $_POST['product_color'];
    }
    // validate input
    if ($color === 'null') {
        $_SESSION['variant'] = "Color is required";
    } elseif ($en_stock === 'null') {
        $_SESSION['variant'] = "stock status is required";
    } elseif ($color === $product_color) {
        $_SESSION['variant'] = "Variant already exists";
        // die;
    } else {
        // Fetch product variant from database
        $sql_var = "SELECT * FROM product_variants WHERE id = $id";
        $stmt_var = $connection->prepare($sql);
        if (!$stmt_var) {
            $_SESSION['variant'] = "SQL Error: " . $connection->error;
            header("Location: add-product-variant.php?id=" . $_POST['id']);
            exit;
        } else {
            // $stmt_var->bind_param("i", $id);
            $stmt_var->execute();
            $stmt_result = $stmt_var->get_result();
            if ($stmt_result->num_rows > 0) {
                $sql = "UPDATE product_variants SET product_id = ?, color = ?, en_stock = ? WHERE id = ?";
    
                $stmt = $connection->prepare($sql);
                $stmt->bind_param(
                    "isii",
                    $product_id, $color, $en_stock, $id
                );
            } else {
                $sql = "INSERT INTO product_variants (product_id, color, en_stock) VALUES (?, ?, ?)";
    
                $stmt = $connection->prepare($sql);
                $stmt->bind_param(
                    "isi",
                    $product_id, $color, $en_stock
                );
            }
        }

        // Execute the statement
        if ($stmt->execute()) {
            $_SESSION['variant-success'] = "Product variant successfully created.";
            header('Location: index.php');
        } else {
            $_SESSION['variant'] = "Error occurred while creating product variant.";
            header("Location: add-product-variant.php?id=" . $_POST['id']);
        }
        exit;
    }

    if (isset($_SESSION['variant'])) {
        header("Location: add-product-variant.php?id=" . $_POST['id']);
        exit;
    }
}
?>
