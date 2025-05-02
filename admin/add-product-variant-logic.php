<?php
require_once 'config/database.php';

if (isset($_POST['variant_submit']) && isset($_POST['id'] )) {
    $product_id = filter_var($_POST['id'], FILTER_SANITIZE_NUMBER_INT);
    $color =  filter_var($_POST['color'], FILTER_SANITIZE_FULL_SPECIAL_CHARS);
    $en_stock = (int) $_POST['en_stock'];

    
    if (isset($_POST['product_color'])) {
       $product_color= $_POST['product_color'];
    }
    // fetch product color from database
    $sql_product = "SELECT color FROM products WHERE id = ?";
    $stmt_product = $connection->prepare($sql_product);
    if (!$stmt_product) {
        $_SESSION['variant'] = "SQL Error: " . $connection->error;
        header("Location: add-product-variant.php?id=" . $_POST['id']);
        exit;
    }
    $stmt_product->bind_param("i", $product_id);
    $stmt_product->execute();
    $stmt_product->bind_result($product_color);
    $stmt_product->fetch();
    $stmt_product->close();

    // validate input
    if ($color === 'null') {
        $_SESSION['variant'] = "Color is required";
    } elseif ($en_stock === 'null') {
        $_SESSION['variant'] = "stock status is required";
    } elseif ($color === $product_color) {
        $_SESSION['variant'] = "Variant already exists";
    } else {
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
